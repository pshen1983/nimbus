<?php
include_once ("../_obj/Invitation.php");
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
	$_SESSION['_confsEdit'] = array();
}

if( !isset($_SESSION['_confsEdit'][$_GET['url']]) )
{
	$conf = Conference::getConfInfo($_GET['url']);
	$_SESSION['_confsEdit'][$_GET['url']] = $conf;
}

$conf = $_SESSION['_confsEdit'][$_GET['url']];

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "Invitation | ";
	$l_langugage='en';
	$l_send="Send Invitation";
	$l_save="Save";
	$l_submit="Submit";
	$l_cancel="Cancel";
	$l_del_range="Delete Checked";
	$l_del_confirm="Confirm delete?";
	$l_invt_confirm="Are you sure to send invitation to all selected persons?";
	$l_edt_title="Edit";
	$l_del_title="Delete";
	$l_h_send="Send";
	$l_h_fname="Full Name";
	$l_h_comp="Company";
	$l_h_title="Title";
	$l_h_cell="Cellphone";
	$l_h_email="Email";
	$l_h_time="Sent Time";
	$l_t_fname="Full Name: ";
	$l_t_comp="Company: ";
	$l_t_title="Title: ";
	$l_t_cell="Cellphone: ";
	$l_t_email="Email: ";
	$a_miss_cell="Please enter cellphone";
	$a_miss_fullname="Please enter full name";
	$a_miss_all="Please enter all required information";
	$a_fmt_cell="Invalid cellphone number";
	$a_fmt_email="Invalid email address";
	$a_fmt_all="Please do not enter special character in all fields";
	$l_sent="Sent Invitation";
	$l_add_invt="Add Invitation";
	$l_imp_invt="Import Invitation";
	$l_browse_file="Browse A File To Upload: ";
	$l_browse_btn="Upload File";
	$l_note="Note: Imported file needs to meet 3 conditions:";
	$l_note_a="a) The 1st row must be Name, Cell, Email, Company, Title.";
	$l_note_b="b) Name and Cell are required, Cell must be 11 digits.";
	$l_note_c="c) Only allowed UTF-8 encoded csv files, column delimiter is comma.";
	$l_note_z="* Upload fail may be caused by empty required fields, invalid cell & email format.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "发出邀请 | ";
	$l_langugage='zh';
	$l_send="发送邀请";
	$l_save="保存";
	$l_submit="添加";
	$l_cancel="取消";
	$l_del_range="删除选中项";
	$l_del_confirm="确认删除？";
	$l_invt_confirm="确认发送邀请给所有选中的人？";
	$l_edt_title="编辑";
	$l_del_title="删除";
	$l_h_send="发送";	
	$l_h_fname="真实姓名";
	$l_h_comp="公司";
	$l_h_title="职位";
	$l_h_cell="手机";
	$l_h_email="邮箱";
	$l_h_time="发送时间";
	$l_t_fname="真实姓名：";
	$l_t_comp="公司：";
	$l_t_title="职位：";
	$l_t_cell="手机：";
	$l_t_email="邮箱：";	
	$a_miss_cell="请填写手机";
	$a_miss_fullname="请填写真实姓名";
	$a_miss_all="请填写所有必填信息";
	$a_fmt_cell="手机号码格式有误";
	$a_fmt_email="邮箱格式有误";
	$a_fmt_all="请不要在所填信息中使用特殊符号";
	$l_sent="已发送的邀请";
	$l_add_invt="添加邀请";
	$l_imp_invt="导入邀请";
	$l_browse_file="选择文件上传：";
	$l_browse_btn="上传文件";
	$l_note="提示：导入文件需满足三个条件：";
	$l_note_a="a) 文件第一行必须为姓名、手机、邮箱、公司、职位。";
	$l_note_b="b) 姓名、手机不能为空，手机格式为11位数字。";
	$l_note_c="c) 文件必须是编码为UTF-8的csv文件，并用英文逗号分隔开。";
	$l_note_z="* 上传失败的原因可能是必填项为空，手机、邮件格式有误。请不要在信息中使用逗号干扰分列。";
}

//=========================================================================================================

