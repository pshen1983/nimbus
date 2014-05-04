<?php
include_once ("../_obj/Broadcast.php");
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
	if($conf->status == 'C')
	{
		header( 'Location: ../conference/index.php' );
		exit;
	}
	$_SESSION['_confsEdit'][$_GET['url']] = $conf;
}

$conf = $_SESSION['_confsEdit'][$_GET['url']];

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "Broadcast | ";
	$l_edt_title="Edit";
	$l_del_title="Delete";
	$l_del_confirm="Confirm delete?";
	$l_t_title = "Title: ";
	$l_t_body = "Body: ";
	$l_h_title = "Title";
	$l_h_body = "Body";
	$l_bu_sub = 'Submit';
	$l_bu_res = 'Reset';
	$l_cancel = "Cancel";
	$a_miss_title = "Title is required";
	$a_title_len = "Title should be less than 40 characters";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "附加信息 | ";
	$l_edt_title="编辑";
	$l_del_title="删除";
	$l_del_confirm="确认删除？";
	$l_t_title = "标题：";
	$l_t_body = "内容：";
	$l_h_title = "标题";
	$l_h_body = "内容";
	$l_bu_sub = '确定';
	$l_bu_res = '重置';
	$l_cancel = "取消";
	$a_miss_title = "标题为必填项";
	$a_title_len = "标题请不要超过40个字";	
	
}

//=========================================================================================================

$page = 7;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title.$conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<style type="text/css">html,body{margin:0;padding:0;}
img.TableEdt{cursor:pointer;border:0 none;}
img.TableDel{cursor:pointer;border:0 none;}
table.Conf td.iconT
{
	width:16px;
	align:center;
	vertical-align:top;
}
label.text{font-size:.8em;color:#555;}
</style>
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<script type="text/JavaScript" src="../js/conference.js"></script>
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>  
<script charset="utf-8" src="../kindeditor/kindeditor-min.js"></script>
<script>KE.show({ id : 'ins_body', allowFileManager : false, allowUpload : false });</script>
<script>KE.show({ id : 'upd_body', allowFileManager : false, allowUpload : false });</script>
<script type="text/javascript">
jQuery(function($){
	  // before insert broadcast
	  $("#btnIns").click(function() {
	  var ins_title=$.trim($("#ins_title").val());		  
	  //validate data
	  if (ins_title=="")
	  {	  alert("<?php echo $a_miss_title ?>");
	  	  return false;
	  }
	  else if (ins_title.length>40)
	  {   alert("<?php echo $a_title_len ?>");
	      return false;
	  }	
	  }); 
	  
	  $("#btnInsRes").click(function() {
		  $("#ins_title").val("");	
		  KE.html('ins_body', "");	
	  });
	//when broadcast edit click, popup window and fill in content
	$(".TableEdt").live('click', function(){
		showCE("ebcast");
		var id=$(this).attr('id');
		$("#upd_id").val(id);
		$("#upd_title").val($("#TableLink"+id).text());
		KE.html('upd_body', $("#Bbody"+id).html());			
	}); 
	// hide_show
	$(".show_hide").live('click',function(){
		var id=$(this).attr('id').replace("TableLink","");
		$("#Bbody"+id).slideToggle();
	});		
	//delete broadcast
	$(".TableDel").live('click', function(){
		var id=$(this).attr('id');		
		var cid=$("#ins_cid").val(); 
		var dataString='id='+id+'&cid='+cid;
		if(confirm('<?php echo $l_del_confirm ?>'))
		{
			$.ajax({
			type: "POST",
			url: "delBroadcast.inc.php",
			data: dataString,
			success: function(data){
					if( data==0) // success
					{
						$("#Tr"+id).fadeOut("slow");
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
<?php 
	$ConfScheList=Broadcast::getConfBcastList($conf->getCid());
	while($row = mysql_fetch_array($ConfScheList))
	{ 		
		echo '<tr class="odd" id="Tr'.$row['id'].'"><td><a href="#" class="show_hide" id="TableLink'.$row['id'].'">'.$row['btitle'].'</a><div id="Bbody'.$row['id'].'" style="display:none">'.$row['bbody'].'</div></td>'.
		'<td class="iconT" valign="top"><img id="'.$row['id'].'" class="TableEdt" type="image" title="'.$l_edt_title.'" src="../image/edit.png" /></td>'.
		'<td class="iconT"><img id="'.$row['id'].'" class="TableDel" type="image" title="'.$l_del_title.'" src="../image/error.png" /></td></tr>';
 	}
?>
</table>
<form id="ins_form" name="ins_form" method="post" enctype="multipart/form-data" action="insBroadcast.inc.php" accept-charset="UTF-8">
<table>
<tr>
<td><label class="text"><?php echo $l_t_title;?></label></td>
<td><input id="ins_title" name="ins_title" type="text" style="width:300px;" /><label class="label_required">*</label></td>
</tr>
<tr>
<td><label class="text"><?php echo $l_t_body;?></label></td>
<td><textarea id="ins_body" name="ins_body" cols="100" rows="8" style="width:760px;height:300px;"></textarea></td>
</tr>
<tr>
<td></td>
<td align="center">
<input id="btnIns" class="btn" type="submit" value="<?php echo $l_bu_sub ?>"></input>
<input id="btnInsRes" class="btn" type="button" value="<?php echo $l_bu_res ?>"></input>
</td>
</tr>
</table>
<input type="hidden" id="ins" name="ins" value="1" />
<input type="hidden" id="ins_cid" name="ins_cid" value="<?php echo $conf->getCid(); ?>" />
<input type="hidden" id="ins_page" name="ins_page" value="../conference/edit_broadcast.php?url=<?php echo $conf->getUrl()?>"/>
</form>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
<div id="screen"></div>
<div id="ebcast" class="cevent round_border_20" style="min-height:500px;position:normal;z-index:2000;" >
<div id="cbutton" onclick="dismissCE('ebcast')"></div>
<div style="padding:20px 20px 20px 20px;margin-top:20px;" align="center">
<form id="upd_form" name="upd_form" method="post" enctype="multipart/form-data" action="updBroadcast.inc.php" accept-charset="UTF-8">
<table>
<tr>
<td><label class="text"><?php echo $l_t_title;?></label></td>
<td><input id="upd_title" name="upd_title" type="text" style="width:300px;" /><label class="label_required">*</label></td>
</tr>
<tr>
<td><label class="text"><?php echo $l_t_body;?></label></td>
<td><textarea id="upd_body" name="upd_body" cols="100" rows="8" style="width:760px;height:300px;"></textarea></td>
</tr>
<tr>
<td></td>
<td align="center">
<input id="btnUpd" class="btn" type="submit" value="<?php echo $l_bu_sub ?>"></input>
<input class="btn" type="button" onclick="dismissCE('ebcast')" value="<?php echo $l_cancel ?>"></input>
</td>
</tr>
</table>
<input type="hidden" id="upd" name="upd" value="1" />
<input type="hidden" id="upd_id" name="upd_id" value="" />
<input type="hidden" id="upd_cid" name="upd_cid" value="<?php echo $conf->getCid(); ?>" />
<input type="hidden" id="upd_page" name="upd_page" value="../conference/edit_broadcast.php?url=<?php echo $conf->getUrl()?>"/>
</form>
</div>
</div>
</body>
</html>