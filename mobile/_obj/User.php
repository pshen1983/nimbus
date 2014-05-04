<?php
include_once ("../utils/DatabaseUtil.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SessionUtil.php");

class User
{
	private $uid;

	public $fullname;
	public $pic;
	public $description;
	public $uname;
	public $company;
	public $title;
	public $reg_type;
	public $weibo;
	public $email;
	public $cell;

	private $rtime;

	public static $picPath = '../tmp/temp_profile_img_crop100.';

	public static function registerUser($id, $passwd, $fname, $uname=null)
	{
		$atReturn = 0;
		$ins_upd = 'ins';
		$idFormat = CommonUtil::validateIdFormat($id);

		if(!isset($id) || empty($id) || !isset($passwd) || empty($passwd) || !isset($fname) || empty($fname))
		{
			$atReturn = 2;
		}
		else if($idFormat==0 || $idFormat==1)
		{
			$atReturn = 3;
		}
		else if ($idFormat==2)
		{
			$regEmail = self::registeredEmail($id);
			if ($regEmail)
			{
				if(empty($regEmail['passwd']))
				{
					$ins_upd = 'upd';
				}
				else
				{
					$atReturn = 4;
				}
			}
		}

		if($atReturn==0 && $ins_upd =='ins') 
		{			
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);

			$nickName = (isset($uname) && !empty($uname)) ? "'".mysql_real_escape_string($uname)."'" : 'NULL';

			$query = "INSERT INTO USER (EMAIL, PASSWD, FULLNAME, UNAME, REG_TYPE, RTIME)			
				 	 VALUES ('$id', '". md5($passwd) ."', '". mysql_real_escape_string($fname) ."', $nickName, 'C', NOW())";

			if (!DatabaseUtil::insertData($db_connection, $query))
			{
				$atReturn = 1;
			}
		}
		else if($atReturn==0 && $ins_upd =='upd') 
		{			
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);

			$nickName = (isset($uname) && !empty($uname)) ? "'".mysql_real_escape_string($uname)."'" : 'NULL';

			$query = "UPDATE USER SET PASSWD = '". md5($passwd) .
					 "', FULLNAME ='". mysql_real_escape_string($fname) .
					 "', UNAME =  $nickName WHERE EMAIL = '$id'";

