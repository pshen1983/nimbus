<?php        
include_once ("../_obj/Conference.php");
include_once ("../_obj/Broadcast.php");

session_start();

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_error1 = "Title is required";
	$l_error = "System busy, please try again later";
	$l_return = "Return to previous page";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_error1 = "标题为必填项";
	$l_error = "系统忙，请稍候再试";
	$l_return = "返回上一页";
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
</head>
<body>
<?php
if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ins']) && !empty($_POST['ins']) )
	{
		if( isset($_POST['ins_title']) && !empty($_POST['ins_title']) &&
			isset($_POST['ins_cid']) && !empty($_POST['ins_cid']) &&
			Conference::isUserConfById($_SESSION['_userId'], $_POST['ins_cid']) )
		{	
			if (trim($_POST['ins_title'])=="")
			{
				echo $l_error1.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
	   			exit;
			}
			else
			{
				$sResult = Broadcast::create($_POST['ins_cid'], trim($_POST['ins_title']), trim($_POST['ins_body']));
				if ($sResult==1)
				{
					echo $l_error.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
					exit;
				}
				else
				{	// success			
		    		header( 'Location: '.$_POST['ins_page'] ) ;
					exit;
				}
			}
		}
		else
		{
			//echo -3;//missing page input
	   		echo $l_error1.'  <a href="'.$_POST['ins_page'].'">'.$l_return.'</a>';
	   		exit;
		}
	}
	else
	{
		//echo -4; //not post
	    header( 'Location: '.$_POST['ins_page'] );
		exit;
	}
}
else
{      
	//echo -5; //session expire
	header( 'Location: ../default/login.php' );
	exit;
}
?>
</body>
</html>