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

	/* https://api-docs.midtrans.com/#transaction-details
		"transaction_details": {
			"order_id": "A87551",
			"gross_amount": 145000
		}
	 */
	public function setTransactionDetails($transactionDetails) {
		$this->requestBody['transaction_details'] = $transactionDetails;
		return $this;
	}
	
	/* https://api-docs.midtrans.com/#customer-details
		"customer_details": {
		"first_name": "TEST",
		"last_name": "UTOMO",
		"email": "test@midtrans.com",
		"phone": "+628123456",
		"billing_address": {
		  "first_name": "TEST",
		  "last_name": "UTOMO",
		  "phone": "081 2233 44-55",
		  "address": "Sudirman",
		  "city": "Jakarta",
		  "postal_code": "12190",
		  "country_code": "IDN"
		},
		"shipping_address": {
		  "first_name": "TEST",
		  "last_name": "UTOMO",
		  "phone": "0 8128-75 7-9338",
		  "address": "Sudirman",
		  "city": "Jakarta",
		  "postal_code": "12190",
		  "country_code": "IDN"
		}
	  }
	*/
	public function setCustomerDetails($customerDetails) {
		$this->requestBody['customer_details'] = $customerDetails;
		return $this;
	}
	
	/* https://api-docs.midtrans.com/#item-details
		"item_details": [{
			"id": "a1",
			"price": 50000,
			"quantity": 2,
			"name": "Apel",
			"brand": "Fuji Apple",
			"category": "Fruit",
			"merchant_name": "Fruit-store",
			"tenor": "12",
			"code_plan": "000",
			"mid": "123456"
		  }]
	 */
	public function setItemDetails($itemDetails) {
		$this->requestBody['item_details'] = $itemDetails;
		return $this;
	}

	public function setPaymentType($paymentType, $paymentDetails) {
		$this->requestBody['payment_type'] = $paymentType;
		$this->requestBody[$paymentType] =  $paymentDetails;
		return $this;
	}

	//
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
