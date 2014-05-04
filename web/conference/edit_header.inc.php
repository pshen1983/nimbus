<?php

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="";
	$l_basic = "Basic Info";
	$l_sched = "Schedule";
	$l_invite = "Invitation";
	$l_speak = "Speaker";
	$l_atten = "Attendee";
	$l_sponser = "Sponser";
	$l_news = "News";
	$l_create = "Create Event";
	$l_basic = "Basic Info";
	$l_detail = "Details";
	$l_publish = "Publish";
	$l_update = "Update";
	$l_close = "Close";
	$l_preview = "Preview";
	$l_pub_confirm="Are you sure to publish the event?";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="";
	$l_basic = "基本信息";
	$l_sched = "日程表";
	$l_invite = "发送邀请";
	$l_speak = "演讲嘉宾";
	$l_atten = "参会者";
	$l_sponser = "赞助商";
	$l_news = "附加信息";
	$l_create = "创建会议活动";
	$l_basic = "基本信息";
	$l_detail = "详细信息";
	$l_publish = "发布";
	$l_update = "更新";
	$l_close = "关闭";
	$l_preview = "预览";
	$l_pub_confirm="确认发布活动？";
}

//=========================================================================================================

if( !isset($page) ) $page = 1;

?>
<style type="text/css">
div#prepub {width:99%;margin-top:-41px;margin-bottom:20px;text-align:right;}
div#tab_v {height:25px;margin:auto;width:99%;margin-bottom:5px;border-bottom:4px solid #9CC7E5;}
div#tab_v ul {height:100%;}
div#tab_v ul li{padding:5px 8px 0 8px;margin:0 5px 0 0;float:left;list-style-type:none;background-color:#3C8DC5;}
div#tab_v ul li a{text-decoration:none;color:#000080;font-weight:bold;}
div#tab_v ul li a:hover{color:#fff;}
input{border:1px solid #aaa;height:25px;padding-left:4px;margin:0;}
textarea{border:1px solid #aaa;line-height:1.5em;}
span.arr{font-family:'宋体';color:#BBB;}
input.hjconf {
vertical-align:middle;
position:relative;
top:5px;
cursor:pointer;
font-size:.9em;
font-weight:bold;
color:#fff;
padding:4px 15px 5px 15px;
margin-right:10px;
height:25px;
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
<div id="head_info" style="width:100%;height:80px;"><center>
<div class="round_border_10" style="padding:5px 0 5px 0;color:#000080;font-weight:bold;font-size:1.3em;position:relative;top:15px;width:88%;">
<label><?php echo $l_create?> <span class="arr">></span>
<?php echo $l_basic?> <span class="arr">></span>
<?php if ($conf->status=='D') echo '<span style="color:#9CC7E5;font-size:1.3em;">'?><?php echo $l_detail.(($conf->status=='D') ? '</span>' : '')?> <span class="arr">></span>
<?php echo $l_publish?> <span class="arr">></span>
<?php if ($conf->status=='P') echo '<span style="color:#9CC7E5;font-size:1.3em;">'?><?php echo $l_update.(($conf->status=='P') ? '</span>' : '')?> <span class="arr">></span>
<?php if ($conf->status=='C') echo '<span style="color:#9CC7E5;font-size:1.3em;">'?><?php echo $l_close.(($conf->status=='C') ? '</span>' : '')?></label>
</div>
<div style="padding-top:30px;font-weight:bold;color:#555;"><?php echo $conf->name?></div></center></div>
<div id="tab_v">
<ul>
<li id="elink1" class="round_border_t_10"<?php if($page==1) echo ' style="background-color:#9CC7E5;margin-top:2px;"';?>><a href="../conference/edit_basic.php?url=<?php echo $_GET['url']?>"><?php echo $l_basic?></a></li>
<li id="elink2" class="round_border_t_10"<?php if($page==2) echo ' style="background-color:#9CC7E5;margin-top:2px;"';?>><a href="../conference/edit_schedule.php?url=<?php echo $_GET['url']?>"><?php echo $l_sched?></a></li>
<!-- li id="elink3" class="round_border_t_10"<?php if($page==3) echo ' style="background-color:#9CC7E5;margin-top:2px;"';?>><a href="../conference/edit_invite.php?url=<?php echo $_GET['url']?>"><?php echo $l_invite?></a></li -->
<li id="elink4" class="round_border_t_10"<?php if($page==4) echo ' style="background-color:#9CC7E5;margin-top:2px;"';?>><a href="../conference/edit_speaker.php?url=<?php echo $_GET['url']?>"><?php echo $l_speak?></a></li>
<li id="elink5" class="round_border_t_10"<?php if($page==5) echo ' style="background-color:#9CC7E5;margin-top:2px;"';?>><a href="../conference/edit_attendee.php?url=<?php echo $_GET['url']?>"><?php echo $l_atten?></a></li>
<li id="elink6" class="round_border_t_10"<?php if($page==6) echo ' style="background-color:#9CC7E5;margin-top:2px;"';?>><a href="../conference/edit_sponser.php?url=<?php echo $_GET['url']?>"><?php echo $l_sponser?></a></li>
<li id="elink7" class="round_border_t_10"<?php if($page==7) echo ' style="background-color:#9CC7E5;margin-top:2px;"';?>><a href="../conference/edit_broadcast.php?url=<?php echo $_GET['url']?>"><?php echo $l_news?></a></li>
</ul>
</div>
<div id="prepub">
<form method="post" action="../conference/republish.inc.php" enctype="multipart/form-data" name="rpform" id="rpform" accept-charset="UTF-8" >
<input type="hidden" id="curl" name="curl" value="<?php echo $_SERVER['REQUEST_URI']?>" />
<input type="hidden" id="cid" name="cid" value="<?php echo $conf->getCid()?>" />
<input class="hjconf round_border_6" type="button" id="pre_btn" onclick="window.open('../conference/info_home.php?url=<?php echo $conf->getUrl()?>')" value="<?php echo $l_preview?>" />
<?php if ($_SESSION['_userId'] == $conf->getUid() && $conf->status == 'D') { //isOwner and draft  ?>
<input class="hjconf round_border_6" type="submit" id="hjoin_but" onclick="if (confirm('<?php echo $l_pub_confirm?>')) {window.open('../conference/info_home.php?url=<?php echo $conf->getUrl()?>'); return true;} else {return false;}" value="<?php echo $l_publish?>" />
<?php }?>
</form>
</div>