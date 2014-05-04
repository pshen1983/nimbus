<?php
include_once ("../_obj/User.php");
include_once ("DatabaseUtil.php");
include_once ("CommonUtil.php");
include_once ("SessionUtil.php");

class SecurityUtil
{
    static $cypher = 'blowfish';
    static $mode   = 'ecb';
    static $key    = '1a2s3d4f5g6h';

    public static function encrypt($plaintext)
    {
        $td = mcrypt_module_open(self::$cypher, '', self::$mode, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, self::$key, $iv);
        $crypttext = mcrypt_generic($td, $plaintext);
        mcrypt_generic_deinit($td);
        return $iv.$crypttext;
    }

    public static function decrypt($crypttext)
    {
        $plaintext = "";
        $td        = mcrypt_module_open(self::$cypher, '', self::$mode, '');
        $ivsize    = mcrypt_enc_get_iv_size($td);
        $iv        = substr($crypttext, 0, $ivsize);
        $crypttext = substr($crypttext, $ivsize);
        if ($iv)
        {
            mcrypt_generic_init($td, self::$key, $iv);
            $plaintext = mdecrypt_generic($td, $crypttext);
        }
        return trim($plaintext);
    }

    public static function checkLogin($page)
    {
    	if(!isset($_SESSION['_userId']) && !self::cookieLogin())
    	{
    		header( 'Location: ../default/login.php?page=' . str_replace("&", "%%", $page) ) ;
    		exit;
    	}
    	else
    	{
    		if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']."_nimbus2011"))
    		{
    			unset($_SESSION);
				session_destroy();
    			header( 'Location: ../default/login.php?page=' . str_replace("&", "%%", $page) ) ;
    			exit;
    		}
    	}

		if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
    }

    public static function mCheckLogin()
    {
    	if(!isset($_SESSION['_userId']) && !self::cookieLogin())
    	{
    		header( 'Location: ../m.default/index.php' ) ;
    		exit;
    	}
    	else
    	{
    		if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']."_nimbus2011"))
    		{
    			unset($_SESSION);
				session_destroy();
    			header( 'Location: ../m.default/index.php' ) ;
    			exit;
    		}
    	}

		if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
    }

	public static function setCookie($email, $passwd)
	{
		setcookie ("confone_param1", self::encrypt($email), (365*86400) + time(), "/");
		setcookie ("confone_param2", self::encrypt($passwd), (365*86400) + time(), "/");
	}

	public static function unsetCookie()
	{
		setcookie ("confone_param1", null, 0, "/");
		setcookie ("confone_param2", null, 0, "/");
	}

	public static function cookieLogin()
	{
		if(isset($_COOKIE['confone_param1']) && isset($_COOKIE['confone_param2']))
		{
			$email = self::decrypt($_COOKIE['confone_param1']);
			$passwd = self::decrypt($_COOKIE['confone_param2']);

			return (User::loginUser($email, $passwd) == 0);
		}

		return false;
	}
}
?>