$page = 3; //add page number
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
</style>
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery.Jcrop.css" />
<script type="text/JavaScript" src="../js/conference.js"></script>
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>  
<script type="text/JavaScript" src="../js/jquery.Jcrop.min.js"></script>
<script type="text/JavaScript" src="../js/ajaxfileupload.js"></script>
<script type="text/JavaScript" src="../js/date.format.js"></script>

<script type="text/javascript">
jQuery(function($){
	//add invitation
	$("#btnAddInvt").click(function(){
		$("#addInvt").show();
		$("#impInvt").hide();
	});
	//import invitation
	$("#btnImpInvt").click(function(){
		$("#addInvt").hide();
		$("#impInvt").show();
	});		
	//checked invitation
	$("#TblChb").click(function(){
		$("#ListTable .TblChb").each(function(){
			var id = $(this).attr('id').replace("TblChb","");			
			if ($("#TblChb").attr("checked") == "checked")
			{
				$("#TblChb"+id).attr("checked",true);			
			}
			else
			{
				$("#TblChb"+id).attr("checked",false);					
			}				
		});
	});
	
	//send checked invitation
	$("#btnSend").click(function(){
		if(confirm('<?php echo $l_invt_confirm ?>'))
		{
			$("#ListTable .TblChb").each(function(){
				var id = $(this).attr('id').replace("TblChb","");
		
		  		if ($("#TblChb"+id).attr("checked") == "checked")
				{
					var send=$.trim($("#send").val()); 
					var send_cid=$.trim($("#send_cid").val()); 
					var fullname=$.trim($("#TblFname"+id).text()); 
					var comp=$.trim($("#TblComp"+id).text()); 
					var title=$.trim($("#TblTitle"+id).text()); 
					var cell=$.trim($("#TblCell"+id).text()); 
					var email=$.trim($("#TblEmail"+id).text());	  
	
					var dataString='send='+send+'&send_cid='+send_cid+'&id='+id;				
					$.ajax({  
					      type: "POST",  
					      url: "sendInvite.inc.php",  
					      data: dataString,
					      dataType: 'json',  
					      success: function(data) {
						   	  var id=$.trim(data.error);	
						      if (id > 0)
						      {
								$("#TblTr"+id).fadeOut("slow");
								$("#TblTr"+id).remove();
								
						        $('#ListTableSent tr:last').after('<tr class="odd" id="SentTblTr'+id+'"><td><label id="TblFname'+id+'">'+fullname+'</label></td>'+
								'<td><label id="SentTblComp'+id+'">'+comp+'</label></td>'+
								'<td><label id="SentTblTitle'+id+'">'+title+'</label></td>'+
								'<td><label id="SentTblCell'+id+'">'+cell+'</label></td>'+
								'<td><label id="SentTblEmail'+id+'">'+email+'</label></td>'+
						    	'<td><label id="SentTblTime'+id+'">'+dateFormat(new Date(), "yyyy-mm-dd, h:MM TT")+'</label></td></tr>');								
						      }
						      else if (id==-5) {window.location.replace("../default/login.php");}
					      }  
					});
				}
				else
				{
					//alert("not checked "+id);
				}
			});
		}
		return false;
	});
	
	//when edit click, popup window and fill in content
	$(".TblEdt").live('click', function(){
		showCE("epopup");
		var id=$(this).attr('id');
		$("#upd_id").val(id);
		$("#upd_cell").val($("#TblCell"+id).text());
		$("#upd_email").val($("#TblEmail"+id).text());
		$("#upd_fullname").val($("#TblFname"+id).text());
		$("#upd_comp").val($("#TblComp"+id).text());
		$("#upd_title").val($("#TblTitle"+id).text());
	});
	
	// update
	  $("#btnUpd").click(function() {		  
		  var upd=$("#upd").val(); 
		  var upd_cid=$("#upd_cid").val();	
		  var upd_id=$("#upd_id").val();		  
		  var upd_cell=$.trim($("#upd_cell").val());
		  var upd_email=$.trim($("#upd_email").val());
		  var upd_fullname=$.trim($("#upd_fullname").val()); 
		  var upd_comp=$.trim($("#upd_comp").val()); 
		  var upd_title=$.trim($("#upd_title").val());

		  //validate data
		  if (upd_fullname=="")
		  {   alert("<?php echo $a_miss_fullname ?>");
			  return false;
		  }
		  else if (upd_email!="" && validateEmailFormat(upd_email)==false)
	      {   alert("<?php echo $a_fmt_email ?>");
	          return false;  
          }
          
			var dataString='upd='+upd+'&upd_cid='+upd_cid+'&upd_id='+upd_id+'&upd_cell='+upd_cell+'&upd_email='+upd_email+'&upd_fullname='+upd_fullname+
			'&upd_comp='+upd_comp+'&upd_title='+upd_title;						
			$.ajax({  
			      type: "POST",  
			      url: "updInvite.inc.php",  
			      data: dataString,  
			      dataType: 'json',  
			      success: function(data) {
				   	  var id=$.trim(data.error);	
				      if (id > 0)
				      {					   
				          $("#TblEmail"+id).text(upd_email);
				          $("#TblFname"+id).text(upd_fullname);
				          $("#TblComp"+id).text(upd_comp);
				          $("#TblTitle"+id).text(upd_title);
						dismissCE('epopup');		        			      
					  }
				      else if (id==-1 || id==-3) {alert("<?php echo $a_miss_all?>");}
				      else if (id==-2) {alert("<?php echo $a_fmt_cell?>");}
				      //else if (sid==-4) {}
				      else if (id==-5) {window.location.replace("../default/login.php");}
				      else if (id==-7) {alert("<?php echo $a_fmt_all?>");}
				      else if (id==-8) {alert("<?php echo $a_fmt_email?>");}
			      }  
			    });
			    return false;
		  });   
   
	//Cancel insert
	$("#btnInsCancel").click(function() {
		$("#ins_cell").val(""); 
		$("#ins_email").val(""); 
	  	$("#ins_fullname").val(""); 
	  	$("#ins_comp").val(""); 
	  	$("#ins_title").val(""); 
		return false;
	});
// insert
	  $("#btnIns").click(function() {		  
		  var ins=$("#ins").val(); 
		  var ins_cid=$("#ins_cid").val(); 
		  var ins_cell=$.trim($("#ins_cell").val()); 
		  var ins_email=$.trim($("#ins_email").val()); 
		  var ins_fullname=$.trim($("#ins_fullname").val()); 
		  var ins_comp=$.trim($("#ins_comp").val()); 
		  var ins_title=$.trim($("#ins_title").val());
					
		  //validate data
		  if (ins_cell=="")
		  {	  alert("<?php echo $a_miss_cell ?>");
			  return false;
		  }
		  else if (ins_fullname=="")
		  {	  alert("<?php echo $a_miss_fullname ?>");
			  return false;
		  }
		  else if (validateCellFormat(ins_cell)==false)
	      {   alert("<?php echo $a_fmt_cell ?>");
	          return false;  
          }
		  else if (ins_email!="" && validateEmailFormat(ins_email)==false)
	      {   alert("<?php echo $a_fmt_email ?>");
	          return false;  
          }
          
			var dataString='ins='+ins+'&ins_cid='+ins_cid+'&ins_cell='+ins_cell+'&ins_email='+ins_email+'&ins_fullname='+ins_fullname+
			'&ins_comp='+ins_comp+'&ins_title='+ins_title;							
			$.ajax({  
			      type: "POST",  
			      url: "insInvite.inc.php",  
			      data: dataString,
			      dataType: 'json',  
			      success: function(data) {
				   	  var id=$.trim(data.error);	
				      if (id > 0)
				      {				    				        
				        $('#ListTable tr:last').after('<tr class="odd" id="TblTr'+id+'"><td align="center"><input class="TblChb" id="TblChb'+id+'" type="checkbox" style="vertical-align:middle;" checked="checked" /></td>'+
				        '<td><label id="TblFname'+id+'">'+ins_fullname+'</label></td>'+
						'<td><label id="TblComp'+id+'">'+ins_comp+'</label></td>'+
						'<td><label id="TblTitle'+id+'">'+ins_title+'</label></td>'+
						'<td><label id="TblCell'+id+'">'+ins_cell+'</label></td>'+
						'<td><label id="TblEmail'+id+'">'+ins_email+'</label></td>'+
						'<td class="icon"><img id="'+id+'" class="TblEdt" type="image" title="<?php echo $l_edt_title?>" src="../image/edit.png" /></td>'+
						'<td class="icon"><img id="'+id+'" class="TblDel" type="image" title="<?php echo $l_del_title?>" src="../image/error.png" /></td></tr>');

					  	$("#ins_cell").val(""); 
					  	$("#ins_email").val(""); 
					  	$("#ins_fullname").val(""); 
					  	$("#ins_comp").val(""); 
					  	$("#ins_title").val(""); 
				      }
				      else if (id==-1 || id==-3) {alert("<?php echo $a_miss_all?>");}
				      else if (id==-2) {alert("<?php echo $a_fmt_cell?>");}
				      //else if (sid==-4) {}
				      else if (id==-5) {window.location.replace("../default/login.php");}
				      else if (id==-7) {alert("<?php echo $a_fmt_all?>");}
				      else if (id==-8) {alert("<?php echo $a_fmt_email?>");}
			      }  
			    });
			    return false;
		  });    

	//populate info
	  $(function() {  
	  	  $("#ins_cell").change(function() {  		  
	  		  var ins_cell=$.trim($("#ins_cell").val());
			  var ins_cid=$("#ins_cid").val();
			  
	  		  if (ins_cell=="") { return false;  }
	  		  var dataString='ins_cell='+ins_cell+'&ins_cid='+ins_cid;
	  		  $.ajax({  
	  		      type: "POST",  
	  		      url: "selInvite.inc.php",  
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
		  				$("#ins_email").val(data.email);
		  				$("#ins_fullname").val(data.fname);
	  				    $("#ins_comp").val(data.comp);
	  				    $("#ins_title").val(data.title); 						
	  			      }			       
	  		      }  
	  		  });  
	  		  return false;
	  	  });  
	  	});
	//delete
	$(".TblDel").live('click', function(){
		var id=$(this).attr('id');
		var cid=$("#ins_cid").val(); 
		var dataString='id=' + id + '&cid=' + cid;
		if(confirm('<?php echo $l_del_confirm ?>'))
		{
			$.ajax({
			type: "POST",
			url: "delInvite.inc.php",
			data: dataString,
			success: function(data){
					if( data==0) // success
					{
						$("#TblTr"+id).fadeOut("slow");
						$("#TblTr"+id).remove();
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

	//delete checked invitation
	$("#btnDelRange").click(function(){
		if(confirm('<?php echo $l_del_confirm ?>'))
		{	
			var ids = "0";
			var cid=$("#ins_cid").val();
			var i = 0;
			var idArray = new Array();
			
			$("#ListTable .TblChb").each(function(){
				var id = $(this).attr('id').replace("TblChb","");
				
		  		if ($("#TblChb"+id).attr("checked") == "checked")
				{
		  			ids = ids + "," + id;
		  			idArray[i]=id;
		  			i++;
				}
			});
			if (ids != "0")
			{
				var dataString='ids='+ids+'&cid='+cid;
				$.ajax({
					type: "POST",
					url: "delInviteRange.inc.php",
					data: dataString,
					success: function(data){
						if( data==0) // success
						{
							var arLen=idArray.length;
							var m=0;
							for ( m=0; m<arLen; m++ )
							{
								$("#TblTr"+idArray[m]).fadeOut("slow");
								$("#TblTr"+idArray[m]).remove();
							}								
						}
						else if( data==1) // session expired
						{window.location.replace("../default/login.php");}
					}
				});
			}
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
<div align="left">
<input id="btnAddInvt" class="btn" type="button" style="width:130px;" value="<?php echo $l_add_invt ?>"></input>
<input id="btnImpInvt" class="btn" type="button" style="width:130px;" value="<?php echo $l_imp_invt ?>"></input>
<input id="btnDelRange" class="btn" type="button" style="width:130px;" value="<?php echo $l_del_range ?>"></input>
<input id="btnSend" class="btn" type="button" style="width:130px;" value="<?php echo $l_send ?>"></input>
<input type="hidden" id="send" name="send" value="1" />
<input type="hidden" id="send_cid" name="send_cid" value="<?php echo $conf->getCid() ?>" />
</div>
<table id="ListTable" class="Conf" cellspacing='0' cellpadding='0'>
<tr id="head"><th><?php echo $l_h_send1?><input class="" id="TblChb" type="checkbox" style="vertical-align:middle;" checked="checked" /></th><th><?php echo $l_h_fname?></th><th><?php echo $l_h_comp?></th><th><?php echo $l_h_title?></th><th><?php echo $l_h_cell?></th><th><?php echo $l_h_email?></th><th></th><th></th></tr>
<?php
$users=Invitation::getInvtNotSent($conf->getCid());
foreach ($users as $user) 
{
	echo '<tr class="odd" id="TblTr'.$user->getId().'"><td align="center"><input class="TblChb" id="TblChb'.$user->getId().'" type="checkbox" style="vertical-align:middle;" checked="checked" /></td>'.
	'<td><label id="TblFname'.$user->getId().'">'.$user->fullname.'</label></td>'.
	'<td><label id="TblComp'.$user->getId().'">'.$user->company.'</label></td>'.
	'<td><label id="TblTitle'.$user->getId().'">'.$user->title.'</label></td>'.
	'<td><label id="TblCell'.$user->getId().'">'.$user->cell.'</label></td>'.
	'<td><label id="TblEmail'.$user->getId().'">'.$user->email.'</label></td>'.
	'<td class="icon"><img id="'.$user->getId().'" class="TblEdt" type="image" title="'.$l_edt_title.'" src="../image/edit.png" /></td>'.
	'<td class="icon"><img id="'.$user->getId().'" class="TblDel" type="image" title="'.$l_del_title.'" src="../image/error.png" /></td></tr>';
}
?>
</table>
<div id="addInvt" style="display:none">
<form id="ins_form" name="ins_form" method="post" enctype="multipart/form-data" action="insInvite.inc.php" accept-charset="UTF-8">
<table>
<tr align="left">	
<td align="right"><label><?php echo $l_t_cell?></label></td>
<td><input id="ins_cell" name="ins_cell" style="width:160px;" type="text" /><label class="label_required" style="margin-right:10px;">*</label></td>
<td align="right"><label><?php echo $l_t_comp?></label></td>
<td><input id="ins_comp" name="ins_comp" style="width:160px;" type="text" /></td>
</tr>
<tr align="left">
<td align="right"><label><?php echo $l_t_fname?></label></td>
<td><input id="ins_fullname" name="ins_fullname" style="width:160px;" type="text" /><label class="label_required">*</label></td>
<td align="right"><label><?php echo $l_t_title?></label></td>
<td><input id="ins_title" name="ins_title" style="width:160px;" type="text" /></td>
</tr>
<tr align="left">	
<td align="right"><label><?php echo $l_t_email?></label></td>
<td><input id="ins_email" name="ins_email" style="width:160px;" type="text" /></td>
<td></td>
<td></td>
</tr>
<tr>
<td colspan=4 align="center">
<input id="btnIns" class="btn" type="button" value="<?php echo $l_submit ?>"></input>
<input id="btnInsCancel" class="btn" type="button" value="<?php echo $l_cancel ?>"></input></td>
</tr>
</table>
<input type="hidden" id="ins" name="ins" value="1" />
<input type="hidden" id="ins_cid" name="ins_cid" value="<?php echo $conf->getCid(); ?>" />
</form>
</div>
<div id="impInvt" <?php if (!isset($_SESSION['_inviteErr']) || empty($_SESSION['_inviteErr'])){ echo 'style="display:none"';}?>>
<?php 
if(isset($_SESSION['_inviteErr']) && !empty($_SESSION['_inviteErr']))
{
	echo '<a id="imp"></a>';
	echo '<div class="round_border_4" align="left" style="padding:10px 10px 10px 10px;margin:10px 10px 10px 10px;border:1px #CC0000 solid;background-color:#F08080;color:white;width:80%;">';
	echo $_SESSION['_inviteErr'];
	echo '</div>';
	unset($_SESSION['_inviteErr']);
}
?>
<form id="imp_form" name="imp_form" method="post" enctype="multipart/form-data" action="importInvite.inc.php" accept-charset="UTF-8">
<?php echo $l_browse_file ?><input id="imp_file" name="imp_file" type="file" />
<input type="submit" class="btn" style="width:120px;" value="<?php echo $l_browse_btn ?>" />
<input type="hidden" id="import" name="import" value="1" />
<input type="hidden" id="imp_cid" name="imp_cid" value="<?php echo $conf->getCid(); ?>" />
<input type="hidden" id="imp_page" name="imp_page" value="../conference/edit_invite.php?url=<?php echo $conf->getUrl()?>" />
</form>
<div class="round_border_6" align="left" style="padding:0 0 0 10px;margin:10px 10px 10px 10px;border:1px #BFBF04 solid;background-color:#FFFFDD;color:black;font-size:0.8em;width:600px;">
<p><b><?php echo $l_note ?></b></p>
<p style="margin-left:20px;"><?php echo $l_note_a ?></p>
<p style="margin-left:20px;"><?php echo $l_note_b ?></p>
<p style="margin-left:20px;"><?php echo $l_note_c ?></p>
<p><?php echo $l_note_z ?></p>
</div>
</div>
<div style="border-top:1px #DDD solid;width:95%;margin-top:10px;margin-bottom:10px;"></div>
<div align="left" style="font-weight:bold;"><label><?php echo $l_sent?></label></div>
<table id="ListTableSent" class="Conf" cellspacing='0' cellpadding='0'>
<tr id="head"><th><?php echo $l_h_fname?></th><th><?php echo $l_h_comp?></th><th><?php echo $l_h_title?></th><th><?php echo $l_h_cell?></th><th><?php echo $l_h_email?></th><th><?php echo $l_h_time?></th></tr>
<?php
$users=Invitation::getInvtSent($conf->getCid());
foreach ($users as $user) 
{
	echo '<tr class="odd" id="SentTblTr'.$user->getId().'"><td><label id="SentTblFname'.$user->getId().'">'.$user->fullname.'</label></td>'.
	'<td><label id="SentTblComp'.$user->getId().'">'.$user->company.'</label></td>'.
	'<td><label id="SentTblTitle'.$user->getId().'">'.$user->title.'</label></td>'.
	'<td><label id="SentTblCell'.$user->getId().'">'.$user->cell.'</label></td>'.
	'<td><label id="SentTblEmail'.$user->getId().'">'.$user->email.'</label></td>'.
	'<td><label id="SentTblTime'.$user->getId().'">'.date("Y-m-d, g:i A", strtotime($user->stime)).'</label></td></tr>';
}
?>
</table>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
<div id="screen"></div>
<div id="epopup" class="cevent round_border_20" style="min-height:250px;position:normal;z-index:2000;" >
<div id="cbutton" onclick="dismissCE('epopup')"></div>
<div style="padding:20px 20px 20px 20px;margin-top:20px;" align="center">
<form id="upd_form" name="upd_form" method="post" enctype="multipart/form-data" action="updInvite.inc.php" accept-charset="UTF-8">
<table>
<tr align="left">	
<td align="right"><label><?php echo $l_t_cell?></label></td>
<td><input id="upd_cell" name="upd_cell" style="width:160px;" type="text" disabled="disabled" /><label class="label_required" style="margin-right:10px;">*</label></td>
<td align="right"><label><?php echo $l_t_comp?></label></td>
<td><input id="upd_comp" name="upd_comp" style="width:160px;" type="text" /></td>
</tr>
<tr align="left">
<td align="right"><label><?php echo $l_t_fname?></label></td>
<td><input id="upd_fullname" name="upd_fullname" style="width:160px;" type="text" /><label class="label_required">*</label></td>
<td align="right"><label><?php echo $l_t_title?></label></td>
<td><input id="upd_title" name="upd_title" style="width:160px;" type="text" /></td>
</tr>
<tr align="left">
<td align="right"><label><?php echo $l_t_email?></label></td>
<td><input id="upd_email" name="upd_email" style="width:160px;" type="text" /></td>
<td></td>
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
<input type="hidden" id="upd_id" name="upd_id" value="" />
</form>
</div>
</div>
</body>
</html>