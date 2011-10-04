<?php
	
	function setEncryptCookie($key, $value, $time = 0, $path) {
		if (trim(getMcryptKey()) == '') {
			setcookie($key, $value, $time, $path);
		} else {
			setcookie($key, encrypt($value), $time, $path);
		}
	}
	
	function getEncryptCookie($key) {
		if ( isset($_COOKIE[$key]) ) {
			if (trim(getMcryptKey()) == '') {
				return $_COOKIE[$key];
			} else {
				return decrypt($_COOKIE[$key]);
			}
		} else { 
			return null;
		}
	}
	
	function getCookie($key) {
		if ( isset($_COOKIE[$key]) ) 
			return $_COOKIE[$key];
		else 
			return null;
	}
	
	function delCookie($key) {
		setcookie($key, '', time()-300, '/');
	}
	
	function getMcryptKey() {
		if (function_exists('mcrypt_module_open')) 
			return SECURE_KEY;
		else 
			return '';
	}
	
	function encrypt($plain_text) {	  
	  $td = mcrypt_module_open('blowfish', '', 'cfb', '');
	  $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	  mcrypt_generic_init($td, getMcryptKey(), $iv);
	  $crypt_text = mcrypt_generic($td, $plain_text);
	  mcrypt_generic_deinit($td);
	  return base64_encode($iv.$crypt_text);
	}
	  
	function decrypt($crypt_text) {
	  $crypt_text = base64_decode($crypt_text);
	  $td = mcrypt_module_open('blowfish', '', 'cfb', '');
	  $ivsize = mcrypt_enc_get_iv_size($td);
	  $iv = substr($crypt_text, 0, $ivsize);
	  $crypt_text = substr($crypt_text, $ivsize);
	  mcrypt_generic_init($td, getMcryptKey(), $iv);
	  $plain_text = mdecrypt_generic($td, $crypt_text);
	  mcrypt_generic_deinit($td);
	  
	  return $plain_text;
	}
	
	if ( !function_exists('mb_strlen') ) {
		function mb_strlen($text, $encode) {
			if (strtolower($encode) == 'utf-8') {
				return preg_match_all('%(?:
								  [\x09\x0A\x0D\x20-\x7E]     # ASCII
								| [\xC2-\xDF][\x80-\xBF]# non-overlong 2-byte
								|  \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
								| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
								|  \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
								|  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
								| [\xF1-\xF3][\x80-\xBF]{3}   # planes 4-15
								|  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
								)%xs',$text,$out);
			}else{
				return strlen($text);
			}
		}
	}
	
	function spEncrypt($str)
	{
        $encrypt_key = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $decrypt_key = 'ngzqtcobmuhelkpdawxfyivrsj2468021359';
		$enstr = "";
        if (strlen($str) == 0) return false;

        for ($i=0; $i<strlen($str); $i++){
                for ($j=0; $j<strlen($encrypt_key); $j++){
                        if ($str[$i] == $encrypt_key[$j]){
                                $enstr .= $decrypt_key[$j];
                                break;
                        }
                }
        }

        return $enstr;
	}

	//简单解密函数（与php_encrypt函数对应）
	function spDecrypt($str)
	{
		$encrypt_key = 'abcdefghijklmnopqrstuvwxyz1234567890';
		$decrypt_key = 'ngzqtcobmuhelkpdawxfyivrsj2468021359';
		$enstr = "";
		if (strlen($str) == 0) return false;

		for ($i=0; $i<strlen($str); $i++){
				for ($j=0; $j<strlen($decrypt_key); $j++){
						if ($str[$i] == $decrypt_key[$j]){
								$enstr .= $encrypt_key[$j];
								break;
						}
				}
		}

		return $enstr;
	}
?>