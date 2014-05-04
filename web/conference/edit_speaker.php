<?php
include_once ("../_obj/User.php");
include_once ("../_obj/UserT.php");
include_once ("../_obj/Conference.php");
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
	$_SESSION['_confsEdit']=array();
}

if( !isset($_SESSION['_confsEdit'][$_GET['url']]) )
{
	$conf=Conference::getConfInfo($_GET['url']);
	if($conf->status == 'C')
	{
		header( 'Location: ../conference/index.php' );
		exit;
	}
	$_SESSION['_confsEdit'][$_GET['url']]=$conf;
}

$conf=$_SESSION['_confsEdit'][$_GET['url']];

//============================================= Language ==================================================

if($_SESSION['_language']=='en') {
	$l_title="Speaker | ";
	$l_langugage='en';
	$l_save="Save";
	$l_submit="Submit";
	$l_cancel="Cancel";
	$l_del_confirm="Confirm delete?";
	$l_edt_title="Edit";
	$l_del_title="Delete";
	$l_file_size="(Support jpg、png、gif, <span style='color:red'>less than 1MB</span>) ";
	$l_file_preview="Preview";
	$l_h_img="Icon";
	$l_h_fname="Full Name";
	$l_h_comp="Company";
	$l_h_title="Title";
	$l_h_cell="Cellphone";
	$l_h_email="Email";
	$l_h_desc="Self Description";
	$l_desc="Detail Information";
	$l_t_fname="Full Name: ";
	$l_t_comp="Company: ";
	$l_t_title="Title: ";
	$l_t_cell="Cellphone: ";
	$l_t_email="Email: ";
	$l_t_desc="Self Description: ";
	$a_sel_pic="Please select an image before uploading";
	$a_pic_size="Please upload jpg、png、gif image which is less than 1MB";
	$a_pic_err="Image contains error, please upload another one";
	$a_pic_fail="Upload fails, please try again";
	$a_exist="Cellphone number is already in the speaker list, information is updated";
	$a_miss_cell="Please enter cellphone";
	$a_miss_email = "Please enter email";
	$a_miss_fullname="Please enter full name";
	$a_miss_all="Please enter all required information";
	$a_fmt_cell="Invalid cellphone number";
	$a_fmt_email="Invalid email address";
	$a_fmt_all="Please do not enter special character in all fields";
	$l_browse_file="Browse File";
}
else if($_SESSION['_language']=='zh') {
	$l_title="演讲嘉宾 | ";	
	$l_langugage='zh';
	$l_save="保存";
	$l_submit="添加";
	$l_cancel="取消";
	$l_del_confirm="确认删除？";
	$l_edt_title="编辑";
	$l_del_title="删除";
	$l_file_size="(支持jpg、png、gif，<span style='color:red'>小于 1MB</span>) ";
	$l_file_preview="预览";
	$l_h_img="头像";
	$l_h_fname="真实姓名";
	$l_h_comp="公司";
	$l_h_title="职位";
	$l_h_cell="手机";
	$l_h_email="邮箱";
	$l_h_desc="个人介绍";
	$l_desc="详细信息";
	$l_t_fname="真实姓名：";
	$l_t_comp="公司：";
	$l_t_title="职位：";
	$l_t_cell="手机：";
	$l_t_email="邮箱：";
	$l_t_desc="个人介绍：";	
	$a_sel_pic="请选择你要上传的图片";
	$a_pic_size="请上传小于1MB的jpg、png、gif格式图片";
	$a_pic_err="上传的图片有误，请选择正确图片上传";
	$a_pic_fail="上传失败，请重试一次";
	$a_exist="此手机号码已经在演讲嘉宾列表中了，信息已被更新";
	$a_miss_cell="请填写手机";
	$a_miss_email="请填写邮件";
	$a_miss_fullname="请填写真实姓名";
	$a_miss_all="请填写所有必填信息";
	$a_fmt_cell="手机号码格式有误";
	$a_fmt_email="邮箱格式有误";
	$a_fmt_all="请不要在所填信息中使用特殊符号";
	$l_browse_file="选择文件";
}

//=========================================================================================================

