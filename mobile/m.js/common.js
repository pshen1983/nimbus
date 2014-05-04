function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}
function changeCodeImage(id, r)
{
	var end = new Date();
	document.getElementById(id).src="../utils/getCode.php?r="+r+"&time="+end.getTime();
}
function changeLang(lang) {
	var http = GetXmlHttpObject();

	http.open("GET", "../common/change_lang.inc.php?l="+lang, true);
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			if (http.responseText == 0) {
				location.reload(true);
			}
		}
	}
	http.send(null);
}
function nEvents() {
	var geocoder;
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var lat = position.coords.latitude;
			var lng = position.coords.longitude;
			window.location='../m.home/location.php?lat='+lat+'&lng='+lng;
		});
	}
	else {
		window.location='../m.home/location.php';
	}
}
function showAct(height) {
	var sheet = document.getElementById("action");

	if( typeof window.innerHeight != 'undefined' ) {
		viewportheight = window.innerHeight
	}
	else if( typeof document.documentElement != 'undefined' && 
			 typeof document.documentElement.clientWidth != 'undefined' && 
			 document.documentElement.clientWidth != 0 ) {
		viewportheight = document.documentElement.clientHeight
	}
	else {
		viewportheight = document.getElementsByTagName('body')[0].clientHeight
	}
	document.body.style.overflow = "hidden";

	sheet.style.top = (viewportheight-height)+'px';
	sheet.style.visibility="visible";
	document.getElementById("screen").style.visibility="visible";
}
function hideAct(height) {
	document.getElementById("screen").style.visibility="hidden";
	document.getElementById("action").style.visibility="hidden";
	document.body.style.overflow = "auto";
}
function joinConf(cid) {
	var http = GetXmlHttpObject();

	http.open("GET", "../m.conference/joinConf.inc.php?fjoin="+cid, true);
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			if (http.responseText == 0) {
				location.reload(true);
			}
			else if (http.responseText == 1) {
				location.reload(true);
			}
			else if (http.responseText == 2) {
				location.reload(true);
			}
			else if (http.responseText == 3) {
				location.reload(true);
			}
			else if (http.responseText == 4) {
				location.reload(true);
			}
		}
	}
	http.send(null);
}
function leaveConf(cid) {
	var http = GetXmlHttpObject();

	http.open("GET", "../m.conference/leaveConf.inc.php?cid="+cid, true);
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			if (http.responseText == 0) {
				location.reload(true);
			}
			else if (http.responseText == 1) {
				location.reload(true);
			}
			else if (http.responseText == 2) {
				location.reload(true);
			}
			else if (http.responseText == 3) {
				location.reload(true);
			}
		}
	}
	http.send(null);
}