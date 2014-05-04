<?php
include_once ("../_obj/Conference.php");
include_once ("../_obj/Schedule.php");
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
$date = substr($conf->stime, 0, 10);

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Schedule | ";
	$l_save="Save";
	$l_submit="Submit";
	$l_cancel="Cancel";
	$l_schedule_dt="Date: ";
	$l_schedule_tm="Time: ";
	$l_schedule_ad="Location: ";
	$l_schedule_su="Summary: ";
	$l_schedule_no="Note: ";
	$l_schedule_hdt="Date";
	$l_schedule_htm="Time";
	$l_schedule_had="Location";
	$l_schedule_hsu="Summary";
	$l_schedule_hno="Note";
	$l_del_confirm="Confirm delete?";
	$l_edt_title="Edit";
	$l_del_title="Delete";
	$l_ins_missing="Please fill in Date, Time, Location and Summary";
	$l_sche_no_date="Please enter date";
	$l_sche_not_date="Invalid date format (yyyy-mm-dd)";
	$l_sche_no_stim="Please enter starting time";
	$l_sche_not_stim="Invalid starting time format";
	$l_sche_no_etim="Please enter ending time";
	$l_sche_not_etim="Invalid ending time format";
	$l_sche_cmp_time="Starting time can't be later than ending time";
	$l_sche_no_addr="Please enter location";
	$l_sche_no_summ="Please enter summary";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="日程安排 | ";
	$l_langugage='zh';
	$l_save="保存";
	$l_submit="添加";
	$l_cancel="取消";
	$l_schedule_dt="日期：";
	$l_schedule_tm="时间：";
	$l_schedule_ad="地点：";
	$l_schedule_su="内容：";
	$l_schedule_no="备注：";
	$l_schedule_hdt="日期";
	$l_schedule_htm="时间";
	$l_schedule_had="地点";
	$l_schedule_hsu="内容";
	$l_schedule_hno="备注";
	$l_del_confirm="确认删除？";
	$l_edt_title="编辑";
	$l_del_title="删除";
	$l_ins_missing="日期、时间、地点 、内容是必填信息";	
	$l_sche_no_date="请填写日期";
	$l_sche_not_date="日期格式有误 (yyyy-mm-dd)";
	$l_sche_no_stim="请填写开始时间";
	$l_sche_not_stim="开始时间格式有误";
	$l_sche_no_etim="请填写结束时间";
	$l_sche_not_etim="结束时间格式有误";
	$l_sche_cmp_time="开始时间不能晚于结束时间";
	$l_sche_no_addr="请填写地点";
	$l_sche_no_summ="请填写内容";
}

//=========================================================================================================

