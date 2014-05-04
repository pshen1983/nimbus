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
function scheFilterTable(s, e, a, l, sl, el)
{
	var ii=0;
	for (ii=1;ii<=a;ii++)
	{
		document.getElementById("t"+ii).style.display="none";
	}
	for (ii=s;ii<=e;ii++)
	{
		document.getElementById("t"+ii).style.display="";
	}
	for (ii=sl;ii<=el;ii++)
	{
		document.getElementById("a"+ii).style.backgroundColor="transparent";
	}
	document.getElementById("a"+l).style.backgroundColor="#9CC7E5";
}
function updateCityList(pid)
{
	cdiv = document.getElementById("city");
	ddiv = document.getElementById("dist");

	cdiv.innerHTML = '<option value="-1" >--</option>';
	ddiv.innerHTML = '<option value="-1" >--</option>';

	document.getElementById("cityW").style.visibility='visible';

	var http = GetXmlHttpObject();
	var url = "../conference/getCityList.inc.php?id="+pid;
	http.open("GET", url, true);
	http.onreadystatechange = function() 
	{
		if(http.readyState == 4 && http.status == 200) 
		{
			if (http.responseText == '1')
			{
				window.location = '../default/login.php';
			}
			else if (http.responseText != '0')
			{
				cdiv.innerHTML = http.responseText;
				ddiv.innerHTML = '<option value="-1" >--</option>';
			}
		}
	}
	http.send(null);
	document.getElementById("cityW").style.visibility='hidden';
}
function updateDistList(cid)
{
	div = document.getElementById("dist");
	div.innerHTML = '<option value="-1" >--</option>';
	document.getElementById("distW").style.visibility='visible';

	var http = GetXmlHttpObject();
	var url = "../conference/getDistList.inc.php?id="+cid;
	http.open("GET", url, true);
	http.onreadystatechange = function() 
	{
		if(http.readyState == 4 && http.status == 200) 
		{
			if (http.responseText == '1')
			{
				window.location = '../default/login.php';
			}
			else if (http.responseText != '0')
			{
				div.innerHTML = http.responseText;
			}
		}
	}
	http.send(null);
	document.getElementById("distW").style.visibility='hidden';
}
function setDistSelect(cid)
{
	var http = GetXmlHttpObject();
	var url = "../conference/setDistSelect.inc.php?id="+cid;
	http.open("GET", url, true);
	http.onreadystatechange = function() 
	{
		if(http.readyState == 4 && http.status == 200) 
		{
			if (http.responseText == '1')
			{
				window.location = '../default/login.php';
			}
		}
	}
	http.send(null);
}
function isUrlExit(url)
{
	if(url)
	{
		var http = GetXmlHttpObject();
		var url = "../conference/checkUrl.inc.php?url="+url;
		http.open("GET", url, true);
		http.onreadystatechange = function() 
		{
			if(http.readyState == 4 && http.status == 200) 
			{
				if (http.responseText == '0')
				{
				}
				else if (http.responseText == '1')
				{
					window.location = '../default/login.php';
				}
				else
				{
					document.getElementById('uerr'+http.responseText).style.display="inline";
				}
			}
		}
		http.send(null);
	}
}
function numeralsOnly(evt)
{
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));

	if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122)) 
	{
		return false;
	}

	return true;
}
function checkURL(input)
{
	url = document.getElementById(input);
	if(url.value.length > 12)
	{
		url.value=url.value.substring(0, 12);
	}
}
function checkNoneEmpty(input, errLabel)
{
	if(input.value.length == 0)
	{
		document.getElementById(errLabel).style.display='inline';
	}
}
function publishConf(alertstr, cid, curl)
{
	if (confirm(alertstr))
	{
		if(cid)
		{
			var http = GetXmlHttpObject();
			var url = "../conference/publish.inc.php?c="+cid+'&url='+curl;
			http.open("GET", url, true);
			http.onreadystatechange = function() 
			{
				if(http.readyState == 4 && http.status == 200) 
				{
					if (http.responseText == '0')
					{
						window.open('../'+curl);
						window.location = '../conference/index.php';
					}
					else if (http.responseText == '1')
					{
						window.location = '../default/login.php';
					}
					else
					{
					}
				}
			}
			http.send(null);
		}
	}
}
function closeConf(alertstr, cid, curl)
{
	if (confirm(alertstr))
	{
		if(cid)
		{
			var http = GetXmlHttpObject();
			var url = "../conference/close.inc.php?c="+cid+'&url='+curl;
			http.open("GET", url, true);
			http.onreadystatechange = function() 
			{
				if(http.readyState == 4 && http.status == 200) 
				{
					if (http.responseText == '0')
					{
						window.location = '../conference/index.php'
					}
					else if (http.responseText == '1')
					{
						window.location = '../default/login.php';
					}
					else
					{
					}
				}
			}
			http.send(null);
		}
	}
}