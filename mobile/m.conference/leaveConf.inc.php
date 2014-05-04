<?php        
include_once ("../_obj/Conference.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::mCheckLogin();

$atReturn = 0;
if( isset($_GET['cid']) && !empty($_GET['cid']) )
{
	$conf = Conference::getConfUrlStatus($_GET['cid']);

	if( isset($conf['url']) && !empty($conf['url']) && $conf['status']=='P')
	{
		if( Conference::isJoined($_GET['cid'], $_SESSION['_userId']) )
		{
			if( !Conference::leaveConf($_GET['cid'], $_SESSION['_userId']) )
			{
				$atReturn = 1;
			}
		}
	}
	else {
		$atReturn = 2;
	}
}
else {
	$atReturn = 3;
}

echo $atReturn;
?>