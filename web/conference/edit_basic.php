<?php
include_once ("../utils/configuration.inc.php");
include_once ("../_obj/Conference.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../utils/SessionUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);
if($_SESSION['_loginUser']->reg_type != 'E') {
	header( 'Location: ../home/index.php' );
	exit;
}

if( !isset($_GET['url']) || empty($_GET['url']) || !Conference::isUserConfByUrl($_SESSION['_userId'], $_GET['url']) )
{
	header( 'Location: ../conference/index.php' );
	exit;
}

//==================================================================================================

if(!isset($_SESSION['_confsEdit']))
{
	$_SESSION['_confsEdit'] = array();
}

if( !isset($_SESSION['_confsEdit'][$_GET['url']]) )
{
	$conf = Conference::getConfInfo($_GET['url']);
	if($conf->status == 'C')
	{
		header( 'Location: ../conference/index.php' );
		exit;
	}
	$_SESSION['_confsEdit'][$_GET['url']] = $conf;
}

$conf = $_SESSION['_confsEdit'][$_GET['url']];


$cities = DatabaseUtil::getCityList($conf->prov);
$cityL = array();
while($row = mysql_fetch_array($cities, MYSQL_ASSOC))
{
	$cityL[$row['cityid']] = $row['cityname'];
}

$dists = DatabaseUtil::getDistList($conf->city);
$distL = array();
while($row = mysql_fetch_array($dists, MYSQL_ASSOC))
{
	$distL[$row['id']] = $row['disname'];
}

$sftime = strtotime($conf->stime);
$sdate = date('Y-m-d', $sftime);
$stime = date('H:i', $sftime);
if($stime == '00:00') $stime = '';

$eftime = strtotime($conf->etime);
$edate = date('Y-m-d', $eftime);
$etime = date('H:i', $eftime);
if($etime == '00:00') $etime = '';

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_langugage = 'en';
	$l_title = "Basic Info | ";
	$l_e_image = "Change event image";
	$l_e_quick = "Quick Create an Event";
	$l_e_name = "Event Name: ";
	$l_e_time = "Event Time: ";
	$l_e_loca = "Event Place:";
	$l_e_link = "Event Link: ";
	$l_e_open = "Registration:";
	$l_e_desc = "Description:";
	$l_e_open_0 = "Open";
	$l_e_open_1 = "Ristrict";
	$l_e_open_2 = "Open allows user to join your event freely; Ristrict needs your approval to allow user join your event.";
	$l_e_name_0 = "Name, eg: ConfOne 2011 Seminar";
	$l_e_name_1 = "Please enter event name";
	$l_e_time_0 = "Please enter start and end time";
	$l_e_loca_0 = "Detail address, eg: West 1st Ave, Room 100A";
	$l_e_loca_1 = "Please enter event detail address";
	$l_e_loca_2 = "Please select state/city/district informatoin";
	$l_bu_cesub = 'Submit';
	$l_bu_ceres = 'Reset';
	$l_file_preview="Preview";
	$l_file_size="(Support jpg、png、gif, <span style='color:red'>less than 1MB</span>) ";
	$l_bu_chg="Save";
	$a_sel_pic="Please select an image before uploading";
	$a_pic_size="Please upload jpg、png、gif image which is less than 1MB";
	$a_pic_err="Image contains error, please upload another one";
	$a_pic_fail="Upload fails, please try again";
	$a_not_crop="Please select an area to crop the image";
	$a_pic_failupd="Update fails, please upload a new image and try again";
	$l_browse_file="Browse File";
}
else if($_SESSION['_language'] == 'zh') {
	$l_langugage = 'zh';
	$l_title = "基本信息 | ";
	$l_e_image = "更新会议活动标图";
	$l_e_quick = "快速创建会议活动";
	$l_e_name = "活动名称：";
	$l_e_time = "活动时间：";
	$l_e_loca = "活动地点：";
	$l_e_link = "活动链接：";
	$l_e_open = "申请方式：";
	$l_e_desc = "活动介绍：";
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
	$l_e_loca_0 = "详细地址，如：（迎宾路100号，会云大厦100A室）";
	$l_e_loca_1 = "请填写活动详细地址";
	$l_e_loca_2 = "请填写省市地区详细地址";
	$l_bu_cesub = '确定';
	$l_bu_ceres = '重置';
	$l_file_preview="预览";	
	$l_file_size="(支持jpg、png、gif，<span style='color:red'>小于 1MB</span>) ";
	$l_bu_chg="保存";
	$a_sel_pic="请选择你要上传的图片";
	$a_pic_size="请上传小于1MB的jpg、png、gif格式图片";
	$a_pic_err="上传的图片有误，请选择正确图片上传";
	$a_pic_fail="上传失败，请重试一次";
	$a_not_crop="请选择一个区域进行裁剪";
	$a_pic_failupd="图片更新失败，请重新上传";
	$l_browse_file="选择文件";
}

