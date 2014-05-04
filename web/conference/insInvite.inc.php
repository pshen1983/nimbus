<?php        
include_once ("../_obj/User.php");
include_once ("../_obj/Invitation.php");
include_once ("../_obj/Conference.php");

session_start();
$data = new stdClass();

if( isset($_SESSION['_userId']) && !empty($_SESSION['_userId']) )
{
	if( isset($_POST['ins']) && !empty($_POST['ins']) )
	{
		if( isset($_POST['ins_cell']) && !empty($_POST['ins_cell']) &&
			isset($_POST['ins_fullname']) && !empty($_POST['ins_fullname']) &&
			isset($_POST['ins_cid']) && !empty($_POST['ins_cid']) &&
			Conference::isUserConfById($_SESSION['_userId'], $_POST['ins_cid']) )
		{							
			$UserUID=User::registeredCell($_POST['ins_cell']);			
			if($UserUID) // if user exist
			{				
				$Invt_insert = Invitation::create($_POST['ins_cid'], $UserUID['uid'], $_POST['ins_cell'], $_POST['ins_email'], $_POST['ins_fullname'], $_POST['ins_comp'], $_POST['ins_title']);
				
				if ($Invt_insert == -1) // missing cid, uid, fullname
				{
					$data->error = -3;				
				}
				else if ($Invt_insert == -2) // invalid cell format
				{
					$data->error = -2;					
				}
				else if ($Invt_insert == -3) // invalid email format
				{
					$data->error = -8;					
				}
				else if ($Invt_insert == -4) // insert fail
				{
					$data->error = -7;			
				}
				else
				{
					$data->error = $Invt_insert;
				}
			}
			else // user not exist
			{
				//add user
				$resultU = User::addUser($_POST['ins_cell'], $_POST['ins_email'], $_POST['ins_fullname'], null, $_POST['ins_comp'], $_POST['ins_title'], null);
				if ($resultU == 0)
				{
					$UserUID2=User::registeredCell($_POST['ins_cell']);
										
					if($UserUID2)
					{
						$Invt_insert = Invitation::create($_POST['ins_cid'], $UserUID2['uid'], $_POST['ins_cell'], $_POST['ins_email'], $_POST['ins_fullname'], $_POST['ins_comp'], $_POST['ins_title']);
						if ($Invt_insert == -1) // missing cid, uid, fullname
						{
							$data->error = -3;				
						}
						else if ($Invt_insert == -2) // invalid cell format
						{
							$data->error = -2;					
						}
						else if ($Invt_insert == -3) // invalid email format
						{
							$data->error = -8;					
						}
						else if ($Invt_insert == -4) // insert fail
						{
							$data->error = -7;			
						}
						else
						{
							$data->error = $Invt_insert;
						}
					}
				}
				else
				{
					$data->error = $resultU;
				}			
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
