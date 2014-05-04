<?php
include_once ("../_obj/Message.php");
include_once ("../_obj/User.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "Message | ConfOne";
	$l_del_title="Delete";
	$l_del_confirm="Confirm delete?";
	$l_t_receiver = "Cellphone: ";
	$l_t_title = "Title: ";
	$l_t_body = "Body: ";
	$l_h_sender = "Sender";
	$l_h_title = "Title";
	$l_h_time = "Date";
	$l_h_body = "Body";
	$l_bu_sub = 'Send';
	$l_bu_res = 'Reset';
	$a_miss_receiver = "Please enter receiver's cellphone";
	$a_miss_title = "Title is required";
	$a_title_len = "Title should be less than 320 characters";
	$a_no_select = "Please select a mail";
	$a_no_body = "No body";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "站内信 | 会云网";
	$l_del_title="删除";
	$l_del_confirm="确认删除？";
	$l_t_receiver = "收信人邮箱：";
	$l_t_title = "标题：";
	$l_t_body = "内容：";
	$l_h_sender = "发信人";
	$l_h_title = "标题";
	$l_h_time = "时间";
	$l_h_body = "内容";
	$l_bu_sub = '发送';
	$l_bu_res = '重置';
	$a_miss_receiver = "请填写收信人";
	$a_miss_title = "请填写标题";
	$a_title_len = "标题请不要超过320个字";
	$a_no_select = "请选择你要阅读的站内信";
	$a_no_body = "无内容";	
}