			if (!DatabaseUtil::updateData($db_connection, $query))
			{
				$atReturn = 1;
			}
		}

		return $atReturn;
	}	

	public static function addUser($cell, $email, $fname, $pic, $comp, $title, $desc)
	{
		$atReturn = 0;

		if(!isset($email) || empty($email) || !isset($fname) || empty($fname))
		{
			$atReturn = -1;
		}
		else if(!CommonUtil::validateEmailFormat($email))
		{
			$atReturn = -2;
		}
		else if(!empty($cell) && !CommonUtil::validateCellFormat($cell))
		{
			$atReturn = -8;
		}
		
		if($atReturn==0)
		{			
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
			$query = "INSERT INTO USER (CELL, EMAIL, FULLNAME, PIC, PIC_UP, COMPANY, TITLE, DESCRIPTION, REG_TYPE, RTIME)
					VALUES (".DatabaseUtil::checkNull($cell).", ".DatabaseUtil::checkNull($email).", '".mysql_real_escape_string($fname)."', ".(isset($pic) ? "'".$pic."'" : 'NULL').", ".
					(isset($pic) && !empty($pic) ? 'NOW()' : 'NULL').", ".
					DatabaseUtil::checkNull($comp).", ".
					DatabaseUtil::checkNull($title).", ".DatabaseUtil::checkNull($desc).", 'C', NOW())";
			if (!DatabaseUtil::insertData($db_connection, $query))
			{
				$atReturn = -7;
			}
		}

		return $atReturn;
	}
	
	public static function loginUser($id, $passwd)
	{
		if(!isset($id) || empty($id) || !isset($passwd) || empty($passwd))
		{
			$atReturn = 4;
		}
		else if( !CommonUtil::validateEmailFormat($id) )
		{
			$atReturn = 5;
		}
		else
		{
	 		$query = "SELECT uid, email, passwd, fullname, pic, uname, rtime, description, company, title, cell, weibo, reg_type FROM USER WHERE EMAIL='$id'";
			
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
			$user = DatabaseUtil::selectData($db_connection, $query);

			if(isset($user))
			{
				if($user)
				{
					if(strcmp(md5($passwd), $user['passwd']) == 0)
					{
						$_SESSION['_userId'] = $user['uid'];
						$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']."_nimbus2011");

						$myUser = new User();
						$myUser->uid = $user['uid'];
						$myUser->fullname = $user['fullname'];
						$myUser->email = $user['email'];
						$myUser->rtime = $user['rtime'];
						$myUser->uname = $user['uname'];
						$myUser->description = $user['description'];
						$myUser->company = $user['company'];
						$myUser->title = $user['title'];
						$myUser->cell = $user['cell'];
						$myUser->weibo = $user['weibo'];
						$myUser->reg_type = $user['reg_type'];

						if( isset($user['pic']) )
						{
							$profileImg = fopen(self::$picPath.$user['uid'], 'w') or $myUser->pic = '../image/default/default_pro_pic.png';
							fwrite($profileImg, $user['pic']);
							fclose($profileImg);
							$myUser->pic = self::$picPath.$user['uid'].'?t='.time();
						}
						else {
							$myUser->pic = '../image/default/default_pro_pic.png';
						}

						$_SESSION['_loginUser'] = $myUser;
						SessionUtil::initSession();

						$atReturn = 0;
					}
					else $atReturn = 3;
				}
				else $atReturn = 2;
			}
			else $atReturn = 1;
		}

		return $atReturn;
	}

	public static function insertPassword($email, $passwd)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "UPDATE USER SET PASSWD='". md5($passwd) ."' WHERE EMAIL='$email'";
		return DatabaseUtil::updateData($db_connection, $query);
	}

	public static function getUserByEmail($email)
	{
		if(!isset($email) || empty($email) || !CommonUtil::validateEmailFormat($email))
			return null;

		$atReturn = null;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid, fullname, pic, rtime, uname, description, company, title, cell, weibo FROM USER WHERE EMAIL='$email'";
		$user = DatabaseUtil::selectData($db_connection, $query);

		if(isset($user) && $user )
		{
			$atReturn = new User();
			$atReturn->uid = $user['uid'];
			$atReturn->fullname = $user['fullname'];
			$atReturn->email = $email;
			$atReturn->pic = $user['pic'];
			$atReturn->rtime = $user['rtime'];
			$atReturn->uname = $user['uname'];
			$atReturn->description = $user['description'];
			$atReturn->company = $user['company'];
			$atReturn->title = $user['title'];
			$atReturn->cell = $user['cell'];
			$atReturn->weibo = $user['weibo'];

			if( isset($user['pic']) )
			{
				$filename = self::$picPath.$user['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$user['uid'], 'w') or $atReturn->pic = '../image/default/default_pro_pic.png';
					if($atReturn->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $user['pic']);
						fclose($confImg);
						$atReturn->pic = self::$picPath.$user['uid'].'?t='.time();
					}
				}
				else {
					$atReturn->pic = self::$picPath.$user['uid'].'?t='.time();
				}
			}
			else {
				$atReturn->pic = '../image/default/default_pro_pic.png';
			}
		}

		return $atReturn;
	}
	
	public static function getUserByCell($cell)
	{
		if(!isset($cell) || empty($cell) || !CommonUtil::validateCellFormat($cell))
			return null;

		$atReturn = null;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid, email, fullname, pic, rtime, uname, description, company, title, weibo FROM USER WHERE CELL='$cell'";
		$user = DatabaseUtil::selectData($db_connection, $query);

		if(isset($user) && $user )
		{
			$atReturn = new User();
			$atReturn->uid = $user['uid'];
			$atReturn->fullname = $user['fullname'];
			$atReturn->email = $user['email'];
			$atReturn->pic = $user['pic'];
			$atReturn->rtime = $user['rtime'];
			$atReturn->uname = $user['uname'];
			$atReturn->description = $user['description'];
			$atReturn->company = $user['company'];
			$atReturn->title = $user['title'];
			$atReturn->cell = $cell;
			$atReturn->weibo = $user['weibo'];

			if( isset($user['pic']) )
			{
				$filename = self::$picPath.$user['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$user['uid'], 'w') or $atReturn->pic = '../image/default/default_pro_pic.png';
					if($atReturn->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $user['pic']);
						fclose($confImg);
						$atReturn->pic = self::$picPath.$user['uid'].'?t='.time();
					}
				}
				else {
					$atReturn->pic = self::$picPath.$user['uid'].'?t='.time();
				}
			}
			else {
				$atReturn->pic = '../image/default/default_pro_pic.png';
			}
		}

		return $atReturn;
	}
	
	public static function getUserIdByEmail($email)
	{
		if(!isset($email) || empty($email) || !CommonUtil::validateEmailFormat($email))
			return null;

		$atReturn = null;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid, fullname FROM USER WHERE EMAIL='$email'";
		$user = DatabaseUtil::selectData($db_connection, $query);

		if(isset($user) && $user )
		{
			$atReturn = new User();
			$atReturn->uid = $user['uid'];
			$atReturn->fullname = $user['fullname'];		
		}

		return $atReturn;
	}
	
	public static function getUserIdByCell($cell)
	{
		if(!isset($cell) || empty($cell) || !CommonUtil::validateCellFormat($cell))
			return null;

		$atReturn = null;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid, fullname FROM USER WHERE CELL='$cell'";
		$user = DatabaseUtil::selectData($db_connection, $query);

		if(isset($user) && $user )
		{
			$atReturn = new User();
			$atReturn->uid = $user['uid'];
			$atReturn->fullname = $user['fullname'];		
		}

		return $atReturn;
	}
	
	public static function getUser($id)
	{
		if(!isset($id) || empty($id)) return null;

		$atReturn = null;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT email, fullname, pic, rtime, uname, description, company, title, cell, weibo FROM USER WHERE UID=$id";
		$user = DatabaseUtil::selectData($db_connection, $query);

		if( isset($user) && $user )
		{
			$atReturn = new User();
			$atReturn->uid = $id;
			$atReturn->fullname = $user['fullname'];
			$atReturn->email = $user['email'];
			$atReturn->pic = $user['pic'];
			$atReturn->rtime = $user['rtime'];
			$atReturn->uname = $user['uname'];
			$atReturn->description = $user['description'];
			$atReturn->company = $user['company'];
			$atReturn->title = $user['title'];
			$atReturn->cell = $user['cell'];
			$atReturn->weibo = $user['weibo'];

			if( isset($user['pic']) )
			{
				$filename = self::$picPath.$user['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$user['uid'], 'w') or $atReturn->pic = '../image/default/default_pro_pic.png';
					if($atReturn->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $user['pic']);
						fclose($confImg);
						$atReturn->pic = self::$picPath.$user['uid'].'?t='.time();
					}
				}
				else {
					$atReturn->pic = self::$picPath.$user['uid'].'?t='.time();
				}
			}
			else {
				$atReturn->pic = '../image/default/default_pro_pic.png';
			}
		}

		return $atReturn;
	}

	public static function updateUserPasswd($email, $passwd)
	{
		$rs = 0;

		if( isset($email) && !empty($email) && isset($passwd) && !empty($passwd) )
		{
			if ( !CommonUtil::validateEmailFormat($email) )
			{
				$rs = 2;
			}
			else {
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
				$query = "UPDATE USER SET PASSWD='". md5($passwd) ."' WHERE EMAIL='$email'";

				if( !DatabaseUtil::updateData($db_connection, $query) )
				{
					$rs = 3;
				}
			}
		}
		else {
			$rs = 1;
		}

		return $rs;
	}

	public static function updateUserPasswdByCell($cell, $passwd)
	{
		$rs = 0;

		if( isset($cell) && !empty($cell) && isset($passwd) && !empty($passwd) )
		{
			if ( !CommonUtil::validateCellFormat($cell) )
			{
				$rs = 2;
			}
			else {
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
				$query = "UPDATE USER SET PASSWD='". md5($passwd) ."' WHERE CELL='$cell'";

				if( !DatabaseUtil::updateData($db_connection, $query) )
				{
					$rs = 3;
				}
			}
		}
		else {
			$rs = 1;
		}

		return $rs;
	}

	public static function setOrgnaizer($email, $type)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "UPDATE USER SET REG_TYPE='$type' WHERE EMAIL='$email'";
		$result = DatabaseUtil::updateData($db_connection, $query);
	}

	public static function getEmailById($uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT email FROM USER WHERE UID=$uid";
		$result = DatabaseUtil::selectData($db_connection, $query);
		return $result['email'];
	}

	public static function registeredEmail($email)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid, passwd FROM USER WHERE EMAIL='$email'";
		return DatabaseUtil::selectData($db_connection, $query);
	}

	public static function uniqueEmail($email, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid FROM USER WHERE EMAIL='$email' AND UID <> $uid";
		return DatabaseUtil::selectData($db_connection, $query);
	}
	
	public static function registeredCell($cell)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid, passwd FROM USER WHERE CELL='$cell'";
		return DatabaseUtil::selectData($db_connection, $query);
	}
	
	public static function uniqueCell($cell, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid FROM USER WHERE CELL='$cell' AND UID <> $uid";
		return DatabaseUtil::selectData($db_connection, $query);
	}

	private static function nickNameUsed($name)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$query = "SELECT uid FROM USER WHERE UNAME='$name'";
		return DatabaseUtil::selectData($db_connection, $query);
	}

	public static function getRangeUsers($range)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT uid, email, fullname, pic, pic_up, description, company, title, cell, weibo FROM USER WHERE UID IN ($range)";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);
		
		$users = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$users[$index] = new User();
			$users[$index]->uid = $row['uid'];
			$users[$index]->email = $row['email'];
			$users[$index]->fullname = $row['fullname'];
			$users[$index]->pic = $row['pic'];
			$users[$index]->pic_up = $row['pic_up'];
			$users[$index]->description = $row['description'];
			$users[$index]->company = $row['company'];
			$users[$index]->title = $row['title'];
			$users[$index]->cell = $row['cell'];
			$users[$index]->weibo = $row['weibo'];

			if( isset($row['pic']) )
			{
				$filename = self::$picPath.$row['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$row['uid'], 'w') or $users[$index]->pic = '../image/default/default_pro_pic.png';
					if($users[$index]->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $row['pic']);
						fclose($confImg);
						$users[$index]->pic = self::$picPath.$row['uid'].'?t='.time();
					}
				}
				else {
					$users[$index]->pic = self::$picPath.$row['uid'].'?t='.time();
				}
			}
			else {
				$users[$index]->pic = '../image/default/default_pro_pic.png';
			}

			$index++;
		}

		return $users;
	}

	public static function getMessageUserNames($range)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT uid, fullname, uname FROM USER WHERE UID IN ($range)";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$users = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$users[$index] = new User();
			$users[$index]->uid = $row['uid'];
			$users[$index]->uname = $row['uname'];
			$users[$index]->fullname = $row['fullname'];

			$index++;
		}

		return $users;
	}

	public static function mGetRangeUsers($range)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT uid, fullname, pic, pic_up, company, title FROM USER WHERE UID IN ($range)";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$users = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$users[$index] = new User();
			$users[$index]->uid = $row['uid'];
			$users[$index]->fullname = $row['fullname'];
			$users[$index]->pic = $row['pic'];
			$users[$index]->pic_up = $row['pic_up'];
			$users[$index]->company = $row['company'];
			$users[$index]->title = $row['title'];

			if( isset($row['pic']) )
			{
				$filename = self::$picPath.$row['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$row['uid'], 'w') or $users[$index]->pic = '../image/default/default_pro_pic.png';
					if($users[$index]->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $row['pic']);
						fclose($confImg);
						$users[$index]->pic = self::$picPath.$row['uid'].'?t='.time();
					}
				}
				else {
					$users[$index]->pic = self::$picPath.$row['uid'].'?t='.time();
				}
			}
			else {
				$users[$index]->pic = '../image/default/default_pro_pic.png';
			}

			$index++;
		}

		return $users;
	}

	public static function mGetUser($uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT uid, email, uname, fullname, pic, pic_up, company, title, description, cell, weibo FROM USER WHERE UID=$uid";
		$user = DatabaseUtil::selectData($db_connection, $sql);

		if( isset($user) && $user )
		{
			$atReturn = new User();
			$atReturn->uid = $uid;
			$atReturn->fullname = $user['fullname'];
			$atReturn->email = $user['email'];
			$atReturn->pic = $user['pic'];
			$atReturn->uname = $user['uname'];
			$atReturn->description = $user['description'];
			$atReturn->company = $user['company'];
			$atReturn->title = $user['title'];
			$atReturn->cell = $user['cell'];
			$atReturn->weibo = $user['weibo'];

			if( isset($user['pic']) )
			{
				$filename = self::$picPath.$uid;
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$uid, 'w') or $atReturn->pic = '../image/default/default_pro_pic.png';
					if($atReturn->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $user['pic']);
						fclose($confImg);
						$atReturn->pic = self::$picPath.$uid.'?t='.time();
					}
				}
				else {
					$atReturn->pic = self::$picPath.$uid.'?t='.time();
				}
			}
			else {
				$atReturn->pic = '../image/default/default_pro_pic.png';
			}
		}

		return $atReturn;
	}

