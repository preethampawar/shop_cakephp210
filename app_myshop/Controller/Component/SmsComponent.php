<?php
App::uses('Component', 'Controller');

class SmsComponent extends Component {
    var $components = array('Session', 'Auth');

	private $smsNotificationsEnabled = false;
	private $providerIs2Factor = false;
	private $smsProviderDetails = [];
	private $smsProvider = '';


	/*
	 * This function should be called first in any function to initialize the default values.
	 * Can't use in __construct() as Session component is not available in constructor scope
	 */
	public function setSmsProvider()
	{
		$this->smsNotificationsEnabled = (bool)$this->Session->read('Site.sms_notifications');
		$this->smsProviderDetails = Configure::read('Sms');
		$this->smsProvider = Configure::read('SmsProvider');
		$this->providerIs2Factor = false;

		switch ($this->smsProvider) {
			case '2Factor':
				$this->providerIs2Factor = true;
				break;
		}
	}

	public function sendOtp($toPhone, $otp)
	{
		$this->setSmsProvider();

		if ($this->providerIs2Factor && $this->smsNotificationsEnabled) {
			return $this->send2FactorOtp($toPhone, $otp);
		}

		return false;
	}

	public function sendNewOrderSms($toPhone, $orderNo, $var1=null, $var2=null, $var3=null)
	{
		$this->setSmsProvider();

		if ($this->providerIs2Factor && $this->smsNotificationsEnabled) {
			return $this->send2FactorNewOrderSms($toPhone, $orderNo, $var1, $var2, $var3);
		}

		return false;
	}

	public function sendOrderUpdateSms($toPhone, $orderNo, $var1=null, $var2=null, $var3=null)
	{
		$this->setSmsProvider();

		if ($this->providerIs2Factor && $this->smsNotificationsEnabled) {
			return $this->send2FactorOrderUpdateSms($toPhone, $orderNo, $var1, $var2, $var3);
		}

		return false;
	}

	private function send2FactorOtp($toPhone, $otp)
	{
		try {
			$provider = $this->smsProviderDetails['2Factor'];
			$apiKey = $provider['apiKey'];
			$templateName = $provider['otpTemplateName'];
			$otpUrl = $provider['otpUrl'];
			$toPhone = (int) $toPhone;
			$otp = (string)htmlentities($otp);

			$url = str_replace('{api_key}', $apiKey, $otpUrl);
			$url = str_replace('{phone_number}', $toPhone, $url);
			$url = str_replace('{otp}', $otp, $url);
			$url = str_replace('{template_name}', $templateName, $url);

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $url);
			$result = curl_exec($curl);
			curl_close($curl);

			//file_get_contents($url);
			return true;

		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	private function send2FactorNewOrderSms($toPhone, $orderNo, $var1=null, $var2=null, $var3=null)
	{
		try {
			$provider = $this->smsProviderDetails['2Factor'];

			$templateName = $provider['newOrderTemplate'];
			$newOrderSenderId = $provider['newOrderSenderId'];
			$toPhone = (int) $toPhone;

			$postFields = [
				'From' => $newOrderSenderId,
				'To' => $toPhone,
				'TemplateName' => $templateName,
				'VAR1' => $orderNo,
				'VAR2' => $var1,
				'VAR3' => $var2,
				'VAR4' => $var3,
			];

			return $this->send2FactorTransactionSMS($postFields);

		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	private function send2FactorOrderUpdateSms($toPhone, $orderNo, $var1=null, $var2=null, $var3=null)
	{
		try {
			$provider = $this->smsProviderDetails['2Factor'];

			$templateName = $provider['OrderStatusUpdateTemplate'];
			$newOrderSenderId = $provider['OrderStatusUpdateSenderId'];
			$toPhone = (int) $toPhone;

			$postFields = [
				'From' => $newOrderSenderId,
				'To' => $toPhone,
				'TemplateName' => $templateName,
				'VAR1' => $orderNo,
				'VAR2' => $var1,
				'VAR3' => $var2,
				'VAR4' => $var3,
			];

			return $this->send2FactorTransactionSMS($postFields);

		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	private function send2FactorTransactionSMS($postFields)
	{
		$success = true;
		$provider = $this->smsProviderDetails['2Factor'];
		$transactionalUrl = $provider['transactionalUrl'];
		$apiKey = $provider['apiKey'];
		$url = str_replace('{api_key}', $apiKey, $transactionalUrl);

		$headers = array("Content-Type:multipart/form-data");
		$ch = curl_init();
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => true,
			CURLOPT_POST => 1,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => $postFields,
			CURLOPT_RETURNTRANSFER => true
		); // cURL options

		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);

		if(!curl_errno($ch))
		{
			$info = curl_getinfo($ch);
			if ($info['http_code'] != 200)
				$success = false;
		}
		else
		{
			$errmsg = curl_error($ch);
			$success = false;
		}

		curl_close($ch);

		return $success;
	}
}
