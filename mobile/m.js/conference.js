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

function setConfName(c)
{
	var http = GetXmlHttpObject();
	var url = "../m.conference/setConfNameSession.inc.php?c="+c;
	http.open("GET", url, true);
	http.onreadystatechange = function() 
	{
		if(http.readyState == 4 && http.status == 200) 
		{
			if (http.responseText == '0')
			{
			}
			else if(http.responseText == '1')
			{
				window.location = '../m.default/index.php';
			}
			else if(http.responseText == '2')
			{
				window.location = '../m.home/index.php';
			}
		}
	}
	http.send(null);
}