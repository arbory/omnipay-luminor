<?php

namespace Omnipay\Luminor;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\Luminor\Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'merchantId' => '1',
            'merchantBankAccount' => 'ASDF000011',
            'merchantRegNo' => 'LV400000',
            'merchantName' => 'Company x',
            'merchantSwift' => 'ASDF000022',
            'gatewayUrl' => 'https://www.luminor.lv/link/',
            'returnUrl' => 'http://localhost:8080/omnipay/banklink/',
            'returnUrlSecondary' => 'http://localhost:8080/omnipay/banklink/status',
            'privateCertificatePath' => 'tests/Fixtures/key.pem',
            'publicCertificatePath' => 'tests/Fixtures/key.pub',
            'transactionReference' => 'abc123',
            'description' => 'purchase description',
            'amount' => '10.00',
            'currency' => 'EUR',
        );
    }

    public function testPurchaseSuccess()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\Luminor\Messages\PurchaseResponse', $response);
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->isTransparentRedirect());
        $this->assertEquals('POST', $response->getRedirectMethod());
        $this->assertEquals('https://www.luminor.lv/link/', $response->getRedirectUrl());

        $this->assertEquals(array(
            'VK_SERVICE' => '1002',
            'VK_VERSION' => '101',
            'VK_SND_ID' => '1',
            'VK_STAMP' => 'abc123',
            'VK_AMOUNT' => '10.00',
            'VK_CURR' => 'EUR',
            'VK_ACC' => 'ASDF000011',
            'VK_NAME' => 'Company x',
            'VK_REG_ID' => 'LV400000',
            'VK_SWIFT' => 'ASDF000022',
            'VK_REF' => 'abc123',
            'VK_MSG' => 'purchase description',
            'VK_RETURN' => 'http://localhost:8080/omnipay/banklink/',
            'VK_RETURN2' => 'http://localhost:8080/omnipay/banklink/status',
            'VK_MAC' => 'M/AxcwHloRTRYHFrwNqGY3MmlUfwCFa1/Si7lkTRIy+kKHLlcJu5r8WncpFxmrW4Y8BieJVfJOGjzlxazYWnTw94Mcd2zVS5fy7n0ZO2lGId/s4hTMrQfCjq3SUup6tYJqh2XINbyf9I1MfkCHUeQ2HxStHlcuWkKSRVckXA3+6UXDBBiInUHb/S1iTKFDoJZ/8CwN+tPct9ahPQCrxDfHbMczD3NNJDq6PkH5RyjCpLN/GdDazeuX8s+9zzaud3/Sw3kNVg1TQK74huqvV87Oll8i+D0XBBs6GLE58+RM7jfTEypmzsh/yq6mlOdobCUdwgKB1stlphfMG695QEYg==',
            'VK_TIME_LIMIT' => '',
            'VK_LANG' => 'LAT',
        ), $response->getData());

        $this->assertEquals($response->getData(), $response->getRedirectData());
    }

    public function testPurchaseCompleteSuccess()
    {
        $postData = array(
            'VK_SERVICE' => '1102',
            'VK_VERSION' => '101',
            'VK_SND_ID' => 'HP',
            'VK_REC_ID' => 'REFEREND',
            'VK_STAMP' => 'abc-1',
            'VK_T_NO' => 'abc-2',
            'VK_CURR' => 'EUR',
            'VK_AMOUNT' => '12.0',
            'VK_CURR' => 'EUR',
            'VK_REC_ACC' => 'abc-3',
            'VK_REC_NAME' => 'abc-4',
            'VK_REC_REG_ID' => 'abc-5',
            'VK_REC_SWIFT' => 'abc-6',
            'VK_SND_ACC' => 'abc-7',
            'VK_SND_NAME' => 'abc-8',
            'VK_REF' => 'abc-9',
            'VK_MSG' => 'Payment for order 1231223',
            'VK_T_DATE' => '2030-01-01',
            'VK_T_STATUS' => '1',
            'VK_MAC' => 'YeD8T+FxkF29Q3GBD80yuCv4CzNK/WSOxV06gV6TxOCFhVI2ZpNAb9upwyIeO/LnL4mqK23B6DUJ7NRis5w3jm3bJQlBWUhmJVtFDCP01A1ansHGrLLRAHvHyTFhR5nr6mNMymke11aXHgvlVKVzV3Cq0CdBWyq/ZN7KrSlDWtR6sOyOq4xPA6waHLqv/BB5bwzFo9Nj6s8Q4cBJe8885PKW7Vip38thQ4TejUk0Jr+fksrdgUAazyVdUZuY7POcfjE/jBo8pe4q2E4rWa0eY/XIXbbSVFnYlJgX6ckKOdqOeT3fOVEFgvXUS1bB2z8aDna1qus7LWsB4IPwlnTJVA==',
            'VK_LANG' => 'LAT',
        );

        $this->getHttpRequest()->setMethod('POST');
        $this->getHttpRequest()->request->replace($postData);

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isPending());
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertSame('abc-9', $response->getTransactionReference());
        $this->assertSame('Payment was successful', $response->getMessage());
    }

    public function testPurchaseCompleteFailed()
    {
        $postData = array(
            'VK_SERVICE' => '1102',
            'VK_VERSION' => '101',
            'VK_SND_ID' => 'HP',
            'VK_REC_ID' => 'REFEREND',
            'VK_STAMP' => 'abc-1',
            'VK_T_NO' => 'abc-2',
            'VK_CURR' => 'EUR',
            'VK_AMOUNT' => '12.0',
            'VK_CURR' => 'EUR',
            'VK_REC_ACC' => 'abc-3',
            'VK_REC_NAME' => 'abc-4',
            'VK_REC_REG_ID' => 'abc-5',
            'VK_REC_SWIFT' => 'abc-6',
            'VK_SND_ACC' => 'abc-7',
            'VK_SND_NAME' => 'abc-8',
            'VK_REF' => 'abc-9',
            'VK_MSG' => 'Payment for order 1231223',
            'VK_T_DATE' => '2030-01-01',
            'VK_T_STATUS' => '3',
            'VK_MAC' => 'MoRejvfRPlUkrYMT9+SszWBDu2u3Q4rXaKb2wSGqmBP+VX2W1oe83bJyqh+fwmugddTVuwD6dOpsw6bTEYvsWWp7EdwWp3z/6MsUTaOAKscZZD7EDCQbEBeAuSx93iajT/IsIVV52LsSPItpPdhU6q5t/TZZlV2HGaFz0Ly6GmtzGYrha2coGld5qBO6Gtcb2yu3bvbtcNSmZca0woDWnC1Vf0SlolzIKPx6bxwAoDhl+CF/tl1UJToCWmqpbYRq6FVLrSRhL/rucxeUiP7eawp4ItJR0zEtz14+x2cTduV2KOIhbY1urGQrhQqmQYFLz6G8UD/h9UlJDrhckPh/+w==',
            'VK_LANG' => 'LAT',
        );

        $this->getHttpRequest()->query->replace($postData);

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertTrue($response->isCancelled());
        $this->assertSame('abc-9', $response->getTransactionReference());
        $this->assertSame('Payment canceled by user', $response->getMessage());
    }

    public function testPurchaseCompleteFailedWithForgedSignature()
    {
        $postData = array(
            'VK_SERVICE' => '1901',
            'VK_VERSION' => '008',
            'VK_SND_ID' => 'HP',
            'VK_REC_ID' => 'REFEREND',
            'VK_STAMP' => 'abc123',
            'VK_CURR' => 'EUR',
            'VK_REF' => 'abc123',
            'VK_MSG' => 'Payment for order 1231223',
            'VK_LANG' => 'LAT',
            'VK_AUTO' => 'Y',
            'VK_ENCODING' => 'UTF-8',
            'VK_MAC' => 'FORGED_SIGNATURE'
        );

        $this->getHttpRequest()->query->replace($postData);

        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);

        $response = $this->gateway->completePurchase($this->options)->send();
    }

    public function testPurchaseCompleteFailedWithInvalidRequest()
    {
        $postData = array(
            'some_param' => 'x',
        );

        $this->getHttpRequest()->query->replace($postData);

        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);

        $response = $this->gateway->completePurchase($this->options)->send();
    }

    // test with missing VK_REF parameter
    public function testPurchaseCompleteWithMissingFields()
    {
        // missing VK_T_NO here
        $postData = array(
            'VK_SERVICE' => '1102',
            'VK_VERSION' => '008',
            'VK_SND_ID' => 'HP',
            'VK_REC_ID' => 'REFEREND',
            'VK_STAMP' => 'abc123',
            'VK_AMOUNT' => '10.00',
            'VK_CURR' => 'EUR',
            'VK_REC_ACC' => 'XXXXXXXXXX',
            'VK_REC_NAME' => 'Shop',
            'VK_SND_ACC' => 'XXXXXXXXXXXX',
            'VK_SND_NAME' => 'John Mayer',
            'VK_MSG' => 'Payment for order 1231223',
            'VK_T_DATE' => '10.03.2019',
            'VK_LANG' => 'LAT',
            'VK_AUTO' => 'N',
            'VK_ENCODING' => 'UTF-8',
            'VK_MAC' => 'uHB+cjwJa7O1eCo/mwh81aAy9esSTEmExdKvWDxZrK3pn3l/Utr5Sy1vnDUzJSWGq24tBTA3saCmoVZON1FW1XRIwFyd04rhEXG2VwX+zLTzUKOEM+K98Xzs2HX8jAytjlsF2XlJYbxNM3hBej8MndvRHaBYNCl6h4Lv/y9js2z05mi2tTHKamK4w5kVOTDkV1Za0Aafx2rFoQMMqFmE+26TUcUx+Q8IvJ6vGM5+VRnCsCKzQxzN4YYftRFJo+8SHefsdhNirr10UHbkwJFNzhyuKjeEkOglCaEcq+syOhY9MDQ58AVY50vs1/q42dXicv+fTNFvu6tglSNDQJ7Ikg=='
        );

        $this->getHttpRequest()->query->replace($postData);

        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('Data is corrupt or has been changed by a third party');

        $response = $this->gateway->completePurchase($this->options)->send();
    }

    // test with missing VK_REF parameter
    public function testPurchaseCompleteFailedWithIncompleteRequest()
    {
        $postData = array(
            'VK_SERVICE' => '1102',
            'VK_VERSION' => '008',
            'VK_SND_ID' => 'HP',
            'VK_REC_ID' => 'REFEREND',
            'VK_STAMP' => 'abc123',
            'VK_T_NO' => '169',
            'VK_AMOUNT' => '10.00',
            'VK_CURR' => 'EUR',
            'VK_REC_ACC' => 'XXXXXXXXXX',
            'VK_REC_NAME' => 'Shop',
            'VK_SND_ACC' => 'XXXXXXXXXXXX',
            'VK_SND_NAME' => 'John Mayer',
            'VK_MSG' => 'Payment for order 1231223',
            'VK_T_DATE' => '10.03.2019',
            'VK_LANG' => 'LAT',
            'VK_AUTO' => 'N',
            'VK_ENCODING' => 'UTF-8',
            'VK_MAC' => 'uHB+cjwJa7O1eCo/mwh81aAy9esSTEmExdKvWDxZrK3pn3l/Utr5Sy1vnDUzJSWGq24tBTA3saCmoVZON1FW1XRIwFyd04rhEXG2VwX+zLTzUKOEM+K98Xzs2HX8jAytjlsF2XlJYbxNM3hBej8MndvRHaBYNCl6h4Lv/y9js2z05mi2tTHKamK4w5kVOTDkV1Za0Aafx2rFoQMMqFmE+26TUcUx+Q8IvJ6vGM5+VRnCsCKzQxzN4YYftRFJo+8SHefsdhNirr10UHbkwJFNzhyuKjeEkOglCaEcq+syOhY9MDQ58AVY50vs1/q42dXicv+fTNFvu6tglSNDQJ7Ikg=='
        );

        $this->getHttpRequest()->query->replace($postData);

        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage('Data is corrupt or has been changed by a third party');

        $response = $this->gateway->completePurchase($this->options)->send();
    }
}
