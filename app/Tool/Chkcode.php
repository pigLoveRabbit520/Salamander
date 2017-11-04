<?php
	namespace App\Tool;
	
	class Chkcode
	{
		function __construct()
		{
			!session_id() && session_start();
		}

		public function generate($codes = '23546789QWERTYUIPKJHGFDASZXCVBNM', $length = 5)
		{
			if(is_string($codes)) {
				$codes = str_split($codes);
			}
			
			$result = '';
			for($i=0; $i<$length; $i++) {
				$idx = array_rand($codes);
				$result .= $codes[$idx];
			}
			return strtoupper($result);
		}

		public function set($code, $name='code')
		{
			!session_id() && session_start();
			$_SESSION[$name] = $code;
		}
		
		public function verify($code, $name='code')
		{
			!session_id() && session_start();
			$code = strtoupper(trim($code));
			if(isset($_SESSION[$name]) && $code == $_SESSION[$name]) {
				$verified = true;
			} else {
				// 此处注释，mh
					//throw new \Exception('验证码错误'.$code.':'.$_SESSION[$name], 2);
				$verified = false;
			}
            $this->clear();
			return $verified;
		}
		
		function clear($name='code')
		{
			unset($_SESSION[$name]);
		}
	}