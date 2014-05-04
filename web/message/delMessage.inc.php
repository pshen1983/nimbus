<?php        
include_once ("../_obj/Message.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['id']) && !empty($_POST['id']) )
	{		
		if( Message::delete($_POST['id'], $_SESSION['_userId']) )
		{
			$_SESSION['_messNum'] = Message::getUnreadMessageNumber($_SESSION['_userId']);
			$data->messNum = $_SESSION['_messNum'];
			$data->error = 0; //success
		}
		else
		{
			$data->error = 2; //delete fail
		}
	}
	else 
	{
		$data->error = 3; //no id
	}
}
else
{      
	$data->error = 1; //session expire
}
echo json_encode($data);
?>  