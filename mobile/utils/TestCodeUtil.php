<?php
include_once ("../utils/DatabaseUtil.php");
include_once ("../utils/CommonUtil.php");

class TestCodeUtil
{
	public static $E_CODE = "CONFONEORG";

    public static $SOON = array (
	    array( 'host' => 'localhost', 'username' => 'asoon', 'password' => 'asoonpass', 'database' => 'NIMBUS_SOON'),
	    array( 'host' => 'localhost', 'username' => 'asoon', 'password' => 'asoonpass', 'database' => 'NIMBUS_SOON')
    );

	public static function validate($code)
	{
	    $db_connection = DatabaseUtil::getConn(self::$SOON);
		$query = "SELECT id FROM TESTCODE WHERE CODE='$code'";
		$result = DatabaseUtil::selectData($db_connection, $query);

		return isset($result['id']) && !empty($result['id']);
	}

	public static function deleteCode($code)
	{
	    $db_connection = DatabaseUtil::getConn(self::$SOON);
		$query = "DELETE FROM FROM TESTCODE WHERE CODE='$code' LIMIT 1";
		$result = DatabaseUtil::deleteData($db_connection, $query);
	}

	public static function createTestCode()
	{
		$code = CommonUtil::genRandomString(8);

	    $db_connection = DatabaseUtil::getConn(self::$SOON);
		$query = "INSERT INTO TESTCODE (CODE) VALUES ('$code');";

		if( DatabaseUtil::insertData($db_connection, $query) )
		{
			return $code;
		}
		else return null;
	}
}
?>