<?php
date_default_timezone_set("Asia/Taipei").

require_once(dirname(dirname(__FILE__))."/generalPool.php");
#require_once("../generalPool.php");

//Reported HashRate
class uupool extends generalPool {
	
	private $APIurl = "http://uupool.cn/api/getWallet.php?coin=eth&address=";
	private $ReportedAPIurl;
	public $ErrorMsg;
	public $ErrorFlag;
	
    //
	public function __construct(){
		if($this->Coin == "ETH"){
			$this->APIurl = "http://uupool.cn/api/getWallet.php?coin=eth&address=";
			#$this->ReportedAPIurl = "";
		}
		$this->ErrorFlag = False;
		$this->ErrorMsg = "";
	}
    
	//
	public function reset()	{
		if($this->Coin == "ETH"){
			$this->APIurl = "http://uupool.cn/api/getWallet.php?coin=eth&address=";
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

				foreach(array_keys(get_object_vars($response->online)) as $workers){			         
			    	if($workers == $this->Worker){
						$this->HashRate = floatval(substr($response->online->$workers->hr1s, 0, -3));    #hashrate
						$this->HashRate_LongTerm = floatval(substr($response->online->$workers->hr2s, 0, -3));    #h24
					}
			    }
			}
			else{
		    	$this->ErrorFlag = True;
				$this->ErrorMsg = $this->ErrorMsg.$response->error."<br>";
			}
			/*
			#uupool沒有Reported資料
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

<?php
/*
example:
http://uupool.cn/api/getWallet.php?coin=eth&address=0x3853a62a5BEf58E3c6857D8CA60B69c99BCA0E37

{
"hr1s":"124.400 M",
"hr2s":"161.250 M",
"onlineWorkers":1,
"offlineWorkers":2,
"balance":72213962,
"paid":2763880403,
"online":{
	"wargod":{"last":1529391581,"reject":0,"isOnline":1,"hr1":124400000,"hr2":161250000,"hr1s":"124.400 M","hr2s":"161.250 M","rejects":"0","accepts":"0","lastShare":"2018-06-19 14:59:41","rate":"0%"}
	},

"offline":{
	"0":{"last":1529389387,"reject":0,"isOnline":null,"hr1":0,"hr2":129990000,"hr1s":"0 H","hr2s":"129.990 M","rejects":"0","accepts":"0","lastShare":"2018-06-19 14:23:07","rate":"0%"},
	"DigitalEspacio":{"last":1529221925,"reject":0,"isOnline":null,"hr1":0,"hr2":0,"hr1s":"0 H","hr2s":"0 H","rejects":"0","accepts":"0","lastShare":"2018-06-17 15:52:05","rate":"0%"}
	},
	
"payments":[{"time":"2018-06-14 10:03:01","txid":"0x4a71b6540e5e0f12a2e4cffa1e6c4cf9a6e9e5443b7b097fa1489c16fc6b5836","shortTxid":"0x4a71..6b5836","amountFloat":"0.1067 ETH"},{"time":"2018-06-10 10:03:02","txid":"0x71f7b38b7eeffdbd08b40c20e79c8e17aa33a3114e321cc3feb14f710bf095f9","shortTxid":"0x71f7..f095f9","amountFloat":"0.1046 ETH"},{"time":"2018-06-06 10:03:02","txid":"0x17634f8b3563557793437927ba9579d337a950083513d4e781e01e692c09e47e","shortTxid":"0x1763..09e47e","amountFloat":"0.0294 ETH"},{"time":"2018-06-05 10:16:45","txid":"0x6694118e0d502853b6b11bccaab9aaadc482aac55fb2648c466cf852d696903c","shortTxid":"0x6694..96903c","amountFloat":"0.021 ETH"},{"time":"2018-06-04 10:59:49","txid":"0xf532c92b672550bc902d6e0eadedbfd0414e8d6c8d4ac837edb0130ecdfe9cd1","shortTxid":"0xf532..fe9cd1","amountFloat":"0.0295 ETH"},{"time":"2018-06-03 10:12:08","txid":"0xfc3302e1f4b70e1c48986699b58919f416fa3ab5c8c3142d23f592c10b3767bd","shortTxid":"0xfc33..3767bd","amountFloat":"0.0248 ETH"},{"time":"2018-06-02 10:06:15","txid":"0x299bb81b69bd6a1cb547ebdabc30b5b3443eb3a3bc8de4d98e5adba76b914ae8","shortTxid":"0x299b..914ae8","amountFloat":"0.0247 ETH"},{"time":"2018-06-01 10:07:34","txid":"0x6415076c42bf712d311387fb31115700120bbc76ed787f2c6091d2377c124e86","shortTxid":"0x6415..124e86","amountFloat":"0.0226 ETH"},{"time":"2018-05-31 10:10:28","txid":"0x8158a2fa0324da7833d779c917fbb5cec2b2658871afc14f1944591e15f7ea59","shortTxid":"0x8158..f7ea59","amountFloat":"0.0143 ETH"},{"time":"2018-05-30 10:07:08","txid":"0x5947579454d639d310866dc1b14c5f772d8cd368801f72d80aeb19b61da3ec31","shortTxid":"0x5947..a3ec31","amountFloat":"0.0248 ETH"}],

"onlineRecord":1,"offlineRecord":2

}
*/
?>