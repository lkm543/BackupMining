<?php
date_default_timezone_set("Asia/Taipei").

#require_once(dirname(dirname(__FILE__))."/generalPool.php");
require_once("generalPool.php");

//Reported HashRate
class f2pool extends generalPool {
	
	private $APIurl = "http://api.f2pool.com/eth/";
	private $ReportedAPIurl;
	public $ErrorMsg;
	public $ErrorFlag;

    //
	public function __construct(){
		if($this->Coin == "ETH"){
			$this->APIurl = "http://api.f2pool.com/eth/";
			#$this->ReportedAPIurl = "";
		}
		$this->ErrorFlag = False;
		$this->ErrorMsg = "";
	}
    
	//
	public function reset()	{
		if($this->Coin == "ETH"){
			$this->APIurl = "http://api.f2pool.com/eth/";
			#$this->ReportedAPIurl = "";
		}
		$this->ErrorFlag = False;
		$this->ErrorMsg = "";
		
		$this->Address = "NULL";
		$this->Worker = "NULL";
		$this->HashRate = 0.0;
		$this->HashRate_LongTerm = 0.0;
		$this->ReportedHashRate = 0.0;
		$this->Balance = 0.0;
		$this->Coin = "NULL";
	}	
    
	//
	public function getDataFromPool(){
		try {
			//Balance
			$response = file_get_contents($this->APIurl.$this->Address);
			//echo($APIurl.$Address);
			$response = json_decode($response);
			if(empty($response)!=1){
				$this->ErrorFlag = False;
				$this->ErrorMsg = "";
				$this->Balance = $response->balance;
				####
				foreach($response->workers as $workers){			         
			    	if($workers[0] == $this->Worker){
						$this->HashRate = floatval($workers[1]*1E-6);    #hashrate
						$this->HashRate_LongTerm = floatval($workers[4]/(24*3600)*1E-6);    #h24
					}
			    }        
			}
			else{
		    	$this->ErrorFlag = True;
				$this->ErrorMsg = $this->ErrorMsg.$response->error."<br>";
			}
			/*
			#f2pool沒有Reported資料
			//Worker: Reported
			$response = file_get_contents($this->ReportedAPIurl.$this->Address."/".$this->Worker);
			$response = json_decode($response);
			if($response->status=='true'){
				$this->ReportedHashRate = $response->data;
			}
			else{
		    	$this->ErrorFlag = True;
				$this->ErrorMsg = $this->ErrorMsg.$response->error."<br>";
			}
			*/
		}
		catch (Exception $e) {
		    $this->ErrorMsg = $e->getMessage();
		    $this->ErrorFlag = True;
		}
	}
}
?>
