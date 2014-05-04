<?php
include_once ("../_obj/Broadcast.php");
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
	$l_title="Broadcast | ";
	$l_time = "Time: ";
	$l_loca = "Location: ";
	$l_apply = "The event organizer need to manually put you in this event, ConfOne has informed the organizer. Please wait and check your email or ConfOne message box for acceptance.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="附加信息 | ";
	$l_time = "时间：";
	$l_loca = "地点：";
	$l_apply = "参加该活动需要活动举办方同意，会云已经通知举办方您有意参与他们的活动。请关注您的邮箱和站内信，等候回复。";
	
}

//=========================================================================================================

$page = 6;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title.$conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<style type="text/css">html,body{margin:0;padding:0;}a{color:blue;}</style>
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/conference.js"></script>
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>  
<script type="text/javascript">
jQuery(function($){
	// hide_show
	$(".show_hide").live('click',function(){
		var id=$(this).attr('id').replace("TableLink","");
		$("#Bbody"+id).slideToggle();
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

</div>
<div id="h_cbody">
<table id="ListTable" style="width:100%;" class="" cellspacing='10' cellpadding='0'>
<?php 
	$ConfScheList=Broadcast::getConfBcastList($conf->getCid());
	while($row = mysql_fetch_array($ConfScheList))
	{ 		
		echo '<tr id="Tr'.$row['id'].'"><td>
			  <div style="font-size:.8em;float:right">'.date("Y-m-d, g:i A", strtotime($row['utime'])).'</div>
			  <a style="display:block" href="#" class="show_hide" id="TableLink'.$row['id'].'">'.$row['btitle'].'</a>
			  </td></tr>'.
			 '<tr><td style="background-color:#fff;padding-left:10px;">
			  <div id="Bbody'.$row['id'].'" style="display:none">'.$row['bbody'].'</div>
			  </td></tr>';
 	}
?>
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