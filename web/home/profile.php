<?php
include_once ("../_obj/User.php");
include_once ("../_obj/Message.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");

session_start();
SecurityUtil::checkLogin($_SERVER['REQUEST_URI']);

function pr($in) { echo isset($in) && !empty($in) ? $in : ''; }

if(isset($_POST['pf']) && !empty($_POST['pf']) && $_POST['pf']=='pf')
{
	$result_pf = 1;
	if (isset($_POST['fn_in']) && !empty($_POST['fn_in']))
	{
		$_SESSION['_loginUser']->cell = trim($_POST['ce_in']);
		$_SESSION['_loginUser']->fullname = $_POST['fn_in'];
		$_SESSION['_loginUser']->uname = $_POST['un_in'];
		$_SESSION['_loginUser']->description = $_POST['ds_in'];
		$_SESSION['_loginUser']->company = $_POST['co_in'];
		$_SESSION['_loginUser']->title = $_POST['ti_in'];
		$_SESSION['_loginUser']->weibo = $_POST['we_in'];

		$result_pf = $_SESSION['_loginUser']->updateUser();		
	}
	else
	{
		$result_pf = 3;
	}
	
}
if(isset($_POST['pw']) && !empty($_POST['pw']) && $_POST['pw']=='pw')
{
	$result_pw = $_SESSION['_loginUser']->updatePassword($_POST['pw_old_in'], $_POST['pw_new_in']);
}
if(isset($_POST['org_h']) && $_POST['org_h']==1)
{
	$_SESSION['_loginUser']->email = trim($_POST['org_em']);
	$_SESSION['_loginUser']->fullname = trim($_POST['org_na']);
	$_SESSION['_loginUser']->company = trim($_POST['org_co']);
	$_SESSION['_loginUser']->title = trim($_POST['org_ti']);
	$_SESSION['_loginUser']->updateUser();	

	$message = "Name: ".$_POST['org_na'].'<br />'.
			   "Email: ".$_POST['org_em'].'<br />'.
			   "Company: ".$_POST['org_co'].'<br />'.
			   "Title: ".$_POST['org_ti'].'<br />'.
			   "Uid: ".$_SESSION['_userId'].'<br />';

	$result = Message::create('App for Organizor', 1, $_SESSION['_userId'], $message );
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title="Profile";
	$l_bu_chg="Submit";
	$l_bu_img="Change Profile Image";
	$l_update = "Submit";
	$l_p_email="Email：";
	$l_p_fname="Full Name：";
	$l_p_uname="User Name：";
	$l_p_description="Self Description：";
	$l_p_company="Company：";
	$l_p_title="Title：";
	$l_p_cell="Cellphone：";
	$l_p_type = "User Type：";
	$l_p_type_c = "Event Organizer";
	$l_p_type_e = "Event Participant";
	$l_p_update = "Become an Event Organizer";
	$l_p_weibo="Twitter：";
	$l_p_oldpasswd="Old Password：";
	$l_p_newpasswd="New Password：";
	$l_error_pw="Incorrect old password";
	$l_succ_pw="Password successfully changed";
	$l_error_pf="Information update failed";
	$l_error_pf1="System busy, please try again later";
	$l_error_pf2="Invalid phone number format";
	$l_error_pf3="Please enter full name";
	$l_succ_pf="Information successfully changed";
	$l_e_fname_r="Enter full name";
	$l_e_fname_max="Less than 40 characters";
	$l_e_uname_max="Less than 12 characters";
	$l_e_email_max="Less than 11 numbers";
	$l_e_weibo_max="Less than 40 characters";	
	$l_e_old_pw_r="Enter old password";
	$l_e_new_pw_r="Enter new password";
	$l_e_pw_min="Use 6 to 20 characters";
	$l_e_pw_max="Use 6 to 20 characters";
	$l_file_size="(Support jpg、png、gif, <span style='color:red'>less than 1MB</span>) ";
	$l_file_preview="Preview";
	$a_sel_pic="Please select an image before uploading";
	$a_pic_size="Please upload jpg、png、gif image which is less than 1MB";
	$a_pic_err="Image contains error, please upload another one";
	$a_pic_fail="Upload fails, please try again";
	$a_not_crop="Please select an area to crop the image";
	$l_browse_file="Browse File";
	$l_note="Note：Once you are an Event Organizer, you could publish your events. Here is some information.";
	$l_note_a="a) ConfOne supports events with some content, e.g. Speaker, Schedule, Document sharing, Event statistic (coming soon) and etc.";
	$l_note_b="b) ConfOne will generate an event website and a mobile website for each of your events.";
	$l_note_c="c) Visit website at <span style='color:blue'>www.confone.com</span> plus link code and mobile website at <span style='color:blue'>m.confone.com</span>.（iPhone/Andriod Apps coming soon...)";
	$l_note_z="* Please enter the following information to gain Event Organizer privileges.";
	$l_o_fname = "Full Name : ";
	$l_o_email = "Email : ";
	$l_o_company = "Company/Organization : ";
	$l_o_title = "Your Position : ";
	$l_bu_cesub = "Submit";
	$l_bu_ceres = "Reset";
	$l_org_succ = "Thanks for your interests. We will contact you within 24 hrs.";
	$l_org_fail = "Sorry, System is temporarily not available, please try again later.";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title="个人资料";
	$l_bu_chg="保存";
	$l_update = "更改";
	$l_bu_img="修改头像";
	$l_p_email="邮箱：";
	$l_p_fname="真实姓名：";
	$l_p_uname="用户昵称：";
	$l_p_description="个人介绍：";
	$l_p_company="公司：";
	$l_p_title="职位：";
	$l_p_cell="手机：";
	$l_p_type = "用户类型：";
	$l_p_type_c = "会议活动参与者";
	$l_p_type_e = "会议活动组织者";
	$l_p_update = "申请成为会议活动组织者";
	$l_p_weibo="微博：";
	$l_p_oldpasswd="旧密码：";
	$l_p_newpasswd="新密码：";
	$l_error_pw="旧密码错误";
	$l_succ_pw="密码修改成功";
	$l_error_pf="个人信息更新失败";
	$l_error_pf1="系统忙，请稍候再试";
	$l_error_pf2="电话号码格式有误";
	$l_error_pf3="请输入您的真实姓名";
	$l_succ_pf="个人信息更新成功";
	$l_e_fname_r="请输入您的真实姓名";
	$l_e_fname_max="姓名不大于40位";
	$l_e_uname_max="用户昵称不大于12位";	
	$l_e_email_max="电话号码小于11位";	
	$l_e_weibo_max="微博不大于40位";	
	$l_e_old_pw_r="请输入旧密码";
	$l_e_new_pw_r="请输入新密码";
	$l_e_pw_min="密码长度不小于6位";
	$l_e_pw_max="密码长度不大于20位";
	$l_file_size="(支持jpg、png、gif，<span style='color:red'>小于 1MB</span>) ";
	$l_file_preview="预览";
	$a_sel_pic="请选择你要上传的图片";
	$a_pic_size="请上传小于1MB的jpg、png、gif格式图片";
	$a_pic_err="上传的图片有误，请选择正确图片上传";
	$a_pic_fail="上传失败，请重试一次";
	$a_not_crop="请选择一个区域进行裁剪";
	$l_browse_file="选择文件";
	$l_note="提示：成为会议活动组织者后您将可以发布会议活动，以下是一些基本信息";
	$l_note_a="a) 会云支持有一定内容性的活动，如：嘉宾分享，日程安排，分档共享，活动数据统计（即将推出）等。";
	$l_note_b="b) 为方便推广您的活动会云将自动生成一个网络页面，和一个移动页面（手机浏览器访问，在活动过程中以便用户查找细节信息）。";
	$l_note_c="c) 网络页面通过 <span style='color:blue'>www.confone.com</span> 加活动链接码访问，手机页面通过 <span style='color:blue'>m.confone.com</span> 访问。（iPhone/Andriod 原生 App 即将推出)";
	$l_note_z="* 请输入以下基本信息获取会议活动组织者功能。";
	$l_o_fname = "姓名：";
	$l_o_email = "邮箱：";
	$l_o_company = "公司/组织：";
	$l_o_title = "职位：";
	$l_bu_cesub = "确定";
	$l_bu_ceres = "重置";
	$l_org_succ = "感谢您的申请。我们会在24小时之内与您联系。";
	$l_org_fail = "对不起，现在系统忙，请稍候在试。我们对给您带来的不便表示歉意。";
}

