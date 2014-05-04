<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Message.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::mCheckLogin();

$atReturn = 0;
if( isset($_GET['fjoin']) && !empty($_GET['fjoin']) )
{
	$conf = Conference::getConfUrlStatus($_GET['fjoin']);

	if( isset($conf['url']) && !empty($conf['url']) && $conf['status']=='P')
	{
		if( !Conference::isJoined($_GET['fjoin'], $_SESSION['_userId']) )
		{
			if( $conf['registration']=='O') {
				if ( !Conference::joinConf($_GET['fjoin'], $_SESSION['_userId']) )
				{
					$atReturn = 1;
				}
			}
			else {
				Message::createJoinConfMessage( $_SESSION['_userId'],
												$_SESSION['_loginUser']->fullname,
												$_SESSION['_loginUser']->email,
												$_SESSION['_loginUser']->cell,
												$conf['name'],
												$conf['cid'],
												$conf['owner'] );
				$_SESSION['_applyJoin'] = 1;
				$atReturn = 4;
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