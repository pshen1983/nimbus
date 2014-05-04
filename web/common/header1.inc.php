<?php 
//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_logo = "ConfOne";
	$l_h_search = "Search ConfOne";
}
else if($_SESSION['_language'] == 'zh') {
	$l_logo = "会云网";
	$l_h_search = "搜索会云网";
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
}
input.s_input
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
label.hi_l
{
	color:#aaa;
	height:30px;
	font-size:.8em;
	cursor:text;
}
input#s_but
{
	position:relative;
	top:-26px;
	vertical-align: middle;
	background:url(../image/sprite-icons.png);
	background-position:-256px -96px;
	width:14px;
	height:14px;
	border:none!important;
	cursor:pointer;
}
</style>
<div id="header">
<div style="width:980px;margin:auto;height:100%">
<div style="margin-left:3%;float:left;position:relative;top:5px;">
<a href="../default/index.php" style="text-decoration:none;font-size:1.6em;color:#F3F3F3;font-weight:bold;font-family:'Microsoft YaHei'"><?php echo $l_logo?></a>
</div>
<form method="post" enctype="multipart/form-data" action="../search/index.php" style="padding-top:3px;width:220px;float:right;position:relative;right:100px;" accept-charset="UTF-8">
<input id="sein" name="search" type="text" class="s_input round_border_10" onfocus="inputOnFocus('se');" onblur="inputOffFocus('se', this);"/>
<div>
<span style="cursor:text;position:relative;top:-26px;left:10px;"><label id="se" class="hi_l" onclick="hideOnFocus(this, 'sein');"><?php echo $l_h_search?></label></span>
<input id="s_but" style="	left:<?php if($_SESSION['_language'] == 'zh') { echo 115; } else if($_SESSION['_language'] == 'en') { echo 88; }?>px;" type="submit" value="" />
</div>
</form>
</div>
</div>
<div style="height:38px;"></div>