<?php
date_default_timezone_set("Asia/Taipei").

require_once("../database.php");
$db = new db();
$db->DBConnect();

$APIURL = "https://api.coinmarketcap.com/v2/ticker/";
$response = file_get_contents($APIURL);
//echo(var_dump($response));
$response = json_decode($response, ture);

//echo(var_dump($response));
//echo(var_dump($response["data"]["1"]));
$BTCUSD = $response["data"]["1"]["quotes"]["USD"]["price"];
$ETHUSD = $response["data"]["1027"]["quotes"]["USD"]["price"];
$ETCUSD = $response["data"]["1321"]["quotes"]["USD"]["price"];
$ZECUSD = $response["data"]["1437"]["quotes"]["USD"]["price"];
$AIONUSD = $response["data"]["2062"]["quotes"]["USD"]["price"];
$XMRUSD = $response["data"]["328"]["quotes"]["USD"]["price"];
$NEOUSD = $response["data"]["1376"]["quotes"]["USD"]["price"];
$IOTAUSD = $response["data"]["1720"]["quotes"]["USD"]["price"];
$LTCUSD = $response["data"]["2"]["quotes"]["USD"]["price"];
$BCHUSD = $response["data"]["1831"]["quotes"]["USD"]["price"];
$XRPUSD = $response["data"]["52"]["quotes"]["USD"]["price"];
$EOSUSD = $response["data"]["1765"]["quotes"]["USD"]["price"];
$DASHUSD = $response["data"]["131"]["quotes"]["USD"]["price"];
$ADAUSD = $response["data"]["2010"]["quotes"]["USD"]["price"];
$mktsTime = date('Y-m-d H:i:s', $response["metadata"]["timestamp"]);


$APIURL = "https://api.coinmarketcap.com/v2/ticker/2655/";
$response = file_get_contents($APIURL);
$response = json_decode($response, ture);
$XMCUSD = $response["data"]["quotes"]["USD"]["price"];

//Earning
$APIURL = "https://www.f2pool.com";
$response = file_get_contents($APIURL);

$re = '/(data-currency=\"BTC\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningBTC = floatval($matches[0][2]);
//echo(var_dump($EarningBTC));

$re = '/(data-currency=\"LTC\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningLTC = floatval($matches[0][2]);
//echo(var_dump($EarningLTC));

$re = '/(data-currency=\"ZEC\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningZEC = floatval($matches[0][2]);
//echo(var_dump($EarningZEC));

$re = '/(data-currency=\"ETH\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningETH = floatval($matches[0][2]);
//echo(var_dump($EarningETH));

$re = '/(data-currency=\"ETC\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningETC = floatval($matches[0][2]);
//echo(var_dump($EarningETC));

$re = '/(data-currency=\"SC\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningSC = floatval($matches[0][2]);
//echo(var_dump($EarningSC));

$re = '/(data-currency=\"DASH\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningDASH = floatval($matches[0][2]);
//echo(var_dump($EarningDASH));

$re = '/(data-currency=\"XMR\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningXMR = floatval($matches[0][2]);
//echo(var_dump($EarningXMR));

$re = '/(data-currency=\"XMC\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningXMC = floatval($matches[0][2]);
//echo(var_dump($EarningXMC));

$re = '/(data-currency=\"DCR\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningDCR = floatval($matches[0][2]);
//echo(var_dump($EarningDCR));

$re = '/(data-currency=\"XZC\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningXZC = floatval($matches[0][2]);
//echo(var_dump($EarningXZC));

$re = '/(data-currency=\"AION\" data-profit=\")(.*)(\" data-unit)/m';
preg_match_all($re, $response, $matches, PREG_SET_ORDER, 0);
$EarningAION = floatval($matches[0][2]);
//echo(var_dump($EarningAION));

$timeNow = date("Y-m-d H:i:s");

$sql = "INSERT INTO `Analysis` (`EarningBTC`, `EarningETH`, `EarningETC`, `EarningZEC`, `EarningAION`, `EarningXMC`, `EarningXMR`,`EarningLTC`, `EarningSC`, `EarningDASH`, `EarningDCR`, `EarningXZC`,`BTCUSD`, `ETHUSD`, `ETCUSD`, `ZECUSD`, `AIONUSD`, `XMCUSD`, `XMRUSD`, `NEOUSD`, `IOTAUSD`, `LTCUSD`, `BCHUSD`, `XRPUSD`, `EOSUSD`, `DASHUSD`, `ADAUSD`, `MarketTime`, `UpdateTime`) VALUES ($EarningBTC,$EarningETH,$EarningETC,$EarningZEC,$EarningAION,$EarningXMC,$EarningXMR,$EarningLTC,$EarningSC,$EarningDASH,$EarningDCR,$EarningXZC,$BTCUSD,$ETHUSD,$ETCUSD,$ZECUSD,$AIONUSD,$XMCUSD,$XMRUSD,$NEOUSD,$IOTAUSD,$LTCUSD,$BCHUSD,$XRPUSD,$EOSUSD,$DASHUSD,$ADAUSD,'{$mktsTime}','{$timeNow}')";

//echo(var_dump($sql));
$db->query($sql);
$db->close();
?>