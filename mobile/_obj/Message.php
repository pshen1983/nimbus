<?php
include_once ("../utils/DatabaseUtil.php");

class Message
{
	private $id;
	private $title;
	private $r_id;
	private $s_id;
	private $body;
	private $ctime;
	
	public $is_read;

	public static function create($title, $r_id, $s_id, $body)
	{
		$title = trim($title);
		$body = trim($body);

		$atReturn = 0;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "INSERT INTO MESSAGE (TITLE, R_ID, S_ID, BODY, CTIME, IS_READ)
				  VALUES (".DatabaseUtil::checkNull($title).", $r_id, $s_id, ".DatabaseUtil::checkNull($body).", NOW(), 'N')";
		if (!DatabaseUtil::insertData($db_connection, $query))
		{
			$atReturn = -1;
		}
		else
		{
			$atReturn = mysql_insert_id();
		}

		return $atReturn;
	}
		
	public static function setRead($id, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);		
		$query = "UPDATE MESSAGE SET IS_READ='Y' WHERE ID=$id AND R_ID=$uid";
		return DatabaseUtil::updateData($db_connection, $query);
	}	

	public static function delete($id, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "DELETE FROM MESSAGE WHERE ID=$id AND R_ID=$uid";
		return DatabaseUtil::deleteData($db_connection, $query);
	}

	public static function getMessageList($uid, $is_read=null)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "SELECT id, title, s_id, ctime, is_read FROM MESSAGE WHERE R_ID=$uid".(isset($is_read) ? " AND IS_READ='$is_read'" : "")." ORDER BY CTIME DESC";
		$rs = DatabaseUtil::selectDataList($db_connection, $query);

		$messages = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$messages[$index] = new Message();
			$messages[$index]->id = $row['id'];
			$messages[$index]->title = $row['title'];
			$messages[$index]->r_id = $uid;
			$messages[$index]->s_id = $row['s_id'];
			$messages[$index]->ctime = $row['ctime'];
			$messages[$index]->is_read = $row['is_read'];
			$index++;
		}

		return $messages;
	}

	public static function getMessageBody($mid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "SELECT body FROM MESSAGE WHERE ID=$mid AND R_ID=$uid";
		$rs = DatabaseUtil::selectData($db_connection, $query);
		return $rs['body'];
	}

	public static function getUnreadMessageNumber($uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "SELECT COUNT(*) AS cnt FROM MESSAGE WHERE R_ID=$uid AND IS_READ='N'";
		$rs = DatabaseUtil::selectData($db_connection, $query);
		return $rs['cnt'];
	}

	public static function createJoinConfMessage($uid, $name, $email, $cell, $confName, $confId, $ownerId)
	{
		//============================================= Language ==================================================
		
		if($_SESSION['_language']=='en') {
			$l_title = $name." apply to join your event: ".$confName;
			$l_body = '<br />Contact information of '.$name.':<br />
					  Email: '.$email.'<br />
					  Cell:  '.$cell.'<br /><br />
					  Do you want to 
					  <a href="../utils/agreeJoinConf.inc.php?p1='.$uid.'&p2='.$confId.'">AGREE</a> or 
					  <a href="../utils/rejectJoinConf.inc.php?p1='.$uid.'&p2='.$confId.'">REJECT</a> this application?';
		}
		else if($_SESSION['_language']=='zh') {
			$l_title = $name."申请加入您的活动：".$confName;
			$l_body = '<br />'.$name.'的联系方式：<br />邮件：'.$email.'<br />手机：'.$cell.'<br /><br />
					  <a href="../utils/agreeJoinConf.inc.php?p1='.$uid.'&p2='.$confId.'">同意</a> 还是
					  <a href="../utils/rejectJoinConf.inc.php?p1='.$uid.'&p2='.$confId.'">拒绝</a>'.$name.'的申请？';
		}
		//=========================================================================================================

		return self::create($l_title, $ownerId, $uid, $l_body);
	}
//==================================================== static / normal ========================================================


	public function getId()	{
		return $this->id;
	}

	public function getTitle()	{
		return $this->title;
	}

	public function getRid()	{
		return $this->r_id;
	}

	public function getSid()	{
		return $this->s_id;
	}

	public function getBody()	{
		return $this->body;
	}

	public function getCtime()	{
		return $this->ctime;
	}

	public function getIsRead()	{
		return $this->is_read;
	}

	public function isRead() {
		return ($this->is_read == 'Y');
	}
}
?>