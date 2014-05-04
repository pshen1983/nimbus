<?php
class CommonUtil
{
	private static $LANGUAGES = array('zh', 'en');
	private static $RADIUS = 6371;
	private static $PI = 3.14159265359;

	public static function genRandomString($length)
	{
	    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $string = "";

	   	$size = strlen($characters) - 1;
	    for ($p = 0; $p < $length; $p++)
	    {
	        $string = $string . $characters[mt_rand(0, $size)];
	    }

	    return $string;
	}

	public static function genRandomNumString($length)
	{
	    $characters = "0123456789";
	    $string = "";

	   	$size = strlen($characters) - 1;
	    for ($p = 0; $p < $length; $p++)
	    {
	        $string = $string . $characters[mt_rand(0, $size)];
	    }

	    return $string;
	}

	public static function genRandomCharString($length)
	{
	    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $string = "";

	   	$size = strlen($characters) - 1;
	    for ($p = 0; $p < $length; $p++)
	    {
	        $string = $string . $characters[mt_rand(0, $size)];
	    }

	    return $string;
	}

	public static function validateEmailFormat($emailAddr)
	{
		$atReturn = preg_match ("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]{2,3}))$/", $emailAddr);

		if ($atReturn) return $atReturn==1;
		else return false;
	}

	public static function validateCellFormat($cell)
	{
		$atReturn = preg_match ("/^1[0-9]{10}$/", $cell);

		if ($atReturn) return $atReturn==1;
		else return false;
	}
	
	public static function validateIdFormat($id)
	{
		$atReturn = 0;
		
		if ( preg_match ("/^1[0-9]{10}$/", $id) == 1 )
		{
			$atReturn = 1;
		}
		else if ( preg_match ("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]{2,3}))$/", $id) == 1 )
		{
			$atReturn = 2;
		}
		
		return $atReturn;
	}

	public static function validateDateFormat($date)
	{
		$atReturn = preg_match ("/^(19|20)[0-9]{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/", $date);

		if ($atReturn) return $atReturn==1;
		else return false;
	}

	public static function truncate($input, $size)
	{
		if(mb_strlen($input, 'utf-8')>$size)
		{
			$proj_text = mb_substr($input, 0, $size - 1, 'utf-8');
			$proj_text .= " ...";
		}
		else
		{
			$proj_text = $input;
		}
	
		return $proj_text;
	}

	public static function html_entity_decode_utf8($string)
	{
	    static $trans_tbl;
	
	    // replace numeric entities
	    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $string);
	    $string = preg_replace('~&#([0-9]+);~e', 'self::code2utf(\\1)', $string);
	
	    // replace literal entities
	    if (!isset($trans_tbl))
	    {
	        $trans_tbl = array();
	
	        foreach (get_html_translation_table(HTML_ENTITIES) as $val=>$key)
	            $trans_tbl[$key] = utf8_encode($val);
	    }
	
