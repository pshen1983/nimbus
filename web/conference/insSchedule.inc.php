<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Schedule.php");

session_start();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['sche']) && !empty($_POST['sche']) )
	{
		if( isset($_POST['date']) && !empty($_POST['date']) && isset($_POST['stime']) && !empty($_POST['stime']) &&
			isset($_POST['etime']) && !empty($_POST['etime']) && isset($_POST['addr']) && !empty($_POST['addr']) &&
			isset($_POST['summ']) && !empty($_POST['summ']) && isset($_POST['cid']) && !empty($_POST['cid']) &&
			Conference::isUserConfById($_SESSION['_userId'], $_POST['cid']) )
		{			
			$sResult = Schedule::create($_POST['cid'], $_SESSION['_userId'], $_POST['date'], $_POST['stime'], $_POST['etime'], $_POST['addr'], $_POST['summ'], $_POST['note']);
			echo $sResult;
		}
		else
		{
			echo -3;//missing page input
		}
	}
	else
	{
		echo -4; //not post
	}
}
else
{      
	echo -5; //session expire
}
?>  