$page = 2;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title.$conf->name?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<style type="text/css">
html,body{margin:0;padding:0;}
img.ScheEdt{cursor:pointer;border:0 none;}
img.ScheDel{cursor:pointer;border:0 none;}
table td label.text{font-size:.8em;color:#555;}
</style>
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/conference.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery-ui.css" media="all" /> 
<script type="text/javascript" src="../js/conference.js"></script>
<script type="text/javascript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-custom.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-timepicker.js"></script> 
<script type="text/javascript">
$(function(){ $("#date").datepicker($.datepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $('#stime').timepicker($.timepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $('#etime').timepicker($.timepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $("#s_e_date").datepicker($.datepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $('#s_e_stime').timepicker($.timepicker.regional['<?php echo $l_langugage;?>']); });
$(function(){ $('#s_e_etime').timepicker($.timepicker.regional['<?php echo $l_langugage;?>']); });
</script>
<script type="text/javascript" >
//delete schedule
$(function() {
	$(".ScheDel").live('click', function(){
		var sid = $(this).attr('id');
		var dataString = 'id='+sid;
		if(confirm('<?php echo $l_del_confirm ?>'))
		{
			$.ajax({
			type: "POST",
			url: "delSchedule.inc.php",
			data: dataString,
			success: function(data){
					if( data==0) // success
					{
						$("#ScheTr"+sid).fadeOut("slow");
					}
					else if( data==1) // session expired
					{window.location.replace("../default/login.php");}	
					else if( data==2) // delete fail
					{}	
					else if( data==3) // no sid
					{}
				}
			});
		}
		return false;
	});
});
//insert schedule
$(function() {  
	  $("#submitSche").click(function() {  
		  var cid = $("#cid").val(); 
		  var sche = $("#sche").val(); 
		  var date = $.trim($("#date").val()); 
		  var stime = $.trim($("#stime").val()); 
		  var etime = $.trim($("#etime").val()); 
		  var addr = $.trim($("#addr").val()); 
		  var summ = $.trim($("#summ").val()); 
		  var note = $.trim($("#note").val());
		  //validate data
		  if (date=='')
		  {	  alert("<?php echo $l_sche_no_date  ?>");
			  return false;
		  }
		  else if (!isDate(date))
		  {	  alert("<?php echo $l_sche_not_date ?>");
		  	  return false;
	  	  }		  
		  else if (stime=='')
		  {	  alert("<?php echo $l_sche_no_stim ?>");
			  return false;
		  }
		  else if (!isTime(stime))
		  {	  alert("<?php echo $l_sche_not_stim ?>");
		  	  return false;
	  	  }	
		  else if (etime=='')
		  {	  alert("<?php echo $l_sche_no_etim ?>");
			  return false;
		  }
		  else if (!isTime(etime))
		  {	  alert("<?php echo $l_sche_not_etim ?>");
		  	  return false;
	  	  }	
		  else if (!cmpTime(stime, etime))
		  {	  alert("<?php echo $l_sche_cmp_time ?>");
		  	  return false;
	  	  }	
		  else if (addr=='')
		  {	  alert("<?php echo $l_sche_no_addr ?>");
			  return false;
		  }
		  else if (summ=='')
		  {	  alert("<?php echo $l_sche_no_summ ?>");
			  return false;
		  }	  
			  
		  var dataString = 'cid='+cid+'&sche='+sche+'&date='+date+'&stime='+stime+'&etime='+etime+'&addr='+addr+'&summ='+summ+'&note='+note;
	      $.ajax({
	      type: "POST",  
	      url: "insSchedule.inc.php",  
	      data: dataString,  
	      success: function(data) {
				var sid = $.trim(data);			  	  		
				if (sid > 0)
				{
					var note_display;
					if (note.length>20) {note_display = note.substr(0,20)+" ...";}
					else {note_display = note;}									      
					$('#ScheTable tr:last').after('<tr class="odd" id="ScheTr'+sid+'"><td class="day"><label id="ScheDate'+sid+'">'+date+'</label></td>'+
					'<td class="time"><label id="ScheStim'+sid+'">'+stime+'</label>-<label id="ScheEtim'+sid+'">'+etime+'</label></td>'+
					'<td><label id="ScheAddr'+sid+'">'+addr+'</label></td>'+
					'<td><label id="ScheSumm'+sid+'">'+summ+'</label></td>'+
					'<td><label id="ScheNote'+sid+'" title="'+note+'">'+note_display+'</label></td>'+
					'<td class="icon"><img id="'+sid+'" class="ScheEdt" type="image" title="<?php echo $l_edt_title?>" src="../image/edit.png" /></td>'+
					'<td class="icon"><img id="'+sid+'" class="ScheDel" type="image" title="<?php echo $l_del_title?>" src="../image/error.png" /></td></tr>');	
	      		}
	      		else if (sid == -5) {window.location.replace("../default/login.php");}
	      		else {alert("<?php echo $l_ins_missing?>");}
			}  
	    });
		return false;
	});  
});
//update schedule
$(function() {  
	  $("#updateSche").click(function() {  
		  var sid = $("#s_e_sid").val(); 
		  var cid = $("#s_e_cid").val(); 
		  var sche = $("#s_e_sche").val(); 
		  var date = $.trim($("#s_e_date").val()); 
		  var stime = $.trim($("#s_e_stime").val()); 
		  var etime = $.trim($("#s_e_etime").val()); 
		  var addr = $.trim($("#s_e_addr").val()); 
		  var summ = $.trim($("#s_e_summ").val()); 
		  var note = $.trim($("#s_e_note").val());
		  //validate data
		  if (date=='')
		  {	  alert("<?php echo $l_sche_no_date  ?>");
			  return false;
		  }
		  else if (!isDate(date))
		  {	  alert("<?php echo $l_sche_not_date ?>");
		  	  return false;
	  	  }		  
		  else if (stime=='')
		  {	  alert("<?php echo $l_sche_no_stim ?>");
			  return false;
		  }
		  else if (!isTime(stime))
		  {	  alert("<?php echo $l_sche_not_stim ?>");
		  	  return false;
	  	  }	
		  else if (etime=='')
		  {	  alert("<?php echo $l_sche_no_etim ?>");
			  return false;
		  }
		  else if (!isTime(etime))
		  {	  alert("<?php echo $l_sche_not_etim ?>");
		  	  return false;
	  	  }	
		  else if (!cmpTime(stime, etime))
		  {	  alert("<?php echo $l_sche_cmp_time ?>");
		  	  return false;
	  	  }	
		  else if (addr=='')
		  {	  alert("<?php echo $l_sche_no_addr ?>");
			  return false;
		  }
		  else if (summ=='')
		  {	  alert("<?php echo $l_sche_no_summ ?>");
			  return false;
		  }
		  var dataString = 's_e_sid='+sid+'&s_e_cid='+cid+'&s_e_sche='+sche+'&s_e_date='+date+'&s_e_stime='+stime+'&s_e_etime='+etime+'&s_e_addr='+addr+'&s_e_summ='+summ+'&s_e_note='+note;  
		    $.ajax({  
		      type: "POST",  
		      url: "updSchedule.inc.php",  
		      data: dataString,  
		      success: function(data) {
			      if (data == 0)
			      {
			        var note_display;
			        if (note.length>20) { note_display = note.substr(0,20)+" ..."; }
			        else { note_display = note; }
			        $("#ScheDate"+sid).text(date);
			        $("#ScheStim"+sid).text(stime);
			        $("#ScheEtim"+sid).text(etime);
			        $("#ScheAddr"+sid).text(addr);
			        $("#ScheSumm"+sid).text(summ);
			        $("#ScheNote"+sid).text(note_display);
			        $("#ScheNote"+sid).attr('title', note);
					dismissCE("esche");
			      }
			      else
			      {
					alert("<?php echo $l_ins_missing?>");
			      }
		      }  
		    });  
		    return false;
	  });  
});
//when edit click, popup window and fill in content
$(function() {
	$(".ScheEdt").live('click', function(){
		showCE("esche");
		var id = $(this).attr('id');
		$("#s_e_sid").val(id);
		$("#s_e_date").val($("#ScheDate"+id).text());
		$("#s_e_stime").val($("#ScheStim"+id).text());
		$("#s_e_etime").val($("#ScheEtim"+id).text());
		$("#s_e_addr").val($("#ScheAddr"+id).text());
		$("#s_e_summ").val($("#ScheSumm"+id).text());
		$("#s_e_note").val($("#ScheNote"+id).attr('title'));
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
<table id="ScheTable" class="Conf" cellspacing='0' cellpadding='0'>
<tr id="head"><th><?php echo $l_schedule_hdt?></th><th><?php echo $l_schedule_htm?></th><th><?php echo $l_schedule_had?></th><th><?php echo $l_schedule_hsu?></th><th><?php echo $l_schedule_hno?></th><th></th><th></th></tr>
<?php 
	$ConfScheList=Schedule::getConfScheList($conf->getCid());
	while($row = mysql_fetch_array($ConfScheList))
	{ 
		if (mb_strlen($row['note'],'utf-8')>20) {$note=mb_substr($row['note'],0,20,'utf-8').' ...';}
		else {$note =$row['note'];}	
		echo '<tr class="odd" id="ScheTr'.$row['sid'].'"><td class="day"><label id="ScheDate'.$row['sid'].'">'.$row['date'].'</label></td>'.
		'<td class="time"><label id="ScheStim'.$row['sid'].'">'.substr($row['stime'],0,5).'</label>-<label id="ScheEtim'.$row['sid'].'">'.substr($row['etime'],0,5).'</label></td>'.
		'<td><label id="ScheAddr'.$row['sid'].'">'.$row['location'].'</label></td>'.
		'<td><label id="ScheSumm'.$row['sid'].'">'.$row['summary'].'</label></td>'.
		'<td><label id="ScheNote'.$row['sid'].'" title="'.$row['note'].'">'.$note.'</label></td>'.
		'<td class="icon"><img id="'.$row['sid'].'" class="ScheEdt" type="image" title="'.$l_edt_title.'" src="../image/edit.png" /></td>'.
		'<td class="icon"><img id="'.$row['sid'].'" class="ScheDel" type="image" title="'.$l_del_title.'" src="../image/error.png" /></td></tr>';
 	}
?>
</table>
<form id="sche_form" method="post" enctype="multipart/form-data" action="insSchedule.inc.php" name="sche_form" accept-charset="UTF-8">
<table>
<tr align="left">	
<td><label class="text"><?php echo $l_schedule_dt?></label></td>
<td><input id="date" name="date" style="width:80px;" type="text" class="ctimes" value="<?php echo $date?>"><label class="label_required">*</label></td>
<td><label class="text"><?php echo $l_schedule_no?></label></td>
<td rowspan="4" valign="top"><textarea id="note" style="resize:none;" name="note" rows=6 cols=45 class="" ></textarea></td>
</tr>
<tr align="left">
<td><label class="text"><?php echo $l_schedule_tm?></label></td>
<td><input id="stime" name="stime" style="width:46px;" type="text" class="ctimes" value="08:00" /><label style="font-weight:normal;margin-left:2px;margin-right:2px;">~</label><input id="etime" name="etime" style="width:46px;" type="text" class="ctimes" /><label class="label_required">*</label></td>
<td></td>
</tr>
<tr align="left">
<td><label class="text"><?php echo $l_schedule_ad?></label></td>
<td><input id="addr" name="addr" style="width:250px;" type="text" value="<?php echo $conf->addr?>"/><label class="label_required">*</label></td>
<td></td>
</tr>
<tr align="left">
<td><label class="text"><?php echo $l_schedule_su?></label></td>
<td><input id="summ" name="summ" style="width:250px;" type="text" /><label class="label_required">*</label></td>
<td></td>
</tr>
<tr>
<td colspan="4" align="right">
<input id="submitSche" class="btn"  type="submit" value="<?php echo $l_submit ?>"></input>
<input class="btn" type="reset" value="<?php echo $l_cancel ?>"></input>
</td>
</tr>
</table>
<input type="hidden" id="sche" name="sche" value="1" />
<input type="hidden" id="cid" name="cid" value="<?php echo $conf->getCid(); ?>" />
</form>
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
<div id="screen"></div>
<div id="esche" class="cevent round_border_20" style="min-height:400px;position:normal;">
<div id="cbutton" onclick="dismissCE('esche')"></div>
<div style="padding:20px 20px 20px 20px;margin-top:100px;" align="center">
<form id="sche_upd_form" method="post" enctype="multipart/form-data" action="updSchedule.inc.php" name="sche_upd_form" accept-charset="UTF-8">
<table>
<tr align="left">	
<td><label class="text"><?php echo $l_schedule_dt?></label></td>
<td><input id="s_e_date" name="s_e_date" style="width:80px;" type="text" class="ctimes" value=""><label class="label_required">*</label></td>
<td><label class="text"><?php echo $l_schedule_no?></label></td>
<td rowspan="4" valign="top"><textarea id="s_e_note" style="resize:none;" name="s_e_note" rows=6 cols=45 class="" ></textarea></td>
</tr>
<tr align="left">
<td><label class="text"><?php echo $l_schedule_tm?></label></td>
<td><input id="s_e_stime" name="s_e_stime" style="width:46px;" type="text" class="ctimes" value="" /><label style="font-weight:normal;margin-left:2px;margin-right:2px;">-</label><input id="s_e_etime" name="s_e_etime" style="width:46px;" type="text" class="ctimes" /><label class="label_required">*</label></td>
<td></td>
</tr>
<tr align="left">
<td><label class="text"><?php echo $l_schedule_ad?></label></td>
<td><input id="s_e_addr" name="s_e_addr" style="width:250px;" type="text" /><label class="label_required">*</label></td>
<td></td>
</tr>
<tr align="left">
<td><label class="text"><?php echo $l_schedule_su?></label></td>
<td><input id="s_e_summ" name="s_e_summ" style="width:250px;" type="text" /><label class="label_required">*</label></td>
<td></td>
</tr>
<tr>
<td colspan="4" align="right">
<input id="updateSche" class="btn" type="submit" value="<?php echo $l_save ?>"></input>
<input class="btn" type="button" onclick="dismissCE('esche')" value="<?php echo $l_cancel ?>"></input>
</td>
</tr>
</table>
<input type="hidden" id="s_e_sche" name="s_e_sche" value="1" />
<input type="hidden" id="s_e_cid" name="s_e_cid" value="<?php echo $conf->getCid(); ?>" />
<input type="hidden" id="s_e_sid" name="s_e_sid" value="" />
</form>
</div>
</div>
</body>
</html>