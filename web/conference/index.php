<?php
include_once ("../utils/configuration.inc.php");
include_once ("../_obj/User.php");
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/DatabaseUtil.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../utils/SessionUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);
if($_SESSION['_loginUser']->reg_type != 'E') {
	header( 'Location: ../home/index.php' );
	exit;
}

$result = 0;

if( isset($_POST['ce']) && !empty($_POST['ce']) )
{
	if( isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['sdate']) && !empty($_POST['sdate']) &&
		isset($_POST['edate']) && !empty($_POST['edate']) && isset($_POST['prov']) && !empty($_POST['prov']) &&
		isset($_POST['city']) && !empty($_POST['city']) && isset($_POST['daddr']) && !empty($_POST['daddr']) && 
		isset($_POST['reg']) && ($_POST['reg']=='C' || $_POST['reg']=='O') && 
		isset($_POST['search']) && ($_POST['search']=='Y' || $_POST['search']=='N') && 
		isset($_POST['url']) && !empty($_POST['url']) )
	{
		$coun = 1; //hardcode China is 1;	
		$stime = $_POST['sdate'].' '.$_POST['stime'];
		$etime = $_POST['edate'].' '.$_POST['etime'];

		$cResult = Conference::create( $_SESSION['_userId'], 
									   $_POST['url'], 
									   $_POST['title'], 
									   $coun, 
									   $_POST['prov'], 
									   $_POST['city'], 
									   $_POST['dist'], 
									   $_POST['daddr'], 
									   $_POST['reg'], 
									   $_POST['search'], 
									   $stime, 
									   $etime, 
									   $_POST['desc'], 
									   $_POST['lat'], 
									   $_POST['lng'] ); 
		if( $cResult==0 )
		{
			header( 'Location: ../conference/edit_basic.php?url='.$_POST['url'] ) ;
			exit;
		}
		else if( $cResult==1 ){ $result = 2; }
		else if( $cResult==3 ){	$result = 4; }
		else if( $cResult==4 ){	$result = 5; }
	}
	else {
		$result = 3;
	}
}
else {
	$result = 1;
}

