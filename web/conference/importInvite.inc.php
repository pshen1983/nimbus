<?php        
include_once ("../_obj/User.php");
include_once ("../_obj/Invitation.php");
include_once ("../_obj/Conference.php");

session_start();
unset($_SESSION['_inviteErr']);

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_error0 = "System busy, please try later.";
	$l_error1 = "This is not a CSV file.";
	$l_error2 = "The 1st row must be Name, Cell, Email, Company, Title.";
	$l_error3 = "[Please enter all required fields] ";
	$l_error4 = "[Invalid cell format] ";
	$l_error5 = "[Invalid email format] ";
	$l_error6 = "[System error] ";
	$l_return = "Return to previous page";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_error0 = "系统忙，请稍候再试。";
	$l_error1 = "请上传CSV文件";
	$l_error2 = "文件第一行必须为姓名、手机、邮箱、公司、职位。";
	$l_error3 = "[请填写所有必填信息] ";
	$l_error4 = "[手机格式有误] ";
	$l_error5 = "[邮箱格式有误] ";
	$l_error6 = "[系统错误] ";
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
	if( isset($_POST['import']) && !empty($_POST['import']) )
	{
		if( isset($_POST['imp_cid']) && !empty($_POST['imp_cid']) && Conference::isUserConfById($_SESSION['_userId'], $_POST['imp_cid']) )
		{
			if($_FILES['imp_file']['type'] != "application/vnd.ms-excel")
			{	
				$_SESSION['_inviteErr'] = $l_error1;
	    		header( 'Location: '.$_POST['imp_page'].'#imp' );
		   		//echo $l_error1.'  <a href="'.$_POST['imp_page'].'">'.$l_return.'</a>';
		   		exit;
			}
			else if(is_uploaded_file($_FILES['imp_file']['tmp_name']))
			{
	   			//Process file
				$row = 1;				
				$error_list = "";
				if (($handle = fopen($_FILES['imp_file']['tmp_name'], "r")) !== FALSE)
				{
				    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
				    {
				    	$error = 0;
						if ($row == 1)
						{
							$row = 0;
							if (($data[0] != "﻿姓名" || $data[1] != "手机" || $data[2] != "邮箱" || $data[3] != "公司" || $data[4] != "职位") &&   
								($data[0] != "﻿Name" || $data[1] != "Cell" || $data[2] != "Email" || $data[3] != "Company" || $data[4] != "Title"))
							{
								$_SESSION['_inviteErr'] = $l_error2;
	    						header( 'Location: '.$_POST['imp_page'].'#imp' );
						   		//echo $l_error2.'  <a href="'.$_POST['imp_page'].'">'.$l_return.'</a>';
						   		exit;
							}
						}
						else
						{
							$UserUID=User::registeredCell(trim($data[1]));			
							if($UserUID) // if user exist
							{				
								$Invt_insert = Invitation::create($_POST['imp_cid'], $UserUID['uid'], trim($data[1]), trim($data[2]), trim($data[0]), trim($data[3]), trim($data[4]));
								if ($Invt_insert == -1) // missing cid, uid, fullname
								{
									$error = -3;
								}
								else if ($Invt_insert == -2) // invalid cell format
								{
									$error = -2;
								}
								else if ($Invt_insert == -3) // invalid email format
								{
									$error = -8;
								}
								else if ($Invt_insert == -4) // insert fail
								{
									$error = -7;
								}
							}
							else // user not exist
							{
								//add user
								$resultU = User::addUser(trim($data[1]), trim($data[2]), trim($data[0]), null, trim($data[3]), trim($data[4]), null);
								if ($resultU == 0)
								{
									$UserUID2=User::registeredCell(trim($data[1]));														
									if($UserUID2)
									{
										$Invt_insert = Invitation::create($_POST['imp_cid'], $UserUID2['uid'], trim($data[1]), trim($data[2]), trim($data[0]), trim($data[3]), trim($data[4]));
										
										if ($Invt_insert == -1) // missing cid, uid, fullname
										{
											$error = -3;				
										}
										else if ($Invt_insert == -2) // invalid cell format
										{
											$error = -2;					
										}
										else if ($Invt_insert == -3) // invalid email format
										{
											$error = -8;					
										}
										else if ($Invt_insert == -4) // insert fail
										{
											$error = -7;			
										}
									}
								}
								else
								{
									$error = $resultU;
								}			
							}
				    	}
				    	
					    if ($error == -1 || $error == -3) // missing cid, uid, fullname
						{
							$error_list = $error_list . $l_error3 . $data[0].", ".$data[1].", ".$data[2].", ".$data[3].", ".$data[4]."<br />\n";
						}
						else if ($error == -2) // invalid cell format
						{				
							$error_list = $error_list . $l_error4 . $data[0].", ".$data[1].", ".$data[2].", ".$data[3].", ".$data[4]."<br />\n";
						}
						else if ($error == -8) // invalid email format
						{				
							$error_list = $error_list . $l_error5 . $data[0].", ".$data[1].", ".$data[2].", ".$data[3].", ".$data[4]."<br />\n";
						}
						else if ($error == -7) // insert fail
						{			
							$error_list = $error_list . $l_error6 . $data[0].", ".$data[1].", ".$data[2].", ".$data[3].", ".$data[4]."<br />\n";
						}	
				    }
				    fclose($handle);
				}
				if ($error_list == "")
				{									
					header( 'Location: '.$_POST['imp_page'] ) ;
					exit;
				}
				else
				{   
					$_SESSION['_inviteErr'] = $error_list;
	    			header( 'Location: '.$_POST['imp_page'].'#imp' );
		   			//echo $error_list.'<a href="'.$_POST['imp_page'].'">'.$l_return.'</a>';
		   			exit;					
				}
			}
			else
			{			   
		   		echo $l_error0.'  <a href="'.$_POST['imp_page'].'">'.$l_return.'</a>';
		   		exit;
			}
		}
		else
		{
			//missing page input
	    	header( 'Location: '.$_POST['imp_page'] );
			exit;
		}
	}
	else
	{
		//not post
	    header( 'Location: '.$_POST['imp_page'] );
		exit;
	}
}
else
{      
	//session expire
	header( 'Location: ../default/login.php' );
	exit;
}
?>
</body>
</html>