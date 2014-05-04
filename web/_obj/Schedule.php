<?php
include_once ("../utils/DatabaseUtil.php");

Class Schedule
{
	private $sid;
	private $cid;

	public $date;
	public $stime;
	public $etime;
	public $addr;
	public $summ;
	public $note;

	public static function create($cid, $uid, $date, $stime, $etime, $addr, $summ, $note)
	{
		$atReturn = 0; //success

		if( isset($date) && !empty($date) && isset($stime) && !empty($stime) && isset($etime) && !empty($etime) && 
			isset($addr) && !empty($addr) && isset($summ) && !empty($summ) )
		{
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
			
			$addr = mysql_real_escape_string($addr);
			$summ = mysql_real_escape_string($summ);

			$sql = "INSERT INTO SCHE (CID, UID, DATE, STIME, ETIME, LOCATION, SUMMARY, NOTE)
					VALUES('$cid', '$uid', '$date', '$stime', '$etime', '$addr', '$summ', ".DatabaseUtil::checkNull($note).")";

			if (!DatabaseUtil::insertData($db_connection, $sql))
			{
				$atReturn = -1; //insert error
			}
			else
			{
				$atReturn = mysql_insert_id();
			}
		}
		else $atReturn = -2; //missing input

		return $atReturn;
	}
	
	public static function delete($sid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "DELETE FROM SCHE WHERE SID=$sid AND UID=$uid";
		return DatabaseUtil::deleteData($db_connection, $sql);
	}
	
	public static function update($sid, $cid, $uid, $date, $stime, $etime, $addr, $summ, $note)
	{
		$atReturn = 0; //success

		if( isset($date) && !empty($date) && isset($stime) && !empty($stime) && isset($etime) && !empty($etime) && 
			isset($addr) && !empty($addr) && isset($summ) && !empty($summ) )
		{
		
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
			$sql = "UPDATE SCHE SET date = '".$date.
			       "', stime = '".$stime.		
			       "', etime = '".$etime.		
			       "', location = '".$addr.		
			       "', summary = '".$summ.		
			       "', note = '".$note.		
				   "' WHERE SID=$sid AND CID=$cid AND UID=$uid";
			
			if (!DatabaseUtil::updateData($db_connection, $sql))
			{
				$atReturn = -1; //update error
			}
		}
		else 
		{
			$atReturn = -2; //missing input
		}

		return $atReturn;
	}
	
	public static function getConfScheList($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT sid, cid, date, stime, etime, location, summary, note FROM SCHE WHERE CID=$cid";
		return DatabaseUtil::selectDataList($db_connection, $sql);
	}

	public static function getConfScheObjs($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT sid, cid, date, stime, etime, location, summary, note FROM SCHE WHERE CID=$cid";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Schedule();
			$atReturn[$index]->cid = $cid;
			$atReturn[$index]->sid = $row['sid'];
			$atReturn[$index]->date = str_replace('-', '.' ,$row['date']);
			$atReturn[$index]->stime = substr($row['stime'], 0, 5);
			$atReturn[$index]->etime = substr($row['etime'], 0, 5);
			$atReturn[$index]->addr = $row['location'];
			$atReturn[$index]->summ = $row['summary'];
			$atReturn[$index]->note = $row['note'];
			$index++;
		}

		return $atReturn;
	}
	
	public static function mGetEvent($cid, $sid)
	{
		$atReturn = null;
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT sid, date, stime, etime, location, summary, note FROM SCHE WHERE CID=$cid AND SID=$sid";
		$event = DatabaseUtil::selectData($db_connection, $sql);

		if( isset($event['sid']) )
		{
			$atReturn = new Schedule();
			$atReturn->cid = $cid;
			$atReturn->sid = $sid;
			$atReturn->date = str_replace('-', '.' ,$event['date']);
			$atReturn->stime = substr($event['stime'], 0, 5);
			$atReturn->etime = substr($event['etime'], 0, 5);
			$atReturn->addr = $event['location'];
			$atReturn->summ = $event['summary'];
			$atReturn->note = $event['note'];
		}
		
		return $atReturn;
	}

	public static function mGetConfScheList($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT sid, date, stime, etime, location, summary FROM SCHE WHERE CID=$cid";
		return DatabaseUtil::selectDataList($db_connection, $sql);
	}

//==================================================== static / normal ========================================================

	public function getCid()
	{
		return $this->cid;
	}

	public function getSid()
	{
		return $this->sid;
	}
}