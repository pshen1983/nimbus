<?php
include_once ("../_obj/User.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../utils/SessionUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);
SessionUtil::initLocation();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Happening | ConfOne";
	$l_all="Loading Events";
	$l_within="Event Around You";
	$l_samecity="Event In Your City";
	$l_sameprov="Event In Your Province";
	$l_other="Other Event";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="正在上映 | 会云网";
	$l_all="载入会议活动";
	$l_within="您周围的会议活动";
	$l_samecity="同城的会议活动";
	$l_sameprov="同省的会议活动";
	$l_other="所有会议活动";
}

//=========================================================================================================

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<style type="text/css">
html,body{margin:0;padding:0;}
.confElt{padding: 20px 0 20px 20px;}
a.ctitle{color:#000080;font-weight:bold;font-size:.9em;}
</style>
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/home.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>

<script type="text/javascript">
$(document).ready(function($){
	var imgsrc = '<img src="../image/progress-bar.gif" />';
	$("#all").html(imgsrc);
	var geocoder;
//	if (navigator.geolocation) {
//		navigator.geolocation.getCurrentPosition(function(position) {  
//			//document.getElementById("lat").value =  position.coords.latitude;
//			//document.getElementById("lng").value =  position.coords.longitude;
//			var dataString = 'lat='+position.coords.latitude+'&lng='+position.coords.longitude;
//			$.ajax({
//			      type: "POST",
//			      url: "selConf.inc.php",
//			      data: dataString,
//	              dataType: 'json',
//			      success: function(data) {
//						if (data.neighbor == 'none' && data.samecity == 'none' && data.sameprov == 'none' && data.other == 'none')
//						{
//							$("#all").html(data.all);
//						}
//						else
//						{
//							$("#allList").hide();
//							if (data.neighbor == 'none')
//							{
//								$("#neighborList").hide();
//							}
//							else
//							{
//								$("#neighborList").show();
//								$("#neighbor").html(data.neighbor);
//							}
//							
//							if (data.samecity == 'none')
//							{
//								$("#samecityList").hide();
//							}
//							else
//							{
//								$("#samecityList").show();
//								$("#samecity").html(data.samecity);
//							}
//							
//							if (data.sameprov == 'none')
//							{
//								$("#sameprovList").hide();
//							}
//							else
//							{
//								$("#sameprovList").show();
//								$("#sameprov").html(data.sameprov);
//							}
//							
//							if (data.other == 'none')
//							{
//								$("#otherList").hide();
//							}
//							else
//							{
//								$("#otherList").show();
//								$("#other").html(data.other);
//							}
//						}
//			      }
//			});		
//		});
//	} 
//	else	
//	{
	  //alert("geolocation services are not supported by your browser.");
	  var dataString = 'lat=&lng=';
		$.ajax({
		      type: "POST",
		      url: "selConf.inc.php",
		      data: dataString,
              dataType: 'json',
		      success: function(data) {
				$("#neighborList").hide();

				if (data.neighbor == 'none' && data.samecity == 'none' && data.sameprov == 'none' && data.other == 'none')
				{
					$("#all").html(data.all);
				}
				else
				{
					$("#allList").hide();
					if (data.samecity == 'none')
					{
						$("#samecityList").hide();
					}
					else
					{
						$("#samecityList").show();
						$("#samecity").html(data.samecity);
					}
					
					if (data.sameprov == 'none')
					{
						$("#sameprovList").hide();
					}
					else
					{
						$("#sameprovList").show();
						$("#sameprov").html(data.sameprov);
					}
					
					if (data.other == 'none')
					{
						$("#otherList").hide();
					}
					else
					{
						$("#otherList").show();
						$("#other").html(data.other);
					}
				}
		      }
		});
//	}
});
</script>
</head>
<body>
<div style="width:100%;">
<?php $_GET['f']='w'; include_once '../common/header.inc.php'; unset($_GET['w'])?>
<div class="stand_width">
<div id="hmain">
<div id="hright">
</div>
<div id="hleft">
<div class="confElt" id="allList">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_all?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<div id="all"></div>
</div>
<!--  div class="confElt" id="neighborList" style="display:none">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_within?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<div id="neighbor"></div>
</div -->
<div class="confElt" id="samecityList" style="display:none">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_samecity?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<div id="samecity"></div>
</div>
<div class="confElt" id="sameprovList" style="display:none">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_sameprov?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<div id="sameprov"></div>
</div>
<div class="confElt" id="otherList" style="display:none">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_other?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<div id="other"></div>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>