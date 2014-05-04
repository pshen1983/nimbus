<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../_obj/Conference.php");

session_start();
SecurityUtil::mCheckLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_confone = "'s events";
	$l_add = "Add";
	$l_out = "More";
	$l_no_conf = "You do not have any event.";
	$l_near = "Find what's near";
	$l_or = " or ";
	$l_search = "All events.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_confone = "的活动";
	$l_add = "添加";
	$l_out = "更多";
	$l_no_conf = "您没有参加会议活动。";
	$l_near = "看看附近";
	$l_or = " 或 ";
	$l_search = "全部会议活动";
}

//=========================================================================================================

$ids='';
$confIds = Conference::getUserJoinedConfs($_SESSION['_userId']);

while($row = mysql_fetch_array($confIds, MYSQL_ASSOC))
{
	$ids = $ids.$row['cid'].', ';
}
$ids = $ids.'0';

$pList = Conference::getUserConfList($_SESSION['_userId'], 'P');
$confs = Conference::mGetRangeConfBasicInfo($ids, 'P');
$index = count($pList);
$hasId = false;

foreach($confs as $c)
{
	$hasId = false;
	$confId = $c->getCid();
	foreach ($pList as $p)
	{
		if($p->getCid() == $confId)
		{
			$hasId = true;
			break;
		}
	}

	if(!$hasId)
	{
		$pList[$index] = $c;
		$index++;
	}
}
?>
<!DOCTYPE html>
<html manifest="../m.common/m.confone.manifest">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=0;" />
<meta name="format-detection" content="telephone=no" />
<title><?php echo $l_title;?></title>
<link rel="apple-touch-icon" href="../m.image/cbutton.png" />
<link rel="apple-touch-icon-precomposed" href="../m.image/cbutton.png" />
<link rel="stylesheet" href="../m.css/jquery.mobile-1.0b3.css" /> 
<link rel="stylesheet" href="../m.css/generic.css" />
<script src="../m.js/jquery.min.js"></script>
<script src="../m.js/common.js"></script>
<script src="../m.js/conference.js"></script>
<script src="../m.js/jquery.mobile-1.0b3.js"></script>
</head> 
<body>
<div data-role="page" data-theme="b">
<div data-role="header" data-theme="b">
<a href="../m.home/join.php" data-icon="plus" data-transition="slidedown"><?php echo $l_add;?></a>
<h1><?php echo $_SESSION['_loginUser']->fullname.$l_confone;?></h1>
<a href="../m.home/option.php" data-icon="gear" class="ui-btn-right" data-transition="fade"><?php echo $l_out;?></a>
</div>
<div data-role="content" data-theme="d">
<?php 
echo Conference::printConfListM($pList);
if ( sizeof($pList)==0 ) {
	echo '<div>'.$l_no_conf.'<br /><a href="javascript:nEvents();">'.$l_near.'</a>'.$l_or.'<a href="../m.search/index.php?hid=0">'.$l_search.'</a></div>';
}
?>
</div>
<?php include_once '../m.common/footer.inc.php';?>
</div>
</body>
</html>