<?php
date_default_timezone_set("Asia/Taipei").

require_once( 'generalPool.php' );

//Reported HashRate
class nano extends generalPool {
	private $APIurl = "https://api.nanopool.org/v1/eth/user/";
	private $ReportedAPIurl = "https://api.nanopool.org/v1/eth/reportedhashrate/";
	public $ErrorMsg;
	public $ErrorFlag;


	public function __construct()
	{
		if($this->Coin == "ETH"){
			$this->APIurl = "https://api.nanopool.org/v1/eth/user/";
			$this->ReportedAPIurl = "https://api.nanopool.org/v1/eth/reportedhashrate/";
		}
		$this->ErrorFlag = False;
		$this->ErrorMsg = "";
	}

	public function reset()
	{
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
		try {
			//Balance
			$response = file_get_contents($this->APIurl.$this->Address);
			//echo($APIurl.$Address);
			$response = json_decode($response);
			if($response->status=='true'){
				$this->ErrorFlag = False;
				$this->ErrorMsg = "";
				$this->Balance = $response->data->balance;

				foreach($response->data->workers as $workers)
			    {			         
			    	if($workers->id == $this->Worker){
						$this->HashRate = floatval($workers->hashrate);
						$this->HashRate_LongTerm = floatval($workers->h24);
					}
			    }        
			}
			else{
		    	$this->ErrorFlag = True;
				$this->ErrorMsg = $this->ErrorMsg.$response->error."<br>";
			}
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

		}
		catch (Exception $e) {
		    $this->ErrorMsg = $e->getMessage();
		    $this->ErrorFlag = True;
		}
	}
}

?>