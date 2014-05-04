<?php
include_once ("../utils/CommonUtil.php");
include_once ("../utils/DatabaseUtil.php");
include_once ("../_obj/Conference.php");

function confInfoElt($color, $url, $img, $name, $tprint, $faddr)
{
//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_time = "Time: ";
	$l_loca = "Location: ";
}
else if($_SESSION['_language'] == 'zh') {
	$l_time = "时间：";
	$l_loca = "地点：";
}
	return '<li style="background-color:'.$color.';">'.
			'<div class="conf_link">'.
			'<table>'.
			'<tr><td><div class="img_f" style="background:url(\''.$img.'\') no-repeat center center;"></div></td>'.
			'<td style="padding-left:20px;color:#000080">'.
			'<a class="ctitle" target="_blank" href="../conference/info_home.php?url='.$url.'">'.$name.'</a>'.
			'<div style="margin-top:10px;font-size:.8em;color:#333;">'.
			'<span style="font-weight:bold">'.$l_time.'</span>'.$tprint.'<br />'.
			'<span style="font-weight:bold">'.$l_loca.'</span>'.$faddr.
			'</div></td></tr></table></div></li>';
}

function confNoElt($msg)
{
	//return '<div style="margin:10px 0 10px 20px;color:#589ED0"><label>'.$msg.'</label></div>';
	return 'none';
}

session_start();
$data = new stdClass();

if($_SESSION['_language'] == 'en') {
	$l_no_neighbor = "No Event Around You";
	$l_no_samecity = "No Event In Your City";
	$l_no_sameprov = "No Event In Your Province";
	$l_no_other = "No Other Event";
	$l_no_all = "No Event";
}
else if($_SESSION['_language'] == 'zh') {
	$l_no_neighbor = "您周围没有会议活动";
	$l_no_samecity = "没有同城的会议活动";
	$l_no_sameprov = "没有同省的会议活动";
	$l_no_other = "没有其他会议活动";
	$l_no_all = "没有会议活动";
}

$cids = '0';	
$list_all = '';
$ind = 0;

$city = $_SESSION['_geocity'];
$prov = $_SESSION['_geoprov'];

