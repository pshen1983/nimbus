<?php 

include_once ("../_obj/Message.php");
$_SESSION['_messNum'] = Message::getUnreadMessageNumber($_SESSION['_userId']);

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_logo = "ConfOne";
	$l_h_home = "Home";
	$l_h_create = "Create";
	$l_h_update = "Happening";
	$l_h_search = "Search ConfOne";
	$l_h_network = "Network";
	$l_invit_frd = "Invite Friends";
	$l_h_message = "Message";
	$l_h_profile = "Profile";
	$l_h_exit = "Sign out";
}
else if($_SESSION['_language'] == 'zh') {
	$l_logo = "会云网";
	$l_h_home = "主页";
	$l_h_create = "建立会议活动";
	$l_h_update = "正在上映";
	$l_h_search = "搜索会云网";
	$l_h_network = "社交";
	$l_invit_frd = "邀请朋友内测";
	$l_h_message = "站内信";
	$l_h_profile = "个人信息";
	$l_h_exit = "退出";
}

//=========================================================================================================
?>
<style>
div#header
{
	background-color:#589ED0;
	height:38px;
	width:100%;
	-webkit-box-shadow:0 0 5px #333;
	-moz-box-shadow:0 0 5px #333;
	box-shadow:0 0 5px #333;
	position:fixed;
	top:0px;
    z-index:9999;
}
div#header div#contain
{
	width:980px;
	margin:auto;
	height:100%;
}
div#header div.account
{
	display:block;
	text-decoration:none;
	font-size:.8em;
	height:100%;
	width:140px;
	text-align:center;
	border-left:1px solid #71AFDB;
	border-right:1px solid #71AFDB;
	cursor:pointer;
}
div#header ul#head_link
{
	position:absolute;
	top:-13px;
	left:50%;
	margin-left:-350px;
	text-algin:center;
	font-size:.8em;
	font-weight:bold;
	height:100%;
}
div#header ul#head_link li
{
	display:block;
	float:left;
	height:100%;
	list-style-type:none;
}
div#header ul#head_link li span
{
	position:relative;
	top:15px;
}
div#header input.search_input
{
	border:none!important;
	-webkit-box-shadow:0 0,inset 0 1px 3px #666;
	-moz-box-shadow:0 0,inset 0 1px 3px #666;
	box-shadow:0 0,inset 0 1px 3px #666;
	background-color:white;
	height:30px;
	width:90%;
	padding-left:10px;
	opacity:.7;
	-webkit-transition:opacity 1s;
	-moz-transition:opacity 1s;
	-o-transition:opacity 1s;
	-webkit-padding-start:10px;
	-moz-padding-start:10px;
	outline:none;
}
div#header input.search_input:hover
{
	opacity:.85;
}
div#header input.search_input:focus
{
	opacity:.85;
	-webkit-transition:opacity 0s;
	-moz-transition:opacity 0s;
	-o-transition:opacity 0s;
	-webkit-box-shadow:0 0 20px white,inset 0 1px 1px #999;
	-moz-box-shadow:0 0 20px white,inset 0 1px 1px #999;
	box-shadow:0 0 20px white,inset 0 1px 1px #999;
}
div#header input#se_but
{
	vertical-align: middle;
	background:url(../image/sprite-icons.png);
	background-position:-256px -96px;
	width:14px;
	height:14px;
	border:none!important;
	cursor:pointer;
}
div#header label.hi
{
	position:relative;
	top:-26px;
	left:10px;
	color:#aaa;
	height:30px;
	font-size:.8em;
}
div#header label.hi:hover
{
	cursor:text;
}
div#header ul#head_link a
{
	height:100%;
	display:block;
	text-decoration:none;
	color:#D8E7F2;
	padding-bottom:5px;
	text-shadow: 0 0 1px #000;
}
div#header ul#head_link a:hover
{
	color:#fff;
}
div#header ul#head_link a.sel
{
	background: #589ED0;
	background: -moz-linear-gradient(top, #589ED0 0%, #ffffff 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#589ED0), color-stop(100%,#ffffff));
	background: -webkit-linear-gradient(top, #589ED0 0%,#ffffff 100%);
	background: -o-linear-gradient(top, #589ED0 0%,#ffffff 100%);
	background: -ms-linear-gradient(top, #589ED0 0%,#ffffff 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#589ED0', endColorstr='#ffffff',GradientType=0 );
	background: linear-gradient(top, #589ED0 0%,#ffffff 100%);
}
div#header ul#head_link a.sel:hover
{
	color:#fff;
}
div#acc_list
{
	padding-top:1px;
	width:160px;
	background-color:#71AFDB;
	visibility:hidden;
	-webkit-border-bottom-left-radius: 6px;
	-webkit-border-bottom-right-radius: 6px;
	-moz-border-radius-bottomright: 6px;
	-moz-border-radius-bottomleft: 6px;
	border-bottom-left-radius:6px;
	border-bottom-right-radius:6px;
	-webkit-box-shadow:2px 2px 2px #AAA,inset 0 1px 1px #AAA;
	-moz-box-shadow:2px 2px 2px #AAA,inset 0 1px 1px #AAA;
	box-shadow:2px 2px 2px #AAA,inset 0 1px 1px #AAA;
}
div#acc_list a.plink
{
	height:33px;
	display:block;
	font-size:.8em;
	font-weight:bold;
	color:#EEE;
	text-decoration:none;
	text-shadow: 0 0 1px #000;
}
div#acc_list a.plink span
{
	position:relative;
	top:10px;
	left:10%;
}
div#acc_list a.plink:hover
{
	background-color:#9CC7E5;
}
div#acc_list a#bot
{
	-webkit-border-bottom-left-radius: 6px;
	-webkit-border-bottom-right-radius: 6px;
	-moz-border-radius-bottomright: 6px;
	-moz-border-radius-bottomleft: 6px;
	border-bottom-left-radius:6px;
	border-bottom-right-radius:6px;
}
div.r_h
{
	float:right;
	height:100%;
}
div.right_t
{
	width:400px;	
}
a#message
{
background: #ff3019; /* Old browsers */
background: -moz-linear-gradient(top, #ff3019 43%, #cf0404 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(43%,#ff3019), color-stop(100%,#cf0404)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #ff3019 43%,#cf0404 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #ff3019 43%,#cf0404 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #ff3019 43%,#cf0404 100%); /* IE10+ */
background: linear-gradient(top, #ff3019 43%,#cf0404 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff3019', endColorstr='#cf0404',GradientType=0 ); /* IE6-9 */
position:absolute;
top:-6px;
left:26px;
-webkit-border-top-left-radius: 10px;
-webkit-border-top-right-radius: 10px;
-moz-border-radius-topright: 10px;
-moz-border-radius-topleft: 10px;
border-top-left-radius:10px;
border-top-right-radius:10px;
-webkit-border-bottom-left-radius: 10px;
-webkit-border-bottom-right-radius: 10px;
-moz-border-radius-bottomright: 10px;
-moz-border-radius-bottomleft: 10px;
border-bottom-left-radius:10px;
border-bottom-right-radius:10px;
width:16px;height:16px;
text-align:center;
font-size:.6em;
border:2px solid #fff;
color:#fff;
text-decoration:none;
}
</style>
<script type="text/javascript">
function accountOver()
{
	document.body.setAttribute('onclick', '');
}
function accountOut()
{
	document.body.setAttribute('onclick', "javascript:bocyHideAccount('acc_list')");
}
</script>
<div id="header">
<div id="contain">
<div class="r_h right_t">
<div class="r_h">
<div id="temp" class="account" onmouseout="this.style.backgroundColor='transparent';javascript:accountOut();" onmouseover="this.style.backgroundColor='#71AFDB';javascript:accountOver();" onclick="javascript:showDiv('acc_list', 'temp', event)">
<div style="background:url('../image/downarrow.png');width:12px;height:12px;margin:13px 6px 0 0;float:right;font-size:.9em;"></div>
<div style="position:relative;top:6px;padding-left:10px;text-align:left;">
<img style="border:0 none;height:28px;vertical-align:middle;text-algin:left;" src="<?php echo $_SESSION['_loginUser']->pic;?>" />
<span style="vertical-align:middle;color:#EEE;"><?php echo ( isset($_SESSION['_loginUser']->uname) && !empty($_SESSION['_loginUser']->uname) ? $_SESSION['_loginUser']->uname : $_SESSION['_loginUser']->fullname );?></span>
<?php if ($_SESSION['_messNum']>0) {?>
<span id="messC"><a id="message" href="../message/index.php"><span id="messNum" style="position:relative;top:3px;"><?php echo ($_SESSION['_messNum'] > 99) ? 99 : $_SESSION['_messNum'] ?></span></a></span>
<?php } ?>
</div></div>
<div id="acc_list">
<!-- a style="" class="plink" href="#"><span><?php echo $l_h_network?></span></a -->
<a class="plink" href="../home/invite.php"><span><?php echo $l_invit_frd?></span></a>
<a class="plink" href="../message/index.php"><span><?php echo $l_h_message; if($_SESSION['_messNum']>0) echo " (".$_SESSION['_messNum'].")"?></span></a>
<a class="plink" href="../home/profile.php"><span><?php echo $l_h_profile?></span></a>
<a id="bot" class="plink" href="../default/logout.php"><span><?php echo $l_h_exit?></span></a>
</div>
</div>
<form method="post" enctype="multipart/form-data" action="../search/index.php" style="padding-top:3px;width:220px;" accept-charset="UTF-8">
<input id="sein" name="search" type="text" class="search_input round_border_10" onfocus="inputOnFocus('se');" onblur="inputOffFocus('se', this);"/>
<div>
<label id="se" class="hi" onclick="hideOnFocus(this, 'sein');"><?php echo $l_h_search?></label>
<input id="se_but" type="submit" value="" style="position:relative;left:<?php 
if($_SESSION['_language'] == 'zh') { echo 115; }
else if($_SESSION['_language'] == 'en') { echo 88; }?>px;top:-26px;"/>
</div>
</form>
</div>
<div style="margin-left:3%;">
<div style="float:left;position:relative;top:5px;"><a href="../home/index.php" style="text-decoration:none;font-size:1.6em;color:#F3F3F3;font-weight:bold;font-family:'Microsoft YaHei'"><?php echo $l_logo?></a></div>
<div style="height:100%;text-align:center;">
<ul id="head_link">
<li style="width:<?php 
if($_SESSION['_language'] == 'zh') { echo 60; }
else if($_SESSION['_language'] == 'en') { echo 70; }?>px;">
<a <?php if(isset($_GET['f']) && $_GET['f']=='h') echo 'class="sel" ';?>href="../home/index.php"><span><?php echo $l_h_home?></span></a></li>
<?php if($_SESSION['_loginUser']->reg_type == 'E') {?>
<li style="width:<?php 
if($_SESSION['_language'] == 'zh') { echo 120; }
else if($_SESSION['_language'] == 'en') { echo 70; }?>px;">
<a <?php if(isset($_GET['f']) && $_GET['f']=='c') echo 'class="sel" ';?>href="../conference/index.php"><span><?php echo $l_h_create?></span></a></li>
<?php }?>
<li style="width:90px;"><a <?php if(isset($_GET['f']) && $_GET['f']=='w') echo 'class="sel" ';?>href="../conference/happening.php"><span><?php echo $l_h_update?></span></a></li>
</ul>
</div>
</div>
</div>
</div>
<div style="height:38px;"></div>