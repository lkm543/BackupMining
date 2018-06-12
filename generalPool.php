<?php
abstract class ClassName
{
	public $Address;
	public $Worker;
	public $HashRate;
	public $HashRate_LongTerm;
	public $Balance;
	public $Coin;
	
	abstract public function getData();

	public function print(){
		echo("Address:".$Address."<br>");
		echo("Worker:".$Worker."<br>");
		echo("HashRate:".$HashRate."<br>");
		echo("HashRate_LongTerm:".$HashRate_LongTerm."<br>");
		echo("Balance:".$Balance."<br>");
		echo("Address:".$Coin."<br>");
	}

	public function setCoin($CoinInput){
		$Coin = $CoinInput;
	}

	'''
	function __construct(argument)
	{
		# code...
	}
	'''
}

?>