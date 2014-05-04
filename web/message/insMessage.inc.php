<?php        
include_once ("../_obj/User.php");
include_once ("../_obj/Message.php");

session_start();
//$data = new stdClass();

if($_SESSION['_language'] == 'en') {
	$l_error1 = "System busy, please try again later";
	$l_error3 = "Please enter all required information";
	$l_error4 = "The receiver is not a ConfOne user";
	$l_return = "Return to previous page";
}
else if($_SESSION['_language'] == 'zh') {
	$l_error1 = "系统忙，请稍候再试";
	$l_error3 = "请填写所有必填信息";
	$l_error4 = "此收信人不是会云网用户";
	$l_return = "返回上一页";
}

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{	
	if( isset($_POST['ins_receiver']) && !empty($_POST['ins_receiver']) &&
		isset($_POST['ins_title']) && !empty($_POST['ins_title']) )
	{
		$receiver = User::getUserIdByEmail($_POST['ins_receiver']);
		if ($receiver)
		{			
			$sResult = Message::create($_POST['ins_title'], $receiver->getUid(), $_SESSION['_userId'], $_POST['ins_body']);
			/*$data->error = $sResult;
			if ($receiver->getUid() == $_SESSION['_userId'])
			{
				$data->receiver = 1;
			}*/
			
			if ($sResult > 0)
			{			
				header( 'Location: ../message/index.php' ) ;
				exit;
			}
			else
			{
				//insert fail
				$output = $l_error1.'  <a href="index.php">'.$l_return.'</a>';		
			}
		}
		else
		{			
			//$data->error = -4; //no such user
			$output = $l_error4.'  <a href="index.php">'.$l_return.'</a>';
		}			
	}
	else
	{
		//$data->error = -3;//missing page input
		$output = $l_error3.'  <a href="index.php">'.$l_return.'</a>';
	}		
}
else
{
	//$data->error = -2; //session expire
	header( 'Location: ../default/login.php' );
	exit;
}
//echo json_encode($data);
?>
<!DOCTYPE html>
<html manifest="../m.common/m.confone.manifest">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<?php echo $output;?>
</body>
</html>