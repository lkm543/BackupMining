<?php

class DataType{
	public $Address;
	public $Worker;
	public $HashRate;
	public $HashRate_LongTerm;
	public $ReportedHashRate;
	public $Balance;
	public $Coin;

	public function resetData(){
		$this->Address = "";
		$this->Worker = "";
		$this->HashRate = 0.0;
		$this->HashRate_LongTerm = 0.0;
		$this->ReportedHashRate = 0.0;
		$this->Balance = 0.0;
		$this->Coin = "ETH";
	}
}

?>