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
$email = User::getEmailById($_GET['p1']);
//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_m_title = "Cannot join event: ".$name;
	$l_m_body = 'Cannot join event: <a target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a>, event organizer has rejected your request to join this event.';
	$l_e_title = "Cannot join event: ".$name;
	$l_e_body = 'Cannot join event: '.$name.'. <br /> 
				 Cannot join event: <a target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a>, event organizer has rejected your request to join this event.';
}
else if($_SESSION['_language'] == 'zh') {
	$l_m_title = "暂时无法加入活动：".$name;
	$l_m_body = '活动：<a target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a> 暂时无法加入，举办方没能通过您的参加申请。';
	$l_e_title = "暂时无法加入活动：".$name;
	$l_e_body = '活动：<a target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a> 暂时无法加入，举办方没能通过您的参加申请。';
}

//=========================================================================================================

			Message::create($l_m_title, $_GET['p1'], $_SESSION['_userId'], $l_m_body);
			MessageUtil::sendEmail($email, $l_e_title, $l_e_body);
		}
	}
}

header( 'Location: ../message/index.php' ) ;
exit;
?>