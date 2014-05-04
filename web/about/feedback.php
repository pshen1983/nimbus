<?php
include_once ("../_obj/Message.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
if(!isset($_SESSION['_userId'])) SecurityUtil::cookieLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "Feedback | ConfOne";
	$l_search = "You Search For";
	$l_feedback = "Feedback:";
	$l_contact = "Contact:";
	$l_submit = "Send";
	$l_mess_contact = "Thanks for your feedback, please leave a contact for us to get back to you.";
	$l_subject = "Feedback";
	$l_succ = "Thanks for your feedback. It is important to us. We will get back to you soon.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="意见反馈 | 会云网";
	$l_search="您搜索的词条";
	$l_feedback = "反馈意见：";
	$l_contact = "联系方式：";
	$l_submit = "发送";
	$l_mess_contact = "谢谢您宝贵的意见和建议，如果您希望的得到我们的更新，请留给我们一个联系方式。";
	$l_subject = "意见反馈";
	$l_succ = "谢谢您宝贵的建议和意见，我们会尽快和您联系。";
}

//=========================================================================================================

if( isset($_POST['h_feed']) && !empty($_POST['h_feed']) )
{
	if(empty($_POST['contact']) && isset($_SESSION['_loginUser'])) $_POST['contact'] = $_SESSION['_loginUser']->email;
	$result = Message::create($l_subject, 1, 1, $_POST['feed']."<br /><br />".$_POST['contact']);
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/search.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script charset="utf-8" src="../kindeditor/kindeditor-min.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
label{font-size:.9em;font-weight:bold;}
input#contact{border:1px solid #AAA;height:26px;width:260px;padding-left:4px;}
label.err{font-weight:normal;color:#589ED0;display:none;}
input.login{padding: 8px 20px 8px 20px;background: #9CC7E5;border:none!important;-webkit-box-shadow:1px 1px 0 #999;-moz-box-shadow:1px 1px 0 #999;box-shadow:1px 1px 0 #999;}
input.login:hover{cursor:pointer;background-color:#589ED0;}
input.login:active{background-color:#589ED0;-webkit-box-shadow:0 0,inset 1px 1px 0 #777;-moz-box-shadow:0 0,inset 1px 1px 0 #777;box-shadow:0 0,inset 1px 1px 0 #777;}
</style>
</head>
<body <?php if (isset($result) && $result!=-1) echo "onload='alert(\"".$l_succ."\")'";?>>
<div style="width:100%;">
<?php include_once isset($_SESSION['_userId']) ? '../common/header.inc.php' : '../common/header1.inc.php';?>
<div class="stand_width">
<div id="hmain">
<div style="padding:50px 20px 50px 20px">
<form id="feedback_form" method="post" enctype="multipart/form-data" action="feedback.php" name="feedback_form" accept-charset="UTF-8">
<table>
<tr>
<td><label><?php echo $l_feedback?></label></td>
<td>
<script>KE.show({ id : 'feed', allowFileManager : false, allowUpload : false });</script>
<textarea style="width:800px;height:300px;" id="feed" name="feed" ></textarea></td>
</tr>
<tr style="height:36px;">
<td><label><?php echo $l_contact?></label></td>
<td><input style="width:240px;" type="text" id="contact" name="contact" onfocus="showElem('terr');" onblur="hideElem('terr');" />
<label id="terr" class="err">&#9668; <?php echo $l_mess_contact?></label></td>
</tr>
</table>
<input type="hidden" name="h_feed" id="h_feed" value="1" />
<center><input class="login" type="submit" value="<?php echo $l_submit?>" ></center>
</form>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>