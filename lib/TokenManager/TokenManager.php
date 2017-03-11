<?php
//一
require_once 'AES.class.php';

class TokenManager{
	private $nKeys = 3;
	private $refList = array('xkwnak','nw81zu','nqia;s');
	private $keyList = array('xkwnak'=>array('naks81_;ne1@#qpz~!,mqiwo86_1wa/d','kq29:ka!'),'nw81zu'=>array('bxks81_;ne1@#qqu~!,mqiqu86_1wa/d','naj<>c81'),'nqia;s'=>array('nquajzhsjq71827486j2nahxyajwnq;a','nqauz81k')); // ref=>[password,salt]
	private $tokenAvailablePeriod = 0;
	
	// $tokenAvailablePeriod is count as second
	public function __construct($tokenAvailablePeriod = PHP_INT_MAX){
		$this->tokenAvailablePeriod = $tokenAvailablePeriod;
	}
	
	public function EncryptStr($str){
		return $this->CreateToken($str);
	}
	
	public function DecryptStr($cypter){
		$data = $this->ExtractToken($cypter);
		if($data === false){
			return false;
		}else{
			return $data[3];
		}
	}
	
	// return base64 string
	public function CreateToken($extParam=null){
		$x = rand(0,$this->nKeys - 1);
		$ref = $this->refList[$x];
		$keyItem = $this->keyList[$ref];
		$key = $keyItem[0];
		$salt = $keyItem[1];
		
		$data = array($salt,time(),rand(),$extParam);
		$str = json_encode($data);
		$aes = new AES($key);
		return $ref.base64_encode($aes->encrypt($str));
	}
	
	// return true of false
	public function CheckToken($token){
		$data = $this->ExtractToken($token);
		if($data === false){
			return false;
		}else{
			return true;
		}
	}
	
	public function ExtractToken($token){
		if(!is_string($token)){ return false; }
		$ref = substr($token,0,6);
		if(!isset($this->keyList[$ref])){ return false; }
		$cypter = substr($token,6);
		
		$keyItem = $this->keyList[$ref];
		$key = $keyItem[0];
		$salt = $keyItem[1];
		
		$aes = new AES($key);
		$str = $aes->decrypt(base64_decode($cypter));
		$data = json_decode($str,true);
		if(!is_array($data)){ return false; }
		
		if($data[0] !== $salt){ return false; }
		if(time() - $data[1] > $this->tokenAvailablePeriod){ return false; }
		return $data;
	}
	
	public function RenewToken($token){
		$data = $this->ExtractToken($token);
		if($data === false){
			return false;
		}else{
			return $this->CreateToken($data[3]);
		}
	}
}
?>