//==================================================== static / normal ========================================================

	public function updateUser()
	{
		$atReturn = 0;
		if (!empty($this->email) && !CommonUtil::validateEmailFormat($this->email))
		{
			$atReturn = 2;
		}
		
		if ($atReturn == 0)
		{
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);					
			$query = "UPDATE USER SET FULLNAME='".mysql_real_escape_string($this->fullname).
					 "', UNAME='".mysql_real_escape_string($this->uname).
					 "', DESCRIPTION=".DatabaseUtil::checkNull($this->description).
					 ", COMPANY=".DatabaseUtil::checkNull($this->company).
					 ", TITLE=".DatabaseUtil::checkNull($this->title).
					 ", WEIBO=".DatabaseUtil::checkNull($this->weibo).
					 " WHERE UID=".$this->uid;
			if (!DatabaseUtil::updateData($db_connection, $query))
			{
				$atReturn = 1;
			}
		}	

		return $atReturn;
	}

	public function updatePic($image)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "UPDATE USER SET PIC_UP=NOW(), PIC='$image' WHERE UID=".$this->uid;
		return DatabaseUtil::updateData($db_connection, $sql);
	}

	public function updatePassword($old_pw, $new_pw)
	{
		$result = false;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);		
		$query_select = "SELECT passwd FROM USER WHERE UID=".$this->uid;
		$old_pw_select = DatabaseUtil::selectData($db_connection, $query_select);

		if (strcmp($old_pw_select['passwd'], md5($old_pw)) == 0)
		{			
			$query = "UPDATE USER SET PASSWD='".md5($new_pw)."' WHERE UID=".$this->uid;
			$result = DatabaseUtil::updateData($db_connection, $query);
		}			

		return $result;
	}

	public function getUid()
	{
		return $this->uid;
	}

	public function getRtime()
	{
		return $this->rtime;
	}

	public function getEmail()
	{
		return $this->email;
	}
}
?>