	    return strtr($string, $trans_tbl);
	}

	public static function code2utf($num)
	{
	    if ($num < 128) return chr($num);
	    if ($num < 2048) return chr(($num >> 6) + 192) . chr(($num & 63) + 128);
	    if ($num < 65536) return chr(($num >> 12) + 224) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
	    if ($num < 2097152) return chr(($num >> 18) + 240) . chr((($num >> 12) & 63) + 128) . chr((($num >> 6) & 63) + 128) . chr(($num & 63) + 128);
	    return '';
	}

	public static function ncr_decode($string, $target_encoding='UTF-8') {
	    return iconv('UTF-8', $target_encoding, self::html_entity_decode_utf8($string));
	}
	
	public static function count_days( $day1, $day2 ) 
	{
	    $time1 = mktime( 12, 0, 0, $day1['mon'], $day1['mday'], $day1['year'] ); 
	    $time2 = mktime( 12, 0, 0, $day2['mon'], $day2['mday'], $day2['year'] ); 
	    return round( abs( $time1 - $time2 ) / 86400 ); 
	}

	public static function isManagement($role)
	{
		return (strcmp($role, "OWNE") == 0 || strcmp($role, "MANA") == 0);
	}

	public static function isSupportLanguage($lang)
	{
		foreach(self::$LANGUAGES as $language)
		{
			if($lang==$language) return true;
		}

		return false;
	}

	public static function setLanguageCookie($lang)
	{
		if( self::isSupportLanguage($lang) ) {
			setcookie ("confone_param3", $lang,  28 * 86400 + time(), "/");
		}
	}

	public static function setSessionLanguage()
	{
		if( !isset($_SESSION) ) session_start();

		if( !isset($_SESSION['_language']) )
		{
			if( isset($_COOKIE['confone_param3']) && self::isSupportLanguage($_COOKIE['confone_param3']) )
			{
				$_SESSION['_language'] = $_COOKIE['confone_param3'];
			}
			else {
				$lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	
				foreach(self::$LANGUAGES as $language)
				{
					if(strrpos($lang, $language)) {
						$_SESSION['_language'] = $language;
						return;
					}
				}
	
				$_SESSION['_language'] = 'zh';
			}
		}
	}

	public static function printArray($array)
	{
		if(!isset($array)) echo 0;
		if(sizeof($array)==0) echo 0;

		foreach($array as $elem)
		{
			echo $elem;
		}
	}

	public static function get_real_ip()
	{
		$ip=false;

		if(!empty($_SERVER["HTTP_CLIENT_IP"]))
		{
			$ip=$_SERVER["HTTP_CLIENT_IP"];
		}

		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ips=explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);

			if ($ip)
			{
				array_unshift($ips,$ip);
				$ip=FALSE;
			}

			for($i=0;$i<count($ips);$i++)
			{
				if (!eregi("^(10©¦172.16©¦192.168).",$ips[$i]))
				{
					$ip=$ips[$i];
					break;
				}
			}
		}

		return $ip?$ip:$_SERVER['REMOTE_ADDR'];
	}

	public static function printOptions($arr, $compare, $sel=true)
	{
		foreach ($arr as $k=>$v)
		{
			echo '<option value="'.$k.'" '.(($sel && isset($compare) && $k==$compare) ? 'SELECTED' : '').'>'.$v.'</option>';
		}
	}

	public static function isAllowUrl($url)
	{
		return isset($url) && 
			   !empty($url) && 
			   $url!='_obj'&& 
			   $url!='about'&& 
			   $url!='common'&& 
			   $url!='conference'&& 
			   $url!='css'&& 
			   $url!='default'&& 
			   $url!='home'&& 
			   $url!='image'&& 
			   $url!='install'&& 
			   $url!='js'&& 
			   $url!='mobile'&& 
			   $url!='search'&& 
			   $url!='utils';
	}

	public static function isProv($prov)
	{
		return $prov!=1 
			&& $prov!=2 
			&& $prov!=9 
			&& $prov!=27 
			&& $prov!=33 
			&& $prov!=34;
	}

	public static function checkConfUrlFormat($url)
	{
		$atReturn = preg_match ("/^[a-zA-Z0-9]{2,12}$/", $url, $matches);

		if ($atReturn) return $atReturn==1;
		else return false;
	}

	public static function getLatLongRange($pLat, $pLng, $length)
	{
		$radius = self::$RADIUS * cos(self::$PI * $pLat / 180);

		$atReturn = array();
		$dLat = $length / self::$RADIUS;
		$dLng = $length / $radius;

		$atReturn[0] = 180 * $dLat / self::$PI;
		$atReturn[1] = 180 * $dLng / self::$PI;

		return $atReturn;
	}

	public static function ipCity($userip)
	{
	    //IP数据库路径，这里用的是QQ IP数据库 20110405 纯真版
	    $dat_path = '../utils/qqwry.dat';
	
	    //判断IP地址是否有效
	    if(!ereg("^([0-9]{1,3}.){3}[0-9]{1,3}$", $userip)){
	        return 'IP Address Invalid';
	    }
	
	    //打开IP数据库
	    if(!$fd = @fopen($dat_path, 'rb')){
	        return 'IP data file not exists or access denied';
	    }
	
	    //explode函数分解IP地址，运算得出整数形结果
	    $userip = explode('.', $userip);
	    $useripNum = $userip[0] * 16777216 + $userip[1] * 65536 + $userip[2] * 256 + $userip[3];
	
	    //获取IP地址索引开始和结束位置
	    $DataBegin = fread($fd, 4);
	    $DataEnd = fread($fd, 4);
	    $useripbegin = implode('', unpack('L', $DataBegin));
	    if($useripbegin < 0) $useripbegin += pow(2, 32);
	    $useripend = implode('', unpack('L', $DataEnd));
	    if($useripend < 0) $useripend += pow(2, 32);
	    $useripAllNum = ($useripend - $useripbegin) / 7 + 1;
	
	    $BeginNum = 0;
	    $EndNum = $useripAllNum;
	
	    //使用二分查找法从索引记录中搜索匹配的IP地址记录
	    do {
	        $Middle= intval(($EndNum + $BeginNum) / 2);
	
	        //偏移指针到索引位置读取4个字节
	        fseek($fd, $useripbegin + 7 * $Middle);
	        $useripData1 = fread($fd, 4);
	        if(strlen($useripData1) < 4) {
	            fclose($fd);
	            return 'File Error';
	        }
	        //提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
	        $userip1num = implode('', unpack('L', $useripData1));
	        if($userip1num < 0) $userip1num += pow(2, 32);
	
	        //提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
	        if($userip1num > $useripNum) {
	            $EndNum = $Middle;
	            continue;
	        }
	
	        //取完上一个索引后取下一个索引
	        $DataSeek = fread($fd, 3);
	        if(strlen($DataSeek) < 3) {
	            fclose($fd);
	            return 'File Error';
	        }
	        $DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
	        fseek($fd, $DataSeek);
	        $useripData2 = fread($fd, 4);
	        if(strlen($useripData2) < 4) {
	            fclose($fd);
	            return 'File Error';
	        }
	        $userip2num = implode('', unpack('L', $useripData2));
	        if($userip2num < 0) $userip2num += pow(2, 32);
	
	        //找不到IP地址对应城市
	        if($userip2num < $useripNum) {
	            if($Middle == $BeginNum) {
	                fclose($fd);
	                return 'No Data';
	            }
	            $BeginNum = $Middle;
	        }
	    } while($userip1num>$useripNum || $userip2num<$useripNum);
	
	    $useripFlag = fread($fd, 1);
	    if($useripFlag == chr(1)) {
	        $useripSeek = fread($fd, 3);
	        if(strlen($useripSeek) < 3) {
	            fclose($fd);
	            return 'System Error';
	        }
	        $useripSeek = implode('', unpack('L', $useripSeek.chr(0)));
	        fseek($fd, $useripSeek);
	        $useripFlag = fread($fd, 1);
	    }
	
	    if($useripFlag == chr(2)) {
	        $AddrSeek = fread($fd, 3);
	        if(strlen($AddrSeek) < 3) {
	            fclose($fd);
	            return 'System Error';
	        }
	        $useripFlag = fread($fd, 1);
	        if($useripFlag == chr(2)) {
	            $AddrSeek2 = fread($fd, 3);
	            if(strlen($AddrSeek2) < 3) {
	                fclose($fd);
	                return 'System Error';
	            }
	            $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
	            fseek($fd, $AddrSeek2);
	        } else {
	            fseek($fd, -1, SEEK_CUR);
	        }
	
	        while(($char = fread($fd, 1)) != chr(0))
	            $useripAddr2 .= $char;
	
	        $AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
	        fseek($fd, $AddrSeek);
	
	        while(($char = fread($fd, 1)) != chr(0))
	            $useripAddr1 .= $char;
	    } else {
	        fseek($fd, -1, SEEK_CUR);
	        while(($char = fread($fd, 1)) != chr(0))
	            $useripAddr1 .= $char;
	
	        $useripFlag = fread($fd, 1);
	        if($useripFlag == chr(2)) {
	            $AddrSeek2 = fread($fd, 3);
	            if(strlen($AddrSeek2) < 3) {
	                fclose($fd);
	                return 'System Error';
	            }
	            $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
	            fseek($fd, $AddrSeek2);
	        } else {
	            fseek($fd, -1, SEEK_CUR);
	        }
	        while(($char = fread($fd, 1)) != chr(0)){
	            $useripAddr2 .= $char;
	        }
	    }
	    fclose($fd);
	
	    //返回IP地址对应的城市结果
	    if(preg_match('/http/i', $useripAddr2)) {
	        $useripAddr2 = '';
	    }
	    $useripaddr = "$useripAddr1 $useripAddr2";
	    $useripaddr = preg_replace('/CZ88.Net/is', '', $useripaddr);
	    $useripaddr = preg_replace('/^s*/is', '', $useripaddr);
	    $useripaddr = preg_replace('/s*$/is', '', $useripaddr);
	    if(preg_match('/http/i', $useripaddr) || $useripaddr == '') {
	        $useripaddr = 'No Data';
	    }
	
	    $addr = iconv("GB2312", "UTF-8", $useripaddr);
	
	    return $addr;
	}
}
?>