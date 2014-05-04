<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/DatabaseUtil.php");

Class Sponsor
{
	private $sid;
	private $cid;
	
	public $cname;
	public $url;
	public $img;

	public static $picPath = '../tmp/temp_spon_img_crop.';

	public static function create($cid, $cname, $url, $img)
	{
		$atReturn = 0;

		if( isset($cname) && !empty($cname) )
		{
			if(self::isNameExistPerCid($cid, $cname))
			{
				$atReturn = -2; // cnname exist for this conf
			}
			else
			{
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
				$sql = "INSERT INTO SPON (CID, CNAME, URL, IMG)	VALUES ($cid, '".mysql_real_escape_string($cname)."', ".
				DatabaseUtil::checkNull($url).", ".(isset($img) ? "'".$img."'" : 'NULL').")";
					
				if (!DatabaseUtil::insertData($db_connection, $sql))
				{
					$atReturn = -6; // insert fail
				}
				else
				{
					$atReturn = mysql_insert_id();
				}
			}
		}
		else $atReturn = -1; //missing input

		return $atReturn;
	}	
	
	public static function update($sid, $cid, $cname, $url, $img)
	{
		$atReturn = 0;
		
		if(isset($sid) && is_numeric($sid) && isset($cid) && is_numeric($cid) && isset($cname) && !empty($cname))
		{
			if(self::isNameExistPerCidUpdate($sid, $cid, $cname))
			{
				$atReturn = -2; // cnname exist for this conf
			}			
			else
			{
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
				$query = "UPDATE SPON SET CNAME='".mysql_real_escape_string($cname)."', ".
						"IMG=".(isset($img) ? "'".$img."'" : 'NULL').", ".
						"URL=".DatabaseUtil::checkNull($url)." WHERE SID=$sid";
				if (!DatabaseUtil::updateData($db_connection, $query))
				{
					$atReturn = -6; // update fail
				}
				else
				{
					$atReturn = $sid; // success
				}
			}
		}
		else 
		{
			$atReturn = -1; //missing input
		}	

		return $atReturn;
	}
	
	public static function delete($sid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "DELETE FROM SPON WHERE SID=$sid";
		return DatabaseUtil::deleteData($db_connection, $sql);
	}

	public static function isNameExistPerCid($cid, $cname)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid FROM SPON WHERE CID=$cid AND CNAME='$cname'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}
	
	public static function isNameExistPerCidUpdate($sid, $cid, $cname)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid FROM SPON WHERE SID<>$sid AND CID=$cid AND CNAME='$cname'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}
	
	public static function getSponsorListByCid($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT sid, cname, url, img FROM SPON WHERE CID=$cid";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);
		
		$sponsor = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$sponsor[$index] = new Sponsor();
			$sponsor[$index]->sid = $row['sid'];
			$sponsor[$index]->cname = $row['cname'];
			$sponsor[$index]->url = $row['url'];
			$sponsor[$index]->img = $row['img'];

			if( isset($row['img']) )
			{
				$filename = self::$picPath.$row['sid'];
				if( !file_exists($filename) )
				{
					$sponImg = fopen(self::$picPath.$row['sid'], 'w') or $sponsor[$index]->img = '../image/default/default_evn_pic.png';
					if($sponsor[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($sponImg, $row['img']);
						fclose($sponImg);
						$sponsor[$index]->img = self::$picPath.$row['sid'].'?t='.time();
					}
				}
				else
				{
					$sponsor[$index]->img = self::$picPath.$row['sid'].'?t='.time();
				}
			}
			else
			{
				$sponsor[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $sponsor;
	}

//==================================================== static / normal ========================================================

	public function getSid()
	{
		return $this->sid;
	}	
}