$page=4;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title.$conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<style type="text/css">
html,body{margin:0;padding:0;}
img.TblEdt{cursor:pointer;border:0 none;}
img.TblDel{cursor:pointer;border:0 none;}
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
	 var jcrop_api2, boundx2, boundy2;
	    $('#upd_target').Jcrop({
	      onChange: updatePreview2,
	      onSelect: updatePreview2,
	      boxWidth: 320, 
	      boxHeight: 240,
	      aspectRatio: 1
	    },function(){
	      // Use the API to get the real image size
	      var bounds2=this.getBounds();
	      boundx2=bounds2[0];
	      boundy2=bounds2[1];
	      // Store the API in the jcrop_api variable
	      jcrop_api2=this;
	    });
	    function updatePreview2(c)
	    {
	      if (parseInt(c.w) > 0)
	      {
	          var rx=100 / c.w;
	          var ry=100 / c.h;
	        $('#upd_preview').css({
	          width: Math.round(rx * boundx2) + 'px',
	          height: Math.round(ry * boundy2) + 'px',
	          marginLeft: '-' + Math.round(rx * c.x) + 'px',
	          marginTop: '-' + Math.round(ry * c.y) + 'px'
	        });
	      }
	      $('#upd_x1').val(c.x);
		  $('#upd_y1').val(c.y);
		  $('#upd_x2').val(c.x2);
		  $('#upd_y2').val(c.y2);    
	    };  

		//ajax upload upd image
		  $("#upd_file").live('change',function() {
			  $("#progress2").attr("style", "display:inline");
				$("#upd_x1").val("");
				$("#upd_y1").val("");
				$("#upd_x2").val("");
				$("#upd_y2").val("");
			    var fileName=$("#upd_upload_filename").val();
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
						$("#upd_preview").attr("src", data.url);
						boundx2=data.width;
						boundy2=data.height;
						var ratio=1;
						if (boundx2 > boundy2) {ratio=100 / boundy2;}
						else {ratio=100 / boundx2; }				        
						$('#upd_preview').css({width: Math.round(ratio * boundx2)+'px', height: Math.round(ratio * boundy2)+'px', marginLeft: '0px', marginTop: '0px'});
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
		  			  $("#progress2").attr("style", "display:none");
	              }
	          });			
			  $("#upd_file").val("");
			  return false;
		  });

	//when edit click, popup window and fill in content
	$(".TblEdt").live('click', function(){
		showCE("epopup");
		var id=$(this).attr('id');
		$("#upd_uid").val(id);
		$("#upd_cell").val($("#TblCell"+id).text());
		$("#upd_email").val($("#TblEmail"+id).text());
		$("#upd_fullname").val($("#TblFname"+id).text());
		$("#upd_comp").val($("#TblComp"+id).text());
		$("#upd_title").val($("#TblTitle"+id).text());
		KE.html('upd_desc', $("#TblBody"+id).html());
		
		var pic_upd_src=$("#TblImg"+id).attr("src");
		
	    if (pic_upd_src != "../image/default/default_pro_pic.png")
	    {
  	    	jcrop_api2.setImage(pic_upd_src);
			$("#upd_target").attr("src", pic_upd_src);	
			$("#upd_preview").attr("src", pic_upd_src);
			$('#upd_preview').css({width: '100px', height: '100px', marginLeft: '0px', marginTop: '0px'});
			$("#upd_x1").val("0");
			$("#upd_y1").val("0");
			$("#upd_x2").val("100");
			$("#upd_y2").val("100");
			boundx2=100;
			boundy2=100;			  		
	    }
	    else
	    {
  	    	jcrop_api2.setImage("../image/default/default_pic_gray1x1.png");
			$("#upd_target").attr("src", "../image/default/default_pic_gray1x1.png");	
			$("#upd_preview").attr("src", pic_upd_src);
			$('#upd_preview').css({width: '100px', height: '100px', marginLeft: '0px', marginTop: '0px'});
			$("#upd_x1").val("");
			$("#upd_y1").val("");
			$("#upd_x2").val("");
			$("#upd_y2").val("");
			boundx2=1;
			boundy2=1;
	    }
	});
	
	// update
	  $("#btnUpd").click(function() {
		  document.getElementById("upd_desc").value=KE.util.getData('upd_desc');
		  var upd_fullname=$.trim($("#upd_fullname").val()); 
		  var upd_cell=$.trim($("#upd_cell").val()); 
		  $("#upd_filename").val($("#upd_preview").attr("src"));
		  
		  if ($("#upd_preview").attr("src")=="../image/default/default_pro_pic.png")
		  {
			  $("#upd_x1").val("");
			  $("#upd_y1").val("");
			  $("#upd_x2").val("");
			  $("#upd_y2").val("");		  
		  }
					
		  //validate data
		  if (upd_fullname=="")
		  {	  
			  alert("<?php echo $a_miss_fullname ?>");
			  return false;
		  }
		  else if (upd_cell!="" && validateCellFormat(upd_cell)==false)
	      {
		      alert("<?php echo $a_fmt_cell ?>");
	          return false;  
          }

		  $('#upd_form').submit();
	});   
	    
    var jcrop_api1, boundx1, boundy1;
    $('#ins_target').Jcrop({
      onChange: updatePreview1,
      onSelect: updatePreview1,
      boxWidth: 320, 
      boxHeight: 240,
      aspectRatio: 1
    },function(){
      // Use the API to get the real image size
      var bounds1=this.getBounds();
      boundx1=bounds1[0];
      boundy1=bounds1[1];
      // Store the API in the jcrop_api variable
      jcrop_api1=this;
    });
    function updatePreview1(c)
    {
      if (parseInt(c.w) > 0)
      {
          var rx=100 / c.w;
          var ry=100 / c.h;
        $('#ins_preview').css({
          width: Math.round(rx * boundx1) + 'px',
          height: Math.round(ry * boundy1) + 'px',
          marginLeft: '-' + Math.round(rx * c.x) + 'px',
          marginTop: '-' + Math.round(ry * c.y) + 'px'
        });
      }
      $('#ins_x1').val(c.x);
	  $('#ins_y1').val(c.y);
	  $('#ins_x2').val(c.x2);
	  $('#ins_y2').val(c.y2);    
    };  
    
	//ajax upload ins image
	  $("#ins_file").live('change',function() {
		  $("#progress1").attr("style", "display:inline");
			$("#ins_x1").val("");
			$("#ins_y1").val("");
			$("#ins_x2").val("");
			$("#ins_y2").val("");
		    var fileName=$("#ins_upload_filename").val();
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
					$("#ins_preview").attr("src", data.url);
					boundx1=data.width;
					boundy1=data.height;
					var ratio=1;
					if (boundx1 > boundy1) {ratio=100 / boundy1;}
					else {ratio=100 / boundx1; }				        
					$('#ins_preview').css({width: Math.round(ratio * boundx1)+'px', height: Math.round(ratio * boundy1)+'px', marginLeft: '0px', marginTop: '0px'});
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
        		  $("#progress1").attr("style", "display:none");
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
		$("#ins_preview").attr("src", "../image/default/default_pro_pic.png");
		$('#ins_preview').css({width: '100px', height: '100px', marginLeft: '0px', marginTop: '0px'});
	    $("#ins_x1").val("");
		$("#ins_y1").val("");
		$("#ins_x2").val("");
		$("#ins_y2").val("");
		$("#ins_cell").val(""); 
		$("#ins_email").val(""); 
	  	$("#ins_fullname").val(""); 
	  	$("#ins_comp").val(""); 
	  	$("#ins_title").val(""); 
		KE.html('ins_desc', "");
		return false;
	});

	  // insert
	  $("#btnIns").click(function() {
  	      document.getElementById("ins_desc").value=KE.util.getData('ins_desc');  
		  var ins_cell=$.trim($("#ins_cell").val()); 
		  var ins_fullname=$.trim($("#ins_fullname").val()); 
		  var ins_email=$.trim($("#ins_email").val()); 
		 
		  $("#ins_filename").val($("#ins_target").attr("src"));
		  if ($("#ins_preview").attr("src")=="../image/default/default_pro_pic.png")
		  {
			  $("#ins_x1").val("");
			  $("#ins_y1").val("");
			  $("#ins_x2").val("");
			  $("#ins_y2").val("");
		  }

		  //validate data
		  if (ins_email=="")
		  {	  alert("<?php echo $a_miss_email ?>");
			  return false;
		  }
		  else if (ins_fullname=="")
		  {	  alert("<?php echo $a_miss_fullname ?>");
			  return false;
		  }
		  else if (validateEmailFormat(ins_email)==false)
	      {   alert("<?php echo $a_fmt_email?>");
	          return false;  
          }
		  else if (ins_cell!="" && validateCellFormat(ins_cell)==false)
	      {   alert("<?php echo $a_fmt_cell?>");
	          return false;  
          }
		  
		  $('#ins_form').submit();
	});    

	//populate info
	  $(function() {  
	  	  $("#ins_email").change(function() {  		  
	  		  var ins_email=$.trim($("#ins_email").val());
			  var ins_cid=$("#ins_cid").val();
			  
	  		  if (ins_email=="") { return false;  }
	  		  var dataString='ins_email='+ins_email+'&ins_cid='+ins_cid;
	  		  $.ajax({  
	  		      type: "POST",  
	  		      url: "selUser.inc.php",  
	  		      data: dataString,
			      dataType: 'json', 
	  		      success: function(data) {	    		
	  			      if (data.error==-3 || data.error==-4)
	  			      {			      
	  			      }
	  			      else if (data.error==-5)
	  			      {
	  			      	window.location.replace("../default/login.php");
	  			      }
	  			      else
	  			      {	  					  
		  				$("#ins_fullname").val(data.fname);
		  				$("#ins_cell").val(data.cell);
	  				    $("#ins_comp").val(data.comp)
	  				    $("#ins_title").val(data.title);;
	  				    KE.html('ins_desc', data.desc);
		  				if (data.pic != "../image/default/default_pro_pic.png")
	  				    {
		  				    jcrop_api1.setImage(data.pic);
	  						$("#ins_target").attr("src", data.pic);	
	  						$("#ins_preview").attr("src", data.pic);
	  						$('#ins_preview').css({width: '100px', height: '100px', marginLeft: '0px', marginTop: '0px'});
	  						$("#ins_x1").val("0");
	 						$("#ins_y1").val("0");
	 						$("#ins_x2").val("100");
	 						$("#ins_y2").val("100");
	 						boundx1=100;
	 						boundy1=100;			  		
	  				    }				
	  			      }			       
	  		      }  
	  		  });  
	  		  return false;
	  	  });  
	  	});
	//delete
	$(".TblDel").live('click', function(){
		var uid=$(this).attr('id');
		var cid=$("#ins_cid").val(); 
		var dataString='uid=' + uid + '&cid=' + cid;
		if(confirm('<?php echo $l_del_confirm ?>'))
		{
			$.ajax({
			type: "POST",
			url: "delSpeaker.inc.php",
			data: dataString,
			success: function(data){
					if( data==0) // success
					{
						$("#TblTr"+uid).fadeOut("slow");
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


	// hide_show
	$(".show_hide").live('click',function(){
		var id=$(this).attr('id').replace("TblLink","");
		//$("#TblBody"+id).slideToggle();
		showCE("detailpopup");
		$("#contentpopup").html($("#TblBody"+id).html());
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
<tr id="head"><th><?php echo $l_h_img?></th><th><?php echo $l_h_fname?></th><th><?php echo $l_h_comp?></th><th><?php echo $l_h_title?></th><th><?php echo $l_h_cell?></th><th><?php echo $l_h_email?></th><th><?php echo $l_h_desc?></th><th></th><th></th></tr>
<?php
$ids='';
$userIds=Conference::getConfSpeakerIds($conf->getCid());

$users=array();
while($row=mysql_fetch_array($userIds, MYSQL_ASSOC))
{
	$users[$row['uid']]=null;
	$ids=$ids.$row['uid'].', ';
}
$ids=$ids.'0';

$userT=UserT::getRangeConfUsers($conf->getCid(), $ids);
foreach($userT as $user)
{
	$users[$user->getUid()]=$user;
}

$ids='';
foreach ($users as $k=>$v)
{
	if( !isset($users[$k]) )
		$ids=$ids.$k.', ';
}
$ids=$ids.'0';

$userUser=User::getRangeUsers($ids);
foreach($userUser as $user)
{
	$users[$user->getUid()]=$user;
}
foreach ($users as $user) 
{
	if ($user->description=="") {$desc='<td><div id="TblBody'.$user->getUid().'" style="display:none"></div></td>';}
	else {$desc='<td><a href="#" class="show_hide" id="TblLink'.$user->getUid().'">'.$l_desc.'</a><div id="TblBody'.$user->getUid().'" style="display:none">'.$user->description.'</div></td>';}	
	echo '<tr class="odd" id="TblTr'.$user->getUid().'"><td class="icon"><img id="TblImg'.$user->getUid().'" style="vertical-align:middle;width:30px;height:30px;" src="'.$user->pic.'" /></td>'.
	'<td style="min-width:65px;"><label id="TblFname'.$user->getUid().'">'.$user->fullname.'</label></td>'.
	'<td><label id="TblComp'.$user->getUid().'">'.$user->company.'</label></td>'.
	'<td><label id="TblTitle'.$user->getUid().'">'.$user->title.'</label></td>'.
	'<td><label id="TblCell'.$user->getUid().'">'.$user->cell.'</label></td>'.
	'<td><label id="TblEmail'.$user->getUid().'">'.$user->getEmail().'</label></td>'.
	$desc.
	'<td class="icon"><img id="'.$user->getUid().'" class="TblEdt" type="image" title="'.$l_edt_title.'" src="../image/edit.png" /></td>'.
	'<td class="icon"><img id="'.$user->getUid().'" class="TblDel" type="image" title="'.$l_del_title.'" src="../image/error.png" /></td></tr>';
}
?>
</table>
<table>
<tr>
<td rowspan=2>
<form id="ins_form" name="ins_form" method="post" enctype="multipart/form-data" action="insSpeaker.inc.php" accept-charset="UTF-8">
<table>
<tr align="left">	
<td align="right"><label class="text"><?php echo $l_t_email?></label></td>
<td><input id="ins_email" name="ins_email" style="width:200px;" type="text" /><label class="label_required">*</label></td>
</tr>
<tr align="left">
<td align="right"><label class="text"><?php echo $l_t_fname?></label></td>
<td><input id="ins_fullname" name="ins_fullname" style="width:200px;" type="text" /><label class="label_required">*</label></td>
</tr>
<tr align="left">	
<td align="right"><label class="text"><?php echo $l_t_cell?></label></td>
<td><input id="ins_cell" name="ins_cell" style="width:200px;" type="text" /></td>
</tr>
<tr align="left">
<td align="right"><label class="text"><?php echo $l_t_comp?></label></td>
<td><input id="ins_comp" name="ins_comp" style="width:200px;" type="text" /></td>
</tr>
<tr align="left">
<td align="right"><label class="text"><?php echo $l_t_title?></label></td>
<td><input id="ins_title" name="ins_title" style="width:200px;" type="text" /></td>
</tr>
<tr align="left">
<td align="right">
<script charset="utf-8" src="../kindeditor/kindeditor-min.js"></script>
<script>KE.show({ id : 'ins_desc', allowFileManager : false, allowUpload : false });</script>
<label class="text"><?php echo $l_t_desc?></label></td>
<td><textarea id="ins_desc" name="ins_desc" style="resize:none;" rows=10 cols=45></textarea></td>
</tr>
</table>
<input type="hidden" id="ins" name="ins" value="1" />
<input type="hidden" id="ins_cid" name="ins_cid" value="<?php echo $conf->getCid(); ?>" />
<input type="hidden" id="ins_filename" name="ins_filename" value="" />
<input type="hidden" id="ins_page" name="ins_page" value="../conference/edit_speaker.php?url=<?php echo $conf->getUrl()?>" />
<input type="hidden" id="ins_x1" name="ins_x1" />
<input type="hidden" id="ins_y1" name="ins_y1" />
<input type="hidden" id="ins_x2" name="ins_x2" />
<input type="hidden" id="ins_y2" name="ins_y2" />
</form>
</td>
<td>
<div style="width:320px;height:240px;border:1px #999 solid;background:#ddd;display:table-cell;vertical-align:middle;" align="center">
<img src="../image/default/default_pic_gray1x1.png" id="ins_target" />
<img src="../image/progress-bar.gif" id="progress1" style="display:none;"/>
</div>
<form style="margin-top:10px;" id="ins_upload_form" name="ins_upload_form" method="post" action="../utils/AjaxUploadImg.inc.php" enctype="multipart/form-data">
<input type="hidden" id="ins_upload_filename" name="ins_upload_filename" value="../tmp/temp_img.<?php echo $conf->getCid()?>" />
<label class="text"><?php echo $l_file_size ?></label>
<span class="uploadImgSpan">
<input type="file" name="ins_file" id="ins_file" size="1" />
<input type="button" class="btnfile" value="<?php echo $l_browse_file ?>"></input></span>
</form>
</td>
<td align="center">
<label class="text"><?php echo $l_file_preview ?></label>
<div style="width:100px;height:100px;overflow:hidden;border:1px #999 solid;">
<img src="../image/default/default_pro_pic.png" id="ins_preview" />
</div>
</td>
</tr>
<tr align="left">
<td colspan=2>
</td>     
</tr>
<tr><td colspan=3 align="center">
<input id="btnIns" class="btn" type="button" value="<?php echo $l_submit ?>"></input>
<input id="btnInsCancel" class="btn" type="button" value="<?php echo $l_cancel ?>"></input></td>
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
<div id="epopup" class="cevent round_border_20" style="min-height:600px;position:normal;z-index:2000;" >
<div id="cbutton" onclick="dismissCE('epopup')"></div>
<div style="padding:0px 20px 20px 20px;margin-top:20px;" align="center">
<table>
<tr>
<td>
<div style="width:320px;height:240px;border:1px #999 solid;background:#ddd;display:table-cell;vertical-align:middle;" align="center">
<img src="../image/default/default_pic_gray1x1.png" id="upd_target" />
<img src="../image/progress-bar.gif" id="progress2" style="display:none;"/>
</div>
</td>
<td align="center">
<label><?php echo $l_file_preview ?></label>
<div style="width:100px;height:100px;overflow:hidden;border:1px #999 solid;">
<img src="../image/default/default_pro_pic.png" id="upd_preview" />
</div>
</td>
</tr>
<tr align="left">
<td colspan=2>
<form id="upd_upload_form" name="upd_upload_form" method="post" action="../utils/AjaxUploadImg.inc.php" enctype="multipart/form-data">
<input type="hidden" id="upd_upload_filename" name="upd_upload_filename" value="../tmp/temp_img_upd.<?php echo $conf->getCid()?>" />
<label class="text"><?php echo $l_file_size ?></label>
<span class="uploadImgSpan">
<input type="file" name="upd_file" id="upd_file" size="1" />
<input type="button" class="btnfile" value="<?php echo $l_browse_file ?>"></input></span>
</form>
</td>     
</tr>
</table>
<form style="margin-top:30px;" id="upd_form" name="upd_form" method="post" enctype="multipart/form-data" action="updSpeaker.inc.php" accept-charset="UTF-8">
<table>
<tr align="left">	
<td align="right"><label class="text"><?php echo $l_t_email?></label></td>
<td><input id="upd_email" name="upd_email" style="width:200px;background-color:#cccccc;" type="text" readonly="readonly" /><label class="label_required">*</label></td>
<td align="left" style="padding-left:20px">
<script charset="utf-8" src="../kindeditor/kindeditor-min.js"></script>
<script>KE.show({ id : 'upd_desc', allowFileManager : false, allowUpload : false });</script>
<label class="text"><?php echo $l_t_desc?></label></td>
<td rowspan="5" valign="top"><textarea id="upd_desc" name="upd_desc" style="width:530px;height:200px;"></textarea></td>
</tr>
<tr align="left">
<td align="right"><label class="text"><?php echo $l_t_fname?></label></td>
<td><input id="upd_fullname" name="upd_fullname" style="width:200px;" type="text" /><label class="label_required">*</label></td>
<td></td>
</tr>
<tr align="left">
<td align="right"><label class="text"><?php echo $l_t_cell?></label></td>
<td><input id="upd_cell" name="upd_cell" style="width:200px;" type="text" /></td>
<td></td>
</tr>
<tr align="left">
<td align="right"><label class="text"><?php echo $l_t_comp?></label></td>
<td><input id="upd_comp" name="upd_comp" style="width:200px;" type="text" /></td>
<td></td>
</tr>
<tr align="left">
<td align="right"><label class="text"><?php echo $l_t_title?></label></td>
<td><input id="upd_title" name="upd_title" style="width:200px;" type="text" /></td>
<td></td>
</tr>
<tr>
<td colspan="4" align="right">
<input id="btnUpd" class="btn" type="button" value="<?php echo $l_save ?>"></input>
<input class="btn" type="button" onclick="dismissCE('epopup')" value="<?php echo $l_cancel ?>"></input>
</td>
</tr>
</table>
<input type="hidden" id="upd" name="upd" value="1" />
<input type="hidden" id="upd_cid" name="upd_cid" value="<?php echo $conf->getCid(); ?>" />
<input type="hidden" id="upd_uid" name="upd_uid" value="" />
<input type="hidden" id="upd_filename" name="upd_filename" value="" />
<input type="hidden" id="upd_page" name="upd_page" value="../conference/edit_speaker.php?url=<?php echo $conf->getUrl()?>" />
<input type="hidden" id="upd_x1" name="upd_x1" />
<input type="hidden" id="upd_y1" name="upd_y1" />
<input type="hidden" id="upd_x2" name="upd_x2" />
<input type="hidden" id="upd_y2" name="upd_y2" />
</form>
</div>
</div>
<div id="detailpopup" class="cevent round_border_20" style="min-height:400px;height:auto;position:normal;z-index:2000;" >
<div id="cbutton" onclick="dismissCE('detailpopup')"></div>
<div style="padding:20px 20px 20px 20px;margin-top:20px;">
<div id="contentpopup"></div>
</div>
</div>
</body>
</html>