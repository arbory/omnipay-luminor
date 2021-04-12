<?php

namespace Omnipay\Luminor\Messages;

use Omnipay\Common\Message\AbstractResponse as CommonAbstractResponse;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractResponse extends CommonAbstractResponse
{
    public function getTransactionReference()
    {
        $data = $this->getData();
        return $data['VK_REF'] ?? $data['VK_REF'];
    }
}
