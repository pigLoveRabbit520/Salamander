<?php
	namespace  App\Tool;
	
	use App\Library\HttpCurl;

    class Sender
	{
		protected $request;
		protected $gateway = 'https://sms.yunpian.com/v1/sms/send.json';
		protected $api_key = '80c84d416610af4618588960e73faf80';

		protected $result = [
			'code' => 0,
			'msg' => ''
		];
		
		function send($to, $content)
		{
		    $http = new HttpCurl();
		    $http->setParams([
                'apikey'=> $this->api_key,
                'mobile'=> $to,
                'text'=> $content
            ]);
			$res = $http->post(
                $this->gateway,
                'json');
			return $res['code'] == 0;
		}
		
		function errorCode()
		{
			return $this->result['code'];
		}
		
		function errorInfo()
		{
			return $this->result['msg'];
		}
	}