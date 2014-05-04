<?php
include_once ("../utils/DatabaseUtil.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SessionUtil.php");

class UserT
{
	private $cid;
	private $uid;

	public $fullname;
	public $pic;
	public $pic_up;
	public $description;
	public $company;
	public $title;
	public $email;
	public $cell;
	public $weibo;

	private $ctime;

	public static $picPath = '../tmp/temp_user_t_img.';

	public static function createUserT($cid, $uid, $email, $fullname, $pic, $description, $company, $title, $cell, $weibo)
	{
		$users = 0;

		if( isset($cid) && is_numeric($cid) && isset($uid) && is_numeric($uid) && isset($fullname) && !empty($fullname))
		{
			if( !CommonUtil::validateEmailFormat($email) )
			{
				$users = 4;
			}		
			else if( self::isConfUserTEixst($cid, $uid) )
			{
				$users = 2;
			}
			else 
			{
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
				$query = "INSERT INTO USER_T (CID, UID, EMAIL, FULLNAME, PIC, PIC_UP, DESCRIPTION, COMPANY, TITLE, CELL, WEIBO, CTIME)
					 	  VALUES ($cid, $uid, ".DatabaseUtil::checkNull($email).", '".mysql_real_escape_string($fullname)."', ".
				          (isset($pic) ? "'".$pic."'" : 'NULL').", ".(isset($pic) && !empty($pic) ? 'NOW()' : 'NULL').", ".
				          DatabaseUtil::checkNull($description).", ".DatabaseUtil::checkNull($company).", ".
				          DatabaseUtil::checkNull($title).", '$cell', ".
				          DatabaseUtil::checkNull($weibo).", NOW())";

				if (!DatabaseUtil::insertData($db_connection, $query))
				{
					$users = 3;
				}
			}
		}
		else 
		{
			$users = 1;
		}

		return $users;
	}

	public static function updateUserT($cid, $uid, $fullname, $pic, $description, $company, $title, $email, $cell, $weibo)
	{
		$users = 0;

		if( isset($cid) && is_numeric($cid) && isset($uid) && is_numeric($uid) && isset($fullname) && !empty($fullname))
		{	
			if( !CommonUtil::validateEmailFormat($email) )
			{
				$users = 3;
			}
			else
			{
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
				$query = "UPDATE USER_T SET FULLNAME='".mysql_real_escape_string($fullname)."', ".
						"PIC=".(isset($pic) ? "'".$pic."'" : 'NULL').", ".
						"PIC_UP=".(isset($pic) && !empty($pic) ? 'NOW()' : 'NULL').", ".
						"DESCRIPTION=".DatabaseUtil::checkNull($description).", ".
						"COMPANY=".DatabaseUtil::checkNull($company).", ".
						"TITLE=".DatabaseUtil::checkNull($title).", ".
						"EMAIL=".DatabaseUtil::checkNull($email).", ".
						"CELL='".trim($cell)."', ".
						"WEIBO=".DatabaseUtil::checkNull($weibo).", ".
						"CTIME=NOW() WHERE CID=$cid AND UID=$uid";
				if (!DatabaseUtil::updateData($db_connection, $query))
				{
					$users = 2;
				}	
			}
		}
		else 
		{
			$users = 1;
		}	

		return $users;
	}
	
	public static function getRangeConfUsers($cid, $range)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT cid, uid, email, fullname, pic, pic_up, description, company, title, cell, weibo FROM USER_T WHERE CID=$cid AND UID IN ($range)";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$users = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$users[$index] = new UserT();
			$users[$index]->cid = $row['cid'];
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
				$filename = self::$picPath.$row['cid'].'.'.$row['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$row['cid'].'.'.$row['uid'], 'w') or $users[$index]->pic = '../image/default/default_pro_pic.png';
					if($users[$index]->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $row['pic']);
						fclose($confImg);
						$users[$index]->pic = self::$picPath.$row['cid'].'.'.$row['uid'].'?t='.time();
					}
				}
				else {
					$users[$index]->pic = self::$picPath.$row['cid'].'.'.$row['uid'].'?t='.time();
				}
			}
			else {
				$users[$index]->pic = '../image/default/default_pro_pic.png';
			}

			$index++;
		}

		return $users;
	}
	
	public static function getConfUserByEmail($cid, $email)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT cid, uid, fullname, pic, pic_up, description, company, title FROM USER_T WHERE CID=$cid AND EMAIL='$email'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);

		$user = null;
		if(isset($rs['uid']))
		{
			$user = new UserT();
			$user->cid = $rs['cid'];
			$user->uid = $rs['uid'];
			$user->fullname = $rs['fullname'];
			$user->pic = $rs['pic'];
			$user->pic_up = $rs['pic_up'];
			$user->description = $rs['description'];
			$user->company = $rs['company'];
			$user->title = $rs['title'];
	
			if( isset($rs['pic']) )
			{
				$filename = self::$picPath.$rs['cid'].'.'.$rs['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$rs['cid'].'.'.$rs['uid'], 'w') or $user->pic = '../image/default/default_pro_pic.png';
					if($user->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $rs['pic']);
						fclose($confImg);
						$user->pic = self::$picPath.$rs['cid'].'.'.$rs['uid'].'?t='.time();
					}
				}
				else {
					$user->pic = self::$picPath.$rs['cid'].'.'.$rs['uid'].'?t='.time();
				}
			}
			else {
				$user->pic = '../image/default/default_pro_pic.png';
			}
		}

		return $user;
	}

	public static function getConfUserByCell($cid, $cell)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT cid, uid, email, fullname, pic, pic_up, description, company, title FROM USER_T WHERE CID=$cid AND CELL='$cell'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);

		$user = null;
		if(isset($rs['uid']))
		{
			$user = new UserT();
			$user->cid = $rs['cid'];
			$user->uid = $rs['uid'];
			$user->email = $rs['email'];
			$user->fullname = $rs['fullname'];
			$user->pic = $rs['pic'];
			$user->pic_up = $rs['pic_up'];
			$user->description = $rs['description'];
			$user->company = $rs['company'];
			$user->title = $rs['title'];
	
			if( isset($rs['pic']) )
			{
				$filename = self::$picPath.$rs['cid'].'.'.$rs['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$rs['cid'].'.'.$rs['uid'], 'w') or $user->pic = '../image/default/default_pro_pic.png';
					if($user->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $rs['pic']);
						fclose($confImg);
						$user->pic = self::$picPath.$rs['cid'].'.'.$rs['uid'].'?t='.time();
					}
				}
				else {
					$user->pic = self::$picPath.$rs['cid'].'.'.$rs['uid'].'?t='.time();
				}
			}
			else {
				$user->pic = '../image/default/default_pro_pic.png';
			}
		}

		return $user;
	}
	
	public static function isConfUserTEixst($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT cid FROM USER_T WHERE CID=$cid AND UID=$uid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}

	public static function mGetRangeConfUsers($cid, $range)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT cid, uid, fullname, pic, pic_up, company, title FROM USER_T WHERE CID=$cid AND UID IN ($range)";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$users = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$users[$index] = new UserT();
			$users[$index]->cid = $row['cid'];
			$users[$index]->uid = $row['uid'];
			$users[$index]->fullname = $row['fullname'];
			$users[$index]->pic = $row['pic'];
			$users[$index]->pic_up = $row['pic_up'];
			$users[$index]->company = $row['company'];
			$users[$index]->title = $row['title'];

			if( isset($row['pic']) )
			{
				$filename = self::$picPath.$row['cid'].'.'.$row['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$row['cid'].'.'.$row['uid'], 'w') or $users[$index]->pic = '../image/default/default_pro_pic.png';
					if($users[$index]->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $row['pic']);
						fclose($confImg);
						$users[$index]->pic = self::$picPath.$row['cid'].'.'.$row['uid'].'?t='.time();
					}
				}
				else {
					$users[$index]->pic = self::$picPath.$row['cid'].'.'.$row['uid'].'?t='.time();
				}
			}
			else {
				$users[$index]->pic = '../image/default/default_pro_pic.png';
			}

			$index++;
		}

		return $users;
	}

	public static function mGetConfUser($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT cid, uid, fullname, pic, pic_up, description, company, title, email, cell, weibo FROM USER_T WHERE CID=$cid AND UID=$uid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);

		$user = null;
		if(isset($rs['uid']))
		{
			$user = new UserT();
			$user->cid = $rs['cid'];
			$user->uid = $rs['uid'];
			$user->fullname = $rs['fullname'];
			$user->pic = $rs['pic'];
			$user->pic_up = $rs['pic_up'];
			$user->description = $rs['description'];
			$user->company = $rs['company'];
			$user->title = $rs['title'];
			$user->email = $rs['email'];
			$user->cell = $rs['cell'];
			$user->weibo = $rs['weibo'];
	
			if( isset($rs['pic']) )
			{
				$filename = self::$picPath.$rs['cid'].'.'.$rs['uid'];
				if( !file_exists($filename))
				{
					$confImg = fopen(self::$picPath.$rs['cid'].'.'.$rs['uid'], 'w') or $user->pic = '../image/default/default_pro_pic.png';
					if($user->pic != '../image/default/default_pro_pic.png')
					{
						fwrite($confImg, $rs['pic']);
						fclose($confImg);
						$user->pic = self::$picPath.$rs['cid'].'.'.$rs['uid'].'?t='.time();
					}
				}
				else {
					$user->pic = self::$picPath.$rs['cid'].'.'.$rs['uid'].'?t='.time();
				}
			}
			else {
				$user->pic = '../image/default/default_pro_pic.png';
			}
		}

		return $user;
	}

//==================================================== static / normal ========================================================

	public function getUid()
	{
		return $this->uid;
	}
	public function getCid()
	{
		return $this->cid;
	}
	public function getEmail()
	{
		return $this->email;
	}
}