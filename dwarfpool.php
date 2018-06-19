<?php
date_default_timezone_set("Asia/Taipei").

#require_once(dirname(dirname(__FILE__))."/generalPool.php");
require_once("generalPool.php");

//Reported HashRate
class dwarfpool extends generalPool {
	
	private $APIurl = "http://dwarfpool.com/eth/api?wallet=";
	private $ReportedAPIurl;
	public $ErrorMsg;
	public $ErrorFlag;

    //
	public function __construct(){
		if($this->Coin == "ETH"){
			$this->APIurl = "http://dwarfpool.com/eth/api?wallet=";
			#$this->ReportedAPIurl = "";
		}
		$this->ErrorFlag = False;
		$this->ErrorMsg = "";
	}
    
	//
	public function reset()	{
		if($this->Coin == "ETH"){
			$this->APIurl = "http://dwarfpool.com/eth/api?wallet=";
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
			    	if($workers->worker == $this->Worker){
						$this->HashRate = floatval($workers->hashrate);    #hashrate
						$this->HashRate_LongTerm = floatval($workers->hashrate_calculated);    #h24
					}
			    }        
			}
			else{
		    	$this->ErrorFlag = True;
				$this->ErrorMsg = $this->ErrorMsg.$response->error."<br>";
			}
			/*
			#dwarfpool沒有Reported資料
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
