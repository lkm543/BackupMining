<?php
date_default_timezone_set("Asia/Taipei").

require_once( 'generalPool.php' );

//Reported HashRate
class ethfan extends generalPool {
	private $BillAPIurl = "https://eth.sparkpool.com/api/miner/Address/billInfo";
	private $HashRateAPIurl = "https://eth.sparkpool.com/api/worker/hashrate?rig=WorkerName&wallet=Address";
	private $HashRateAPIurlLongTerm = "https://eth.sparkpool.com/api/miner/Address/workers";

	public $ErrorMsg;
	public $ErrorFlag;


	public function __construct()
	{
		if($this->Coin == "ETH"){
			$this->BillAPIurl = "https://eth.sparkpool.com/api/miner/Address/workers";
			$this->HashRateAPIurl = "https://eth.sparkpool.com/api/worker/hashrate?rig=WorkerName&wallet=Address";
			$this->HashRateAPIurlLongTerm = "https://eth.sparkpool.com/api/miner/Address/workers";
		}
		$this->ErrorFlag = False;
		$this->ErrorMsg = "";
	}

	public function reset()
	{
		if($this->Coin == "ETH"){
			$this->BillAPIurl = "https://eth.sparkpool.com/api/miner/Address/workers";
			$this->HashRateAPIurl = "https://eth.sparkpool.com/api/worker/hashrate?rig=WorkerName&wallet=Address";
			$this->HashRateAPIurlLongTerm = "https://eth.sparkpool.com/api/miner/Address/workers";
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
			$newAddress = str_replace("0x","",$this->Address);
			//Balance
			$newURL = str_replace("Address",$newAddress,$this->BillAPIurl);
			//echo("------------------------------------------<br>");
			//echo($newURL."<br>");
			$response = file_get_contents($newURL);
			$response = json_decode($response);
			$this->Balance = floatval($response->balance)/1000000000000000000;
			//echo(var_dump($response->balance)."<br>");
			//if(is_null($response->balance))
		    //	$this->ErrorFlag = True;
			//echo($this->Balance."<br>");

			//Worker: Reported
			$newURL = str_replace("Address",$newAddress,$this->HashRateAPIurl);
			$newURL = str_replace("WorkerName",$this->Worker,$newURL);
			//echo($newURL."<br>");

			$response = file_get_contents($newURL);
			$response = json_decode($response);
			//echo(var_dump($response->data)."<br>");
			//if(is_null($response->data))
		    //	$this->ErrorFlag = True;

			$this->HashRate = floatval(end($response->data)->hashrate)/1000000;
			$this->ReportedHashRate	 = floatval(end($response->data)->localHashrate);


			$newURL = str_replace("Address",$newAddress,$this->HashRateAPIurlLongTerm);
			//echo($newURL."<br>");

			$response = file_get_contents($newURL);
			//echo($APIurl.$Address);
			$response = json_decode($response);
			//echo(var_dump($response->data)."<br>");
			//if(is_null($response->data==NULL))
		    //	$this->ErrorFlag = True;

			foreach($response->data as $workers)
		    {			         
		    	if($workers->rig == $this->Worker){
					$this->HashRate_LongTerm = floatval($workers->hashrate1d)/1000000;
					//echo($workers->hashrate1d."<br>");
				}
		    }        

		}
		catch (Exception $e) {
		    $this->ErrorMsg = $e->getMessage();
		    $this->ErrorFlag = True;
		}
	}
}

?>