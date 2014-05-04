<?php
include_once ("../utils/DatabaseUtil.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SessionUtil.php");

class Invitation
{
	private $id;
	private $cid;	
	private $uid;
	
	public $fullname;
	public $company;
	public $title;
	public $email;
	public $cell;
	public $stime;

	public static function create($cid, $uid, $cell, $email, $fullname, $company, $title)
	{
		$atReturn = 0;

		if( isset($cid) && is_numeric($cid) && isset($uid) && is_numeric($uid) && isset($cell) && !empty($cell) && isset($fullname) && !empty($fullname))
		{
			if(!CommonUtil::validateCellFormat($cell))
			{
				$atReturn = -2;
			}
			else if(!empty($email) && !CommonUtil::validateEmailFormat($email))
			{
				$atReturn = -3;
			}
			else 
			{
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
				$query = "INSERT INTO INVT (CID, UID, CELL, EMAIL, FULLNAME, COMPANY, TITLE)
					 	  VALUES ($cid, $uid, '$cell', ".DatabaseUtil::checkNull($email).", '".mysql_real_escape_string($fullname)."', ".
				          DatabaseUtil::checkNull($company).", ".DatabaseUtil::checkNull($title).")";

				if (!DatabaseUtil::insertData($db_connection, $query))
				{
					$atReturn = -4;
				}
				else
				{					
					$atReturn = mysql_insert_id();
				}
			}
		}
		else 
		{
			$atReturn = -1;
		}

		return $atReturn;
	}

	public static function delete($id)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "DELETE FROM INVT WHERE ID=$id";
		return DatabaseUtil::deleteData($db_connection, $sql);
	}
	
	public static function deleteRange($ids)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "DELETE FROM INVT WHERE ID IN ($ids)";
		return DatabaseUtil::deleteData($db_connection, $sql);
	}
	
	public static function update($id, $fullname, $company, $title, $email)
	{
		$atReturn = 0;

		if( isset($id) && is_numeric($id) && isset($fullname) && !empty($fullname))
		{	
			if(!empty($email) && !CommonUtil::validateEmailFormat($email))
			{
				$atReturn = 3;
			}
			else
			{
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
				$query = "UPDATE INVT SET FULLNAME='".mysql_real_escape_string($fullname)."', ".
						"COMPANY=".DatabaseUtil::checkNull($company).", ".
						"TITLE=".DatabaseUtil::checkNull($title).", ".
						"EMAIL=".DatabaseUtil::checkNull($email).
						" WHERE ID=$id";
				if (!DatabaseUtil::updateData($db_connection, $query))
				{
					$atReturn = 2;
				}
			}
		}
		else 
		{
			$atReturn = 1;
		}	

		return $atReturn;
	}

	public static function updateStime($id)
	{
		$atReturn = 0;

		if( isset($id) && is_numeric($id) )
		{
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
			$query = "UPDATE INVT SET STIME=NOW() WHERE ID=$id";
			if (!DatabaseUtil::updateData($db_connection, $query))
			{
				$atReturn = 2;
			}			
		}
		else 
		{
			$atReturn = 1;
		}	

		return $atReturn;
	}
	
	public static function updateStimeRange($ids)
	{
		$atReturn = 0;

		if( isset($ids) && is_numeric($ids) )
		{
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
			$query = "UPDATE INVT SET STIME=NOW() WHERE ID IN $ids";
			if (!DatabaseUtil::updateData($db_connection, $query))
			{
				$atReturn = 2;
			}			
		}
		else 
		{
			$atReturn = 1;
		}	

		return $atReturn;
	}
	
	public static function getInvtNotSent($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT id, cid, uid, cell, email, fullname, company, title FROM INVT WHERE CID=$cid AND STIME IS NULL";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Invitation();
			$atReturn[$index]->id = $row['id'];
			$atReturn[$index]->cid = $cid;
			$atReturn[$index]->uid = $row['uid'];
			$atReturn[$index]->cell = $row['cell'];
			$atReturn[$index]->email = $row['email'];
			$atReturn[$index]->fullname = $row['fullname'];
			$atReturn[$index]->company = $row['company'];
			$atReturn[$index]->title = $row['title'];
			
			$index++;
		}

		return $atReturn;
	}
	
	public static function getInvtSent($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT id, cid, uid, cell, email, fullname, company, title, stime FROM INVT WHERE CID=$cid AND STIME IS NOT NULL";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Invitation();
			$atReturn[$index]->id = $row['id'];
			$atReturn[$index]->cid = $cid;
			$atReturn[$index]->uid = $row['uid'];
			$atReturn[$index]->cell = $row['cell'];
			$atReturn[$index]->email = $row['email'];
			$atReturn[$index]->fullname = $row['fullname'];
			$atReturn[$index]->company = $row['company'];
			$atReturn[$index]->title = $row['title'];
			$atReturn[$index]->stime = $row['stime'];
			
			$index++;
		}

		return $atReturn;
	}

	public static function getInvtById($id)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$USER);
		$sql = "SELECT id, cid, uid, cell, email, fullname, company, title, stime FROM INVT WHERE ID=$id";
		$row = DatabaseUtil::selectData($db_connection, $sql);

		$atReturn = null;
		if( isset($row) && $row )
		{
			$atReturn = new Invitation();
			$atReturn->id = $id;
			$atReturn->cid = $row['cid'];
			$atReturn->uid = $row['uid'];
			$atReturn->cell = $row['cell'];
			$atReturn->email = $row['email'];
			$atReturn->fullname = $row['fullname'];
			$atReturn->company = $row['company'];
			$atReturn->title = $row['title'];
			$atReturn->stime = $row['stime'];			
		}

		return $atReturn;
	}
//==================================================== static / normal ========================================================

	public function getId()
	{
		return $this->id;
	}
	public function getUid()
	{
		return $this->uid;
	}
	public function getCid()
	{
		return $this->cid;
	}
}