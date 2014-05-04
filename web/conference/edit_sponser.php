<?php
include_once ("../_obj/Conference.php");
include_once ("../_obj/Sponsor.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

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

//============================================= Language ==================================================

if($_SESSION['_language']=='en') {
	$l_title="Sponsor | ";
	$l_langugage='en';
	$l_save="Save";
	$l_submit="Submit";
	$l_cancel="Cancel";
	$l_del_confirm="Confirm delete?";
	$l_edt_title="Edit";
	$l_del_title="Delete";
	$l_file_size="(Support jpg、png、gif, <span style='color:red'>less than 1MB</span>) ";
	$l_h_img="Sponsor Icon";
	$l_h_cname="Sponsor Name";
	$l_h_url="Sponsor Website";
	$l_t_cname="Sponsor Name: ";
	$l_t_url="Sponsor Website: ";
	$a_sel_pic="Please select an image before uploading";
	$a_pic_size="Please upload jpg、png、gif image which is less than 1MB";
	$a_pic_err="Image contains error, please upload another one";
	$a_pic_fail="Upload fails, please try again";
	$a_exist="Sponsor exists, please update information in the list";
	$a_miss_cname="Please enter sponsor name";
	$a_miss_all="Please enter all required information";
	$a_fmt_all="Please do not enter special character in all fields";
	$l_browse_file="Browse File";
}
else if($_SESSION['_language']=='zh') {
	$l_title="赞助商 | ";	
	$l_langugage='zh';
	$l_save="保存";
	$l_submit="添加";
	$l_cancel="取消";
	$l_del_confirm="确认删除？";
	$l_edt_title="编辑";
	$l_del_title="删除";
	$l_file_size="(支持jpg、png、gif，<span style='color:red'>小于 1MB</span>) ";
	$l_h_img="赞助商图标";
	$l_h_cname="赞助商名称";
	$l_h_url="赞助商网址";	
	$l_t_cname="赞助商名称：";
	$l_t_url="赞助商网址：";	
	$a_sel_pic="请选择你要上传的图片";
	$a_pic_size="请上传小于1MB的jpg、png、gif格式图片";
	$a_pic_err="上传的图片有误，请选择正确图片上传";
	$a_pic_fail="上传失败，请重试一次";
	$a_exist="赞助商已存在，请在列表中更新";
	$a_miss_cname="请填写赞助商名称";
	$a_miss_all="请填写所有必填信息";
	$a_fmt_all="请不要在所填信息中使用特殊符号";
	$l_browse_file="选择文件";
}

//=========================================================================================================

$page = 6;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title.$conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<style type="text/css">
html,body{margin:0;padding:0;}
img.TableEdt{cursor:pointer;border:0 none;}
img.TableDel{cursor:pointer;border:0 none;}
label.text{font-size:.8em;color:#555;}
</style>
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery.Jcrop.css" />
<script type="text/JavaScript" src="../js/conference.js"></script>
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>  
<script type="text/JavaScript" src="../js/jquery.Jcrop.min.js"></script>
<script type="text/JavaScript" src="../js/ajaxfileupload.js"></script>

<script type="text/javascript">
jQuery(function($){
	 var jcrop_api2;
	    $('#upd_target').Jcrop({
	      onChange: updatePreview2,
	      onSelect: updatePreview2,
	      boxWidth: 320, 
	      boxHeight: 240,
	      bgOpacity: 0.3
	    },function(){
	      // Store the API in the jcrop_api variable
	      jcrop_api2=this;
	    });
	    function updatePreview2(c)
	    {
	      $('#upd_x1').val(c.x);
		  $('#upd_y1').val(c.y);
		  $('#upd_x2').val(c.x2);
		  $('#upd_y2').val(c.y2);    
	    };  

		//ajax upload sponsor upd image
		  $("#upd_file").live('change',function() {
				$("#upd_x1").val("");
				$("#upd_y1").val("");
				$("#upd_x2").val("");
				$("#upd_y2").val("");
			    var fileName=$("#upd_filename").val();
			    if($("#upd_file").val()=="")
			    {
			    	alert("<?php echo $a_sel_pic?>");
			    	return false;
			    }			    
			    $.ajaxFileUpload(	
		        {				       
	              url:"../utils/AjaxUploadImg.inc.php",
	              secureuri:false,
	              fileElementId:'upd_file',
	              data:{'fileElement':'upd_file', 'fileSize':'1', 'fileName':fileName},
	              dataType:'json', 
	              success: function (data)
	              {
	              	if (data.error==0)
	              	{
						jcrop_api2.setImage(data.url);
						$("#upd_target").attr("src", data.url);
						$("#upd_x1").val("0");
						$("#upd_y1").val("0");
						$("#upd_x2").val(data.width);
						$("#upd_y2").val(data.height);
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
	              }
	          });			
			  $("#upd_file").val("");
			  return false;
		  });

	//when sponsor edit click, popup window and fill in content
	$(".TableEdt").live('click', function(){
		showCE("espon");
		var id=$(this).attr('id');
		$("#upd_sid").val(id);
		$("#upd_cname").val($("#Cname"+id).text());
		$("#upd_url").val($("#Cname"+id).attr('href'));
		//$("#upd_url").val($("#Url"+id).text());
				
		var pic_upd_src=$("#Img"+id).attr("src");
	    if (pic_upd_src != "../image/default/default_evn_pic.png")
	    {
  	    	jcrop_api2.setImage(pic_upd_src);
			$("#upd_target").attr("src", pic_upd_src);
			$("#upd_x1").val("0");
			$("#upd_y1").val("0");
			$("#upd_x2").val("9.9");
			$("#upd_y2").val("9.9");					
	    }
	    else
	    {
  	    	jcrop_api2.setImage("../image/default/default_pic_gray1x1.png");
			$("#upd_target").attr("src", "../image/default/default_pic_gray1x1.png");
			$("#upd_x1").val("");
			$("#upd_y1").val("");
			$("#upd_x2").val("");
			$("#upd_y2").val("");
	    }
	});
	
	// update sponsor
	  $("#btnUpdSpon").click(function() {		  
		  var upd=$("#upd").val(); 
		  var upd_sid=$("#upd_sid").val(); 
		  var upd_cid=$("#upd_cid").val(); 		  
		  var upd_cname=$.trim($("#upd_cname").val());
		  var upd_url=$.trim($("#upd_url").val()); 
		  var upd_x1=$("#upd_x1").val(); 
		  var upd_y1=$("#upd_y1").val(); 
		  var upd_x2=$("#upd_x2").val(); 
		  var upd_y2=$("#upd_y2").val();	
		  var upd_filename=$("#upd_target").attr("src");
		  if (upd_filename=="../image/default/default_pic_gray1x1.png")
		  {
			  upd_x1=0; 
			  upd_y1=0; 
			  upd_x2=0; 
			  upd_y2=0;			  
		  }
					
		  //validate data
		  if (upd_cname=="")
		  {	  alert("<?php echo $a_miss_cname ?>");
			  return false;
		  }		  
		  var dataString='upd='+upd+'&upd_sid='+upd_sid+'&upd_cid='+upd_cid+'&upd_cname='+upd_cname+'&upd_url='+upd_url+
		  '&upd_x1='+upd_x1+'&upd_y1='+upd_y1+'&upd_x2='+upd_x2+'&upd_y2='+upd_y2+'&upd_filename='+upd_filename;	
	      $.ajax({  
		      type: "POST",  
		      url: "updSponsor.inc.php",  
		      data: dataString,  
		      dataType: 'json',  
		      success: function(data) {
			   	  var sid=$.trim(data.error);
			      if (sid > 0)
			      {
					$("#Img"+sid).attr("src", data.img);
			        $("#Cname"+sid).text(upd_cname);
					$("#Cname"+sid).attr('href', upd_url);
					
				    $("#upd_x1").val("");
					$("#upd_y1").val("");
					$("#upd_x2").val("");
					$("#upd_y2").val("");	
					dismissCE('espon');		        			      
				  }
			      else if (sid==-1 || sid==-3) {alert("<?php echo $a_miss_all?>");}
			      else if (sid==-2) {alert("<?php echo $a_exist ?>");}
			      //else if (sid==-4) {}
			      else if (sid==-5) {window.location.replace("../default/login.php");}
			      else if (sid==-6) {alert("<?php echo $a_fmt_all?>");}
		      }  
		    });
		    return false;
	});   
	    
    var jcrop_api1;
    $('#ins_target').Jcrop({
      onChange: updatePreview1,
      onSelect: updatePreview1,
      boxWidth: 320, 
      boxHeight: 240,
      bgOpacity: 0.3
    },function(){
      // Store the API in the jcrop_api variable
      jcrop_api1=this;
    });
    function updatePreview1(c)
    {
      $('#ins_x1').val(c.x);
	  $('#ins_y1').val(c.y);
	  $('#ins_x2').val(c.x2);
	  $('#ins_y2').val(c.y2);    
    };  
    
	//ajax upload sponsor ins image
	  $("#ins_file").live('change',function() {
			$("#ins_x1").val("");
			$("#ins_y1").val("");
			$("#ins_x2").val("");
			$("#ins_y2").val("");
		    var fileName=$("#ins_filename").val();
		    if($("#ins_file").val()=="")
		    {
		      alert("<?php echo $a_sel_pic ?>");
		      return false;
		    }
		    $.ajaxFileUpload(	
	        {				       
              url:"../utils/AjaxUploadImg.inc.php",
              secureuri:false,
              fileElementId:'ins_file',
              data:{'fileElement':'ins_file', 'fileSize':'1', 'fileName':fileName},
              dataType:'json', 
              success: function (data)
              {
              	if (data.error==0)
              	{
					jcrop_api1.setImage(data.url);
					$("#ins_target").attr("src", data.url);
					$("#ins_x1").val("0");
					$("#ins_y1").val("0");
					$("#ins_x2").val(data.width);
					$("#ins_y2").val(data.height);
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
              }
          });			
		  $("#ins_file").val("");
		  return false;
	  });

	//Cancel insert
	$("#btnInsCancel").click(function() {
		var default_img="../image/default/default_pic_gray1x1.png";
		jcrop_api1.setImage(default_img);
		$("#ins_target").attr("src", default_img);
	    $("#ins_x1").val("");
		$("#ins_y1").val("");
		$("#ins_x2").val("");
		$("#ins_y2").val("");
		$("#ins_cname").val(""); 
	  	$("#ins_url").val(""); 
		return false;
	});  
	
	// insert sponsor
	$("#btnInsSpon").click(function() {		  
		  var ins=$("#ins").val(); 
		  var ins_cid=$("#ins_cid").val(); 
		  var ins_cname=$.trim($("#ins_cname").val()); 
		  var ins_url=$.trim($("#ins_url").val()); 
		  var ins_x1=$("#ins_x1").val(); 
		  var ins_y1=$("#ins_y1").val(); 
		  var ins_x2=$("#ins_x2").val(); 
		  var ins_y2=$("#ins_y2").val();		  
		  var ins_filename=$("#ins_target").attr("src");
		  if (ins_filename=="../image/default/default_pic_gray1x1.png")
		  {
			  ins_x1=0; 
			  ins_y1=0; 
			  ins_x2=0; 
			  ins_y2=0;			  
		  }
					
		  //validate data
		  if (ins_cname=="")
		  {	  alert("<?php echo $a_miss_cname ?>");
			  return false;
		  }  
			var dataString='ins='+ins+'&ins_cid='+ins_cid+'&ins_cname='+ins_cname+'&ins_url='+ins_url+
			'&ins_x1='+ins_x1+'&ins_y1='+ins_y1+'&ins_x2='+ins_x2+'&ins_y2='+ins_y2+'&ins_filename='+ins_filename;	

			$.ajax({  
			      type: "POST",  
			      url: "insSponsor.inc.php",  
			      data: dataString,  
			      dataType: 'json',  
			      success: function(data) {
				   	  var sid=$.trim(data.error);
				      if (sid > 0)
				      {				    				        
				        $('#ListTable tr:last').after('<tr style="height:80px;" class="odd" id="Tr'+sid+'"><td align="center"><img id="Img'+sid+'" style="vertical-align:middle;max-height:70px;" src="'+data.img+'" /></td>'+
					    '<td><a id="Cname'+sid+'" href="'+ins_url+'" target="_blank">'+ins_cname+'</a></td>'+
						'<td class="icon"><img id="'+sid+'" class="TableEdt" type="image" title="<?php echo $l_edt_title?>" src="../image/edit.png" /></td>'+
						'<td class="icon"><img id="'+sid+'" class="TableDel" type="image" title="<?php echo $l_del_title?>" src="../image/error.png" /></td></tr>');
								        
					  	$("#ins_cname").val(""); 
					  	$("#ins_url").val(""); 

						var default_img="../image/default/default_pic_gray1x1.png";
						jcrop_api1.setImage(default_img);
						$("#ins_target").attr("src", default_img);
						
					    $("#ins_x1").val("");
						$("#ins_y1").val("");
						$("#ins_x2").val("");
						$("#ins_y2").val("");
				      }
				      else if (sid==-1 || sid==-3) {alert("<?php echo $a_miss_all?>");}
				      else if (sid==-2) {alert("<?php echo $a_exist ?>");}
				      else if (sid==-4) {}
				      else if (sid==-5) {window.location.replace("../default/login.php");}
				      else if (sid==-6) {}// insert fail
			      }  
			    });
			    return false;
		  }); 

	//delete sponsor
	$(".TableDel").live('click', function(){
		var sid=$(this).attr('id');
		var cid=$("#ins_cid").val(); 
		var dataString='sid='+sid+'&cid='+cid;
		if(confirm('<?php echo $l_del_confirm ?>'))
		{
			$.ajax({
			type: "POST",
			url: "delSponsor.inc.php",
			data: dataString,
			success: function(data){
					if( data==0) // success
					{
						$("#Tr"+sid).fadeOut("slow");
					}
					else if( data==1) // session expired
					{window.location.replace("../default/login.php");}	
					else if( data==2) // delete fail
					{}	
					else if( data==3) // no input
					{}
				}
			});
		}
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
<div id="content" style="margin:auto;width:98%;min-height:700px;" align="center">
<table id="ListTable" class="Conf" cellspacing='0' cellpadding='0'>
<tr id="head"><th><?php echo $l_h_img?></th><th><?php echo $l_h_cname?></th><th></th><th></th></tr>
<?php
$sponsor = Sponsor::getSponsorListByCid($conf->getCid());
foreach ($sponsor as $spon) 
{	
	echo '<tr style="height:80px;" class="odd" id="Tr'.$spon->getSid().'"><td align="center"><img id="Img'.$spon->getSid().'" style="vertical-align:middle;max-height:70px;" src="'.$spon->img.'" /></td>'.
	'<td><a id="Cname'.$spon->getSid().'" href="'.$spon->url.'" target="_blank">'.$spon->cname.'</a></td>'.
	'<td class="icon"><img id="'.$spon->getSid().'" class="TableEdt" type="image" title="'.$l_edt_title.'" src="../image/edit.png" /></td>'.
	'<td class="icon"><img id="'.$spon->getSid().'" class="TableDel" type="image" title="'.$l_del_title.'" src="../image/error.png" /></td></tr>';
}
?>
</table>
<table>
<tr>
<td>
<div style="width:320px;height:240px;border:1px #999 solid;background:#AAA;display:table-cell;vertical-align:middle;" align="center">
<img src="../image/default/default_pic_gray1x1.png" id="ins_target" />
</div>
</td>
<td rowspan=2 valign="middle">
<form id="ins_form" name="ins_form" method="post" enctype="multipart/form-data" action="insSponsor.inc.php" accept-charset="UTF-8">
<table>
<tr align="left">	
<td align="right"><label class="text"><?php echo $l_t_cname?></label></td>
<td><input id="ins_cname" name="ins_cname" style="width:160px;" type="text" /><label class="label_required">*</label></td>
</tr>
<tr align="left">	
<td align="right"><label class="text"><?php echo $l_t_url?></label></td>
<td><input id="ins_url" name="ins_url" style="width:160px;" type="text" /></td>
</tr>
<tr>
<td colspan="2" align="right">
<input id="btnInsSpon" class="btn" type="button" value="<?php echo $l_submit ?>"></input>
<input id="btnInsCancel" class="btn" type="reset" value="<?php echo $l_cancel ?>"></input>
</td>
</tr>
</table>
<input type="hidden" id="ins" name="ins" value="1" />
<input type="hidden" id="ins_cid" name="ins_cid" value="<?php echo $conf->getCid() ?>" />
</form>
</td>
</tr>
<tr>
<td align="left">
<input type="hidden" id="ins_x1" name="ins_x1" />
<input type="hidden" id="ins_y1" name="ins_y1" />
<input type="hidden" id="ins_x2" name="ins_x2" />
<input type="hidden" id="ins_y2" name="ins_y2" />
<form id="ins_upload_form" name="ins_upload_form" method="post" action="../utils/AjaxUploadImg.inc.php" enctype="multipart/form-data">
<input type="hidden" id="ins_filename" name="ins_filename" value="../tmp/temp_spon_img.<?php echo $conf->getCid()?>" />
<label class="text"><?php echo $l_file_size ?></label>
<span class="uploadImgSpan">
<input type="file" name="ins_file" id="ins_file" size="1" />
<input type="button" class="btnfile" value="<?php echo $l_browse_file ?>"></input></span>
</form>
</td>     
</tr>
</table>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
<div id="screen"></div>
<div id="espon" class="cevent round_border_20" style="min-height:450px;position:normal;z-index:2000;" >
<div id="cbutton" onclick="dismissCE('espon')"></div>
<div style="padding:20px 20px 20px 20px;margin-top:20px;" align="center">
<table>
<tr>
<td>
<div style="width:320px;height:240px;border:1px #999 solid;background:#AAA;display:table-cell;vertical-align:middle;" align="center">
<img src="../image/default/default_pic_gray1x1.png" id="upd_target" />
</div>
</td>
</tr>
<tr align="left">
<td>
<input type="hidden" id="upd_x1" name="upd_x1" />
<input type="hidden" id="upd_y1" name="upd_y1" />
<input type="hidden" id="upd_x2" name="upd_x2" />
<input type="hidden" id="upd_y2" name="upd_y2" />
<form id="upd_upload_form" name="upd_upload_form" method="post" action="../utils/AjaxUploadImg.inc.php" enctype="multipart/form-data">
<input type="hidden" id="upd_filename" name="upd_filename" value="../tmp/temp_spon_img_upd.<?php echo $conf->getCid()?>" />
<label class="text"><?php echo $l_file_size ?></label>
<span class="uploadImgSpan">
<input type="file" name="upd_file" id="upd_file" size="1" />
<input type="button" class="btnfile" value="<?php echo $l_browse_file ?>"></input></span>
</form>
</td>     
</tr>
</table>
<form id="upd_form" name="upd_form" method="post" enctype="multipart/form-data" action="updSponsor.inc.php" accept-charset="UTF-8">
<table>
<tr align="left">	
<td align="right"><label class="text"><?php echo $l_t_cname?></label></td>
<td><input id="upd_cname" name="upd_cname" style="width:160px;" type="text" /><label class="label_required">*</label></td>
</tr>
<tr align="left">
<td align="right"><label class="text"><?php echo $l_t_url?></label></td>
<td><input id="upd_url" name="upd_url" style="width:160px;" type="text" /></td>
</tr>
<tr>
<td colspan="2" align="right">
<input id="btnUpdSpon" class="btn" type="button" value="<?php echo $l_save ?>"></input>
<input class="btn" type="button" onclick="dismissCE('espon')" value="<?php echo $l_cancel ?>"></input>
</td>
</tr>
</table>
<input type="hidden" id="upd" name="upd" value="1" />
<input type="hidden" id="upd_cid" name="upd_cid" value="<?php echo $conf->getCid() ?>" />
<input type="hidden" id="upd_sid" name="upd_sid" value="" />
</form>
</div>
</div>
</body>
</html>