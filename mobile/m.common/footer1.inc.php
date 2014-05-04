<?php 
	if($_SESSION['_language']=='en') {
		$l_ft_company = "ConfOne";
		$l_about = "About us";
		$l_contact = "Contact";
	}
	else if($_SESSION['_language']=='zh') {
		$l_ft_company = "会云网";
		$l_about = "关于会云";
		$l_contact = "联系我们";
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
<div style="margin:0 0 5px 2px;">
<a class="footer" style="color:#000080" data-transition="slideup" href="../m.default/about.php" onclick="this.style.color='#fff'"><?php echo $l_about;?></a> ·
<a class="footer" style="color:#000080" data-transition="slideup" href="../m.default/contact.php" onclick="this.style.color='#fff'"><?php echo $l_contact?></a><br />
</div>
<label><?php echo $l_ft_company?> &copy; <?php echo date('Y')?></label></div>