function isZh(){return $_SESSION['_language'] == 'zh';}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Create Event | ConfOne";
	$l_dlist = "My Drafts";
	$l_plist = "My Published Events";
	$l_c_new = "Create an Event";
	$l_edit = "Edit";
	$l_publish = "Publish";
	$l_update = "Update";
	$l_close = "Close";
	$l_history = "My Closed Events";
	$l_langugage = 'en';
	$l_bu_cesub = 'Submit';
	$l_bu_ceres = 'Reset';
	$l_time = "Time: ";
	$l_loca = "Location: ";
	$l_no_draft = 'There is no event draft, <a class="l_sugg" href="javascript:showCE(\'cevent\');">create one</a>';
	$l_no_publish = "There is no publicshed event";
	$l_no_closed = "There is no closed event";
	$l_m_url = "Enter url to create event home page.";
	$l_e_quick = "Quick Create an Event";
	$l_e_create = "Create Event";
	$l_e_basic = "Basic Info";
	$l_e_detail = "Details";
	$l_e_publish = "Publish";
	$l_e_update = "Update";
	$l_e_close = "Close";
	$l_e_name = "Event Name: ";
	$l_e_time = "Event Time: ";
	$l_e_loca = "Event Place:";
	$l_e_link = "Event Link: ";
	$l_e_open = "Registration:";
	$l_e_desc = "Description:";
	$l_more = "More ...";
	$l_e_open_0 = "Open";
	$l_e_open_1 = "Ristrict";
	$l_e_open_2 = "Open allows user join your event freely; Ristrict needs your approval to allow user join your event.";
	$l_e_name_0 = "Name, eg: ConfOne 2011 Seminar";
	$l_e_name_1 = "Please enter event name";
	$l_e_time_0 = "Please enter start and end time";
	$l_e_loca_0 = "Detail address, eg: West 1st Ave, Room 100A";
	$l_e_loca_1 = "Please enter event detail address";
	$l_e_loca_2 = "Please select state/city/district informatoin";
	$l_e_link_0 = "2 - 12 letters or numbers. eg: \"conf11\", <span style='color:blue;text-decoration:underline;cursor:pointer;'>../conf11</span> is the link.";
	$l_e_link_1 = "Please enter an event link (2 - 12 letters or numbers)";
	$l_e_link_2 = "Event link should be 2 - 12 letters or numbers, e.g \"conf11\"";
	$l_e_link_3 = "This link already exists, please try another (2 - 12 letters or numbers)";
	$l_pub_confirm="Are you sure to publish the event?";
	$l_close_confirm="Are you sure to close the event?";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="创建会议活动 | 会云网";
	$l_dlist = "我的草稿";
	$l_plist = "我已发布的会议活动";
	$l_c_new = "创建新的会议活动";
	$l_edit = "编辑活动";
	$l_publish = "发布活动";
	$l_update = "更新内容";
	$l_close = "关闭活动";
	$l_history = "我关闭的会议活动";
	$l_langugage = 'zh';
	$l_bu_cesub = '确定';
	$l_bu_ceres = '重置';
	$l_time = "时间：";
	$l_loca = "地点：";
	$l_no_draft = '您没有会议活动草稿，<a class="l_sugg" href="javascript:showCE(\'cevent\');">创建一个</a>';
	$l_no_publish = "您没有发布的会议活动";
	$l_no_closed = "您没有已经关闭的会议活动";
	$l_m_url = "填写活动URL，生成活动主页。";
	$l_e_create = "创建会议活动";
	$l_e_basic = "基本信息";
	$l_e_detail = "详细信息";
	$l_e_publish = "发布";
	$l_e_update = "更新";
	$l_e_close = "关闭";
	$l_e_name = "活动名称：";
	$l_e_time = "活动时间：";
	$l_e_loca = "活动地点：";
	$l_e_link = "活动链接：";
	$l_e_open = "申请方式：";
	$l_e_desc = "活动介绍：";
	$l_more = "更多 ...";
	$l_e_open_0 = "开放式";
	$l_e_open_1 = "审核式";
	$l_e_open_2 = "开放式允许用户自由加入您的活动，审核式需要您通过站内信审核通过用户的申请。";
	$l_e_search = "搜索类型：";
	$l_e_search_0 = "可搜索";
	$l_e_search_1 = "无搜索";
	$l_e_search_2 = "可搜索允许用户搜索您的活动，无搜索则用户无法搜索到您的活动。";
	$l_e_name_0 = "名称，如：会云网2011年技术交流会";
	$l_e_name_1 = "请填写活动名称";
	$l_e_time_0 = "请选测活动开始和结束日期";
	$l_e_loca_0 = "详细地址，如：迎宾路100号，会云大厦100A室";
	$l_e_loca_1 = "请填写活动详细地址";
	$l_e_loca_2 = "请填写省市地区详细地址";
	$l_e_link_0 = "2 - 12 个字母或数字。如：conf2011，则<span style='color:blue;text-decoration:underline;cursor:pointer;'>".$HTTP_BASE."/conf2011</span>为主页";
	$l_e_link_1 = "请输入一个活动链接（2 - 12 个字母或数字）";
	$l_e_link_2 = "链接需为 2 - 12 个字母或数字，如：conf2011";
	$l_e_link_3 = "此链接已被使用，请输入一个新链接（2 - 12 个字母或数字）";
	$l_pub_confirm="确认发布活动？";
	$l_close_confirm="确认关闭活动？";
}

//=========================================================================================================

