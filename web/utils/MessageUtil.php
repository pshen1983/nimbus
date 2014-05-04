<?php
include_once ("../utils/DatabaseUtil.php");

class MessageUtil
{
	public static $EMAIL = "pshen1983@gmail.com";
	public static $CELL = "";

	public static function resetPasswdEmail($to, $passwd, $lang)
	{
		date_default_timezone_set('Asia/Shanghai');

		if($lang == 'zh')
		{
			$subject = '会云网找回密码';
			$message = "会云密码助手已经将您的密码重新设置，您的临时密码为：".$passwd."\n\n
						请用这个临时密码登录<http://www.confone.com>再修改您的密码。";
		}
		else if($lang == 'en')
		{
			$subject = 'Reset my ConfOne password';
			$message = "Dear client, your temporary password has been reset to: ".$passwd."\n\n
						Please login <http://www.confone.com> with this temporary password and change your password again.";
		}

		$headers = "From: ".self::$EMAIL."\r\nReply-To: ".self::$EMAIL;

		$mail_sent = mail( $to, $subject, $message, $headers );
	}

	public static function sendEmail($to, $subject, $message)
	{
		date_default_timezone_set('Asia/Shanghai');
		$headers = "From: ".self::$EMAIL."\r\nReply-To: ".$to;

		$mail_sent = mail( $to, $subject, $message, $headers );
	}

	public static function sendTestCode($to, $code)
	{
		$subject = "欢饮您参加会云内测 (www.confone.com)";
		$body = "感谢您关注会云内网测，您的内测码是：$code
				 马上注册：http://www.confone.com/default/register.php?c=$code
				 
				 	会云网页：www.confone.com
				 	手机网页：m.confone.com
				 	原生 App：iPhone/iPad/Andriod 即将推出


				 会云团队";

		self::sendEmail($to, $subject, $body);
	}

	public static function sendTestInvi($to, $code)
	{
		$subject = $_SESSION['_loginUser']->fullname."邀请您参加会云网内测";
		$body = "您的朋友".$_SESSION['_loginUser']->fullname."邀请您参加会云网内测。您的内测码是：".$code."
				 马上注册：http://www.confone.com/default/register.php?c=$code

				 会云倡导数字化会议活动革新体验
				 
				 创建，参与您喜欢的会议，会务，交流等活动。结识新朋友，了解新动态，会云伴您与数字化一起飞。
				 

				 	会云网页：www.confone.com
				 	手机网页：m.confone.com
				 	原生 App：iPhone/iPad/Andriod 即将推出

				 	
				 会云团队";

		self::sendEmail($to, $subject, $body);
	}
}
?>