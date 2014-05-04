<?php
include_once ("../_obj/User.php");
include_once ("../_obj/Conference.php");
include_once ("../_obj/Message.php");
include_once ("../utils/MessageUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);
if( isset($_GET['p1']) && !empty($_GET['p1']) && isset($_GET['p2']) && !empty($_GET['p2']) )
{
	if( Conference::isUserConfById($_SESSION['_userId'], $_GET['p2']) )
	{
		if( !Conference::isJoined($_GET['p2'], $_GET['p1']) )
		{

$name = Conference::getConfName($_GET['p2']);
$url = Conference::getConfUrl($_GET['p2']);
$email = User::getEmailById($_GET['p1']);
//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_m_title = "Welcome to event: ".$name;
	$l_m_body = 'Check out event details: <a target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a>';
	$l_e_title = "Welcome to event: ".$name;
	$l_e_body = 'Welcome to event: '.$name.'. <br /> 
				 To check out the details please visit <a target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a>';
}
else if($_SESSION['_language'] == 'zh') {
	$l_m_title = "欢饮参加活动：".$name;
	$l_m_body = '查看活动细节：<a target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a>';
	$l_e_title = "欢饮参加活动：".$name;
	$l_e_body = '欢饮参加活动：'.$name.' <br /> 关注活动细节：<a target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a>';
}

//=========================================================================================================

			Conference::joinConf($_GET['p2'], $_GET['p1']);
			Message::create($l_m_title, $_GET['p1'], $_SESSION['_userId'], $l_m_body);
			MessageUtil::sendEmail($email, $l_e_title, $l_e_body);
		}
	}
}

header( 'Location: ../message/index.php' ) ;
exit;
?>