//SessionUtil::initLocation();

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery-ui.css" type="text/css" media="all" /> 
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script> 
<script type="text/javascript" src="../js/jquery-ui-custom.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-timepicker.js"></script> 
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/conference.js"></script>
<script type="text/javascript">
$(function(){ $("#sdate").datepicker($.datepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $('#stime').timepicker($.timepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $("#edate").datepicker($.datepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $('#etime').timepicker($.timepicker.regional['<?php echo $l_langugage;?>']); });
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
div#cevent div{margin-left:20px;}
span.arr{font-family:'宋体';}
div.conf{padding:6px 0 6px 6px;}
a.ctitle{color:#000080;font-weight:bold;font-size:.9em;}
label.title{color:#555;}
</style>
<script type="text/javascript">
jQuery(function($){
      $("#btnInsConf").click(function() { 
    	  	  document.getElementById("desc").value=KE.util.getData('desc');
    	   	  var pid = $("#prov").val();
        	  var cid = $("#city").val();
        	  var did = $("#dist").val();
        	  var prov = $("#prov option[value='"+pid+"']").text();
        	  var city = $("#city option[value='"+cid+"']").text();
        	  var dist = $("#dist option[value='"+did+"']").text();
        	  var addr = $.trim($("#daddr").val());

        	  if (did == "" || did == "-1" || did == "0") 
              {
            	  dist = "";
              }
              
			  if (pid == "" || cid == "" || cid == "-1" || cid == "0" || addr == "")
			  {
				  alert("<?php echo $l_e_loca_1?>");
				  return false;
			  }
        	  if (prov == city)
        	  {            	  
				  var fulladdr = city+dist+addr;
				  var paddr = city+dist;
        	  }
        	  else
        	  {       
				  var fulladdr = prov+city+dist+addr;
				  var paddr = prov+city+dist;
        	  }
        	    var geocoder = null;
        	    geocoder = new google.maps.Geocoder();

                if (geocoder) {
                  geocoder.geocode( { 'address': fulladdr}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) 
                    {
                        $("#lat").val(results[0].geometry.location.lat());  
                        $("#lng").val(results[0].geometry.location.lng());
                        $('#ceform').submit();
                    }
                    else 
                    {
                    	geocoder.geocode( { 'address': paddr}, function(results2, status2) {
                            if (status2 == google.maps.GeocoderStatus.OK) 
                            {
                              $("#lat").val(results2[0].geometry.location.lat());  
                              $("#lng").val(results2[0].geometry.location.lng());	            
                            }
                            $('#ceform').submit();	
                        });
                    }
                  });
                }        	  
      }); 
});
</script>      
</head>
<body>
<div style="width:100%;">
<?php $_GET['f']='c'; include_once '../common/header.inc.php'; unset($_GET['f'])?>
<div class="stand_width">
<div id="hmain">
<div id="hright">
<center><div id="create_div" class="round_border_6" onclick="showCE('cevent');">
<div id="plus"><div><span><?php echo $l_c_new;?></span></div></div>
</div></center>
<div style="margin:auto;border-bottom:1px solid #DDD;"></div>
<div style="padding: 20px 0 20px 10px;font-size:.8em;">
<label style="font-weight:bold;"><?php echo $l_history?></label><br />
<div id="slink">
<?php 
$counter = 0;
$cConfs = Conference::getUserClosedConfList($_SESSION['_userId'], 0, 3);
while($row = mysql_fetch_array($cConfs, MYSQL_ASSOC))
{
$counter++;
?>
<a target="_blank" title="<?php echo $row['name']?>" href="../conference/info_home.php?url=<?php echo $row['url']?>"><?php echo CommonUtil::truncate($row['name'], 15);?></a><br />
<?php }
if($counter > 2) {?>
<div style="margin-top:10px;"><a style="text-decoration:underline" href="../conference/closed.php"><?php echo $l_more?></a></div>
<?php } else if($counter == 0){
echo '<label style="color:#666">'.$l_no_closed.'</label>';
}?>
</div>
</div>
</div>
<div id="hleft">
<div id="in_con">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_dlist?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<div style=""></div>
<ul class="clist">
<!--  ================================================================================================================ --> 
<?php 
$dList = Conference::getUserConfList($_SESSION['_userId'], 'D');

$ind = 0;
foreach ($dList as $conf) 
{
	$sftime = strtotime($conf->stime);
	$sdate = date('Y-m-d', $sftime);
	$stime = date('H:i', $sftime);
	$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');

	if( isset($conf->etime) && !empty($conf->etime) )
	{
		$eftime = strtotime($conf->etime);
		$edate = date('Y-m-d', $eftime);
		$etime = date('H:i', $eftime);
		$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
	}
?>
<li style="background-color:<?php echo ($ind%2==0) ? "#f1f1f1" : "#ffffff";?>;">
<div class="conf">
<div class="bdiv">
<button class="join" onclick="window.location='../conference/edit_basic.php?url=<?php echo $conf->getUrl()?>'"><span><?php echo $l_edit?></span></button><br />
<button class="join" onclick="publishConf('<?php echo $l_pub_confirm?>','<?php echo $conf->getCid()?>','<?php echo $conf->getUrl()?>')"><span><?php echo $l_publish?></span></button><br />
<!-- div style="width:12px;height:12px;background:<?php echo $_SESSION['_color'][$colorIndex]?>;margin:10px 0 0 28px;" title="<?php echo $conf->name;?>"></div -->
</div>
<!--div class="ccolor" style="background:<?php echo $_SESSION['_color'][$ind];?>;"></div -->
<table>
<tr><td><div class="img_f" style="background:url('<?php echo $conf->img?>') no-repeat center center;"></div></td>
<td style="padding-left:20px;color:<?php echo $_SESSION['_color'][$ind];?>">
<a target="_blank" href="../conference/info_home.php?url=<?php echo $conf->getUrl();?>" class="ctitle"><?php echo $conf->name;?></a>
<div style="margin-top:10px;font-size:.8em;color:#333;">
<span style="font-weight:bold"><?php echo $l_time;?></span><?php echo $tprint;?><br />
<span style="font-weight:bold"><?php echo $l_loca;?></span><?php echo $conf->getFaddr();?>
</div></td></tr></table></div></li>
<?php 
$ind++;
}
if($ind==0) {
	echo '<div style="margin:10px 0 10px 20px;color:#666"><label>'.$l_no_draft.'</label></div>';
}?>
<!--  ================================================================================================================ --> 
</ul>
</div>
<div style="border-top:1px solid #EEE;"></div>
<div id="in_con">
<label style="font-size:.8em;font-weight:bold;"><?php echo $l_plist?></label>
<div style="border-bottom:1px solid #DDD;width:40%;margin: 5px 0 15px 0;"></div>
<ul class="clist">
<!--  ================================================================================================================ --> 
<?php 
$pList = Conference::getUserConfList($_SESSION['_userId'], 'P');

$colorIndex = 15;

$ind = 0;
foreach ($pList as $conf) 
{
	$sftime = strtotime($conf->stime);
	$sdate = date('Y-m-d', $sftime);
	$stime = date('H:i', $sftime);
	$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');

	if( isset($conf->etime) && !empty($conf->etime) )
	{
		$eftime = strtotime($conf->etime);
		$edate = date('Y-m-d', $eftime);
		$etime = date('H:i', $eftime);
		$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
	}
?>
<li style="background-color:<?php echo ($ind%2==0) ? "#f1f1f1" : "#ffffff";?>;">
<div class="conf">
<div class="bdiv">
<button class="join" onclick="window.location='../conference/edit_basic.php?url=<?php echo $conf->getUrl()?>'"><span><?php echo $l_update?></span></button><br />
<button class="join" onclick="closeConf('<?php echo $l_close_confirm?>','<?php echo $conf->getCid()?>','<?php echo $conf->getUrl()?>')"><span><?php echo $l_close?></span></button><br />
<!-- div style="width:12px;height:12px;background:<?php echo $_SESSION['_color'][$colorIndex]?>;margin:10px 0 0 28px;" title="<?php echo $row['name'];?>"></div -->
</div>
<!--div class="ccolor" style="background:<?php echo $_SESSION['_color'][$ind];?>;"></div -->
<table>
<tr><td><div class="img_f" style="background:url('<?php echo $conf->img?>') no-repeat center center;"></div></td>
<td style="padding-left:20px;color:<?php echo $_SESSION['_color'][$ind];?>">
<a target="_blank" href="../conference/info_home.php?url=<?php echo $conf->getUrl();?>" class="ctitle"><?php echo $conf->name;?></a>
<div style="margin-top:10px;font-size:.8em;color:#333;">
<span style="font-weight:bold"><?php echo $l_time;?></span><?php echo $tprint;?><br />
<span style="font-weight:bold"><?php echo $l_loca;?></span><?php echo $conf->getFaddr();?>
</div></td></tr></table></div></li>
<?php 
	$ind++;
	$colorIndex++;
}
if($ind==0) {
	echo '<div style="margin:10px 0 10px 20px;color:#666"><label>'.$l_no_publish.'</label></div>';
}
?>
<!--  ================================================================================================================ --> 
</ul>
</div>
<div id="near_by">
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
<div id="screen"<?php if($result != 1) echo ' style="visibility:visible;"'; ?>></div>
<div id="cevent" class="cevent round_border_20"<?php if($result != 1) echo ' style="visibility:visible;"'; ?>>
<div id="cbutton" onclick="dismissCE('cevent')"></div>
<div id="confind">
<div id="head_info" style="width:100%;height:60px;"><center>
<div class="round_border_10" style="padding:5px 0 5px 0;color:#000080;font-weight:bold;font-size:1.5em;margin:10px 0 10px 0;width:88%;">
<label><?php echo $l_e_create?></label> <span class="arr">></span>
<label style="color:#9CC7E5;"><?php echo $l_e_basic?></label> <span class="arr">></span>
<label><?php echo $l_e_detail?></label> <span class="arr">></span>
<label><?php echo $l_e_publish?></label> <span class="arr">></span>
<label><?php echo $l_e_update?></label> <span class="arr">></span>
<label><?php echo $l_e_close?></label>
</div></center>
</div>
<form method="post" enctype="multipart/form-data" action="index.php" name="ceform" id="ceform" accept-charset="UTF-8">
<table style="width:98%;">
<tr>
<td style="width:<?php echo isZh() ? '7' : '8' ?>0px;">
<label class="title"><?php echo $l_e_name;?></label></td>
<td>
<input id="title" type="text" name="title" onfocus="showElem('nl');hideElem('nerr')" onblur="hideElem('nl');checkNoneEmpty(this, 'nerr');" value="<?php if( isset($_POST['title']) ) echo $_POST['title'];?>"/>
<label id="nl" class="hint">&#9668; <?php echo $l_e_name_0;?></label>
<label id="nerr" class="err"<?php if($result==3 && (!isset($_POST['title'])|| empty($_POST['title']))) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_name_1;?></label>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_time;?></label></td>
<td><input id="sdate" name="sdate" style="width:80px;" type="text" class="ctimes" onfocus="hideElem('terr');" value="<?php echo (isset($_POST['sdate']) && !empty($_POST['sdate']) ? $_POST['sdate'] : date("Y-m-d"))?>" />
<input id="stime" name="stime" style="width:46px;" type="text" class="ctimes" value="<?php if(isset($_POST['stime'])) echo $_POST['stime'];?>" />
<label style="font-weight:normal;margin:0 4px 0 5px;">~</label>
<input id="edate" name="edate" style="width:80px;" type="text" class="ctimes" onfocus="hideElem('terr');" value="<?php echo (isset($_POST['edate']) && !empty($_POST['edate']) ? $_POST['edate'] : date("Y-m-d"))?>" />
<input id="etime" name="etime" style="width:46px;" type="text" class="ctimes" value="<?php if(isset($_POST['etime'])) echo $_POST['etime'];?>" />
<label id="terr" class="err"<?php if($result==3 && (!isset($_POST['sdate']) || empty($_POST['sdate']) || !isset($_POST['edate']) || empty($_POST['edate']))) echo ' style="display:inline;"'?>>&#9668; <?php $l_e_time_0?></label>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_loca;?></label></td>
<td><select name="prov" id="prov" onfocus="hideElem('aerr');" onchange="updateCityList(this.options[this.options.selectedIndex].value);"><option value="" >--</option><?php
CommonUtil::printOptions($_SESSION['_provL'], isset($_SESSION['_prov']) ? $_SESSION['_prov'] : null);
?></select><select name="city" id="city" onfocus="hideElem('aerr');" onchange="updateDistList(this.options[this.options.selectedIndex].value);"><option value="" >--</option><?php
CommonUtil::printOptions($_SESSION['_cityL'], isset($_SESSION['_city']) ? $_SESSION['_city'] : null);
?></select><img id="cityW" class="wait" src="../image/loading_min.gif"/><select name="dist" id="dist" onfocus="hideElem('aerr');" onchange="setDistSelect(this.options[this.options.selectedIndex].value);"><option value="" >--</option><?php
CommonUtil::printOptions($_SESSION['_distL'], isset($_SESSION['_dist']) ? $_SESSION['_dist'] : null);
?></select><img id="distW" class="wait" src="../image/loading_min.gif"/><input id="daddr" type="text" name="daddr" onfocus="showElem('al');hideElem('aderr');hideElem('aerr');" onblur="hideElem('al');checkNoneEmpty(this,'aderr');" value="<?php if(isset($_POST['daddr'])) echo $_POST['daddr'];?>" />
<label id="al" class="hint">&#9668; <?php echo $l_e_loca_0?></label>
<label id="aderr" class="err"<?php if($result==3 && (!isset($_POST['daddr']) || empty($_POST['daddr']))) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_loca_1?></label>
<label id="aerr" class="err"<?php if($result==3 && isset($_POST['daddr']) && !empty($_POST['daddr']) && (!isset($_POST['city']) || empty($_POST['city']) || !isset($_POST['prov']) || empty($_POST['prov']) || $_POST['city']==-1 || $_POST['prov']==-1)) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_loca_2?></label>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_link;?></label></td>
<td><label style="font-weight:normal;font-size:1em;"><?php echo $HTTP_BASE?>/</label><input id="url" type="text" onkeypress="return numeralsOnly(event)" onkeydown="checkURL('url')" onkeyup="checkURL('url')" onfocus="showElem('ul');hideElem('uerr2');hideElem('uerr3');hideElem('uerr4');" onblur="isUrlExit(this.value);hideElem('ul');checkNoneEmpty(this,'uerr2');" name="url" title="<?php echo $l_m_url?>" value="<?php if(isset($_POST['url'])) echo $_POST['url'];?>" />
<label id="ul" class="hint">&#9668; <?php echo $l_e_link_0;?></label>
<label id="uerr2" class="err"<?php if($result==3 && (!isset($_POST['url']) || empty($_POST['url']))) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_link_1;?></label>
<label id="uerr3" class="err"<?php if($result==5 && isset($_POST['url']) && !empty($_POST['url'])) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_link_2;?></label>
<label id="uerr4" class="err"<?php if($result==4 && isset($_POST['url']) && !empty($_POST['url'])) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_link_3;?></label>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_open;?></label></td><td>
<input style="border:0 none;vertical-align:middle;" type="radio" name="reg" value="O" CHECKED />
<span style="font-size:.8em;"><?php echo $l_e_open_0?></span>
<input style="border:0 none;vertical-align:middle;" type="radio" name="reg" value="C" />
<span style="font-size:.8em;margin-right:10px;"><?php echo $l_e_open_1?></span>
<label style="font-weight:normal;color:#999;">&#9668; <?php echo $l_e_open_2?></label>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_search;?></label></td><td>
<input style="border:0 none;vertical-align:middle;" type="radio" name="search" value="Y" CHECKED />
<span style="font-size:.8em;"><?php echo $l_e_search_0?></span>
<input style="border:0 none;vertical-align:middle;" type="radio" name="search" value="N" />
<span style="font-size:.8em;margin-right:10px;"><?php echo $l_e_search_1?></span>
<label style="font-weight:normal;color:#999;">&#9668; <?php echo $l_e_search_2?></label>
</td>
</tr>
<tr>
<td>
<script charset="utf-8" src="../kindeditor/kindeditor-min.js"></script>
<script>KE.show({ id : 'desc', allowFileManager : false, allowUpload : false });</script>
<label class="title" style="float:left;"><?php echo $l_e_desc;?></label></td>
<td>
<textarea id="desc" name="desc" cols="100" rows="8"><?php if(isset($_POST['desc'])) echo $_POST['desc']?></textarea>
</td>
</tr>
<tr>
<td></td>
<td align="center">
<input id="btnInsConf" style="height:30px;" class="btn" type="button" value="<?php echo $l_bu_cesub ?>" />
<input id="btnReset" style="height:30px;" class="btn" type="reset" value="<?php echo $l_bu_ceres ?>" />
</td>
</tr>
</table>
<input type="hidden" name="ce" value="1" />
<input type="hidden" id="lat" name="lat" value="" />
<input type="hidden" id="lng" name="lng" value="" />
</form>
</div>
</div>
</body>
</html>