//=========================================================================================================

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<style type="text/css">html,body{margin:0;padding:0;}
tr.odd{cursor:pointer;border:0 none;}
img.TableDel{cursor:pointer;border:0 none;}
table.Conf
{
	padding: 10px 10px 10px 10px;
	text-align:left;
	width:100%;
	font-size:.8em;
	border:0px none;
}
table.Conf tr
{
	line-height:2em;
}
table.Conf tr#head
{
	text-align:center;
	background:#CCC;
	font-weight:bold;
}
table.Conf tr.odd
{
	background:#FFF;
}
table.Conf td
{
	padding:5px 5px 5px 5px;
	border-right:1px solid #eee;
	border-bottom:1px solid #eee;
}
table.Conf td.iconT
{
	width:16px;
	align:center;
	vertical-align:top;
}
input {height:25px;border:1px solid #CCC;padding-left:4px;}
input.btn
{
	padding: 5px 5px 5px 5px;
	margin: 10px 15px 0 15px;
	background: #9CC7E5;
	border:none!important;
	-webkit-box-shadow:1px 1px 0 #999;
	-moz-box-shadow:1px 1px 0 #999;
	box-shadow:1px 1px 0 #999;
	width:66px;
	height:30px;
}
input.btn:hover
{
	cursor:pointer;
	background-color:#589ED0;
}
input.btn:active
{
	background-color:#589ED0;
	-webkit-box-shadow:0 0,inset 1px 1px 0 #777;
	-moz-box-shadow:0 0,inset 1px 1px 0 #777;
	box-shadow:0 0,inset 1px 1px 0 #777;
}
label.text { font-size:.8em;color:#555; }
label.msg:hover {cursor:pointer;}
</style>
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>  
<script charset="utf-8" src="../kindeditor/kindeditor-min.js"></script>
<script>KE.show({ id : 'ins_body', allowFileManager : false, allowUpload : false });</script>
<script>KE.show({ id : 'upd_body', allowFileManager : false, allowUpload : false });</script>
<script type="text/javascript">
jQuery(function($){
	  // before insert
	  $("#btnIns").click(function() {	
		  var ins_receiver=$.trim($("#ins_receiver").val());	
		  var ins_title=$.trim($("#ins_title").val());
		  
		  //validate data
		  if (ins_receiver=="")
		  {	  alert("<?php echo $a_miss_receiver ?>");
		  	  return false;
		  }
		  else if (ins_title=="")
		  {	  alert("<?php echo $a_miss_title ?>");
		  	  return false;
		  }
		  else if (ins_title.length>320)
		  {   alert("<?php echo $a_title_len ?>");
		      return false;
		  }	
	  }); 
	  //reset
	  $("#btnInsRes").click(function() {
		  $("#ins_title").val("");	
		  KE.html('ins_body', "");	
	  });
	// hide_show
	$(".odd").live('click',function(){
		var id=$(this).attr('id').replace("Tr","");
	    var dataString='id='+id;
		$("#body"+id).slideToggle();
		if ($("#body"+id).html()=="")
		{
			$("#body"+id).html('<img src="../image/progress-bar.gif" />');		
			$.ajax({  
			      type: "POST",  
			      url: "selMsgBody.inc.php",  
			      data: dataString,
			      dataType: 'json',  
			      success: function(data) {	
				      if (data.error > 0)
				      {
				    	  $("#body"+data.error).html(data.body);
				    	  $("#Tr"+id).removeAttr( "style" );
				    	  $("#TblSender"+id).removeAttr( "style" );
				    	  $("#TblTitle"+id).removeAttr( "style" );
				    	  $("#TblTime"+id).removeAttr( "style" );
				    	  if (data.messNum>0)
				    	  {	
				    	  	$("#messNum").html(data.messNum);
				    	  }
				    	  else
				    	  {
				    		  $("#messC").html("");
				    	  }			    	  	    	  
				      }
				      else if (data.error==-1) {window.location.replace("../default/login.php");}
				      else if (data.error==-2) {alert("<?php echo $a_no_select ?>");}
				      else if (data.error==-3) 
					  {
						  $("#body"+id).html("<?php echo $a_no_body ?>");
				    	  $("#Tr"+id).removeAttr( "style" );
				    	  $("#TblSender"+id).removeAttr( "style" );
				    	  $("#TblTitle"+id).removeAttr( "style" );
				    	  $("#TblTime"+id).removeAttr( "style" );
				    	  if (data.messNum>0)
				    	  {	
				    	  	$("#messNum").html(data.messNum);
				    	  }
				    	  else
				    	  {
				    		  $("#messC").html("");
				    	  }
					}
			    }
			});
		}
	});
	//delete
	$(".TableDel").live('click', function(){
		var id=$(this).attr('id');
		var dataString='id='+id;
		if(confirm('<?php echo $l_del_confirm ?>'))
		{
			$.ajax({
			type: "POST",
			url: "delMessage.inc.php",
			data: dataString,
		    dataType: 'json',  
			success: function(data){
					if(data.error==0) // success
					{
					  $("#Tr"+id).fadeOut("slow");
			    	  if (data.messNum>0)
			    	  {	
			    	  	$("#messNum").html(data.messNum);
			    	  }
			    	  else
			    	  {
			    		  $("#messC").html("");
			    	  }	
					}
					else if(data.error==1) // session expired
					{window.location.replace("../default/login.php");}	
					else if(data.error==2) // delete fail
					{}	
					else if(data.error==3) // no input
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
<div id="content" style="margin:auto;width:98%;min-height:700px;" align="center">
<table id="ListTable" class="Conf" cellspacing='0' cellpadding='0'>
<tr id="head"><th><?php echo $l_h_sender?></th><th><?php echo $l_h_title?></th><th><?php echo $l_h_time?></th><th></th></tr>
<?php 
$mess = Message::getMessageList($_SESSION['_userId']);
$ids = ''; 
foreach ($mess as $m) 
{ $ids .= $m->getSid().' ,'; }
$ids .= '0'; 
$users = User::getMessageUserNames($ids);

foreach ($mess as $m) 
{	
	foreach ($users as $u) 
	{
		if ($m->getSid() == $u->getUid())
		{
			$fullname = $u->fullname;
			break;
		}		
	}
	
	if ($m->is_read == 'N')
	{
		echo '<tr class="odd" id="Tr'.$m->getId().'"  style="background:#F3F3F3;"><td style="width:100px;vertical-align:top;"><label class="msg" id="TblSender'.$m->getId().'" style="font-weight:bold;">'.$fullname.'</label></td>'.
		'<td style="max-width:400px;"><label class="msg" id="TblTitle'.$m->getId().'" style="font-weight:bold;">'.$m->getTitle().'</label><div id="body'.$m->getId().'" style="display:none"></div></td>'.
		'<td style="width:140px;vertical-align:top;"><label class="msg" id="TblTime'.$m->getId().'" style="font-weight:bold;">'.date("Y-m-d, g:i A", strtotime($m->getCtime())).'</label></td>'.
		'<td class="iconT"><img id="'.$m->getId().'" class="TableDel" type="image" title="'.$l_del_title.'" src="../image/error.png" /></td></tr>';
	}
	else
	{
		echo '<tr class="odd" id="Tr'.$m->getId().'"><td style="width:100px;vertical-align:top;"><label class="msg" id="TblSender'.$m->getId().'">'.$fullname.'</label></td>'.
		'<td style="max-width:400px;"><label class="msg" id="TblTitle'.$m->getId().'">'.$m->getTitle().'</label><div id="body'.$m->getId().'" style="display:none"></div></td>'.
		'<td style="width:140px;vertical-align:top;"><label class="msg" id="TblTime'.$m->getId().'">'.date("Y-m-d, g:i A", strtotime($m->getCtime())).'</label></td>'.
		'<td class="iconT"><img id="'.$m->getId().'" class="TableDel" type="image" title="'.$l_del_title.'" src="../image/error.png" /></td></tr>';
	}
}
?>
</table>

<form style="margin-top:20px" id="ins_form" name="ins_form" method="post" enctype="multipart/form-data" action="insMessage.inc.php" accept-charset="UTF-8">
<table>
<tr>
<td align="right"><label class="text"><?php echo $l_t_receiver;?></label></td>
<td><input id="ins_receiver" name="ins_receiver" type="text" style="width:300px;" /><label class="label_required">*</label></td>
</tr>
<tr>
<td align="right"><label class="text"><?php echo $l_t_title;?></label></td>
<td><input id="ins_title" name="ins_title" type="text" style="width:740px;" /><label class="label_required">*</label></td>
</tr>
<tr>
<td align="right"><label class="text"><?php echo $l_t_body;?></label></td>
<td><textarea id="ins_body" name="ins_body" cols="100" rows="8" style="width:746px;height:300px;"></textarea></td>
</tr>
<tr>
<td></td>
<td align="center">
<input id="btnIns" class="btn" type="submit" value="<?php echo $l_bu_sub ?>"></input>
<input id="btnInsRes" class="btn" type="button" value="<?php echo $l_bu_res ?>"></input>
</td>
</tr>
</table>
</form>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
</html>