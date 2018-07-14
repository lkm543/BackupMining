<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0" />
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>內部測試</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>

<script type="text/javascript">
	$(document).ready(
		function() {
			$.ajaxSetup({ cache: false }); // This part addresses an IE bug. without it, IE will only load the first number and will never refresh
			setInterval(
				function() {
					var requestURL = 'https://digital-espacio.com/API/ActualMiner/3/Admin/ea264832617374326d05e912dd21e449';
					var request = new XMLHttpRequest();
					request.open('Get',requestURL);
					request.responseType = 'json';
					request.onreadystatechange = function() {
						content('MsgCode',this.response['MsgCode']);
						content('Message',this.response['Message']);
						content('EarningETH_EthFan',this.response['EarningETH_EthFan']);
						content('EarningETH_F2Pool',this.response['EarningETH_F2Pool']);
						content('ETHUSD',this.response['ETHUSD']);
						content('USDTWD',this.response['USDTWD']);

						content('TotalSpecifiedHashRate',this.response['TotalSpecifiedHashRate']);
						content('TotalReportHashRate',this.response['TotalReportHashRate']);
						content('TotalLongTermHashRate',this.response['TotalLongTermHashRate']);
						
						content('CardAmounts',this.response['CardAmounts']);
						content('FineCards',this.response['FineCards']);
						content('SlowReportCards',this.response['SlowReportCards']);
						content('SlowLongtermCards',this.response['SlowLongtermCards']);
						content('ShutDownCards',this.response['ShutDownCards']);
						content('APIErrorCards',this.response['APIErrorCards']);

						content('Estimate2Y RevenueTWD2Y',this.response['Estimate2Y']['RevenueTWD2Y']);
						content('Estimate2Y ROI(%)',this.response['Estimate2Y']['ROI(%)']);
						content('Estimate2Y BEP(ETHUSD)',this.response['Estimate2Y']['BEP(ETHUSD)']);

						content('Estimate4Y RevenueTWD4Y',this.response['Estimate4Y']['RevenueTWD4Y']);
						content('Estimate4Y ROI(%)',this.response['Estimate4Y']['ROI(%)']);
						content('Estimate4Y BEP(ETHUSD)',this.response['Estimate4Y']['BEP(ETHUSD)']);
						content('UpdateTime',this.response['UpdateTime']);
					};
					request.send();

				}, 5000); // the "3000" here refers to the time to refresh the div. it is in milliseconds.
			setInterval(
				function() {
					var now = new Date();
					content('TimeNow',now.toLocaleTimeString());
			}, 1000); // the "3000" here refers to the time to refresh the div. it is in milliseconds.
			
		}
	);
	function content(divSelector, value) {
    		document.getElementById(divSelector).innerHTML = value;
	}
</script>
MsgCode:<div id="MsgCode">Loading data ...</div>
Message:<div id="Message">Loading data ...</div>
EarningETH_EthFan:<div id="EarningETH_EthFan">Loading data ...</div>
EarningETH_F2Pool:<div id="EarningETH_F2Pool">Loading data ...</div>
ETHUSD:<div id="ETHUSD">Loading data ...</div>
USDTWD:<div id="USDTWD">Loading data ...</div>


總額定算力:<div id="TotalSpecifiedHashRate">Loading data ...</div>
總回報算力:<div id="TotalReportHashRate">Loading data ...</div>
總長期平均算力:<div id="TotalLongTermHashRate">Loading data ...</div>

總卡數:<div id="CardAmounts">Loading data ...</div>
正常卡數:<div id="FineCards">Loading data ...</div>
回報低落:<div id="SlowReportCards">Loading data ...</div>
長期低落:<div id="SlowLongtermCards">Loading data ...</div>
掉卡/當機:<div id="ShutDownCards">Loading data ...</div>
API錯誤:<div id="APIErrorCards">Loading data ...</div>

兩年利潤:<div id="Estimate2Y RevenueTWD2Y">Loading data ...</div>
兩年投報:<div id="Estimate2Y ROI(%)">Loading data ...</div>
損益兩平幣價:<div id="Estimate2Y BEP(ETHUSD)">Loading data ...</div>

四年利潤:<div id="Estimate4Y RevenueTWD4Y">Loading data ...</div>
四年投報:<div id="Estimate4Y ROI(%)">Loading data ...</div>
損益兩平幣價:<div id="Estimate4Y BEP(ETHUSD)">Loading data ...</div>

更新時間:<div id="UpdateTime">Loading data ...</div>
現在時間:<div id="TimeNow">Loading data ...</div>

</body>
</html>