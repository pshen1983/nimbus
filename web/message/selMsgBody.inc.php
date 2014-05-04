<?php
include_once ("../_obj/Message.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{	
	if( isset($_POST['id']) && !empty($_POST['id']) )
	{
		$body = Message::getMessageBody($_POST['id'], $_SESSION['_userId']); 
		Message::setRead($_POST['id'], $_SESSION['_userId']);		
		
		$_SESSION['_messNum'] = Message::getUnreadMessageNumber($_SESSION['_userId']);
		$data->messNum = $_SESSION['_messNum'];
		if ($body != null && $body != "")
		{
			$data->error = $_POST['id'];
			$data->body = $body;
		}
		else
		{
			$data->error = -3; //empty body
		}
	}
	else
	{
		$data->error = -2; //no id selected
	}
}
else
{   
	$data->error = -1; //session expire
}
echo json_encode($data);
?>  