<?php
include_once ("../_obj/Conference.php");
include_once ("../_obj/Sponsor.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
SecurityUtil::cookieLogin();

if( !isset($_GET['url']) || empty($_GET['url']) )
{
	header( 'Location: ../conference/index.php' );
	exit;
}

$conf = Conference::getConfInfo($_GET['url']);

if( $conf->search == 'N' && 
	( !isset($_SESSION['_userId']) || 
	  ( !Conference::isJoined($conf->getCid(), $_SESSION['_userId']) && 
	    !Conference::isUserConfById($_SESSION['_userId'], $conf->getCid() ) ) ) )
{
	header( 'Location: ../default/index.php' );
	exit;
}

$sftime = strtotime($conf->stime);
$sdate = date('Y.m.d', $sftime);
$stime = date('H:i', $sftime);
if($stime == '00:00') $stime = '';

$eftime = strtotime($conf->etime);
$edate = date('Y.m.d', $eftime);
$etime = date('H:i', $eftime);
if($etime == '00:00') $etime = '';

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Sponsers | ";
	$l_time = "Time";
	$l_loca = "Location";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="赞助商 | ";
	$l_time = "时间：";
	$l_loca = "地点：";
	
}

//=========================================================================================================

$page = 5;

$sponsors = Sponsor::getSponsorListByCid($conf->getCid());
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title.$conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/conference.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
table.spon{text-align:center;width:90%;}
table.spon td{vertical-align:middel;height:140px;border-bottom:1px solid #EEE;}
table.spon td a{text-decoration:none;color:#000080;display:block;}
table.spon td a img{max-height:100px;}</style>
</head>
<body>
<div style="width:100%;">
<div class="stand_width">
<?php include_once '../conference/info_header.inc.php';?>
<div id="hmain">
<div id="hright">
<div style="padding: 20px 0 20px 10px;font-size:.8em;"><?php include_once "../common/JiaThisB32.inc.php";?></div>
</div>
<div id="hleft">
<div id="h_ctop">

</div>
<div id="h_cbody">
<table class="spon" align="center">
<?php foreach ($sponsors as $spon) {?>
<tr><td>
<a href="<?php echo $spon->url;?>" target="_blank" title="<?php echo $spon->url?>">
<img style="border:0 none;" src="<?php echo $spon->img;?>" /><br /><?php echo $spon->cname;?></a>
</td></tr>
<?php } ?>
</table>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>