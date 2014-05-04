<?php
include_once ("../utils/configuration.inc.php");
include_once ("../utils/TestCodeUtil.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../_obj/User.php");
include_once ("../_obj/Message.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();

$imageId = CommonUtil::genRandomString(8);

function pr($in) { echo (isset($in) && !empty($in)) ? $in : ''; }

if( isset($_POST['where']) && !empty($_POST['where']) )//&& $_POST['where']=='reg' )
{
	if( $_POST['te_in'] == TestCodeUtil::$E_CODE || TestCodeUtil::validate($_POST['te_in']) )
	{
//		if( isset($_POST['cc_in']) && !empty($_POST['cc_in']) && 
//			isset($_POST['check_against']) && !empty($_POST['check_against']) && 
//			$_POST['cc_in'] == $_SESSION['_register'][$_POST['check_against']] )
//		{
			$result = User::registerUser($_POST['le_in'], $_POST['pw_in'], $_POST['fn_in'], $_POST['un_in']);
			if($result==0)
			{
				TestCodeUtil::deleteCode( $_POST['te_in'] );

				$newUser = User::registeredEmail($_POST['le_in']);

				if($_POST['te_in'] == TestCodeUtil::$E_CODE) 
				{
					User::setOrgnaizer($_POST['le_in'], 'E');
				}
				else {
					Message::create( '如何成为活动组织者', 
									 $newUser['uid'], 
									 1, 
									 '点击右上角下拉列表，进入<a href="../home/profile.php">个人信息页</a>，点击成为会议活动组织者链接。' );
				}

				Message::create( '试用一下手机网页', 
								 $newUser['uid'], 
								 1, 
								 '用 iPhone/iPad/Andriod 浏览器看看会云网（m.confone.com），有意想不到的活动感受。' );

				if( User::loginUser($_POST['le_in'], $_POST['pw_in'])==0 )
				{
					if( isset($_POST['remember']) && $_POST['remember'] )
						SecurityUtil::setCookie($_POST['le_in'], $_POST['pw_in']);
	
					header( 'Location: '.$HTTP_BASE.'/home/index.php' );
				}
				else {
					header( 'Location: '.$HTTP_BASE.'/default/index.php' );
				}
	
				exit;
			}
//		}
//		else {
//			$result = -1;
//		}
	}
	else {
		$result = -2;
	}
}

//============================================= Language ==================================================
if($_SESSION['_language'] == 'en') {
	$l_title = "Registration | ConfOne";
	$l_reg_message0 = "Join ";
	$l_reg_message1 = "ConfOne";
	$l_reg_message2 = "Follow Conferences, Conventions and other events of your interests.";
	$l_reg_message3 = "<ul>
					   <li>Get updated information anytime</li>
					   <li>Manage your own schedule</li>
					   <li>Follow what's going on now</li>
					   <li>Sharing</li>
					   <li>and More</li>
					   </ul>";
	$l_bhome = "Bach to Home Page";
	$l_email = "Account/Email";
	$l_fname = "Full Name";
	$l_passwd = "Password";
	$l_uname = "User Name";
	$l_ccode = "Check";
	$l_remember_pw = "Keep me logged-in on this computer.";
	$l_reg_diff_check = 'Try a different check code';
	$l_bu_reg = "Register Now";
	$l_error1n = "Check code error, please enter again";
	$l_error1 = "System busy, please try again later";
	$l_error2 = "Please fill in all information";
	$l_error3 = "Invalid email format";
	$l_error4 = "Account already exisits, Please <a href='../default/login.php'>Login</a>";
	$l_error5 = "Nick name already exisits";
	$l_e_fn_r = "Enter full name";
	$l_e_fn_max = "Use less than 40 characters";
	$l_e_le_r = "Enter Email";
	$l_e_pw_r = "Enter password";
	$l_e_pw_min = "Use 6 to 20 characters";
	$l_e_pw_max = "Use 6 to 20 characters";
	$l_t_code_info = "Internal Test Code, welcome to join the test. <a href='../about/test.php'>Apply for a code</a>";
	$l_error2n = "Invalide Test Code. <a href='../about/test.php'>Get one now!</a>";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "注册 | 会云网";
	$l_reg_message0 = "加入";
	$l_reg_message1 = "会云网";
	$l_reg_message2 = "关注您喜欢的会议，会务，交流等活动";
	$l_reg_message3 = "<ul>
					   <li>及时获得活动更新信息</li>
					   <li>安排自己的活动日程</li>
					   <li>关注活动实时事件</li>
					   <li>分享感悟与心得</li>
					   <li>还有更多</li>
					   </ul>";
	$l_bhome = "返回会云网主页";
	$l_email = "帐号 / 邮箱";
	$l_fname = "真实姓名";
	$l_passwd = "密码 ";
	$l_uname = "用户昵称";
	$l_ccode = "验证码";
	$l_remember_pw = "本机不再输入密码，自动登录。";
	$l_reg_diff_check = '看不清，换一个验证码';
	$l_bu_reg = "立刻注册";
	$l_error1n = "验证码有误，请从新输入";
	$l_error1 = "系统忙碌，请稍候再试";
	$l_error2 = "请填写所有必填信息";
	$l_error3 = "邮箱格式有误";
	$l_error4 = "该用户已存在，请<a href='../default/login.php'>登录</a>";
	$l_error5 = "昵称已存在";
	$l_e_fn_r = "请输入您的真实姓名";
	$l_e_fn_max = "真实姓名不大于40个汉字";
	$l_e_le_r = "请输入邮件地址";
	$l_e_pw_r = "请输入密码";
	$l_e_pw_min = "密码长度不小于6位";
	$l_e_pw_max = "密码长度不大于20位";
	$l_t_code_info = "内测码，会云欢迎您参加内测。<a href='../about/test.php'>获取内测码</a>";
	$l_error2n = "内测码有误，尚无内测码？<a href='../about/test.php'>马上获取</a>";
}
//=========================================================================================================
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/default.css" />
<script type="text/JavaScript" src="../js/default.js"></script>
<script type="text/JavaScript" src="../js/jquery-1.6.2.min.js"></script>
<script type="text/JavaScript" src="../js/jquery.validate.min.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
label.error{background: url('../image/error.png') no-repeat center left;color: red;font-size: .9em;padding-left: 1em;margin-left: 0.5em;z-index: 1;}
label.valid{background: url('../image/check.gif') no-repeat center left;padding-right: 10px;}
label.hint{margin-left:5px;font-weight:normal;color:#71AFDB;font-size:.9em;display:none;}
<?php if($_SESSION['_language']=='en') echo "form#reg_form td.first label{left:25px;} form#reg_form td.first{width:125px;}";?></style>
<script type="text/javascript">
jQuery.validator.setDefaults({
	debug: false,
	success: "valid"
});
</script>

<script>
	$(document).ready(function(){
		$("#reg_form").validate({
			rules: {
        		   	fn_in: {
        					required: true,
        					maxlength: 40
      					   },
      				le_in: {
          					required: true,
        				   },
   					pw_in: {
       						required: true,
        					minlength: 6,
        					maxlength: 20
    					   } 
  				   },
    		messages: {
  				      	fn_in: {
  				      			required: "<?php echo $l_e_fn_r ?>",
     							maxlength: "<?php echo $l_e_fn_max?> "
  				 			   },
  			     		le_in: {
  				      			required: "<?php echo $l_e_le_r?>",
  				  			   },  
						pw_in: {
      							required: "<?php echo $l_e_pw_r?>",
     							minlength: "<?php echo $l_e_pw_min?>",
     							maxlength: "<?php echo $l_e_pw_max?>"
  							   }
  				   	  }
	});
});
</script>
</head>
<body>
<div style="width:100%;">
<?php include_once '../common/header1.inc.php';?>
<div class="stand_width">
<div id="reg_div">
<form id="reg_form" method="post" enctype="multipart/form-data" action="<?php echo $HTTP_BASE?>/default/register.php" name="reg_form" accept-charset="UTF-8">
<table border="0">
<tr>
<td rowspan="8" class="info">
<div style="font-size:1.2em;font-weight:bold;"><?php echo $l_reg_message0?>
<a class="home" style="" href="../default/index.php" title="<?php echo $l_bhome?>"><?php echo $l_reg_message1?></a></div><br />
<div style="font-size:0.8em;font-weight:bold;color:#666;"><?php echo $l_reg_message2?></div><br />
<div style="font-size:0.8em;color:#666;"><?php echo $l_reg_message3?></div><br />
</td>
<td class="first"><label id="fn" ><?php if ( !isset($_POST['fn_in']) || empty($_POST['fn_in'])) echo $l_fname;?></label></td>
<td><input type="text" name="fn_in" class="reg_in round_border_4" value="<?php if(isset($_POST['fn_in'])) echo $_POST['fn_in'];?>" onfocus="hideLabel('fn');showElem('l_fname');" onblur="displayLabel('fn', this);hideElem('l_fname');" /><label id="l_fname" class="hint">&#9668; <?php echo $l_fname?></label></td>
<td></td>
</tr>
<tr>
<td class="first"><label id="le" ><?php if (!isset($_POST['le_in']) || empty($_POST['le_in'])) echo $l_email;?></label></td>
<td><input type="text" name="le_in" class="reg_in round_border_4" value="<?php if(isset($_POST['le_in'])) echo $_POST['le_in'];?>" onfocus="hideLabel('le');showElem('l_email');" onblur="displayLabel('le', this);hideElem('l_email');" /><label id="l_email" class="hint">&#9668; <?php echo $l_email?></label></td>
<td></td>
</tr>
<tr>
<td class="first"><label id="pw" ><?php if (!isset($_POST['pw_in']) || empty($_POST['pw_in'])) echo $l_passwd;?></label></td>
<td><input type="password" name="pw_in" class="reg_in round_border_4" value="<?php if( isset($_POST['pw_in']) && !empty($_POST['pw_in']) ) pr($_POST['pw_in'])?>" onfocus="hideLabel('pw');showElem('l_passwd');" onblur="displayLabel('pw', this);hideElem('l_passwd');"/><label id="l_passwd" class="hint">&#9668; <?php echo $l_passwd?></label></td>
<td></td>
</tr>
<tr>
<td class="first"><label id="un" ><?php if (!isset($_POST['un_in']) || empty($_POST['un_in'])) echo $l_uname;?></label></td>
<td><input type="text" name="un_in" class="reg_in round_border_4" value="<?php if(isset($_POST['un_in'])) echo $_POST['un_in'];?>" onfocus="hideLabel('un');showElem('l_uname');" onblur="displayLabel('un', this);hideElem('l_uname');"/><label id="l_uname" class="hint">&#9668; <?php echo $l_uname?></label></td>
<td></td>
</tr>
<tr>
<td class="first"><label id="te" ><?php if (!isset($_POST['$te_in']) || empty($_POST['te_in'])) echo $l_t_code;?></label></td>
<td><input type="text" style="width:115px;" name="te_in" class="reg_in round_border_4" value="<?php if(isset($_POST['te_in'])) echo $_POST['te_in']; if(isset($_GET['c'])) echo $_GET['c'];?>"/><label class="hint" style="display:inline;">&#9668; <?php echo $l_t_code_info?></label></td>
</tr>
<!-- tr>
<td class="first"><label id="cc" ><?php echo $l_ccode;?></label></td>
<td><input type="text" id="cc_in" name="cc_in" class="reg_in round_border_4" style="width:70px;" onfocus="hideLabel('cc');" onblur="displayLabel('cc', this);"/>
<input type="hidden" value="<?php echo $imageId?>" name="check_against" />
<img style="border:1px solid #DDD;vertical-align:middle;height:36px;margin-left:5px;" id="verifyPic" src="../utils/getCode.php?r=<?php echo $imageId?>" />
<a id="reg_form_diffrent_code" href="javascript:changeCodeImage('verifyPic','<?php echo $imageId?>')"><?php echo $l_reg_diff_check?></a>
<input type="hidden" value="buttonclick" name="reg_hide" /></td>
<td></td>
</tr -->
<tr>
<td></td>
<td><div style="text-align:left;margin-left:-110px;"><input type="checkbox" name="remember" id="remember"><label for="remember" style="font-size:.8em;"><?php echo $l_remember_pw?></label></div></td>
<td></td>
</tr>
<tr>
<td></td>
<td>
<?php 
if (isset($result) && $result != 0) 
{
	echo '<div class="round_border_4" style="padding:15px 0 15px 0;text-align:center;margin-top:10px;margin-left:-110px;width:320px;border:2px #CC0000 solid;background-color:#F08080;color:white;"><label>';
	if ($result == -1) echo $l_error1n;
	elseif ($result == -2) echo $l_error2n;
	elseif ($result == 1) echo $l_error1;
	elseif ($result == 2) echo $l_error2;
	elseif ($result == 3) echo $l_error3;
	elseif ($result == 4) echo $l_error4;
	elseif ($result == 5) echo $l_error5;
	else echo "";
	echo '</label></div>';
}
?>
</td>
<td></td>
</tr>
<tr>
<td></td>
<td>
<input type="hidden" name="where" value="reg" />
<button type="submit" class="reg round_border_4" style="margin-top:30px;margin-left:-110px;width:320px;"><span class="reg"><?php echo $l_bu_reg?></span></button>
</td>
<td></td>
</tr>
</table>
</form>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
<?php include_once '../common/footer.inc.php';?>
</div>
</body>
</html>