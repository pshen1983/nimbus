<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Schedule.php");

session_start();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['s_e_sche']) && !empty($_POST['s_e_sche']) )
	{
		if( isset($_POST['s_e_date']) && !empty($_POST['s_e_date']) && isset($_POST['s_e_stime']) && !empty($_POST['s_e_stime']) &&
			isset($_POST['s_e_etime']) && !empty($_POST['s_e_etime']) && isset($_POST['s_e_addr']) && !empty($_POST['s_e_addr']) &&
			isset($_POST['s_e_summ']) && !empty($_POST['s_e_summ']) && isset($_POST['s_e_cid']) && !empty($_POST['s_e_cid']) &&
			isset($_POST['s_e_sid']) && !empty($_POST['s_e_sid']) && Conference::isUserConfById($_SESSION['_userId'], $_POST['s_e_cid']) )
		{			
			$sResult = Schedule::update($_POST['s_e_sid'], $_POST['s_e_cid'], $_SESSION['_userId'], $_POST['s_e_date'], $_POST['s_e_stime'], $_POST['s_e_etime'], $_POST['s_e_addr'], $_POST['s_e_summ'], $_POST['s_e_note']);
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