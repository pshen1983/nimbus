<style>
	a.footer{color:#000080;font-family:tahoma;font-size:12px;text-decoration:none;}
	a.footer:hover{text-decoration:underline;}
	label.footer{padding-left:1px;padding-right:1px;color:#AAAAAA;font-size:14px;}
	div#lang_list{visibility:hidden;position:absolute;background-color:#F8F8F8;padding:0px 3px 0px 3px;border-right:1px solid #DDD;border-bottom:1px solid #DDD;}
	a.icp{color:#AAA;font-family:tahoma;font-size:12px;text-decoration:none;margin-left:10px;}
	a.icp:hover{color:#333}
</style>
<script type="text/JavaScript" src="../js/common.js"></script>
<?php 
	if($_SESSION['_language']=='en') {
		$l_about = 'About';
		$l_contact = 'Contact';
		$l_help = 'Help';
		$l_feedback = 'Feedback';
		$l_follow = 'Follow';
		$l_language = 'Choose Language';
		$l_company = "ConfOne";
	}
	else if($_SESSION['_language']=='zh') {
		$l_about = '&#20851;&#20110;';
		$l_contact = '&#32852;&#31995;&#25105;&#20204;';
		$l_help = '&#24110;&#21161;';
		$l_feedback = '&#24847;&#35265;&#21453;&#39304;';
		$l_follow = '&#20851;&#27880;&#25105;&#20204;';
		$l_language = '&#36873;&#25321;&#35821;&#35328;';
		$l_company = "会云网";
	}
?>
<div style="width:100%;height:5px;"></div>
<div style="width:980px;margin:auto;height:50px;">
<label style="margin-top:5px;font-size:12px;color:#AAAAAA;float:left;padding-left:2.5%;font-family:tahoma;">&copy; <?php echo date('Y')." ".$l_company?><a class="icp" href="http://www.miibeian.gov.cn/" target="_blank">沪ICP备1103567号</a></label>
<div style="float:right;padding-right:40px;" >
<a id="lang_link" href="javascript:getLangList()" class="footer"><?php echo $l_language?> <span id="lang_arrow">&#9660;</span></a>
<label class="footer">|</label>
<a href="../about/index.php" class="footer"><?php echo $l_about?></a>
<label class="footer">|</label>
<a href="../about/feedback.php" class="footer"><?php echo $l_feedback?></a>
<label class="footer">|</label>
<a href="../about/contact.php" class="footer"><?php echo $l_contact?></a>
</div>
<div id="lang_list">
<?php include_once 'language.inc.php';?>
</div>
</div>