<?php

namespace Omnipay\Luminor;

use Omnipay\Common\AbstractGateway;
use Omnipay\Luminor\Messages\PurchaseRequest;
use Omnipay\Luminor\Messages\CompleteRequest;

/**
 * Class Gateway
 *
 * @package Omnipay\Luminor
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Luminor Link';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'gatewayUrl' => 'http://ib-t.dnb.lv/login/index.php',
            'merchantId' => '', //VK_SND_ID
            'merchantBankAccount' => '', //VK_ACC
            'merchantName' => '', //VK_NAME
            'merchantRegNo' => '', //VK_REG_ID
            'merchantSwift' => '', //VK_SWIFT
            'returnUrl' => '',
            'returnUrlSecondary' => '',

            'privateCertificatePath' => '',
            'privateCertificatePassword' => null,
            'publicCertificatePath' => '',

            //Global parameters for requests will be set via gateway
            'language' => 'LAT',
            'encoding' => 'UTF-8'
        );
    }


    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * Complete transaction
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $options = [])
    {
        return $this->createRequest(CompleteRequest::class, $options);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setGatewayUrl($value)
    {
        return $this->setParameter('gatewayUrl', $value);
    }

    /**
     * @return string
     */
    public function getGatewayUrl()
    {
        return $this->getParameter('gatewayUrl');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMerchantBankAccount($value)
    {
        return $this->setParameter('merchantBankAccount', $value);
    }

    /**
     * @return string
     */
    public function getMerchantBankAccount()
    {
        return $this->getParameter('merchantBankAccount');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMerchantName($value)
    {
        return $this->setParameter('merchantName', $value);
    }

    /**
     * @return string
     */
    public function getMerchantName()
    {
        return $this->getParameter('merchantName');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMerchantRegNo($value)
    {
        return $this->setParameter('merchantRegNo', $value);
    }

    /**
     * @return string
     */
    public function getMerchantRegNo()
    {
        return $this->getParameter('merchantRegNo');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setMerchantSwift($value)
    {
        return $this->setParameter('merchantSwift', $value);
    }

    /**
     * @return string
     */
    public function getMerchantSwift()
    {
        return $this->getParameter('merchantSwift');
    }


    /**
     * @param string $value
     * @return $this
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setReturnUrlSecondary($value)
    {
        return $this->setParameter('returnUrlSecondary', $value);
    }

    /**
     * @return string
     */
    public function getReturnUrlSecondary()
    {
        return $this->getParameter('returnUrlSecondary');
    }

    public function setPrivateCertificatePath($value)
    {
        return $this->setParameter('privateCertificatePath', $value);
    }


    public function getPrivateCertificatePath()
    {
        return $this->getParameter('privateCertificatePath');
    }


    public function setPrivateCertificatePassword($value)
    {
        return $this->setParameter('privateCertificatePassword', $value);
    }

    public function getPrivateCertificatePassword()
    {
        return $this->getParameter('privateCertificatePassword');
    }

    public function setPublicCertificatePath($value)
    {
        return $this->setParameter('publicCertificatePath', $value);
    }

    public function getPublicCertificatePath()
    {
        return $this->getParameter('publicCertificatePath');
    }

    /**
     * @param $value
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @return mixed
     */
    public function getEncoding()
    {
        return $this->getParameter('encoding');
    }

    /**
     * @param $value
     */
    public function setEncoding($value)
    {
        return $this->setParameter('encoding', $value);
    }
}
