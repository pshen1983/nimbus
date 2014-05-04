<?php 
	if($_SESSION['_language']=='en') {
		$l_ft_company = "ConfOne";
	}
	else if($_SESSION['_language']=='zh') {
		$l_ft_company = "会云网";
	}
?>
<style>
a.footer
{
	text-decoration:none;
	font-size:.8em;
	text-shadow:none;
}
</style>
<div data-role="footer" data-theme="b">
<div style="margin-bottom:5px;">
<a class="footer" style="color:#000080" href="javascript:changeLang('en')" onclick="this.style.color='#fff'">English (US)</a> ·
<a class="footer" style="color:#000080" href="javascript:changeLang('zh')" onclick="this.style.color='#fff'">简体中文</a><br />
</div>
<label><?php echo $l_ft_company?> &copy; <?php echo date('Y')?></label></div>