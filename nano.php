<?php
date_default_timezone_set("Asia/Taipei").

require_once( 'generalPool.php' );
require_once( 'database.php' );

//Reported HashRate
class nano extends generalPool {
	private $APIurl;
	private $ReportedAPIurl;
	public $ErrorMsg;
	public $ErrorFlag;

	public function __construct()
	{
		if($Coin == "ETH"){
			$APIurl = "https://api.nanopool.org/v1/eth/user/";
			$ReportedAPIurl = "https://api.nanopool.org/v1/eth/reportedhashrate/";
		}
		$ErrorFlag = False;
		$ErrorMsg = "";
	}

	public function reset()
	{
		if($Coin == "ETH"){
			$APIurl = "https://api.nanopool.org/v1/eth/user/";
			$ReportedAPIurl = "https://api.nanopool.org/v1/eth/reportedhashrate/";
		}
		$ErrorFlag = False;
		$ErrorMsg = "";
		$Address = "NULL";
		$Worker = "NULL";
		$HashRate = 0.0;
		$HashRate_LongTerm = 0.0;
		$ReportedHashRate = 0.0;
		$Balance = 0.0;
		$Coin = "NULL";
	}	

	public function getDataFromPool(){
		try {
			//Balance
			$response = file_get_contents($APIurl.$Address);
			$response = json_decode($response);
			if($response->status=='true'){
				$ErrorFlag = False;
				$ErrorMsg = "";
				$Balance = $response->data->balance;

				foreach($response->workers as $workers)
			    {			         
			    	if($workers->id == $Worker){
						$HashRate = $workers->hashrate;
						$HashRate_LongTerm = $workers->h24;
					}
			    }        
			}
			else{
		    	$ErrorFlag = True;
				$ErrorMsg = $ErrorMsg.$response->error."<br>";
			}
			//Worker: Reported
			$response = file_get_contents($ReportedAPIurl.$Address."/".$Worker);
			$response = json_decode($response);
			if($response->status=='true'){
				$ReportedHashRate = $response->data;
			}
			else{
		    	$ErrorFlag = True;
				$ErrorMsg = $ErrorMsg.$response->error."<br>";
			}

		}
		catch (Exception $e) {
		    $ErrorMsg = $e->getMessage();
		    $ErrorFlag = True;
		}
	}

??