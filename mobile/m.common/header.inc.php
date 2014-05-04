<?php 
	if($_SESSION['_language']=='en') {
		$l_back = "Back";
		$l_home = "Home";
	}
	else if($_SESSION['_language']=='zh') {
		$l_back = "返回";
		$l_home = "主页";
	}
?>
<div data-role="header" data-theme="b">
<a data-rel="back" data-icon="arrow-l"  data-direction="reverse"><?php echo $l_back?></a>
<h2><?php echo $pageTitle;?></h2>
<a href="../m.home/index.php" data-icon="home" data-transition="pop"><?php echo $l_home?></a>
</div>
