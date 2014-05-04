<?php
include_once ("CommonUtil.php");

class DatabaseUtil
{
    public static $USER = array (
	    array( 'host' => 'localhost', 'username' => 'auser', 'password' => 'auserpass', 'database' => 'NIMBUS_USER'),
	    array( 'host' => 'localhost', 'username' => 'auser', 'password' => 'auserpass', 'database' => 'NIMBUS_USER')
    );

    public static $IPAD = array (
	    array( 'host' => 'localhost', 'username' => 'aipad', 'password' => 'aipadpass', 'database' => 'NIMBUS_IPAD'),
	    array( 'host' => 'localhost', 'username' => 'aipad', 'password' => 'aipadpass', 'database' => 'NIMBUS_IPAD')
    );

    public static $CONF = array (
	    array( 'host' => 'localhost', 'username' => 'aconf', 'password' => 'aconfpass', 'database' => 'NIMBUS_CONF'),
	    array( 'host' => 'localhost', 'username' => 'aconf', 'password' => 'aconfpass', 'database' => 'NIMBUS_CONF')
    );

    public static $NOTE = array (
	    array( 'host' => 'localhost', 'username' => 'anote', 'password' => 'anotepass', 'database' => 'NIMBUS_NOTE'),
	    array( 'host' => 'localhost', 'username' => 'anote', 'password' => 'anotepass', 'database' => 'NIMBUS_NOTE')
    );

    public static function getConn($servers)
    {
    	$serverIdx = rand(0, count(sizeof($servers))-1);
    	$server = $servers[$serverIdx];
		$db_connection = mysql_pconnect($server['host'], $server['username'], $server['password']);
		mysql_query("SET character_set_results=utf8", $db_connection);
		mysql_query("SET character_set_client=utf8", $db_connection); 
		mysql_query("SET character_set_connection=utf8", $db_connection);

		if($db_connection)
		{
			mysql_select_db($server['database'], $db_connection);
		}

		return $db_connection;
    }

    public static function selectData($db_connection, $query)
    {
		if ( isset($db_connection) )
		{
			$select_result = mysql_query($query, $db_connection);
			$result = mysql_fetch_array($select_result, MYSQL_ASSOC);

			return $result;
		}

		return null;
    }

    public static function deleteData($db_connection, $query)
    {
    	$result = false;

		if ( isset($db_connection) )
			$result = mysql_query($query, $db_connection);

		return $result;
    }

	public static function selectDataList($db_connection, $query)
	{		
		if ( isset($db_connection) )
		{
			$select_result = mysql_query($query, $db_connection);
			return $select_result;
		}

		return null;
	}

    public static function insertData($db_connection, $query)
    {
    	$result = false;

		if ( isset($db_connection) )
			$result = mysql_query($query, $db_connection);

		return $result;
    }

    public static function updateData($db_connection, $query)
    {
    	$result = false;

		if ( isset($db_connection) )
			$result = mysql_query($query, $db_connection);

		return $result;
    }

    public static function checkNull($input)
    {
    	return (isset($input) && !empty($input) ? "'". mysql_real_escape_string($input) . "'" : "NULL");
    }

//=========================================== IP and Address Functions ======================================================

    public static function getProvName($id)
    {
    	$db_connection = DatabaseUtil::getConn(DatabaseUtil::$IPAD);

		$sql = "SELECT proname FROM PROV WHERE PROVID=$id";
		$rs = DatabaseUtil::selectData($db_connection, $sql);

		return $rs["proname"];
    }

    public static function getCityName($id)
    {
    	$db_connection = DatabaseUtil::getConn(DatabaseUtil::$IPAD);

		$sql = "SELECT cityname FROM CITY WHERE CITYID=$id";
		$rs = DatabaseUtil::selectData($db_connection, $sql);

		return $rs["cityname"];
    }

    public static function getDistName($id)
    {
    	$db_connection = DatabaseUtil::getConn(DatabaseUtil::$IPAD);

		$sql = "SELECT disname FROM DIST WHERE ID=$id";
		$rs = DatabaseUtil::selectData($db_connection, $sql);

		return $rs["disname"];
    }

    public static function getProvList()
    {
    	$db_connection = DatabaseUtil::getConn(DatabaseUtil::$IPAD);

		$query = "SELECT provid, proname FROM PROV";
		$result = DatabaseUtil::selectDataList($db_connection, $query);

		return $result;
    }

    public static function getCityList($provid)
    {
    	$db_connection = DatabaseUtil::getConn(DatabaseUtil::$IPAD);

		$query = "SELECT cityid, cityname FROM CITY WHERE PROVID=$provid";
		$result = DatabaseUtil::selectDataList($db_connection, $query);

		return $result;
    }

    public static function getDistList($cityid)
    {
    	$db_connection = DatabaseUtil::getConn(DatabaseUtil::$IPAD);

		$query = "SELECT id, disname FROM DIST WHERE CITYID=$cityid";
		$result = DatabaseUtil::selectDataList($db_connection, $query);

		return $result;
    }
}
?>