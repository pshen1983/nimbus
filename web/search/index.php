<?php
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
if(!isset($_SESSION['_userId'])) SecurityUtil::cookieLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Search | ConfOne";
	$l_search="You Search For";
	$l_time = "Time: ";
	$l_loca = "Location: ";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="搜索 | 会云网";
	$l_search="您搜索的词条";
	$l_time = "时间：";
	$l_loca = "地点：";
}

//=========================================================================================================

$confs = array();
if( isset($_POST['search']) && !empty($_POST['search']))
{
	$search = '<div style="margin:10px 0 10px 20px;color:#589ED0"><label>'.$_POST['search'].'</label></div>';
}

$confs = Conference::searchConfAll( (isset($_POST['search']) ? $_POST['search'] : ""),
									0, 
									20 );
	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/search.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
a.ctitle{color:blue;font-weight:bold;font-size:.9em;}
</style>
</head>
<body>
<div style="width:100%;">
<?php include_once isset($_SESSION['_userId']) ? '../common/header.inc.php' : '../common/header1.inc.php';?>
<div class="stand_width">
<div id="hmain">
<div id="hright">
</div>
<div id="hleft">
<div id="result">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_search?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<?php echo $search?>
<ul class="clist">
<!--  ================================================================================================================ --> 
<?php 
$ind = 0;
foreach ($confs as $conf) 
{
	$sftime = strtotime($conf->stime);
	$sdate = date('Y-m-d', $sftime);
	$stime = date('H:i', $sftime);
	$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');

	if( isset($conf->etime) && !empty($conf->etime) )
	{
		$eftime = strtotime($conf->etime);
		$edate = date('Y-m-d', $eftime);
		$etime = date('H:i', $eftime);
		$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
	}
?>
<li style="background-color:<?php echo ($ind%2==0) ? "#f1f1f1" : "#ffffff";?>;">
<div class="conf_link">
<!--div class="ccolor" style="background:<?php echo $_SESSION['_color'][$ind];?>;"></div -->
<table>
<tr><td><div class="img_f" style="background:url('<?php echo $conf->img?>') no-repeat center center;"></div></td>
<td style="padding-left:20px;">
<a style="color:<?php echo $_SESSION['_color'][$ind];?>" class="ctitle" href="../conference/info_home.php?url=<?php echo $conf->getUrl()?>" target="_blank"><?php echo $conf->name;?></a>
<div style="margin-top:10px;font-size:.8em;color:#333;">
<label><span style="font-weight:bold"><?php echo $l_time;?></span><?php echo $tprint;?></label><br />
<label><span style="font-weight:bold"><?php echo $l_loca;?></span><?php echo $conf->getFaddr();?></label>
</div></td></tr></table></div></li>
<?php 
$ind++;
}
if($ind==0) {
	echo '<div style="margin:10px 0 10px 20px;color:#589ED0"><label>'.$l_no_join.'</label></div>';
}?>
<!--  ================================================================================================================ --> 
</ul></div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>