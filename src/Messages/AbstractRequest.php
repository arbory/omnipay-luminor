<?php

namespace Omnipay\Luminor\Messages;

use Omnipay\Common\Message\AbstractRequest as CommonAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractRequest extends CommonAbstractRequest
{

    /**
     * @return mixed
     */
    public function getEncoding()
    {
        return $this->getParameter('encoding');
    }

    /**
     * @param mixed $encoding
     */
    public function setEncoding($value)
    {
        return $this->setParameter('encoding', $value);
    }

    public function setPrivateCertificatePassword($value)
    {
        $this->setParameter('privateCertificatePassword', $value);
    }

    public function getPrivateCertificatePassword()
    {
        return $this->getParameter('privateCertificatePassword');
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    /**
     * @param mixed $returnUrl
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    public function setPrivateCertificatePath($value)
    {
        $this->setParameter('privateCertificatePath', $value);
    }


    public function getPrivateCertificatePath()
    {
        return $this->getParameter('privateCertificatePath');
    }

    public function setPublicCertificatePath($value)
    {
        $this->setParameter('publicCertificatePath', $value);
    }


    public function getPublicCertificatePath()
    {
        return $this->getParameter('publicCertificatePath');
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * @param $value
     * @return CommonAbstractRequest
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @param $value
     * @return CommonAbstractRequest
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
     * @param $value
     */
    public function setReturnUrlSecondary($value)
    {
        $this->setParameter('returnUrlSecondary', $value);
    }

    /**
     * @return mixed
     */
    public function getReturnUrlSecondary()
    {
        return $this->getParameter('returnUrlSecondary');
    }

    /**
     * @param $value
     */
    public function setMerchantId($value)
    {
        $this->setParameter('merchantId', $value);
    }

    /**
     * @return mixed
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
        $this->setParameter('merchantBankAccount', $value);
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
        $this->setParameter('merchantName', $value);
    }

    /**
     * @return string
     */
    public function getMerchantName()
    {
        return $this->getParameter('merchantName');
    }

    /**
     * @param $value
     */
    public function setMerchantRegNo($value)
    {
        $this->setParameter('merchantRegNo', $value);
    }

    /**
     * @return string
     */
    public function getMerchantRegNo()
    {
        return $this->getParameter('merchantRegNo');
    }

    /**
     * @param $value
     */
    public function setMerchantSwift($value)
    {
        $this->setParameter('merchantSwift', $value);
    }

    /**
     * @return string
     */
    public function getMerchantSwift()
    {
        return $this->getParameter('merchantSwift');
    }
}
