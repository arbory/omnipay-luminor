<?php

namespace Omnipay\Luminor\Messages;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Luminor\Utils\Pizza;
use Symfony\Component\HttpFoundation\ParameterBag;

class CompleteRequest extends AbstractRequest
{
    protected $responseKeys = [
        'VK_SERVICE' => true,
        'VK_VERSION' => true,
        'VK_SND_ID' => true,
        'VK_REC_ID' => true,
        'VK_STAMP' => true,
        'VK_T_NO' => true,
        'VK_AMOUNT' => true,
        'VK_CURR' => true,
        'VK_REC_ACC' => true,
        'VK_REC_NAME' => true,
        'VK_REC_REG_ID' => true,
        'VK_REC_SWIFT' => true,
        'VK_SND_ACC' => true,
        'VK_SND_NAME' => true,
        'VK_REF' => true,
        'VK_MSG' => true,
        'VK_T_DATE' => true,
        'VK_T_STATUS' => true,
        'VK_MAC' => false,
        'VK_LANG' => false
    ];

    public function getData()
    {
        if ($this->httpRequest->getMethod() == 'POST') {
            return $this->httpRequest->request->all();
        } else {
            return $this->httpRequest->query->all();
        }
    }

    /*
     * Faking sending flow
     */
    public function createResponse(array $data)
    {
        // Read data from request object
        return $purchaseResponseObj = new CompleteResponse($this, $data);
    }

    /**
     * @param mixed $data
     * @return \Omnipay\Common\Message\ResponseInterface|AbstractResponse|CompleteResponse
     * @throws InvalidRequestException
     */
    public function sendData($data)
    {
        //Validate response data before we process further
        $this->validateData();

        // Create fake response flow
        /** @var CompleteResponse $purchaseResponseObj */
        $response = $this->createResponse($data);
        return $response;
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateData()
    {
        $response = $this->getData();
        if (!isset($response['VK_SERVICE']) || !in_array($response['VK_SERVICE'], ['1102'])) {
            throw new InvalidRequestException('Unknown VK_SERVICE code');
        }

        //verify data corruption
        $this->validateIntegrity($this->responseKeys);
    }

    /**
     * @param array $responseFields
     * @throws InvalidRequestException
     */
    protected function validateIntegrity(array $responseFields)
    {
        $controlCodeFields = [];
        $responseData = new ParameterBag($this->getData());

        foreach ($responseFields as $key => $shouldValidate) {
            if ($shouldValidate) {
                $controlCodeFields[$key] = $responseData->get($key);
            }
        }

        if (!Pizza::isValidControlCode(
            $controlCodeFields,
            $responseData->get('VK_MAC'),
            $this->getPublicCertificatePath(),
            $this->getEncoding()
        )) {
            throw new InvalidRequestException('Data is corrupt or has been changed by a third party');
        }
    }
}
