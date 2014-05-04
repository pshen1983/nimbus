<?php
include_once ("../_obj/Message.php");
include_once ("../_obj/Conference.php");
include_once ("../utils/SecurityUtil.php");

session_start();

function notifySpeakers($cid, $url)
{
	$name = null;
	if(isset($_SESSION['_confsEdit']) && isset($_SESSION['_confsEdit'][$url]))
	{
		$name = $_SESSION['_confsEdit'][$url]->name;
	}
	else {
		$name = Conference::getConfName($cid);
	}

	//============================================= Language ==================================================

	if($_SESSION['_language'] == 'en') {
		$l_spea_title = "Speaker invitation.";
		$l_spea_message = $_SESSION['_loginUser']->fullname." has invited you to speak at the event - ".$name;
	}
	else if($_SESSION['_language'] == 'zh') {
		$l_spea_title = "演讲嘉宾邀请";
		$l_spea_message = $_SESSION['_loginUser']->fullname."邀请您作为演讲嘉宾参加会议活动 —— ".$name;
	}

	//=========================================================================================================

	$speakerIds = Conference::getConfSpeakerIds($cid);

	while($id = mysql_fetch_array($speakerIds, MYSQL_ASSOC))
	{
		Message::create($l_spea_title, $id['uid'], $_SESSION['_userId'], $l_spea_message);
	}
}

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['url']) && !empty($_GET['url']))
	{
		if(Conference::isUserConfById($_SESSION['_userId'], $_GET['c']))
		{
			if( Conference::publishConf($_GET['c']) )
			{
				if( isset($_SESSION['_confsEdit']) &&  isset($_SESSION['_confsEdit'][$_GET['url']]) )
				{
					$_SESSION['_confsEdit'][$_GET['url']]->status='P';
				}

				notifySpeakers($_GET['c'], $_GET['url']);

				echo 0;
			}
			else {
				echo 4;
			}
		}
		else {
			echo 3;
		}
	}
	else {
		echo 2;
	}
}
else {
	echo 1;
}
?>