//=========================================================================================================

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<style type="text/css">html,body{margin:0;padding:0;}</style>
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/home.css" />
<link type="text/css" rel="stylesheet" href="../css/jquery.Jcrop.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<script type="text/JavaScript" src="../js/jquery-1.6.2.min.js"></script>
<script type="text/JavaScript" src="../js/jquery.validate.min.js"></script>
<script type="text/JavaScript" src="../js/jquery.Jcrop.min.js"></script>
<script type="text/JavaScript" src="../js/ajaxfileupload.js"></script>
<style>
form#profile_form label.error{background: url('../image/error.png') no-repeat center left;color: red;font-size: .9em;padding-left: 1em;margin-left: 0.5em;z-index: 1;}
form#profile_form label.valid{background: url('../image/check.gif') no-repeat center left;padding-right: 10px;}
form#passwd_form label.error{background: url('../image/error.png') no-repeat center left;color: red;font-size: .9em;padding-left: 1em;margin-left: 0.5em;z-index: 1;}
form#passwd_form label.valid{background: none;padding-right: 10px;}
label.info{font-size:.9em;color:#333;}
div.hidden{min-height:500px;position:normal;z-index:2000;}
</style>
<script type="text/javascript">
jQuery.validator.setDefaults({
	debug: false,
	success: "valid"
});

$(document).ready(function(){
	$("#profile_form").validate({
		rules: {
    		   	fn_in: {
    					required: true,
    					maxlength: 40
  					   },
  			   	un_in: {
  						maxlength: 12
  					   },
    		   	ce_in: {
    				    maxlength: 11,
    			   	   },
			   	we_in: {
						maxlength: 40
					   }
			   },
		messages: {
				   fn_in: {
				      	   required: "<?php echo $l_e_fname_r?>",
 						   maxlength: "<?php echo $l_e_fname_max?>"
				 	      },
				   un_in: {
				      	    maxlength: "<?php echo $l_e_uname_max?>"
				 	      },
				   ce_in: {
				      	    maxlength: "<?php echo $l_e_email_max?>",
				      	    cell: "<?php echo $l_error_pf2?>"
				 	      },
				   we_in: {
				      	    maxlength: "<?php echo $l_e_weibo_max?>"
				 	      }
				  }
	});
});

$(document).ready(function(){
	$("#passwd_form").validate({
		rules: {
    		   	pw_old_in: {
    						required: true,
    						minlength: 6,
    						maxlength: 20
  					  	   },
			   	pw_new_in: {
							required: true,
							minlength: 6,
							maxlength: 20
						   }
			   },
		messages: {
				   pw_old_in: {
     						   required: "<?php echo $l_e_old_pw_r?>",
    						   minlength: "<?php echo $l_e_pw_min?>",
    						   maxlength: "<?php echo $l_e_pw_max?>"
 							   },
				   pw_new_in: {
      						   required: "<?php echo $l_e_new_pw_r?>",
    						   minlength: "<?php echo $l_e_pw_min?>",
    						   maxlength: "<?php echo $l_e_pw_max?>"
	  						  }
				  }
	});
});
</script>

<script type="text/javascript">
jQuery(function($){
      // Create variables (in this scope) to hold the API and image size
      var jcrop_api, boundx, boundy;
      
      $('#target').Jcrop({
        onChange: updatePreview,
        onSelect: updatePreview,
	    boxWidth: 400, 
	    boxHeight: 400,
        aspectRatio: 1
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
          var rx = 100 / c.w;
          var ry = 100 / c.h;

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

    //ajax upload profile image
	  $("#file").live('change',function() {
		    $("#progress").attr("style", "display:inline");
			$("#x1").val("");
			$("#y1").val("");
			$("#x2").val("");
			$("#y2").val("");
		    var fileName=$("#filename").val();
		    if($("#file").val()=="")
		    {
		    	alert("<?php echo $a_sel_pic?>");
		    	return false;
		    }
		    $.ajaxFileUpload(	
	        {
              url:"../utils/AjaxUploadImg.inc.php",
              secureuri:false,
              fileElementId:'file',
              data:{'fileElement':'file', 'fileSize':'1', 'fileName':fileName},
              dataType:'json', 
              success: function (data)
              {
              	if (data.error==0)
              	{
					jcrop_api.setImage(data.url);
					$("#target").attr("src", data.url);
					$("#preview").attr("src", data.url);
					boundx=data.width;
					boundy=data.height;
					var ratio=1;
					if (boundx > boundy) {ratio=100 / boundy;}
					else {ratio=100 / boundx; }				        
					$('#preview').css({width: Math.round(ratio * boundx)+'px', height: Math.round(ratio * boundy)+'px', marginLeft: '0px', marginTop: '0px'});
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

	$("#SaveImg").click(function() {		
		  var x1=$("#x1").val(); 
		  var y1=$("#y1").val(); 
		  var x2=$("#x2").val(); 
		  var y2=$("#y2").val();	
		  var target_src = $("#target").attr("src");

		  if (target_src.indexOf("image/default/default_pic_gray1x1") >= 0)
		  {	  
			  alert("<?php echo $a_sel_pic ?>");
			  return false;
		  }
		  else  if (x1=='' || y1=='' || x2=='' || y2=='' || x1==x2 || y1==y2)
		  {	  
			  alert("<?php echo $a_not_crop ?>");
			  return false;
		  }
	 });      
});
</script>
</head>
<body <?php if(isset($result)) echo "onload=\"alert('".($result != -1 ? $l_org_succ : $l_org_fail)."')\""?>>
<div style="width:100%;">
<?php $_GET['f']='p'; include_once '../common/header.inc.php'; unset($_GET['p'])?>
<div class="stand_width">
<div id="hmain">
<div id="hright">
</div>
<div id="hleft">
<div id="in_con3">
<a id=img></a>
<table border="0">
<tr>
<td class="first">
<div style="width:100px;height:100px;border: 1px #999 solid;">
<img src="<?php echo $_SESSION['_loginUser']->pic; ?>" id="profile_img" />
</div></td>
<td valign="bottom"><a style="margin-left:15px;width:130px;font-size:.9em;" href="javascript:showCE('profImg');"><?php echo $l_bu_img?></a>
</td>
</tr>
</table>
</div>
<div style="border-top:1px #DDD solid;width:90%;margin:auto;"></div>
<div id="">
<a id=pf></a>
<form id="profile_form" name="profile_form" method="post" enctype="multipart/form-data" action="profile.php#pf" accept-charset="UTF-8">
<table border="0">
<tr>
<td class="first"><label><?php echo $l_p_fname ?></label></td>
<td><input type="text" name="fn_in" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->fullname ?>" /></td>
</tr>
<tr>
<td class="first"><label><?php echo $l_p_type ?></label></td>
<td><label class="info"><?php echo ($_SESSION['_loginUser']->reg_type == 'E' ? $l_p_type_e : $l_p_type_c.' <span style="font-size:.8em;">( <a href="javascript:showCE(\'org\');">'.$l_p_update.'</a> )</span>') ?></label></td>
</tr>
<tr>
<td class="first"><label><?php echo $l_p_email  ?></label></td>
<td><label class="info"><?php echo $_SESSION['_loginUser']->email ?></label></td>
</tr>
<tr>
<td class="first"><label><?php echo $l_p_uname ?></label></td>
<td><input type="text" name="un_in" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->uname ?>" /></td>
</tr>
<tr>
<td class="first"><label><?php echo $l_p_company ?></label></td>
<td><input type="text" name="co_in" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->company ?>" /></td>
</tr>
<tr>
<td class="first"><label><?php echo $l_p_title ?></label></td>
<td><input type="text" name="ti_in" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->title ?>" /></td>
</tr>
<tr>
<td class="first"><label><?php echo $l_p_cell?></label></td>
<td><input type="text" name="ce_in" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->cell ?>" /></td>
</tr>
<tr>
<td class="first"><label><?php echo $l_p_weibo ?></label></td>
<td><input type="text" name="we_in" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->weibo ?>" /></td>
</tr>
<tr>
<td class="first" style="vertical-align:top;padding-top:8px;">
<script charset="utf-8" src="../kindeditor/kindeditor-min.js"></script>
<script>KE.show({ id : 'ds_in', allowFileManager : false, allowUpload : false });</script>
<label><?php echo $l_p_description ?></label></td>
<td><textarea style="width:530px;height:300px;" id="ds_in" name="ds_in" class="reg_in round_border_4" ><?php echo $_SESSION['_loginUser']->description ?></textarea></td>
</tr>
<tr>
<td></td>
<td>
<?php 
	if (isset($result_pf) && $result_pf == 0) 
	{
		echo '<div style="text-align:center;margin-top:5px;width:326px;border:1px #122B12 solid;background-color:#5CD65C;color:white;"><div style="padding:10px;">';
	    echo $l_succ_pf;
		echo '</div></div>';
	}
	else if (isset($result_pf) && $result_pf > 0) 
	{
		echo '<div style="text-align:center;margin-top:5px;width:326px;border:1px #CC0000 solid;background-color:#F08080;color:white;"><div style="padding:10px;">';
		if ($result_pf == 1)
		{
			echo $l_error_pf1;			
		}
		else if ($result_pf == 2)
		{
			echo $l_error_pf2;			
		}
		else if ($result_pf == 3)
		{
			echo $l_error_pf3;			
		}
		echo '</div></div>';
	}			
?>
</td>
</tr>
<tr>
<td></td>
<td style="padding-left:197px;">
<input type="hidden" name="pf" value="pf" />
<button type="submit" class="reg round_border_4" style="margin-top:15px;width:130px;"><span class="reg"><?php echo $l_bu_chg?></span></button>
</td>
</tr>
</table>
</form>
</div>
<div style="border-top:1px #DDD solid;width:90%;margin:auto;"></div>
<div>
<a id=pw></a>
<form id="passwd_form" method="post" enctype="multipart/form-data" action="profile.php#pw" name="passwd_form" accept-charset="UTF-8">
<table border="0">
<tr>
<td class="first"><label><?php echo $l_p_oldpasswd ?></label></td>
<td><input type="password" name="pw_old_in" class="reg_in round_border_4" value="" /></td>
</tr>
<tr>
<td class="first"><label><?php echo $l_p_newpasswd ?></label></td>
<td><input type="password" name="pw_new_in" class="reg_in round_border_4" value="" /></td>
</tr>
<tr>
<td></td>
<td>				
<?php 
	if (isset($result_pw) && $result_pw == false) 
	{
		echo '<div style="text-align:center;margin-top:5px;width:326px;border:1px #CC0000 solid;background-color:#F08080;color:white;"><div style="padding:10px;">';
	    echo $l_error_pw;
		echo '</div></div>';
	}
	else if (isset($result_pw) && $result_pw == true) 
	{
		echo '<div style="text-align:center;margin-top:5px;width:326px;border:1px #122B12 solid;background-color:#5CD65C;color:white;"><div style="padding:10px;">';
	    echo $l_succ_pw;
		echo '</div></div>';
	}
?>
</td>
</tr>
<tr>
<td></td>
<td style="padding-left:197px;">
<input type="hidden" name="pw" value="pw" />
<button type="submit" class="reg round_border_4" style="margin-top:15px;width:130px;"><span class="reg"><?php echo $l_update?></span></button>
</td>
</tr>
</table>
</form>

</div>
<div id="near_by">
</div>
</div>
</div>
<div style="width:100%;border-bottom:1px solid #DDD;margin-bottom:10px;"></div>
</div>
</div>
<?php include_once '../common/footer.inc.php';?>
<div id="screen"></div>
<div id="profImg" class="cevent round_border_20 hidden">
<div id="cbutton" onclick="dismissCE('profImg')"></div>
<div style="padding:20px 20px 20px 20px;" align="center">
<table>
<tr>
<td>
<div style="width:400px;height:400px;border:1px #999 solid;background:#ddd;display:table-cell;vertical-align:middle;" align="center">
<img src="../image/default/default_pic_gray1x1.png" id="target" />
<img src="../image/progress-bar.gif" id="progress" style="display:none;"/>
</div>
</td>
<td align="center">
<label><?php echo $l_file_preview ?></label>
<div style="width:100px;height:100px;overflow:hidden;border: 1px #999 solid;">
<img src="../image/default/default_pro_pic.png" id="preview" />
</div>
</td>
</tr>
<tr align="left">
<td>
<form id="upload_form" name="upload_form" method="post" action="../utils/AjaxUploadImg.inc.php" enctype="multipart/form-data">
<input type="hidden" id="filename" name="filename" value="../tmp/temp_profile_img.<?php echo $_SESSION['_userId'];?>" />
<label><?php echo $l_file_size ?></label>
<span class="uploadImgSpan">
<input type="file" name="file" id="file" size="1" />
<input type="button" value="<?php echo $l_browse_file ?>"></input></span>
</form>
</td>
<td align="center">
<form id="simg" method="post" action="../utils/saveProImg.inc.php" enctype="multipart/form-data">
<input type="hidden" name="filename" value="../tmp/temp_profile_img.<?php echo $_SESSION['_userId'];?>" />
<input type="hidden" name="fpage" value="../home/profile.php"/>
<input type="hidden" id="x1" name="x1" />
<input type="hidden" id="y1" name="y1" />
<input type="hidden" id="x2" name="x2" />
<input type="hidden" id="y2" name="y2" />
<input type="submit" id="SaveImg" value="<?php echo $l_bu_chg ?>" />
</form>
</td>
</tr>
</table>
</div>
</div>
<div id="org" class="cevent round_border_20 hidden">
<div id="cbutton" onclick="dismissCE('org')"></div>
<div align="center">
<div class="round_border_6" style="text-align:left;padding:0 0 0 10px;margin:50px 0 20px 0;border:1px #BFBF04 solid;background-color:#FFFFDD;color:black;font-size:0.8em;width:800px;">
<p><b><?php echo $l_note ?></b></p>
<p style="margin-left:20px;"><?php echo $l_note_a ?></p>
<p style="margin-left:20px;"><?php echo $l_note_b ?></p>
<p style="margin-left:20px;"><?php echo $l_note_c ?></p>
<p><?php echo $l_note_z ?></p>
</div>
<form id="org_form" name="org_form" method="post" action="../home/profile.php" enctype="multipart/form-data">
<label></label>
<table>
<tr><td class="first"><label class="info"><?php echo $l_o_fname?></label></td>
<td><input type="text" name="org_na" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->fullname ?>" /> <label style="color:red">*</label></td></tr>
<tr><td class="first"><label class="info"><?php echo $l_o_email?></label></td>
<td><label><?php echo $_SESSION['_loginUser']->email ?></label></td></tr>
<tr><td class="first"><label class="info"><?php echo $l_o_company?></label></td>
<td><input type="text" name="org_co" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->company ?>" /> <label style="color:red">*</label></td></tr>
<tr><td class="first"><label class="info"><?php echo $l_o_title?></label></td>
<td><input type="text" name="org_ti" class="reg_in round_border_4" value="<?php echo $_SESSION['_loginUser']->title ?>" /> <label style="color:red">*</label></td></tr>
</table>
<input name="org_h" type="hidden" value="1" />
<input id="btnInsConf" style="height:30px;" class="btn" type="submit" value="<?php echo $l_bu_cesub ?>" />
<input id="btnReset" style="height:30px;" class="btn" type="reset" value="<?php echo $l_bu_ceres ?>" />
</form>
</div>
</div>
</body>
</html>