//=========================================================================================================

function isZh(){return $_SESSION['_language'] == 'zh';}

SessionUtil::initLocation();

$page = 1;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title.$conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery-ui.css" media="all" /> 
<link type="text/css" rel="stylesheet" href="../css/jquery.Jcrop.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/conference.js"></script>
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script> 
<script type="text/javascript" src="../js/jquery-ui-custom.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-timepicker.js"></script> 
<script type="text/JavaScript" src="../js/jquery.Jcrop.min.js"></script>
<script type="text/JavaScript" src="../js/ajaxfileupload.js"></script>
<script type="text/javascript">
$(function(){ $("#bsdate").datepicker($.datepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $('#bstime').timepicker($.timepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $("#bedate").datepicker($.datepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $('#betime').timepicker($.timepicker.regional['<?php echo $l_langugage;?>']); });
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
div#cevent div{margin-left:10px;margin-bottom:5px;}
label.title{color:#555;}
</style>
<script type="text/javascript">
jQuery(function($){
      // Create variables (in this scope) to hold the API and image size
      var jcrop_api, boundx, boundy;
      $('#target').Jcrop({
        onChange: updatePreview,
        boxWidth: 600, 
        boxHeight: 400,
        aspectRatio: 13/8
      },function(){
        // Use the API to get the real image size
        var bounds = this.getBounds();
        boundx = bounds[0];
        boundy = bounds[1];
        // Store the API in the jcrop_api variable
        jcrop_api = this;
      });

      function updatePreview(c)
      {
        if (parseInt(c.w) > 0)
        {
            var rx = 130 / c.w;
            var ry = 80 / c.h;            
          $('#preview').css({
            width: Math.round(rx * boundx) + 'px',
            height: Math.round(ry * boundy) + 'px',
            marginLeft: '-' + Math.round(rx * c.x) + 'px',
            marginTop: '-' + Math.round(ry * c.y) + 'px'
          });
        }
         $('#x1').val(c.x);
	     $('#y1').val(c.y);
	     $('#x2').val(c.x2);
	     $('#y2').val(c.y2); 
      };   

      $("#btnUpdConf").click(function() {  
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
      
      //ajax upload image
	  $("#file").live('change',function() {
		  $("#progress").attr("style", "display:inline");
			$("#x1").val("");
			$("#y1").val("");
			$("#x2").val("");
			$("#y2").val("");
		    var fname = $("#fname").val();
		    if($("#file").val()=="")
		    {
		    	alert("<?php echo $a_sel_pic ?>");
		    	return false;
		    }
		    
		    $.ajaxFileUpload(	
	        {	 		                  
                url:"../utils/AjaxUploadImg.inc.php",
                secureuri:false,
                fileElementId:'file',
	            data:{'fileElement':'file', 'fileSize':'1', 'fileName':fname},
                dataType:'json', 
                success: function (data)
                {
                	if (data.error==0)
                	{
						jcrop_api.setImage(data.url);
						$("#target").attr("src", data.url);
						$("#preview").attr("src", data.url);
						boundx = data.width;
						boundy = data.height;

						var ratio=13/8;
						if ( boundx/boundy > ratio ) {rx=80*(boundx/boundy);ry=80;}
						else { rx=130;ry=130*(boundy/boundx); }				        
						$('#preview').css({width: Math.round(rx)+'px', height: Math.round(ry)+'px', marginLeft: '0px', marginTop: '0px'});
                	}
                	else if (data.error==1)
                    { 
                        alert("<?php echo $a_pic_size ?>");
                    }
                	else if (data.error==2)
                    { 
                        alert("<?php echo $a_pic_err ?>");
                    }
                	else if (data.error==3)
                    { 
                        alert("<?php echo $a_pic_fail ?>");
                    }
	            	$("#progress").attr("style", "display:none");
                }
            }); 
			
		    $("#file").val("");
		    return false;
	  });
	  
      //Save Conf Image
      $("#SaveConfImg").click(function() {
		  var x1 = $("#x1").val(); 
		  var y1 = $("#y1").val(); 
		  var x2 = $("#x2").val(); 
		  var y2 = $("#y2").val(); 
		  var fname = $.trim($("#fname").val());
		  var simg_cid = $("#simg_cid").val();
		  var url = $.trim($("#url").val());
		  var target_src = $("#target").attr("src");
				
		  //validate data
		  if (x1=='' || y1=='' || x2=='' || y2=='' || x1==x2 || y1==y2)
		  {	  
			  alert("<?php echo $a_not_crop ?>");
			  return false;
		  }
		  else if (target_src.indexOf("image/default/default_pic_gray1x1") >= 0)
		  {	  
			  alert("<?php echo $a_sel_pic ?>");
			  return false;
		  }
		  
	 	  var dataString = 'x1='+x1+'&y1='+y1+'&x2='+x2+'&y2='+y2+'&fname='+fname+'&simg_cid='+simg_cid+'&url='+url;
	      $.ajax({
		      type: "POST",  
		      url: "../utils/saveConfImg.inc.php",  
		      data: dataString,
		      success: function(data) {			      
			  if (data.indexOf("temp_conf_img_crop") >= 0)
			   {	
				    var default_img = "../image/default/default_pic_gray1x1.png";
					$("#conf_img").attr("src", data);				
					jcrop_api.setImage(default_img);
					$("#target").attr("src", default_img);
					$("#preview").attr("src", data);
					$('#preview').css({width: '130px', height: '80px', marginLeft: '0px', marginTop: '0px'});
					dismissCE("cimgdiv");
			   }
			   else //if (data == 1 || data == 2)
			   {
				   alert("<?php echo $a_pic_failupd ?>");
			   }
		      }  
		    });
    
			$("#x1").val("");
			$("#y1").val("");
			$("#x2").val("");
			$("#y2").val("");
		    return false;
	  });       
});
</script>
</head>
<body>
<div style="width:100%;">
<?php include_once '../common/header.inc.php'; ?>
<div class="stand_width">
<div id="hmain">
<div id="hleft" style="width:100%;">
<?php include_once 'edit_header.inc.php';?>
<div id="content" style="margin:auto;width:99%;min-height:700px;">
<div id="cevent">
<div style="padding:10px 0 10px 0px;">
<img style="margin-right:10px;-webkit-box-shadow:0 0 5px #666;-moz-box-shadow:0 0 5px #666;box-shadow:0 0 5px #666;border:1px solid #eee;" id="conf_img" src="<?php echo $conf->img?>" />
<a class="img_a" href="javascript:showCE('cimgdiv')"><?php echo $l_e_image;?></a></div>
<form style="margin-top:10px;" method="post" enctype="multipart/form-data" action="updateConf.inc.php" id="ceform" name="ceform" accept-charset="UTF-8">
<table>
<tr>
<td style="width:<?php echo isZh() ? '7' : '8' ?>0px;">
<label class="title"><?php echo $l_e_name;?></label></td>
<td>
<input id="title" type="text" name="title" onfocus="showElem('nl');hideElem('nerr')" onblur="hideElem('nl');checkNoneEmpty(this, 'nerr');" value="<?php echo $conf->name?>"/>
<label id="nl" class="hint">&#9668; <?php echo $l_e_name_0;?></label>
<label id="nerr" class="err"<?php if(isset($_POST['ce']) && (!isset($_POST['title']) || empty($_POST['title']))) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_name_1;?></label>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_time;?></label></td>
<td><input id="bsdate" name="bsdate" style="width:80px;" type="text" class="ctimes" onfocus="hideElem('terr');" value="<?php echo $sdate?>" />
<input id="bstime" name="bstime" style="width:46px;" type="text" class="ctimes" value="<?php echo $stime?>" />
<label style="font-weight:normal;margin:0 4px 0 5px;">~</label>
<input id="bedate" name="bedate" style="width:80px;" type="text" class="ctimes" onfocus="hideElem('terr');" value="<?php echo $edate?>" />
<input id="betime" name="betime" style="width:46px;" type="text" class="ctimes" value="<?php echo $etime?>" />
<label id="terr" class="err"<?php if(isset($_POST['ce']) && (!isset($_POST['sdate']) || empty($_POST['sdate']) || !isset($_POST['edate']) || empty($_POST['edate']))) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_time_0?></label>
<input type="hidden" id="url" name="url" value="<?php echo $_GET['url'] ?>" />
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_loca;?></label></td>
<td><select name="prov" id="prov" onfocus="hideElem('aerr');" onchange="updateCityList(this.options[this.options.selectedIndex].value);"><option value="" >--</option><?php
CommonUtil::printOptions($_SESSION['_provL'], $conf->prov);
?></select><select name="city" id="city" onfocus="hideElem('aerr');" onchange="updateDistList(this.options[this.options.selectedIndex].value);"><option value="" >--</option><?php
CommonUtil::printOptions($cityL, $conf->city);
?></select><img id="cityW" class="wait" src="../image/loading_min.gif"/>
<select name="dist" id="dist" onfocus="hideElem('aerr');" onchange="setDistSelect(this.options[this.options.selectedIndex].value);"><option value="" >--</option><?php
CommonUtil::printOptions($distL, $conf->dist);
?></select><img id="distW" class="wait" src="../image/loading_min.gif"/><input id="daddr" type="text" name="daddr" onfocus="showElem('al');hideElem('aderr');hideElem('aerr');" onblur="hideElem('al');checkNoneEmpty(this,'aderr');" value="<?php echo $conf->addr?>" />
<label id="al" class="hint">&#9668; <?php echo $l_e_loca_0?></label>
<label id="aderr" class="err"<?php if(isset($_POST['ce']) && (!isset($_POST['daddr']) || empty($_POST['daddr']))) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_loca_1?></label>
<label id="aerr" class="err"<?php if(isset($_POST['daddr']) && !empty($_POST['daddr']) && (!isset($_POST['city']) || empty($_POST['city']) || !isset($_POST['prov']) || empty($_POST['prov']) || $_POST['city']==-1 || $_POST['prov']==-1)) echo ' style="display:inline;"'?>>&#9668; <?php echo $l_e_loca_2?></label>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_link;?></label></td>
<td><a target="_blank" href="../conference/info_home.php?url=<?php echo $conf->getUrl();?>" style="font-weight:normal;font-size:1em;"><?php echo $HTTP_BASE?>/<?php echo $conf->getUrl();?></a>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_open;?></label></td>
<td>
<input style="border:0 none;vertical-align: middle;" type="radio" name="reg" value="O" <?php echo ($conf->registration == 'O' ? 'CHECKED' : '')?> />
<span style="font-size:.8em;"><?php echo $l_e_open_0?></span>
<input style="border:0 none;vertical-align: middle;" type="radio" name="reg" value="C" <?php echo ($conf->registration == 'C' ? 'CHECKED' : '')?> />
<span style="font-size:.8em;margin-right:10px;"><?php echo $l_e_open_1?></span>
<label style="font-weight:normal;color:#999;">&#9668; <?php echo $l_e_open_2?></label>
</td>
</tr>
<tr>
<td>
<label class="title"><?php echo $l_e_search;?></label></td><td>
<input style="border:0 none;vertical-align:middle;" type="radio" name="search" value="Y" <?php echo ($conf->search == 'Y' ? 'CHECKED' : '')?> />
<span style="font-size:.8em;"><?php echo $l_e_search_0?></span>
<input style="border:0 none;vertical-align:middle;" type="radio" name="search" value="N" <?php echo ($conf->search == 'N' ? 'CHECKED' : '')?>/>
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
<textarea id="desc" name="desc" cols="100" rows="8" style="width:800px;height:300px;"><?php echo $conf->description?></textarea>
</td>
</tr>
<tr>
<td></td>
<td align="center">
<input id="btnUpdConf" style="height:30px;" class="btn" type="button" value="<?php echo $l_bu_cesub ?>" ></input>
<input id="btnReset" style="height:30px;" class="btn" type="reset" value="<?php echo $l_bu_ceres ?>" ></input>
</td>
</tr>
</table>
<input type="hidden" name="ce" value="1" />
<input type="hidden" id="lat" name="lat" value="" />
<input type="hidden" id="lng" name="lng" value="" />
<input type="hidden" id="edform_cid" name="edform_cid" value="<?php echo $conf->getCid(); ?>" />
<input type="hidden" id="edform_fpage" name="edform_fpage" value="../conference/edit_basic.php?url=<?php echo $conf->getUrl()?>"/>
</form>
</div>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
<div id="screen"></div>
<div id="cimgdiv" class="cevent round_border_20" style="min-height:490px;position:normal;">
<div id="cbutton" onclick="dismissCE('cimgdiv')"></div>
<div style="padding:20px 20px 20px 20px;" align="center">
<table>
<tr>
<td>
<div style="width:600px;height:400px;border:1px #999 solid;background:#ddd;display:table-cell;vertical-align:middle;" align="center">
<img src="../image/default/default_pic_gray1x1.png" id="target" />
<img src="../image/progress-bar.gif" id="progress" style="display:none;"/>
</div>
</td>
<td align="center">
<label><?php echo $l_file_preview ?></label>
<div style="width:130px;height:80px;overflow:hidden;border: 1px #999 solid;">
<img src="<?php echo $conf->img; ?>" id="preview" />
</div>
</td>
</tr>
<tr align="left">
<td>
<form id="fform" method="post" action="../utils/uploadConfImg.inc.php" enctype="multipart/form-data">
<input type="hidden" id="fname" name="fname" value="../tmp/temp_conf_img.<?php echo $conf->getCid()?>" />
<label><?php echo $l_file_size ?></label>
<span class="uploadImgSpan">
<input type="file" name="file" id="file" size="1" />
<input class="btnfile" type="button" value="<?php echo $l_browse_file ?>"></input></span>
</form>
</td>
<td align="center">
<form id="simg" method="post" action="../utils/saveConfImg.inc.php" enctype="multipart/form-data">
<input type="hidden" id="x1" name="x1" />
<input type="hidden" id="y1" name="y1" />
<input type="hidden" id="x2" name="x2" />
<input type="hidden" id="y2" name="y2" />
<input type="hidden" id="simg_cid" name="simg_cid" value="<?php echo $conf->getCid(); ?>" />
<input type="hidden" id="url" name="url" value="<?php echo $conf->getUrl(); ?>" />
<input id="SaveConfImg" style="margin-top:-30px;" class="btn" type="button" value="<?php echo $l_bu_chg ?>" />
</form>
</td>      
</tr>
</table>
</div>
</div>
</body>
</html>