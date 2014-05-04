<?php        
include_once ("../_obj/Conference.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

if( isset($_POST['leave']) && !empty($_POST['leave']) )
{
	$conf = Conference::getConfIdStatus($_POST['leave']);

	if( isset($conf['cid']) && !empty($conf['cid']) && $conf['status']=='P')
	{
		if( Conference::isJoined($conf['cid'], $_SESSION['_userId']) )
		{
			Conference::leaveConf($conf['cid'], $_SESSION['_userId']);
		}
	}
}

if( isset($_POST['curl']) && !empty($_POST['curl']) )
{
	header( 'Location: '.$_POST['curl'] ) ;
}
else
{
	header( 'Location: ../home/index.php' ) ;
}

?>