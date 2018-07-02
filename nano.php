<?php
date_default_timezone_set("Asia/Taipei").

require_once('generalPool.php');

##Reported HashRate
class nano extends generalPool{
	private $APIurl = "https://api.nanopool.org/v1/eth/user/";
	private $ReportedAPIurl = "https://api.nanopool.org/v1/eth/reportedhashrate/";
	public $ErrorMsg;
	public $ErrorFlag;
	
	public function __construct(){
		if($this->Coin == "ETH"){
			$this->APIurl = "https://api.nanopool.org/v1/eth/user/";
			$this->ReportedAPIurl = "https://api.nanopool.org/v1/eth/reportedhashrate/";
		}
		$this->ErrorFlag = False;
		$this->ErrorMsg = "";
	}

	public function reset(){
		if($this->Coin == "ETH"){
			$this->APIurl = "https://api.nanopool.org/v1/eth/user/";
			$this->ReportedAPIurl = "https://api.nanopool.org/v1/eth/reportedhashrate/";
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

	public function getDataFromPool(){
		try{
			##require from API of Pool
			#echo($this->APIurl.$this->Address);
			$response = file_get_contents($this->APIurl.$this->Address);
			$response = json_decode($response);
			
			##Successfully connected?
			if($response->status=='true'){
				$this->ErrorFlag = False;
				$this->ErrorMsg = "";
				
				##Balance
				$this->Balance = $response->data->balance;
				##HashRate
				foreach($response->data->workers as $workers){			         
			    	if($workers->id == $this->Worker){
						$this->HashRate = floatval($workers->hashrate);    #real time [Mh/s]
						$this->HashRate_LongTerm = floatval($workers->h24);    #Worker Average Hashrate for 24 hour [Mh/s]
					}
			    }
			}else{
		    	$this->ErrorFlag = True;
				$this->ErrorMsg = $this->ErrorMsg.$response->error."<br>";
			}
			
			try{
				##Worker: Reported [Mh/s]. Maybe some Pool don't have this.
				$response = file_get_contents($this->ReportedAPIurl.$this->Address."/".$this->Worker);
				$response = json_decode($response);
				if($response->status=='true'){
					$this->ReportedHashRate = $response->data;    #
				}else{
					$this->ErrorFlag = True;
					$this->ErrorMsg = $this->ErrorMsg.$response->error."<br>";
				}
			}catch (Exception $e){
				$this->ErrorMsg = $e->getMessage();
				$this->ErrorFlag = True;
			}

		
		}catch (Exception $e){
		    $this->ErrorMsg = $e->getMessage();
		    $this->ErrorFlag = True;
		}
	}
}

?>