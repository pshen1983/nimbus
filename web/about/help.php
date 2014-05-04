<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
if(!isset($_SESSION['_language'])) CommonUtil::setSessionLanguage();
if(!isset($_SESSION['_userId'])) SecurityUtil::cookieLogin();

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Help | ConfOne";
	$l_search="You Search For";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="帮助 | 会云网";
	$l_search="您搜索的词条";
}

//=========================================================================================================
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/search.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">html,body{margin:0;padding:0;}</style>
<style>
div.help_detail
{
	margin-top:30px;
	margin-bottom:30px;
	width:80%;
	text-align:left;
	padding-left:30px;
}
div.help_detail p
{
	text-indent:2em;
	color:#333;
	font-size:.8em;
	line-height:1.6em;
	padding-bottom:10px;
}
div.help_detail li
{
	color:#333;
	font-size:.8em;
	margin-bottom:5px;
}
div.weak_sap
{
	width:100%;
	border-top:1px solid #DDD;
	margin-top:20px;
	margin-bottom:20px;
}
label.help_header
{
	font-size:.95em;
	font-weight:bold;
}
a.help_link:hover 
{
	font-weight:bold;
}
</style>
</head>
<body>
<div style="width:100%;">
<?php include_once isset($_SESSION['_userId']) ? '../common/header.inc.php' : '../common/header1.inc.php';?>
<div class="stand_width">
<div id="hmain">
<div style="padding:15px 50px 50px 50px;">
<p style="font-size:1.4em"><b>帮助中心</b></p>
<div class="weak_sap"></div>
<div class="help_detail">
<label class="help_header">账户帮助</label>
<ul>
<li><a href="#q1" class="help_link">账户帮助1</a></li>
<li><a href="#q2" class="help_link">账户帮助2</a></li>
<li><a href="#q3" class="help_link">账户帮助3</a></li>
</ul>
</div>
<div class="weak_sap"></div>
<div class="help_detail">
<label class="help_header">发布帮助</label>
<ul>
<li><a href="#q1" class="help_link">发布帮助1</a></li>
<li><a href="#q2" class="help_link">发布帮助2</a></li>
<li><a href="#q3" class="help_link">发布帮助3</a></li>
</ul>
</div>
<div class="weak_sap"></div>
<div class="help_detail">
<label class="help_header">搜索帮助</label>
<ul>
<li><a href="#q1" class="help_link">搜索帮助1</a></li>
<li><a href="#q2" class="help_link">搜索帮助2</a></li>
<li><a href="#q3" class="help_link">搜索帮助3</a></li>
</ul>
</div>
<div class="weak_sap"></div>

</div>
</div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>