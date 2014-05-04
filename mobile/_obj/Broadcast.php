<?php
include_once ("../utils/DatabaseUtil.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SessionUtil.php");

class Broadcast
{
	private $id;
	private $cid;

	public $btitle;
	public $bbody;

	private $ctime;
	private $utime;

	public static function create($cid, $btitle, $bbody)
	{
		$atReturn = 0;

		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "INSERT INTO BROADCAST (CID, BTITLE, BBODY, CTIME, UTIME)
				  VALUES ($cid, '".mysql_real_escape_string($btitle)."', ".DatabaseUtil::checkNull($bbody).", NOW(), NOW())";
		if (!DatabaseUtil::insertData($db_connection, $query))
		{
			$atReturn = 1;
		}

		return $atReturn;
	}	

	public static function delete($id)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "DELETE FROM BROADCAST WHERE ID=$id";
		return DatabaseUtil::deleteData($db_connection, $query);
	}

	public static function update($id, $btitle, $bbody)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);		
		$query = "UPDATE BROADCAST SET BTITLE=".DatabaseUtil::checkNull($btitle).
				 ", BBODY=".DatabaseUtil::checkNull($bbody).
				 ", UTIME=NOW() WHERE ID=$id";
		return DatabaseUtil::updateData($db_connection, $query);
	}

	public static function getConfBcastList($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "SELECT id, btitle, bbody, utime FROM BROADCAST WHERE CID=$cid ORDER BY UTIME DESC";
		return DatabaseUtil::selectDataList($db_connection, $query);
	}

	public static function getConfBcastObjs($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "SELECT id, btitle, bbody, utime FROM BROADCAST WHERE CID=$cid ORDER BY UTIME DESC";
		$rs = DatabaseUtil::selectDataList($db_connection, $query);

		$bcast = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$bcast[$index] = new Broadcast();
			$bcast[$index]->id = $row['id'];
			$bcast[$index]->cid = $cid;
			$bcast[$index]->btitle = $row['btitle'];
			$bcast[$index]->bbody = $row['bbody'];
			$bcast[$index]->utime = $row['utime'];
			$index++;
		}

		return $bcast;
	}

	public static function mGetConfBcasts($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$query = "SELECT id, btitle, utime FROM BROADCAST WHERE CID=$cid ORDER BY UTIME DESC";
		$rs = DatabaseUtil::selectDataList($db_connection, $query);

		$bcast = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$bcast[$index] = new Broadcast();
			$bcast[$index]->id = $row['id'];
			$bcast[$index]->cid = $cid;
			$bcast[$index]->btitle = $row['btitle'];
			$bcast[$index]->utime = $row['utime'];
			$index++;
		}

		return $bcast;
	}

	public static function mGetBcast($id, $cid)
	{
		$atReturn = null;
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$sql = "SELECT btitle, bbody, utime FROM BROADCAST WHERE CID=$cid AND ID=$id";
		$cast = DatabaseUtil::selectData($db_connection, $sql);

		if( $cast && isset($cast) )
		{
			$atReturn = new Broadcast();
			$atReturn->id = $id;
			$atReturn->cid = $cid;
			$atReturn->btitle = $cast['btitle'];
			$atReturn->bbody = $cast['bbody'];
			$atReturn->utime = $cast['utime'];
		}
		
		return $atReturn;
	}
	
//==================================================== static / normal ========================================================

	public function getId()
	{
		return $this->id;
	}

	public function getCid()
	{
		return $this->cid;
	}

	public function getUtime()
	{
		return $this->utime;
	}
}
?>