<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/DatabaseUtil.php");

Class Conference
{
	private $cid;
	private $url;
	private $owner;

	public $name;
	public $img;
	public $banner;
	public $coun;
	public $prov;
	public $city;
	public $dist;
	public $addr;
	public $registration;
	public $search;
	public $stime;
	public $etime;
	public $status;
	public $description;
	public $lat;
	public $lng;

	private $faddr; //full address
	private $ctime;

	public static $imgPath = '../tmp/temp_conf_img_crop130x80.';

	public static function create( $owner, 
								   $url, 
								   $name, 
								   $coun, 
								   $prov, 
								   $city, 
								   $dist, 
								   $addr, 
								   $registration, 
								   $search, 
								   $stime, 
								   $etime, 
								   $description, 
								   $lat, 
								   $lng)
	{
		$atReturn = 0;

		if( isset($owner) && !empty($owner) && isset($url) && !empty($url) &&
			isset($name) && !empty($name) && isset($coun) && !empty($coun) && 
			isset($prov) && !empty($prov) && isset($city) && !empty($city) && 
			isset($registration) && !empty($registration) && 
			isset($search) && !empty($search) && isset($stime) && !empty($stime) )
		{
			if(self::isUrlExist($url))
			{
				$atReturn = 3;
			}
			else if(!CommonUtil::checkConfUrlFormat($url))
			{
				$atReturn = 4;
			}
			else {
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
				$name = mysql_real_escape_string($name);
	
				$faddr = DatabaseUtil::getCityName($city);
	
				if( isset($dist) && !empty($dist) )
				{
					$faddr = $faddr.DatabaseUtil::getDistName($dist).$addr;
				}
				else {
					$faddr = $faddr.$addr;
				}
				if (!isset($lat) || !isset($lng) || !is_numeric($lat) || !is_numeric($lng))
				{
					$lat = 'NULL';
					$lng = 'NULL';
				}
	
				$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
				$sql = "INSERT INTO CONF ( OWNER, 
										   NAME, 
										   URL, 
										   COUN, 
										   PROV, 
										   CITY, 
										   DIST, 
										   ADDR, 
										   FADDR, 
										   REGISTRATION, 
										   SEARCH, 
										   STIME, 
										   ETIME, 
										   STATUS, 
										   DESCRIPTION, 
										   LAT, 
										   LNG, 
										   CTIME )
						VALUES( $owner, 
								'$name', 
								'$url', 
								$coun, 
								$prov, 
								$city, ".
								DatabaseUtil::checkNull($dist).", ".
								DatabaseUtil::checkNull($addr).", 
								'$faddr', 
								'$registration', 
								'$search', 
								'$stime', ".
								DatabaseUtil::checkNull($etime).", 
								'D', 
								".DatabaseUtil::checkNull($description).", 
								".$lat.", 
								".$lng.", 
								NOW() )";
	
				if (!DatabaseUtil::insertData($db_connection, $sql))
				{
					$atReturn = 1;
				}
			}
		}
		else $atReturn = 2;

		return $atReturn;
	}

	public static function update( $cid, 
								   $name, 
								   $coun, 
								   $prov, 
								   $city, 
								   $dist, 
								   $addr, 
								   $stime, 
								   $etime, 
								   $registration, 
								   $search, 
								   $description, 
								   $lat, 
								   $lng )
	{
		$atReturn = 0; //success
		
		if( isset($name) && !empty($name) && isset($coun) && !empty($coun) && 
			isset($prov) && !empty($prov) && isset($city) && !empty($city) && 
			isset($registration) && !empty($registration) && 
			isset($search) && !empty($search) && isset($stime) && !empty($stime))
		{	
			$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
			$name = mysql_real_escape_string($name);

			$faddr = DatabaseUtil::getCityName($city);

			if( isset($dist) && !empty($dist) )
			{
				$faddr = $faddr.DatabaseUtil::getDistName($dist).$addr;
			}
			else 
			{
				$faddr = $faddr.$addr;
			}
			if (!isset($lat) || !isset($lng) || !is_numeric($lat) || !is_numeric($lng))
			{
				$lat = 'NULL';
				$lng = 'NULL';
			}
			
			$sql = "UPDATE CONF SET NAME='$name', 
									COUN=$coun, 
									PROV=$prov, 
									CITY=$city, 
									DIST=".DatabaseUtil::checkNull($dist).", 
									ADDR=".DatabaseUtil::checkNull($addr).", 
									FADDR='$faddr', 
									REGISTRATION='$registration', 
									SEARCH='$search', 
									STIME='$stime', 
									ETIME=".DatabaseUtil::checkNull($etime).", 
									DESCRIPTION=".DatabaseUtil::checkNull($description).", 
									LAT=$lat, 
									LNG=$lng
									WHERE CID=$cid";
							
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

	public static function isUrlExist($url)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid FROM CONF WHERE URL='$url'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}

	public static function getConfInfo($url)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, owner, name, img, banner, coun, prov, city, dist, addr, faddr, stime, etime, registration, search, status, description FROM CONF WHERE URL='$url'";

		$rs = DatabaseUtil::selectData($db_connection, $sql);

		if($rs)
		{
			$atReturn = new Conference();
			$atReturn->cid = $rs['cid'];
			$atReturn->owner = $rs['owner'];
			$atReturn->name = $rs['name'];
			$atReturn->url = $url;
			$atReturn->img = $rs['img'];
			$atReturn->banner = $rs['banner'];
			$atReturn->coun = $rs['coun'];
			$atReturn->prov = $rs['prov'];
			$atReturn->city = $rs['city'];
			$atReturn->dist = $rs['dist'];
			$atReturn->addr = $rs['addr'];
			$atReturn->faddr = $rs['faddr'];
			$atReturn->stime = $rs['stime'];
			$atReturn->etime = $rs['etime'];
			$atReturn->registration = $rs['registration'];
			$atReturn->search = $rs['search'];
			$atReturn->status = $rs['status'];
			$atReturn->description = $rs['description'];
	
			if( isset($rs['img']))
			{
				if( !file_exists(self::$imgPath.$rs['cid']))
				{
					$confImg = fopen(self::$imgPath.$rs['cid'], 'w') or $atReturn->img = '../image/default/default_evn_pic.png';
					if($atReturn->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $rs['img']);
						fclose($confImg);
						$atReturn->img = self::$imgPath.$rs['cid'];
					}
				}
				else {
					$atReturn->img = self::$imgPath.$rs['cid'];
				}
			}
			else {
				$atReturn->img = '../image/default/default_evn_pic.png';
			}
		}
		else {
			$atReturn = null;
		}

		return $atReturn;
	}
	
	public static function getConfInfoByCid($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, owner, name, url, img, banner, coun, prov, city, dist, addr, faddr, stime, etime, registration, search, status, description FROM CONF WHERE CID='$cid'";

		$rs = DatabaseUtil::selectData($db_connection, $sql);

		if($rs)
		{
			$atReturn = new Conference();
			$atReturn->cid = $cid;
			$atReturn->owner = $rs['owner'];
			$atReturn->name = $rs['name'];
			$atReturn->url = $rs['url'];
			$atReturn->img = $rs['img'];
			$atReturn->banner = $rs['banner'];
			$atReturn->coun = $rs['coun'];
			$atReturn->prov = $rs['prov'];
			$atReturn->city = $rs['city'];
			$atReturn->dist = $rs['dist'];
			$atReturn->addr = $rs['addr'];
			$atReturn->faddr = $rs['faddr'];
			$atReturn->stime = $rs['stime'];
			$atReturn->etime = $rs['etime'];
			$atReturn->registration = $rs['registration'];
			$atReturn->search = $rs['search'];
			$atReturn->status = $rs['status'];
			$atReturn->description = $rs['description'];
	
			if( isset($rs['img']))
			{
				if( !file_exists(self::$imgPath.$rs['cid']))
				{
					$confImg = fopen(self::$imgPath.$rs['cid'], 'w') or $atReturn->img = '../image/default/default_evn_pic.png';
					if($atReturn->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $rs['img']);
						fclose($confImg);
						$atReturn->img = self::$imgPath.$rs['cid'];
					}
				}
				else {
					$atReturn->img = self::$imgPath.$rs['cid'];
				}
			}
			else {
				$atReturn->img = '../image/default/default_evn_pic.png';
			}
		}
		else {
			$atReturn = null;
		}

		return $atReturn;
	}

	public static function getRangeBasicConfInfo($range, $status)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, url, name, img, addr, faddr, stime, etime FROM CONF WHERE CID IN ($range) AND STATUS='$status'";

		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Conference();
			$atReturn[$index]->cid = $row['cid'];
			$atReturn[$index]->name = $row['name'];
			$atReturn[$index]->url = $row['url'];
			$atReturn[$index]->img = $row['img'];
			$atReturn[$index]->addr = $row['addr'];
			$atReturn[$index]->faddr = $row['faddr'];
			$atReturn[$index]->stime = $row['stime'];
			$atReturn[$index]->etime = $row['etime'];

			if( isset($row['img']) )
			{
				if( !file_exists(self::$imgPath.$row['cid']))
				{
					$confImg = fopen(self::$imgPath.$row['cid'], 'w') or $atReturn[$index]->img = '../image/default/default_evn_pic.png';
					if($atReturn[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $row['img']);
						fclose($confImg);
						$atReturn[$index]->img = self::$imgPath.$row['cid'];
					}
				}
				else {
					$atReturn[$index]->img = self::$imgPath.$row['cid'];
				}
			}
			else {
				$atReturn[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $atReturn;
	}
	
	public static function getNeighborConfInfoWithin($lat1, $lng1, $lat2, $lng2, $status, $search='Y')
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, url, name, img, addr, faddr, stime, etime FROM CONF WHERE LAT > $lat1 AND LAT < $lat2 AND LNG > $lng1 AND LNG < $lng2 AND STATUS='$status' AND SEARCH='$search'";

		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Conference();
			$atReturn[$index]->cid = $row['cid'];
			$atReturn[$index]->name = $row['name'];
			$atReturn[$index]->url = $row['url'];
			$atReturn[$index]->img = $row['img'];
			$atReturn[$index]->addr = $row['addr'];
			$atReturn[$index]->faddr = $row['faddr'];
			$atReturn[$index]->stime = $row['stime'];
			$atReturn[$index]->etime = $row['etime'];

			if( isset($row['img']) )
			{
				if( !file_exists(self::$imgPath.$row['cid']))
				{
					$confImg = fopen(self::$imgPath.$row['cid'], 'w') or $atReturn[$index]->img = '../image/default/default_evn_pic.png';
					if($atReturn[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $row['img']);
						fclose($confImg);
						$atReturn[$index]->img = self::$imgPath.$row['cid'];
					}
				}
				else {
					$atReturn[$index]->img = self::$imgPath.$row['cid'];
				}
			}
			else {
				$atReturn[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $atReturn;
	}
	
	public static function getConfInfoSameCity($city, $not_range, $status, $search='Y')
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, url, name, img, addr, faddr, stime, etime FROM CONF WHERE CITY = $city AND CID NOT IN ($not_range) AND STATUS='$status' AND SEARCH='$search'";

		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Conference();
			$atReturn[$index]->cid = $row['cid'];
			$atReturn[$index]->name = $row['name'];
			$atReturn[$index]->url = $row['url'];
			$atReturn[$index]->img = $row['img'];
			$atReturn[$index]->addr = $row['addr'];
			$atReturn[$index]->faddr = $row['faddr'];
			$atReturn[$index]->stime = $row['stime'];
			$atReturn[$index]->etime = $row['etime'];

			if( isset($row['img']) )
			{
				if( !file_exists(self::$imgPath.$row['cid']))
				{
					$confImg = fopen(self::$imgPath.$row['cid'], 'w') or $atReturn[$index]->img = '../image/default/default_evn_pic.png';
					if($atReturn[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $row['img']);
						fclose($confImg);
						$atReturn[$index]->img = self::$imgPath.$row['cid'];
					}
				}
				else {
					$atReturn[$index]->img = self::$imgPath.$row['cid'];
				}
			}
			else {
				$atReturn[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $atReturn;
	}

	public static function getConfInfoSameProv($prov, $not_range, $status, $search='Y')
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, url, name, img, addr, faddr, stime, etime FROM CONF WHERE PROV = $prov AND CID NOT IN ($not_range) AND STATUS='$status' AND SEARCH='$search'";

		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Conference();
			$atReturn[$index]->cid = $row['cid'];
			$atReturn[$index]->name = $row['name'];
			$atReturn[$index]->url = $row['url'];
			$atReturn[$index]->img = $row['img'];
			$atReturn[$index]->addr = $row['addr'];
			$atReturn[$index]->faddr = $row['faddr'];
			$atReturn[$index]->stime = $row['stime'];
			$atReturn[$index]->etime = $row['etime'];

			if( isset($row['img']) )
			{
				if( !file_exists(self::$imgPath.$row['cid']))
				{
					$confImg = fopen(self::$imgPath.$row['cid'], 'w') or $atReturn[$index]->img = '../image/default/default_evn_pic.png';
					if($atReturn[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $row['img']);
						fclose($confImg);
						$atReturn[$index]->img = self::$imgPath.$row['cid'];
					}
				}
				else {
					$atReturn[$index]->img = self::$imgPath.$row['cid'];
				}
			}
			else {
				$atReturn[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $atReturn;
	}	

	public static function getConfInfoOther($not_range, $status, $search='Y')
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, url, name, img, addr, faddr, stime, etime FROM CONF WHERE CID NOT IN ($not_range) AND STATUS='$status' AND SEARCH='$search'";

		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Conference();
			$atReturn[$index]->cid = $row['cid'];
			$atReturn[$index]->name = $row['name'];
			$atReturn[$index]->url = $row['url'];
			$atReturn[$index]->img = $row['img'];
			$atReturn[$index]->addr = $row['addr'];
			$atReturn[$index]->faddr = $row['faddr'];
			$atReturn[$index]->stime = $row['stime'];
			$atReturn[$index]->etime = $row['etime'];

			if( isset($row['img']) )
			{
				if( !file_exists(self::$imgPath.$row['cid']))
				{
					$confImg = fopen(self::$imgPath.$row['cid'], 'w') or $atReturn[$index]->img = '../image/default/default_evn_pic.png';
					if($atReturn[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $row['img']);
						fclose($confImg);
						$atReturn[$index]->img = self::$imgPath.$row['cid'];
					}
				}
				else {
					$atReturn[$index]->img = self::$imgPath.$row['cid'];
				}
			}
			else {
				$atReturn[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $atReturn;
	}	
	
	public static function getConfName($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT name FROM CONF WHERE CID=$cid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return $rs['name'];
	}

	public static function getConfUrl($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT url FROM CONF WHERE CID=$cid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return $rs['url'];
	}

	public static function getBasicConfInfoWithDescription($cid, $status)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT url, name, img, addr, faddr, registration, description, stime, etime FROM CONF WHERE CID=$cid AND STATUS='$status'";

		$rs = DatabaseUtil::selectData($db_connection, $sql);

		if($rs)
		{
			$atReturn = new Conference();
			$atReturn->cid = $cid;
			$atReturn->name = $rs['name'];
			$atReturn->url = $rs['url'];
			$atReturn->img = $rs['img'];
			$atReturn->addr = $rs['addr'];
			$atReturn->faddr = $rs['faddr'];
			$atReturn->registration = $rs['registration'];
			$atReturn->stime = $rs['stime'];
			$atReturn->etime = $rs['etime'];
			$atReturn->description = $rs['description'];
	
			if( isset($rs['img']) )
			{
				if( !file_exists(self::$imgPath.$cid))
				{
					$confImg = fopen(self::$imgPath.$cid, 'w') or $atReturn->img = '../image/default/default_evn_pic.png';
					if($atReturn->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $rs['img']);
						fclose($confImg);
						$atReturn->img = self::$imgPath.$cid;
					}
				}
				else {
					$atReturn->img = self::$imgPath.$cid;
				}
			}
			else {
				$atReturn->img = '../image/default/default_evn_pic.png';
			}
		}
		else {
			$atReturn = null;
		}

		return $atReturn;
	}

	public static function getUserConfList($uid, $status=null)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, name, url, img, addr, faddr, stime, etime FROM CONF WHERE OWNER=$uid".(isset($status) ? " AND STATUS='$status'" : "");
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$atReturn = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$atReturn[$index] = new Conference();
			$atReturn[$index]->cid = $row['cid'];
			$atReturn[$index]->name = $row['name'];
			$atReturn[$index]->url = $row['url'];
			$atReturn[$index]->img = $row['img'];
			$atReturn[$index]->addr = $row['addr'];
			$atReturn[$index]->faddr = $row['faddr'];
			$atReturn[$index]->stime = $row['stime'];
			$atReturn[$index]->etime = $row['etime'];

			if( isset($row['img']) )
			{
				if( !file_exists(self::$imgPath.$row['cid']))
				{
					$confImg = fopen(self::$imgPath.$row['cid'], 'w') or $atReturn[$index]->img = '../image/default/default_evn_pic.png';
					if($atReturn[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $row['img']);
						fclose($confImg);
						$atReturn[$index]->img = self::$imgPath.$row['cid'];
					}
				}
				else {
					$atReturn[$index]->img = self::$imgPath.$row['cid'];
				}
			}
			else {
				$atReturn[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $atReturn;
	}

	public static function getUserClosedConfList($uid, $start=null, $num=null)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, name, url FROM CONF WHERE OWNER=$uid AND STATUS='C'".
				((isset($start) && is_numeric($start) && isset($num) && is_numeric($num)) ? " LIMIT $start, $num" : "");
		return DatabaseUtil::selectDataList($db_connection, $sql);
	}

	public static function getUserFinishedConfList($uid, $start=null, $num=null)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, name, url FROM CONF WHERE CID IN (SELECT cid FROM RELA WHERE UID=$uid) AND STATUS='C'".
				((isset($start) && is_numeric($start) && isset($num) && is_numeric($num)) ? " LIMIT $start, $num" : "");
		return DatabaseUtil::selectDataList($db_connection, $sql);
	}

	public static function isUserConfByUrl($uid, $url)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid FROM CONF WHERE URL='$url' AND OWNER=$uid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}

	public static function isUserConfById($uid, $cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid FROM CONF WHERE CID=$cid AND OWNER=$uid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}

	public static function publishConf($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "UPDATE CONF SET status='P' WHERE CID=$cid";
		return DatabaseUtil::updateData($db_connection, $sql);
	}

	public static function closeConf($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "UPDATE CONF SET status='C' WHERE CID=$cid";
		return DatabaseUtil::updateData($db_connection, $sql);
	}

	public static function joinConf($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "INSERT INTO RELA(UID, CID, ROLE) VALUES($uid, $cid, 'A')";
		return DatabaseUtil::insertData($db_connection, $sql);
	}

	public static function leaveConf($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "DELETE FROM RELA WHERE UID=$uid AND CID=$cid";
		return DatabaseUtil::insertData($db_connection, $sql);
	}

	public static function isJoined($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid FROM RELA WHERE CID=$cid AND UID=$uid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}

	public static function isSpeaker($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid FROM RELA WHERE CID=$cid AND UID=$uid AND ROLE='S'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}

	public static function isAttendee($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid FROM RELA WHERE CID=$cid AND UID=$uid AND ROLE='A'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return isset($rs['cid']);
	}

	public static function addSpeaker($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "INSERT INTO RELA(UID, CID, ROLE) VALUES($uid, $cid, 'S')";
		return DatabaseUtil::insertData($db_connection, $sql);
	}
	
	public static function delSpeaker($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "DELETE FROM RELA WHERE UID=$uid AND CID=$cid AND ROLE='S'";
		return DatabaseUtil::deleteData($db_connection, $sql);
	}

	public static function addAttendee($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "INSERT INTO RELA(UID, CID, ROLE) VALUES($uid, $cid, 'A')";
		return DatabaseUtil::insertData($db_connection, $sql);
	}
	
	public static function delAttendee($cid, $uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "DELETE FROM RELA WHERE UID=$uid AND CID=$cid AND ROLE='A'";
		return DatabaseUtil::deleteData($db_connection, $sql);
	}

	public static function getConfIdStatus($url)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, owner, name, status, registration FROM CONF WHERE URL='$url'";
		return DatabaseUtil::selectData($db_connection, $sql);
	}

	public static function getConfUrlStatus($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT url, owner, name, status, registration FROM CONF WHERE CID=$cid";
		return DatabaseUtil::selectData($db_connection, $sql);
	}

	public static function getUserJoinedConfs($uid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT DISTINCT cid FROM RELA WHERE UID=$uid";
		return DatabaseUtil::selectDataList($db_connection, $sql);
	}

	public static function getConfSpeakerIds($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT uid FROM RELA WHERE CID=$cid AND ROLE='S' ORDER BY ID";
		return DatabaseUtil::selectDataList($db_connection, $sql);
	}

	public static function getConfAttendeeIds($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT uid FROM RELA WHERE CID=$cid AND ROLE='A' ORDER BY ID";
		return DatabaseUtil::selectDataList($db_connection, $sql);
	}

	public static function getConfUserIds($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT uid FROM RELA WHERE CID=$cid ORDER BY ID";
		return DatabaseUtil::selectDataList($db_connection, $sql);
	}

	public static function mGetRangeConfBasicInfo($range, $status)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, url, name, addr, stime, etime FROM CONF WHERE CID IN ($range) AND STATUS='$status'";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$confs = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$confs[$index] = new Conference();
			$confs[$index]->cid = $row['cid'];
			$confs[$index]->url = $row['url'];
			$confs[$index]->name = $row['name'];
			$confs[$index]->addr = $row['addr'];
			$confs[$index]->stime = $row['stime'];
			$confs[$index]->etime = $row['etime'];
			$index++;
		}

		return $confs;
	}

	public static function updateImg($cid, $img, $isBanner)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "UPDATE CONF SET ".($isBanner ? 'BANNER' : 'IMG')."='$img', ".($isBanner ? 'BANNER_UP' : 'IMG_UP')."=NOW() WHERE CID=$cid";
		return DatabaseUtil::updateData($db_connection, $sql);
	}

	public static function hasSchedule($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT COUNT(*) as count FROM SCHE WHERE CID=$cid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return $rs['count'] > 0;
	}

	public static function hasSpeaker($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT COUNT(*) as count FROM RELA WHERE CID=$cid AND ROLE='S'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return $rs['count'] > 0;
	}

	public static function hasAttendee($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT COUNT(*) as count FROM RELA WHERE CID=$cid AND ROLE='A'";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return $rs['count'] > 0;
	}

	public static function hasSponser($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT COUNT(*) as count FROM SPON WHERE CID=$cid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return $rs['count'] > 0;
	}

	public static function hasBcasts($cid)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$NOTE);
		$sql = "SELECT COUNT(*) as count FROM BROADCAST WHERE CID=$cid";
		$rs = DatabaseUtil::selectData($db_connection, $sql);
		return $rs['count'] > 0;
	}

	public static function searchConf($name)
	{
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$sql = "SELECT cid, url, owner, name, img, coun, prov, city, dist, addr, faddr, stime, etime FROM CONF WHERE SEARCH='Y' AND STATUS='P' AND NAME LIKE '%$name%'";
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$confs = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$confs[$index] = new Conference();
			$confs[$index]->cid = $row['cid'];
			$confs[$index]->url = $row['url'];
			$confs[$index]->name = $row['name'];
			$confs[$index]->owner = $row['owner'];
			$confs[$index]->img = $row['img'];
			$confs[$index]->coun = $row['coun'];
			$confs[$index]->prov = $row['prov'];
			$confs[$index]->city = $row['city'];
			$confs[$index]->dist = $row['dist'];
			$confs[$index]->addr = $row['addr'];
			$confs[$index]->faddr = $row['faddr'];
			$confs[$index]->stime = $row['stime'];
			$confs[$index]->etime = $row['etime'];

			if( isset($row['img']) )
			{
				if( !file_exists(self::$imgPath.$row['cid']))
				{
					$confImg = fopen(self::$imgPath.$row['cid'], 'w') or $confs[$index]->img = '../image/default/default_evn_pic.png';
					if($confs[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $row['img']);
						fclose($confImg);
						$confs[$index]->img = self::$imgPath.$row['cid'];
					}
				}
				else {
					$confs[$index]->img = self::$imgPath.$row['cid'];
				}
			}
			else {
				$confs[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $confs;
	}
	
	public static function searchConfAll($str_list, $start, $end)
	{
		$str_list = trim($str_list);
		$strA = explode(" ", $str_list);
		$r = sizeof($strA);

		$sql = "SELECT cid, url, owner, name, img, coun, prov, city, dist, addr, faddr, stime, etime FROM CONF WHERE SEARCH='Y' AND STATUS='P' ORDER BY stime DESC LIMIT $start, $end";
		$str = "";
		if ( $r > 0 && !empty($str_list) )
		{
			for ($i = 0; $i < $r; $i++)
			{
				$str = $str." OR NAME LIKE '%$strA[$i]%' OR FADDR LIKE '%$strA[$i]%' OR DESCRIPTION LIKE '%$strA[$i]%'";
			}
			$sql = "SELECT cid, url, owner, name, img, coun, prov, city, dist, addr, faddr, stime, etime FROM CONF WHERE SEARCH='Y' AND STATUS='P' ORDER BY stime DESC LIMIT $start, $end".
				   " AND (".substr($str, 4).")";
			
		}
		
		$db_connection = DatabaseUtil::getConn(DatabaseUtil::$CONF);
		$rs = DatabaseUtil::selectDataList($db_connection, $sql);

		$confs = array();
		$index = 0;
		while($row = mysql_fetch_array($rs, MYSQL_ASSOC))
		{
			$confs[$index] = new Conference();
			$confs[$index]->cid = $row['cid'];
			$confs[$index]->url = $row['url'];
			$confs[$index]->name = $row['name'];
			$confs[$index]->owner = $row['owner'];
			$confs[$index]->img = $row['img'];
			$confs[$index]->coun = $row['coun'];
			$confs[$index]->prov = $row['prov'];
			$confs[$index]->city = $row['city'];
			$confs[$index]->dist = $row['dist'];
			$confs[$index]->addr = $row['addr'];
			$confs[$index]->faddr = $row['faddr'];
			$confs[$index]->stime = $row['stime'];
			$confs[$index]->etime = $row['etime'];

			if( isset($row['img']) )
			{
				if( !file_exists(self::$imgPath.$row['cid']))
				{
					$confImg = fopen(self::$imgPath.$row['cid'], 'w') or $confs[$index]->img = '../image/default/default_evn_pic.png';
					if($confs[$index]->img != '../image/default/default_evn_pic.png')
					{
						fwrite($confImg, $row['img']);
						fclose($confImg);
						$confs[$index]->img = self::$imgPath.$row['cid'];
					}
				}
				else {
					$confs[$index]->img = self::$imgPath.$row['cid'];
				}
			}
			else {
				$confs[$index]->img = '../image/default/default_evn_pic.png';
			}

			$index++;
		}

		return $confs;
	}

	public static function printConfListM($confs)
	{
		$ind = 0;
		$atReturn = '';

		//============================================= Language ==================================================
		
		if($_SESSION['_language'] == 'en') {
			$l_time = "Time: ";
			$l_loca = "Location: ";
		}
		else if($_SESSION['_language'] == 'zh') {
			$l_time = "时间：";
			$l_loca = "地点：";
		}
		
		//=========================================================================================================
				
		foreach ($confs as $conf) 
		{
			$sftime = strtotime($conf->stime);
			$sdate = date('Y.m.d', $sftime);
			$stime = date('H:i', $sftime);
			$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');

			if( isset($conf->etime) && !empty($conf->etime) )
			{
				$eftime = strtotime($conf->etime);
				$edate = date('Y.m.d', $eftime);
				$etime = date('H:i', $eftime);
				$tprint = $tprint.' ~ '.$edate.(($etime!='00:00') ? ' '.$etime : '');
			}

			if($ind==0) $atReturn = '<ul data-role="listview">';

			$atReturn .= '<li>
<a href="../m.conference/index.php?c='.$conf->getCid().'" data-transition="slide">
<label>'.$conf->name.'</label>
<span style="margin-top:10px;font-size:.8em;display:block;">
<span style="font-weight:bold">'.$l_time.'</span>'.$tprint.'<br />
<span style="font-weight:bold">'.$l_loca.'</span>'.$conf->addr.'
</span></a>
</li>';
			$ind++;
		}
		if ($ind!=0) $atReturn .= '</ul>';

		return $atReturn;
	}
//==================================================== static / normal ========================================================

	public function getCid()
	{
		return $this->cid;
	} 

	public function getUrl()
	{
		return $this->url;
	}

	public function getUid()
	{
		return $this->owner;
	}

	public function getFaddr()
	{
		return $this->faddr;
	}

	public function getCtime()
	{
		return $this->ctime;
	}
}