<?php
include_once ("../_obj/Schedule.php");
include_once ("../_obj/Conference.php");
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
	$l_title="Schedule | ";
	$l_time = "Time: ";
	$l_loca = "Location: ";
	$l_t_date = "Date";
	$l_t_time = "Time";
	$l_t_loca = "Location";
	$l_t_summ = "Summary";
	$l_t_note = "Note";
	$l_all = "All";
	$l_apply = "The event organizer need to manually put you in this event, ConfOne has informed the organizer. Please wait and check your email or ConfOne message box for acceptance.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="日程安排 | ";
	$l_time = "时间：";
	$l_loca = "地点：";
	$l_t_date = "日期";
	$l_t_time = "时间";
	$l_t_loca = "地点";
	$l_t_summ = "内容";
	$l_t_note = "备注";
	$l_all = "全部";
	$l_apply = "参加该活动需要活动举办方同意，会云已经通知举办方您有意参与他们的活动。请关注您的邮箱和站内信，等候回复。";
}

//=========================================================================================================

$sches = Schedule::getConfScheObjs($conf->getCid());
$dArr = array();
$lArr = array();

foreach ($sches as $sche) {
	$hasD = false;
	$hasL = false;

	foreach ($dArr as $d) {
		if($sche->date == $d) {
			$hasD = true;
			break;
		}
	}

	foreach ($lArr as $l) {
		if($sche->addr == $l) {
			$hasL = true;
			break;
		}
	}

	if(!$hasD) $dArr[$sche->getSid()] = $sche->date;
	if(!$hasL) $lArr[$sche->getSid()] = $sche->addr;
}

if(sizeof($dArr) == 1) $dArr = array();
if(sizeof($lArr) == 1) $lArr = array();

$index = 0;

$page = 2;
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
<script type="text/JavaScript" src="../js/jquery-1.6.2.min.js"></script>
<script type="text/JavaScript" src="../js/jquery.metadata.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
.selc_d,.selc_l{font-size:.8em;padding:5px;cursor:pointer;text-decoration:underline;color:blue;}
.selc_l:hover,.selc_d:hover{text-decoration:none;}
.selc_act{background:#9CC7E5;color:#fff;padding:4px;text-decoration:none;}
table#choice{padding-bottom:10px;margin-bottom:10px;border-bottom:1px solid #AAA;}
table#choice tr{line-height:1.3em;}
table#choice td.lab{font-size:.8em;font-weight:bold;white-space:nowrap;}
</style>
<script type="text/javascript">
$(function () {
    $(".selc_d,.selc_l").click(function () {
        $(this).addClass("selc_act");
        $(this).siblings().removeClass("selc_act");

        var d = $(".selc_d").filter(".selc_act").metadata().v;
        var l = $(".selc_l").filter(".selc_act").metadata().v;

        $(".Conf .elem").each(function () {
            var e_d = $(this).metadata().date;
            var e_l = $(this).metadata().loca;

			if (e_d == d && e_l == l) $(this).show();
			else if (e_d == d && l == undefined) $(this).show();
			else if (e_l == l && d == undefined) $(this).show();
			else if (l == undefined && d == undefined) $(this).show();
			else $(this).hide();
        });
    });
});
</script>
</head>
<body <?php if(isset($_SESSION['_applyJoin'])) { unset($_SESSION['_applyJoin']); echo "onload='alert(\"".$l_apply."\")'";}?>>
<div style="width:100%;">
<div class="stand_width">
<?php include_once '../conference/info_header.inc.php';?>
<div id="hmain">
<div id="hright">
<div style="padding: 20px 0 20px 10px;font-size:.8em;"><?php include_once "../common/JiaThisB32.inc.php";?></div>
</div>
<div id="hleft">
<div id="h_ctop">
<table id="choice">
<tr><td class="lab"><?php echo $l_time?></td>
<td><a class="selc_d selc_act round_border_6"><?php echo $l_all?></a>
<?php foreach ($dArr as $d) {?>
<a class="selc_d {v:'<?php echo $d?>'} round_border_6"> <?php echo $d?></a> 
<?php }?>
</td>
</tr>
<tr><td class="lab"><?php echo $l_loca?></td>
<td><a class="selc_l selc_act round_border_6"><?php echo $l_all?></a>
<?php foreach ($lArr as $l) {?>
<a class="selc_l {v:'<?php echo $l?>'} round_border_6"> <?php echo $l?></a> 
<?php }?>
</td>
</tr>
</table>
</div>
<table class="Conf" cellspacing="0" cellpadding="0">
<tr id="head"><td class="day"><?php echo $l_t_date?></td><td class="time"><?php echo $l_t_time?></td><td><?php echo $l_t_loca?></td><td><?php echo $l_t_summ?></td></tr>
<?php foreach ($sches as $sche){?>
<tr class="<?php echo ($index%2==0 ? "odd" : "even")?> {date:'<?php echo $sche->date;?>', loca:'<?php echo $sche->addr;?>'} elem">
<td><?php echo $sche->date;?></td>
<td><?php echo $sche->stime.' ~ '.$sche->etime?></td>
<td><?php echo $sche->addr;?></td>
<td title="<?php echo $sche->note?>"><?php echo $sche->summ;?></td>
</tr>
<?php $index++;}?>
</table>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>