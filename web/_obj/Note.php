<?php
include_once ("../utils/DatabaseUtil.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SessionUtil.php");

class Note
{
	private $id;
	private $cid;
	private $uid;

	public $nbody;
	public $mdata;

	private $ctime;

	public static function create($cid, $uid, $nbody, $mdata=null)
	{
		$atReturn = 0;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "INSERT INTO NOTE (CID, UID, NBODY, MDATA, CTIME)
				  VALUES ($cid, $uid, ".DatabaseUtil::checkNull($nbody).", ".DatabaseUtil::checkNull($mdata).", NOW())";
		if (!DatabaseUtil::insertData($db_connection, $query))
		{
			$atReturn = 1;
		}

		return $atReturn;
	}

	public static function getUserConfNoteList($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "SELECT id, nbody, mdata, ctime FROM NOTE WHERE CID=$cid AND UID=$uid";
		$rs = DatabaseUtil::selectDataList($db_connection, $query);

		$notes = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$notes[$index] = new Note();
			$notes[$index]->id = $row['id'];
			$notes[$index]->cid = $row['cid'];
			$notes[$index]->uid = $row['uid'];
			$notes[$index]->nbody = $row['nbody'];
			$notes[$index]->mdata = $row['mdata'];
			$notes[$index]->ctime = $row['ctime'];
		}

		return $notes;
	}

//==================================================== static / normal ========================================================

	public function update()
	{
		$result = false;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);		
		$query = "UPDATE NOTE SET NBODY=".DatabaseUtil::checkNull($this->nbody).
				 ", MDATA=".DatabaseUtil::checkNull($this->mdata).
				 " WHERE UID=".$this->uid." AND CID=".$this->cid;
		return DatabaseUtil::updateData($db_connection, $query);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getCid()
	{
		return $this->cid;
	}

	public function getUid()
	{
		return $this->uid;
	}

	public function getCtime()
	{
		return $this->ctime;
	}
}
?>