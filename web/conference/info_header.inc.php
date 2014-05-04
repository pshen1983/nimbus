<?php
if($_SESSION['_language'] == 'en') {
	$l_info_home = "Home";
	$l_info_sche = "Schedule";
	$l_info_spea = "Speaker";
	$l_info_spon = "Sponser";
	$l_info_atte = "Attendee";
	$l_info_news = "Broadcasts";
	$l_login_join = "Login and Join";
	$l_header_join = "Join Event";
	$l_header_pub = "Publish";
	$l_header_repub = "Re-Publish";
	$l_repub_confirm="Are you sure to re-publish the event?";
	$l_pub_confirm="Are you sure to publish the event?";
}
else if($_SESSION['_language'] == 'zh') {
	$l_info_home = "首页";
	$l_info_sche = "日程安排";
	$l_info_spea = "演讲嘉宾";
	$l_info_spon = "赞助商";
	$l_info_atte = "参会者";
	$l_info_news = "附加信息";
	$l_login_join = "登录参加";
	$l_header_join = "参加活动";
	$l_header_pub = "发布";
	$l_header_repub = "重新发布";
	$l_repub_confirm="确认重新发布活动？";
	$l_pub_confirm="确认发布活动？";
}
?>
<div id="cheader" style="background: url('../image/conf_logo.png') no-repeat top">
<div style="padding:10px 0 10px 0;font-size:3em;color:#999;"><center><?php echo $conf->name;?></center></div>
<div style="border-top:1px solid #AAA;margin:auto;padding:0 0 15px 0;width:80%"></div>
<?php if( !isset($_SESSION['_userId']) ) {?>
<style type="text/css">
#hjform
{
	margin-top:-10px;
}
a.hjconf {
text-decoration:none;
cursor:pointer;
font-size:1em;
font-weight:bold;
color:#fff;
padding:5px 30px 5px 30px;
border:0 none;
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 50%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(50%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* W3C */
}
a.hjconf:hover {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 100%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(100%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* W3C */
}
a.hjconf:active {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 0%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(0%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* W3C */
}
</style>
<div style="text-align:center;padding:6px 0 10px 0;">
<a class="hjconf round_border_6" href="../default/login.php?page=<?php echo str_replace("&", "%%", $_SERVER['REQUEST_URI'])?>"><?php echo $l_login_join?></a>
</div>
<div style="text-align:center;padding:0 0 0 0;font-size:1em;color:#999;">
<span style="color:#3C8DC5;"><?php echo '( '.$sdate.' ~ '.$edate.' )';?></span>
<span style="color:orange;"><?php echo $conf->getFaddr();?></span></div>
<?php } else if( $_SESSION['_userId'] != $conf->getUid() && !Conference::isJoined($conf->getCid(), $_SESSION['_userId']) && $conf->status == 'P') {?>
<style type="text/css">
#hjform
{
	margin-top:-10px;
}
input.hjconf {
cursor:pointer;
font-size:1em;
font-weight:bold;
color:#fff;
padding:5px 30px 5px 30px;
border:0 none;
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 50%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(50%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* W3C */
}
input.hjconf:hover {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 100%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(100%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* W3C */
}
input.hjconf:active {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 0%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(0%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* W3C */
}
</style>
<div style="text-align:center;padding:6px 0 5px 0;">
<form method="post" action="../conference/joinConf.inc.php" enctype="multipart/form-data" name="hjform" id="hjform" accept-charset="UTF-8" >
<input type="hidden" id="fjoin" name="fjoin" value="<?php echo $_GET['url']?>" />
<input type="hidden" id="curl" name="curl" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input class="hjconf round_border_6" type="submit" id="hjoin_but" value="<?php echo $l_header_join?>" />
</form>
</div>
<div style="text-align:center;padding:0 0 0 0;font-size:1em;color:#999;">
<span style="color:#3C8DC5;"><?php echo '( '.$sdate.' ~ '.$edate.' )';?></span>
<span style="color:orange;"><?php echo $conf->getFaddr();?></span></div>
<?php } else if ($_SESSION['_userId'] == $conf->getUid() && $conf->status == 'C') { //isOwner and confClosed  ?>
<style type="text/css">
#rpform
{
	margin-top:-10px;
}
input.hjconf {
cursor:pointer;
font-size:1em;
font-weight:bold;
color:#fff;
padding:5px 30px 5px 30px;
border:0 none;
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 50%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(50%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* W3C */
}
input.hjconf:hover {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 100%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(100%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* W3C */
}
input.hjconf:active {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 0%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(0%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* W3C */
}
</style>
<div style="text-align:center;padding:6px 0 5px 0;">
<form method="post" action="../conference/republish.inc.php" enctype="multipart/form-data" name="rpform" id="rpform" accept-charset="UTF-8" >
<input type="hidden" id="curl" name="curl" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input type="hidden" id="cid" name="cid" value="<?php echo $conf->getCid()?>" />
<input class="hjconf round_border_6" type="submit" id="hjoin_but" value="<?php echo $l_header_repub?>" onclick="return confirm('<?php echo $l_repub_confirm?>');" />
</form>
</div>
<div style="text-align:center;padding:0 0 0 0;font-size:1em;color:#999;">
<span style="color:#3C8DC5;"><?php echo '( '.$sdate.' ~ '.$edate.' )';?></span>
<span style="color:orange;"><?php echo $conf->getFaddr();?></span></div>
<?php } else if ($_SESSION['_userId'] == $conf->getUid() && $conf->status == 'D') { //isOwner and confClosed  ?>
<style type="text/css">
#rpform
{
	margin-top:-10px;
}
input.hjconf {
cursor:pointer;
font-size:1em;
font-weight:bold;
color:#fff;
padding:5px 30px 5px 30px;
border:0 none;
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 50%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(50%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 50%,#7cbc0a 100%); /* W3C */
}
input.hjconf:hover {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 100%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(100%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 100%,#7cbc0a 100%); /* W3C */
}
input.hjconf:active {
background: #9dd53a; /* Old browsers */
background: -moz-linear-gradient(top, #9dd53a 0%, #80c217 0%, #7cbc0a 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#9dd53a), color-stop(0%,#80c217), color-stop(100%,#7cbc0a)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* Opera11.10+ */
background: -ms-linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* IE10+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9dd53a', endColorstr='#7cbc0a',GradientType=0 ); /* IE6-9 */
background: linear-gradient(top, #9dd53a 0%,#80c217 0%,#7cbc0a 100%); /* W3C */
}
</style>
<div style="text-align:center;padding:6px 0 5px 0;">
<form method="post" action="../conference/republish.inc.php" enctype="multipart/form-data" name="rpform" id="rpform" accept-charset="UTF-8" >
<input type="hidden" id="curl" name="curl" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input type="hidden" id="cid" name="cid" value="<?php echo $conf->getCid()?>" />
<input class="hjconf round_border_6" type="submit" id="hjoin_but" value="<?php echo $l_header_pub?>" onclick="return confirm('<?php echo $l_pub_confirm?>');" />
</form>
</div>
<div style="text-align:center;padding:0 0 0 0;font-size:1em;color:#999;">
<span style="color:#3C8DC5;"><?php echo '( '.$sdate.' ~ '.$edate.' )';?></span>
<span style="color:orange;"><?php echo $conf->getFaddr();?></span></div>
<?php } else { //Registerted or closed/draft  ?>
<div style="text-align:right;padding:0 40px 0 0;font-size:1em;color:#999;">
<span style="color:#3C8DC5;"><?php echo '( '.$sdate.' ~ '.$edate.' )';?></span>
<span style="color:orange;"><?php echo $conf->getFaddr();?></span></div>
<?php }?>
</div>
<div id="ctoplk">
<ul id="clkul">
<li class="sep"></li>
<li><a id="ctl1" <?php if($page==1) echo 'style="cursor:pointer;background-color:#9CC7E5; "'?>href="info_home.php?url=<?php echo $_GET['url']?>"><span><?php echo $l_info_home?></span></a></li>
<li class="sep"></li>
<?php if (Conference::hasSchedule($conf->getCid())) {?>
<li><a id="ctl2" <?php if($page==2) echo 'style="cursor:pointer;background-color:#9CC7E5; "'?>href="info_schedule.php?url=<?php echo $_GET['url']?>" ><span><?php echo $l_info_sche?></span></a></li>
<li class="sep"></li>
<?php }?>
<?php if (Conference::hasSpeaker($conf->getCid())) {?>
<li><a id="ctl3" <?php if($page==3) echo 'style="cursor:pointer;background-color:#9CC7E5; "'?>href="info_speaker.php?url=<?php echo $_GET['url']?>" ><span><?php echo $l_info_spea?></span></a></li>
<li class="sep"></li>
<?php }?>
<?php if (Conference::hasAttendee($conf->getCid())) {?>
<li><a id="ctl4" <?php if($page==4) echo 'style="cursor:pointer;background-color:#9CC7E5; "'?>href="info_attendee.php?url=<?php echo $_GET['url']?>" ><span><?php echo $l_info_atte?></span></a></li>
<li class="sep"></li>
<?php }?>
<?php if (Conference::hasSponser($conf->getCid())) {?>
<li><a id="ctl5" <?php if($page==5) echo 'style="cursor:pointer;background-color:#9CC7E5; "'?>href="info_sponser.php?url=<?php echo $_GET['url']?>" ><span><?php echo $l_info_spon?></span></a></li>
<li class="sep"></li>
<?php }?>
<?php if (Conference::hasBcasts($conf->getCid())) {?>
<li><a id="ctl5" <?php if($page==6) echo 'style="cursor:pointer;background-color:#9CC7E5; "'?>href="info_broadcast.php?url=<?php echo $_GET['url']?>" ><span><?php echo $l_info_news?></span></a></li>
<li class="sep"></li>
<?php }?>
</ul>
</div>