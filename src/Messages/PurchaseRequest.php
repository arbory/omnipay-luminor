<?php

namespace Omnipay\Luminor\Messages;

use Omnipay\Luminor\Utils\Pizza;

class PurchaseRequest extends AbstractRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    private function getEncodedData()
    {
        $data = [
            'VK_SERVICE' => '1002', // Service code
            'VK_VERSION' => '101', // Protocol version
            'VK_SND_ID' => $this->getMerchantId(),
            'VK_STAMP' => $this->getTransactionReference(),  // Max 20 length
            'VK_AMOUNT' => $this->getAmount(), // Decimal with point
            'VK_CURR' => $this->getCurrency(), // ISO 4217 format (LVL/EUR, etc.)
            'VK_ACC' => $this->getMerchantBankAccount(),
            'VK_NAME' => $this->getMerchantName(),
            'VK_REG_ID' => $this->getMerchantRegNo(),
            'VK_SWIFT' => $this->getMerchantSwift(),
            'VK_REF' => $this->getTransactionReference(),  // Max 20 length
            'VK_MSG' => $this->getDescription(), // Max 300 length,
            'VK_RETURN' => $this->getReturnUrl(), // 400 characters max
            'VK_RETURN2' => $this->getReturnUrlSecondary(), // 400 characters max
        ];
        return $data;
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    private function getDecodedData()
    {
        $data = [
            // MAC - Control code / signature
            'VK_MAC' => $this->generateControlCode($this->getEncodedData()),
            // date( 'd.m.Y H:i:s', strtotime( '+1 hour' ) ); banks default = +10 days till 21:00:00
            'VK_TIME_LIMIT' => '',
            // Communication language (LAT, ENG RUS), no format standard?
            'VK_LANG' => $this->getLanguage()
        ];

        return $data;
    }

    /**
     * @param $data
     * @return string
     */
    private function generateControlCode($data)
    {
        return Pizza::generateControlCode(
            $data,
            $this->getEncoding(),
            $this->getPrivateCertificatePath(),
            $this->getPrivateCertificatePassword()
        );
    }

    /**
     * Glue together encoded and raw data
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $data = $this->getEncodedData() + $this->getDecodedData();
        return $data;
    }

    /**
     * @param mixed $data
     * @return \Omnipay\Common\Message\ResponseInterface|PurchaseResponse
     */
    public function sendData($data)
    {
        // Create fake response flow, so that user can be redirected
        /** @var AbstractResponse $purchaseResponseObj */
        return $purchaseResponseObj = new PurchaseResponse($this, $data);
    }
}
