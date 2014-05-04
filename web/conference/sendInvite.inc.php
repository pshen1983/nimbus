<?php
include_once ("../_obj/Invitation.php");
include_once ("../_obj/Conference.php");
include_once ("../_obj/Message.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['send']) && !empty($_POST['send']) )
	{
		if( isset($_POST['id']) && !empty($_POST['id']) && Conference::isUserConfById($_SESSION['_userId'], $_POST['send_cid']) )
		{
			$atReturn = Invitation::updateStime($_POST['id']);
					
			if ($atReturn == 1) // miss input
			{
				$data->error = -3;
			}
			else if ($atReturn == 2) // update fail
			{
				$data->error = -7;				
			}
			else
			{
				//success
				// message
				$receiver = Invitation::getInvtById($_POST['id']);
				if ($receiver)
				{			
					$conf = Conference::getConfInfoByCid($_POST['send_cid']);
					$body = "<p>尊敬的 ".$receiver->fullname." 先生/女士：</P><p>您被邀请参加".$conf->name.
							"。<a href=\"../conference/info_home.php?url=".$conf->getUrl().
							"\" target=\"_blank\">查看详细信息</a>。</P>";
					$sResult = Message::create("邀请您参加".$conf->name, $receiver->getUid(), $_SESSION['_userId'], $body);
								
					if ($sResult > 0)
					{			
						
					}
					else
					{
						//insert fail		
					}
				}
				// sms
				// email
				$data->error = $_POST['id'];
			}
		}
		else
		{
			$data->error = -3;//missing page input
		}
	}
	else
	{
		$data->error = -4; //not post
	}
}
else
{      
	$data->error = -5; //session expire
}
echo json_encode($data);
?>