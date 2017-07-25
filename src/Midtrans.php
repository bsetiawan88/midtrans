<?php

namespace Bagus\Midtrans;

use Requests;

class Midtrans
{

	CONST DEV_URL = 'https://api.sandbox.midtrans.com/v2/';
	CONST PROD_URL = 'https://api.midtrans.com/v2/';

	var $environment = 'development';
	var $url;
	var $options = [];
	var $serverKey;
	var $requestBody;

	public function __construct()
	{
		$this->url = self::DEV_URL;
	}

	public function setEnvironment($environment)
	{
		if ($environment == 'production') {
			$this->environment = $environment;
		} else {
			$this->options = [
				'verify' => FALSE
			];
		}
	}

	public function setServerKey($serverKey)
	{
		$this->serverKey = $serverKey;
	}

	public function setTransactionDetails($transactionDetails)
	{
		$this->requestBody['transaction_details'] = $transactionDetails;
		return $this;
	}

	public function setCustomerDetails($customerDetails)
	{
		$this->requestBody['customer_details'] = $customerDetails;
		return $this;
	}

	public function setItemDetails($itemDetails)
	{
		$this->requestBody['item_details'] = $itemDetails;
		return $this;
	}

	public function setPaymentType($paymentType, $paymentDetails)
	{
		$this->requestBody['payment_type'] = $paymentType;
		$this->requestBody[$paymentType] = $paymentDetails;
		return $this;
	}

	public function setUrl($url)
	{
		$this->url = $url;
	}

	public function charge()
	{
		return Requests::post($this->_getUrl() . 'charge', $this->_getHeader(), json_encode($this->requestBody), $this->options);
	}

	public function checkStatus($id)
	{
		return Requests::get($this->_getUrl() . $id . '/status', $this->_getHeader(), $this->options);
	}

	private function _getHeader()
	{
		return [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
		];
	}

	private function _getUrl()
	{
		if ($this->environment == 'production') {
			return self::PROD_URL;
		} else if (!empty($this->url)) {
			return $this->url;
		} else {
			return self::DEV_URL;
		}
	}

}