if( isset($_POST['lat']) && !empty($_POST['lat']) && isset($_POST['lng']) && !empty($_POST['lng']) )
{	
//	$range = CommonUtil::getLatLongRange($_POST['lat'], $_POST['lng'], 10);
//	$lat1 = $_POST['lat']-$range[0];
//	$lat2 = $_POST['lat']+$range[0];
//	$lng1 = $_POST['lng']-$range[1];
//	$lng2 = $_POST['lng']+$range[1];	
//	
//	//neighbor
//	$confWithin = Conference::getNeighborConfInfoWithin($lat1, $lng1, $lat2, $lng2, 'P');	
//	foreach ($confWithin as $conf) 
//	{
//		$sftime = strtotime($conf->stime);
//		$sdate = date('Y-m-d', $sftime);
//		$stime = date('H:i', $sftime);
//		$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');
//	
//		if( isset($conf->etime) && !empty($conf->etime) )
//		{
//			$eftime = strtotime($conf->etime);
//			$edate = date('Y-m-d', $eftime);
//			$etime = date('H:i', $eftime);
//			$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
//		}
//		$color = "#f1f1f1";
//		if ($ind%2==0)
//		{
//			$color = "#f1f1f1";
//		}
//		else
//		{
//			$color = "#ffffff";		
//		}
//		
//		$cids = $cids.','.$conf->getCid();
//		
//		$list = confInfoElt($color, $conf->getUrl(), $conf->img, $conf->name, $tprint, $conf->getFaddr());
//		
//		$list_all = $list_all.$list;
//		$ind++;
//	}
//	if($ind==0) 
//	{
//		$data->neighbor = confNoElt($l_no_neighbor);
//	}
//	else
//	{		
//		$data->neighbor = '<ul class="clist">'.$list_all.'</ul>';
//	}
	
	//same city
	$list_all = '';
	$ind = 0;	
	$confSameCity = Conference::getConfInfoSameCity($city, $cids, 'P');	
	foreach ($confSameCity as $conf) 
	{
		$sftime = strtotime($conf->stime);
		$sdate = date('Y-m-d', $sftime);
		$stime = date('H:i', $sftime);
		$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');
	
		if( isset($conf->etime) && !empty($conf->etime) )
		{
			$eftime = strtotime($conf->etime);
			$edate = date('Y-m-d', $eftime);
			$etime = date('H:i', $eftime);
			$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
		}
		$color = "#f1f1f1";
		if ($ind%2==0)
		{
			$color = "#f1f1f1";
		}
		else
		{
			$color = "#ffffff";		
		}
		
		$cids = $cids.','.$conf->getCid();	
		
		$list = confInfoElt($color, $conf->getUrl(), $conf->img, $conf->name, $tprint, $conf->getFaddr());
		$list_all = $list_all.$list;
		$ind++;
	}
	if($ind==0) 
	{
		$data->samecity = confNoElt($l_no_samecity);
	}
	else
	{		
		$data->samecity = '<ul class="clist">'.$list_all.'</ul>';
	}

	//same prov
	$list_all = '';
	$ind = 0;	
	$confSameProv = Conference::getConfInfoSameProv($prov, $cids, 'P');	
	foreach ($confSameProv as $conf) 
	{
		$sftime = strtotime($conf->stime);
		$sdate = date('Y-m-d', $sftime);
		$stime = date('H:i', $sftime);
		$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');
	
		if( isset($conf->etime) && !empty($conf->etime) )
		{
			$eftime = strtotime($conf->etime);
			$edate = date('Y-m-d', $eftime);
			$etime = date('H:i', $eftime);
			$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
		}
		$color = "#f1f1f1";
		if ($ind%2==0)
		{
			$color = "#f1f1f1";
		}
		else
		{
			$color = "#ffffff";		
		}
		
		$cids = $cids.','.$conf->getCid();
		
		$list = confInfoElt($color, $conf->getUrl(), $conf->img, $conf->name, $tprint, $conf->getFaddr());
		$list_all = $list_all.$list;
		$ind++;
	}
	if($ind==0) 
	{
		$data->sameprov = confNoElt($l_no_sameprov);
	}
	else
	{		
		$data->sameprov = '<ul class="clist">'.$list_all.'</ul>';
	}
	
	//same other
	$list_all = '';
	$ind = 0;	
	$confOther = Conference::getConfInfoOther($cids, 'P');	
	foreach ($confOther as $conf) 
	{
		$sftime = strtotime($conf->stime);
		$sdate = date('Y-m-d', $sftime);
		$stime = date('H:i', $sftime);
		$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');
	
		if( isset($conf->etime) && !empty($conf->etime) )
		{
			$eftime = strtotime($conf->etime);
			$edate = date('Y-m-d', $eftime);
			$etime = date('H:i', $eftime);
			$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
		}
		$color = "#f1f1f1";
		if ($ind%2==0)
		{
			$color = "#f1f1f1";
		}
		else
		{
			$color = "#ffffff";		
		}
		
		$cids = $cids.','.$conf->getCid();
		
		$list = confInfoElt($color, $conf->getUrl(), $conf->img, $conf->name, $tprint, $conf->getFaddr());
		$list_all = $list_all.$list;
		$ind++;
	}
	if($ind==0) 
	{
		$data->other = confNoElt($l_no_other);
	}
	else
	{		
		$data->other = '<ul class="clist">'.$list_all.'</ul>';
	}
	
}
else
{
//	$data->neighbor = confNoElt($l_no_neighbor);
	
	//same city
	$list_all = '';
	$ind = 0;	
	$confSameCity = Conference::getConfInfoSameCity($city, $cids, 'P');	
	foreach ($confSameCity as $conf) 
	{
		$sftime = strtotime($conf->stime);
		$sdate = date('Y-m-d', $sftime);
		$stime = date('H:i', $sftime);
		$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');
	
		if( isset($conf->etime) && !empty($conf->etime) )
		{
			$eftime = strtotime($conf->etime);
			$edate = date('Y-m-d', $eftime);
			$etime = date('H:i', $eftime);
			$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
		}
		$color = "#f1f1f1";
		if ($ind%2==0)
		{
			$color = "#f1f1f1";
		}
		else
		{
			$color = "#ffffff";		
		}
		
		$cids = $cids.','.$conf->getCid();
		
		$list = confInfoElt($color, $conf->getUrl(), $conf->img, $conf->name, $tprint, $conf->getFaddr());
		$list_all = $list_all.$list;
		$ind++;
	}
	if($ind==0) 
	{
		$data->samecity = confNoElt($l_no_samecity);
	}
	else
	{		
		$data->samecity = '<ul class="clist">'.$list_all.'</ul>';
	}

	//same prov
	$list_all = '';
	$ind = 0;	
	$confSameProv = Conference::getConfInfoSameProv($prov, $cids, 'P');	
	foreach ($confSameProv as $conf) 
	{
		$sftime = strtotime($conf->stime);
		$sdate = date('Y-m-d', $sftime);
		$stime = date('H:i', $sftime);
		$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');
	
		if( isset($conf->etime) && !empty($conf->etime) )
		{
			$eftime = strtotime($conf->etime);
			$edate = date('Y-m-d', $eftime);
			$etime = date('H:i', $eftime);
			$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
		}
		$color = "#f1f1f1";
		if ($ind%2==0)
		{
			$color = "#f1f1f1";
		}
		else
		{
			$color = "#ffffff";		
		}
		
		$cids = $cids.','.$conf->getCid();
		
		$list = confInfoElt($color, $conf->getUrl(), $conf->img, $conf->name, $tprint, $conf->getFaddr());
		$list_all = $list_all.$list;
		$ind++;
	}
	if($ind==0) 
	{
		$data->sameprov = confNoElt($l_no_sameprov);
	}
	else
	{		
		$data->sameprov = '<ul class="clist">'.$list_all.'</ul>';
	}
	
	//same other
	$list_all = '';
	$ind = 0;	
	$confOther = Conference::getConfInfoOther($cids, 'P');	
	foreach ($confOther as $conf) 
	{
		$sftime = strtotime($conf->stime);
		$sdate = date('Y-m-d', $sftime);
		$stime = date('H:i', $sftime);
		$tprint = $sdate.(($stime!='00:00') ? ' '.$stime : '');
	
		if( isset($conf->etime) && !empty($conf->etime) )
		{
			$eftime = strtotime($conf->etime);
			$edate = date('Y-m-d', $eftime);
			$etime = date('H:i', $eftime);
			$tprint = $tprint.' - '.$edate.(($etime!='00:00') ? ' '.$etime : '');
		}
		$color = "#f1f1f1";
		if ($ind%2==0)
		{
			$color = "#f1f1f1";
		}
		else
		{
			$color = "#ffffff";		
		}
		
		$cids = $cids.','.$conf->getCid();
		
		$list = confInfoElt($color, $conf->getUrl(), $conf->img, $conf->name, $tprint, $conf->getFaddr());
		$list_all = $list_all.$list;
		$ind++;
	}
	if($ind==0) 
	{
		$data->other = confNoElt($l_no_other);
	}
	else
	{		
		$data->other = '<ul class="clist">'.$list_all.'</ul>';
	}	
}
$data->all = '<div style="margin:10px 0 10px 20px;color:#666"><label>'.$l_no_all.'</label></div>';
echo json_encode($data);
?>  