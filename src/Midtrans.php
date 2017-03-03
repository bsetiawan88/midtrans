<?php

namespace Bagus\Midtrans;
use Requests;

class Midtrans {

	var $environment = 'developent';
	var $development = 'https://api.sandbox.midtrans.com/v2/';
	var $production = 'https://api.midtrans.com/v2/';
	//
	var $serverKey;
	//
	var $requestBody;

	public function setEnvironment($environment) {
		if ($environment == 'development' || $environment == 'production') {
			$this->environment = $environment;
		}
	}

	public function setServerKey($serverKey) {
		$this->serverKey = $serverKey;
	}

	public function setTransactionDetails($transactionDetails) {
		$this->requestBody['transaction_details'] = $transactionDetails;
		return $this;
	}

	public function setCustomerDetails($customerDetails) {
		$this->requestBody['customer_details'] = $customerDetails;
		return $this;
	}
	
	public function setItemDetails($itemDetails) {
		$this->requestBody['item_details'] = $itemDetails;
		return $this;
	}

	public function setPaymentType($paymentType, $paymentDetails) {
		$this->requestBody['payment_type'] = $paymentType;
		$this->requestBody[$paymentType] =  $paymentDetails;
		return $this;
	}

	public function charge() {
		if ($this->environment == 'production') {
			$url = $this->production;
		} else {
			$url = $this->development;
		}
		
		return Requests::post($url . 'charge', $this->_getHeader(), json_encode($this->requestBody));
	}

	private function _getHeader() {
		return [
			'Accept' => 'application/json',
			'Content-Type' => 'application/json',
			'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
		];
	}

}
