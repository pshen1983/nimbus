<?php        
include_once ("../_obj/Conference.php");

session_start();

/* //using jquery ajax, but kindeditor 图片编码有误
if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ce']) && !empty($_POST['ce']) )
	{
		if( isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['stime']) && !empty($_POST['stime']) &&
			isset($_POST['etime']) && !empty($_POST['etime']) && isset($_POST['coun']) && !empty($_POST['coun']) &&
			isset($_POST['prov']) && !empty($_POST['prov']) && isset($_POST['city']) && !empty($_POST['city']) &&
			isset($_POST['dist']) && !empty($_POST['dist']) && isset($_POST['daddr']) && !empty($_POST['daddr']) &&
			isset($_POST['desc']) && !empty($_POST['desc']) && Conference::isUserConfById($_SESSION['_userId'], $_POST['edform_cid']) )
		{						  
			$sResult = Conference::update($_POST['edform_cid'],$_POST['title'],$_POST['coun'],$_POST['prov'],$_POST['city'],$_POST['dist'],$_POST['daddr'],$_POST['stime'],$_POST['etime'],$_POST['desc']);
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
}*/


if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ce']) && !empty($_POST['ce']) )
	{
		if( isset($_POST['title']) && !empty($_POST['title']) && 
			isset($_POST['bsdate']) && !empty($_POST['bsdate']) &&
			isset($_POST['bedate']) && !empty($_POST['bedate']) && 
			isset($_POST['edform_fpage']) && !empty($_POST['edform_fpage']) &&
			isset($_POST['prov']) && !empty($_POST['prov']) && 
			isset($_POST['city']) && !empty($_POST['city']) &&
			isset($_POST['daddr']) && !empty($_POST['daddr']) &&
			isset($_POST['reg']) && ($_POST['reg']=='C' || $_POST['reg']=='O') &&
			isset($_POST['search']) && ($_POST['search']=='Y' || $_POST['search']=='N') &&
			isset($_POST['url']) && !empty($_POST['url']) &&
			Conference::isUserConfById($_SESSION['_userId'], $_POST['edform_cid']) )
		{			
			$coun = 1; //hardcode China is 1;	
			$stime = $_POST['bsdate'].' '.$_POST['bstime'];
			$etime = $_POST['bedate'].' '.$_POST['betime'];

			$rs = Conference::update( $_POST['edform_cid'],
									  $_POST['title'],
									  $coun,
									  $_POST['prov'],
									  $_POST['city'],
									  $_POST['dist'],
									  $_POST['daddr'],
									  $stime,
									  $etime,
									  $_POST['reg'],
									  $_POST['search'],
									  $_POST['desc'],
									  $_POST['lat'],
									  $_POST['lng'] );
			if($rs == 0)
			{
				if( isset($_SESSION['_confsEdit'][$_POST['url']]) && 
					$_SESSION['_confsEdit'][$_POST['url']]->getCid() == $_POST['edform_cid'])
				{
					unset($_SESSION['_confsEdit'][$_POST['url']]);
				}
			}
			header( 'Location: '.$_POST['edform_fpage'] ) ;
			exit;
		}
		else
		{
			//return -3;//missing page input
			header( 'Location: '.$_POST['edform_fpage'] ) ;
			exit;
		}
	}
	else
	{
		//return -4; //not post
		header( 'Location: '.$_POST['edform_fpage'] ) ;
		exit;
	}	
}
else
{      
	//return -5; //session expire
	header( 'Location: ../default/login.php' ) ;
	exit;
}
?>
