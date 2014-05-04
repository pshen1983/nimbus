<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Message.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

if( isset($_POST['fjoin']) && !empty($_POST['fjoin']) )
{
	$conf = Conference::getConfIdStatus($_POST['fjoin']);

	if( isset($conf['cid']) && !empty($conf['cid']) && $conf['status']=='P')
	{
		if( !Conference::isJoined($conf['cid'], $_SESSION['_userId']) )
		{
			if( $conf['registration']=='O') {
				Conference::joinConf($conf['cid'], $_SESSION['_userId']);
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
			}
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