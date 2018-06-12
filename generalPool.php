<?php
abstract class generalPool
{
	public $Address;
	public $Worker;
	public $HashRate;
	public $HashRate_LongTerm;
	public $ReportedHashRate;
	public $Balance;
	public $Coin;
	
	//Generate Address Worker Coin from DB
	public function setBasicData($addr,$workerID,$CoinInput){
		$this->Address = $addr;
		$this->Worker = $workerID;
		$this->Coin = $CoinInput;
	}
	//Generate HashRate HashRate_LongTerm Balance from DB
	abstract public function getDataFromPool();

	//Reset Class while error!
	abstract public function reset();

	//abstract protected function dataProcessed();

	public function print(){
		echo("Address:".$this->Address."<br>");
		echo("Worker:".$this->Worker."<br>");
		echo("HashRate:".$this->HashRate."<br>");
		echo("HashRate_LongTerm:".$this->HashRate_LongTerm."<br>");
		echo("ReportedHashRate:".$this->ReportedHashRate."<br>");
		echo("Balance:".$this->Balance."<br>");
		echo("Coin:".$this->Coin."<br>");
	}

	protected function setCoin($CoinInput){
		$this->Coin = $CoinInput;
	}

	function __construct()
	{
		$this->Address = "NULL";
		$this->Worker = "NULL";
		$this->HashRate = 0.0;
		$this->HashRate_LongTerm = 0.0;
		$this->ReportedHashRate = 0.0;
		$this->Balance = 0.0;
		$this->Coin = "NULL";
	}
}

?>