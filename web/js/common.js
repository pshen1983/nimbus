function findPosX(obj)
{
	var curleft = 0;
	if(obj.offsetParent) {
		while(1) {
			curleft += obj.offsetLeft;
			if(!obj.offsetParent) break;
			obj = obj.offsetParent;
		}
	}
	else if(obj.x)
	curleft += obj.x;
	return parseInt(curleft);
}

function findPosY(obj)
{
	var curtop = 0;
	if(obj.offsetParent) {
		while(1) {
			curtop += obj.offsetTop;
			if(!obj.offsetParent) break;
			obj = obj.offsetParent;
		}
	}
	else if(obj.y)
	curtop += obj.y;
	return parseInt(curtop);
}

function css_browser_selector(u)
{
	var ua=u.toLowerCase(),
		is=function(t){return ua.indexOf(t)>-1},
		g='gecko',
		w='webkit',
		s='safari',
		o='opera',
		m='mobile',
		h=document.documentElement,
		b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3.6')?g+' ff3 ff3_6':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('blackberry')?m+' blackberry':is('android')?m+' android':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?m+' j2me':is('iphone')?m+' iphone':is('ipod')?m+' ipod':is('ipad')?m+' ipad':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win'+(is('windows nt 6.0')?' vista':''):is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js'];

	c = b.join(' ');
	h.className += ' '+c;
	return c;
}; 
css_browser_selector(navigator.userAgent);

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
function showElem(id)
{
	document.getElementById(id).style.display='inline';
}
function hideElem(id)
{
	document.getElementById(id).style.display='none';
}
function select_innerHTML(objeto,innerHTML)
{
	objeto.innerHTML = "";
	var selTemp = document.createElement("micoxselect");
	var opt;
	selTemp.id="micoxselect1";
	document.body.appendChild(selTemp);
	selTemp = document.getElementById("micoxselect1");
	selTemp.style.display="none";
	if(innerHTML.toLowerCase().indexOf("<option")<0){
		innerHTML = "<option>" + innerHTML + "</option>";
	}
	innerHTML = innerHTML.replace(/<option/g,"<span").replace(/<\/option/g,"</span");
	selTemp.innerHTML = innerHTML;
	  
	
	for(var i=0;i<selTemp.childNodes.length;i++)
	{
		var spantemp = selTemp.childNodes[i];
		if(spantemp.tagName){     
			opt = document.createElement("OPTION");
		    
			if(document.all){
				objeto.add(opt);
			}
			else{
				objeto.appendChild(opt);
			}    
			opt.value = spantemp.getAttribute("value");
			opt.text = spantemp.innerHTML;
			opt.selected = spantemp.getAttribute('selected');
			opt.className = spantemp.className;
		} 
	}    
	document.body.removeChild(selTemp);
	selTemp = null;
}
function showHideNavCoEx(id, tId, cId)
{
	if(document.getElementById(tId).style.visibility == 'visible')
	{
		document.getElementById(tId).style.visibility = 'hidden';
		document.getElementById(tId).style.display = "none";
		document.getElementById(cId).style.backgroundColor  = 'transparent';
		document.getElementById(id).src="../image/collapse.png";

		document.getElementById(cId).onmouseover = function(){this.style.backgroundColor = "#54D5F5";}
		document.getElementById(cId).onmouseout = function(){this.style.backgroundColor = "transparent";}
	}
	else {
		document.getElementById(tId).style.visibility = 'visible';
		document.getElementById(tId).style.display = "block";
		document.getElementById(cId).style.backgroundColor  = '#CCC';
		document.getElementById(id).src="../image/expand.png";

		document.getElementById(cId).onmouseover = "none";
		document.getElementById(cId).onmouseout = "none";
	}

	document.getElementById(tId).style.width = "100%";
	document.getElementById(cId).style.display  = 'block';
	document.getElementById(cId).style.textDecoration  = 'none';
}

function hideShowNavCoEx(id, tId, cId)
{
	if(document.getElementById(tId).style.visibility == 'hidden')
	{
		document.getElementById(tId).style.visibility = 'visible';
		document.getElementById(tId).style.display = "block";
		document.getElementById(cId).style.backgroundColor  = '#CCC';
		document.getElementById(id).src="../image/expand.png";

		document.getElementById(cId).onmouseover = "none";
		document.getElementById(cId).onmouseout = "none";
	}
	else {
		document.getElementById(tId).style.visibility = 'hidden';
		document.getElementById(tId).style.display = "none";
		document.getElementById(cId).style.backgroundColor  = 'transparent';
		document.getElementById(id).src="../image/collapse.png";

		document.getElementById(cId).onmouseover = function(){this.style.backgroundColor = "#54D5F5";}
		document.getElementById(cId).onmouseout = function(){this.style.backgroundColor = "transparent";}
	}

	document.getElementById(tId).style.width = "100%";
	document.getElementById(cId).style.display  = 'block';
	document.getElementById(cId).style.textDecoration  = 'none';
}

function showHideDiv(link_id, div_id)
{
	var link = document.getElementById(link_id);
	var elem = document.getElementById(div_id);

	if (elem.style.visibility == 'hidden')
	{
		elem.style.visibility = 'visible';
		elem.style.display = 'block';
		link.innerHTML = '<img id=\'double_arrow_incom\' class=\'double_arrow\' src=\'../image/common/double_arrow_up.png\'></img>';
	}
	else {
		elem.style.display = 'none';
		elem.style.visibility = 'hidden';
		link.innerHTML = '<img id=\'double_arrow_incom\' class=\'double_arrow\' src=\'../image/common/double_arrow_down.png\'></img>';
	}

	setLeftRightNavHeight();
}

function hideShowDiv(link_id, div_id)
{
	var link = document.getElementById(link_id);
	var elem = document.getElementById(div_id);

	if (elem.style.visibility == 'visible'){
		elem.style.display = 'none';
		elem.style.visibility = 'hidden';
		link.innerHTML = '<img id=\'double_arrow_incom\' class=\'double_arrow\' src=\'../image/common/double_arrow_down.png\'></img>';
	}
	else
	{
		elem.style.visibility = 'visible';
		elem.style.display = 'block';
		link.innerHTML = '<img id=\'double_arrow_incom\' class=\'double_arrow\' src=\'../image/common/double_arrow_up.png\'></img>';
	}

	setLeftRightNavHeight();
}
function showComments(link_id, div_id, work_type, work_id, s_id)
{
	var link = document.getElementById(link_id);
	var elem = document.getElementById(div_id);

	if (elem.style.visibility == 'visible')
	{
		elem.style.display = 'none';
		elem.style.visibility = 'hidden';
		link.src = '../image/common/double_arrow_down.png';
	}
	else {
		if(elem.innerHTML == '') {
			link.src = '../image/snake.gif';
			var http = GetXmlHttpObject();
			http.open("GET", "../project/comments.inc.php?type="+work_type+"&work_id="+work_id+"&sid="+s_id, true);

			http.onreadystatechange = function()
			{
				if(http.readyState == 4 && http.status == 200) 
				{
					elem.innerHTML = http.responseText;
					setLeftRightNavHeight();
				}
			}
			http.send(null);
		}

		elem.style.visibility = 'visible';
		elem.style.display = 'block';
		link.src = '../image/common/double_arrow_up.png';
	}

	setLeftRightNavHeight();
}

function enableElement(src_id, tar_id)
{
	var src = document.getElementById(src_id);
	var tar = document.getElementById(tar_id);

	if(src.value == '') {
		tar.disabled = true;
	}
	else {
		tar.disabled = false;
	}
}

function limitTextLength(src_id, limit, monitor_label_id)
{
	var src = document.getElementById(src_id);
	var mon = document.getElementById(monitor_label_id);

	if (src.value.length > limit) {
		src.value = src.value.substring(0, limit);
	}
	else {
		mon.innerHTML = limit-src.value.length;
	}
}

function mousePress(id)
{
	document.getElementById(id).style.backgroundImage = "url(../image/button/button_background_over.png)";
}

function mouseRelease(id)
{
	document.getElementById(id).style.backgroundImage = "url(../image/button/button_background.png)";
}

function mousePressHeaderSearch(id)
{
	document.getElementById(id).style.backgroundImage = "url(../image/button/search_background_over.png)";
}

function mouseReleaseHeaderSearch(id)
{
	document.getElementById(id).style.backgroundImage = "url(../image/button/search_background.png)";
}

function setLeftRightNavHeight()
{
	var height = document.getElementById('main_body').offsetHeight;
	document.getElementById('left_nav').style.height = height+'px';
}

function addUserSub(link_id, work)
{
	var link = document.getElementById(link_id);
	link.innerHTML = '<img class="wait_snake" src="../image/snake.gif"></img>';
	link.href= "#";
	var http = GetXmlHttpObject();
	http.open("GET", "../project/add_user_sub.inc.php?w="+work, true);
	
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			if( http.responseText != 0 ) {
				link.href= "javascript:removeUserSub('"+link_id+"', '"+work+"')";
				link.innerHTML = http.responseText;
			}
		}
	}
	http.send(null);
}

function removeUserSub(link_id, work)
{
	var link = document.getElementById(link_id);
	link.innerHTML = '<img class="wait_snake" src="../image/snake.gif"></img>';
	link.href= "#";
	var http = GetXmlHttpObject();
	http.open("GET", "../project/remove_user_sub.inc.php?w="+work, true);
	
	http.onreadystatechange = function()
	{
		if(http.readyState == 4 && http.status == 200)
		{
			if( http.responseText != 0 ) {
				link.href= "javascript:addUserSub('"+link_id+"', '"+work+"')";
				link.innerHTML = http.responseText;
			}
		}
	}
	http.send(null);
}

function getCompWp(c_id, wp_id, p_id)
{
	var link = document.getElementById(c_id);
	var wp = document.getElementById(wp_id);
	var comp_id = link.options[link.selectedIndex].value;
	var http = GetXmlHttpObject();

	if(comp_id == '')
		http.open("GET", "../project/get_proj_wp.inc.php?p="+p_id, true);
	else
		http.open("GET", "../project/get_comp_wp.inc.php?c="+comp_id, true);

	http.onreadystatechange = function() {
		if(http.readyState == 4 && http.status == 200) {
			select_innerHTML(wp, '<option value=""></option>'+http.responseText);
		}
	}
	http.send(null);
}

function getHighest(div_1, div_2)
{
	document.getElementById(div_1).style.height = "100%";
	var height1 = document.getElementById(div_1).offsetHeight;
	var height2 = document.getElementById(div_2).offsetHeight;

	if (height2 >= height1)
		document.getElementById(div_1).style.height = height2+"px";
}

function timeOutCallsAtLoad()
{
	alert("Halo");
	t=setTimeout('timeOutCallsAtLoad()',3000);
}

function changeLang(lang)
{
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

function getLangList()
{
	var cont = document.getElementById('lang_list');
	var arrow = document.getElementById('lang_arrow');

	if (cont.style.visibility == 'visible') {
		cont.style.visibility = 'hidden';
		arrow.innerHTML = "&#9660;";
	}
	else {
		var link = document.getElementById('lang_link');
		var top = findPosY(link) + link.offsetHeight + 1;
		var left = findPosX(link);

		cont.style.left = left + "px";
		cont.style.top = top + "px";
		arrow.innerHTML = "&#9650;";
		cont.style.visibility = 'visible';
	}
}
function showDiv(div, link, event)
{
	var evt = event || window.event;
    var targ = evt.target || evt.srcElement;
    var el = targ;
    if (targ.nodeType === 1) {

        while (el && el.id !== 'message') {
            el = el.parentNode;
        }
        if (!el) {
        	divE = document.getElementById(div);

	    	if (divE.style.visibility == 'visible') {
	    		divE.style.visibility = 'hidden';
	    		document.getElementById(link).setAttribute("onmouseover", "this.style.backgroundColor='#71AFDB';javascript:accountOver();");
	    		document.getElementById(link).setAttribute("onmouseout", "this.style.backgroundColor='#589ED0';javascript:accountOut();");
	    	}
	    	else {
	    		divE.style.visibility = 'visible';
	    		document.getElementById(link).style.backgroundColor = "#71AFDB"
	    		document.getElementById(link).setAttribute("onmouseover", "javascript:accountOver();");
	    		document.getElementById(link).setAttribute("onmouseout", "javascript:accountOut();");
	    	}
        }
    }
}
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

function inputOnFocus(spanName)
{
	var l = document.getElementById(spanName);
	l.style.visibility="hidden";
}
function inputOffFocus(spanName, input)
{
	if(input.value=="")
	{
		var l = document.getElementById(spanName);
		l.style.visibility="visible";
	}
}
function hideLabel(lName)
{
	var l = document.getElementById(lName);
	l.style.display="none";
}
function displayLabel(lName, input)
{
	if(input.value=="")
	{
		var l = document.getElementById(lName);
		l.style.display="block";
	}
}
function hideOnFocus(hide, focus)
{
	hide.style.visibility="hidden";
	document.getElementById(focus).focus();
}
function changeCodeImage(id, r)
{
	var end = new Date();
	document.getElementById(id).src="../utils/getCode.php?r="+r+"&time="+end.getTime();
}
function bocyHideAccount(id)
{
	document.getElementById(id).style.visibility="hidden";
	document.body.setAttribute('onclick', '');
	document.getElementById("temp").style.backgroundColor = "transparent"
	document.getElementById("temp").setAttribute('onmouseover', 'this.style.backgroundColor="#71AFDB";javascript:accountOver();');
	document.getElementById("temp").setAttribute('onmouseout', 'this.style.backgroundColor="transparent";javascript:accountOut();');
}
function showCE(id)
{
	document.getElementById("screen").style.visibility="visible";
	if(document.documentElement.scrollTop) {
		document.getElementById(id).style.top=(60+document.documentElement.scrollTop)+'px';
	}
	else {
		document.getElementById(id).style.top=(60+document.body.scrollTop)+'px';
	}
	fadeUp(10, id);
}
function dismissCE(id)
{
	document.getElementById(id).style.visibility="hidden";
	fadeDown(30);
}
function fadeUp(num, id)
{
	if(num >= 30) {
		document.getElementById(id).style.visibility="visible";
		return;
	}

	setOpacity('screen', num);
	total = num + 10;

	var t=setTimeout(function(){fadeUp(total, id);}, 10);
}
function fadeDown(num)
{
	if(num <= 10) {
		document.getElementById("screen").style.visibility="hidden";
		setOpacity('screen', 0);
		return;
	}

	total = num - 10;
	setOpacity('screen', num);

	var t=setTimeout(function(){fadeDown(total);}, 10);
}

function setOpacity(elem, perc)
{
	var objToFade = document.getElementById(elem);
	if(typeof objToFade.style.opacity == "string")
	{
		objToFade.style.opacity = "0."+perc;
	} 
	else {
		objToFade.filters.alpha.opacity = perc;
	}
}

function resizeImg(id, len)
{
	pic = document.getElementById(id); 
	h = pic.offsetHeight; 
	w = pic.offsetWidth;

	if(h>w) {
		w = len*w/h;
		h = len;
	}
	else {
		h = len*h/w;
		w = len;
	}

	pic.style.height=h+"px";
	pic.style.width=w+"px";
}

function resizeImgWidLen(id, wid, len)
{
	pic = document.getElementById(id); 
	h = pic.offsetHeight; 
	w = pic.offsetWidth;

	if(w/h>wid/len) {
		w1 = wid;
		h1 = h*wid/w;
	}
	else {
		h1 = len;
		w1 = w*len/h;
	}

	pic.style.height=h1+"px";
	pic.style.width=w1+"px";
}

function isDate(strDate) {
    var objDate, mSeconds, day, month, year; 
    if (strDate.length !== 10) {return false;}
    // third and sixth character should be '/'
    if (strDate.substring(4, 5) !== '-' || strDate.substring(7, 8) !== '-') {
        return false;
    }
    year = strDate.substring(0, 4) - 0;
    month = strDate.substring(5, 7) - 1; // because months in JS start from 0
    day = strDate.substring(8, 10) - 0;
    // test year range
    if (year < 1000 || year > 4000) {
        return false;
    }
    mSeconds = (new Date(year, month, day)).getTime();
    // initialize Date() object from calculated milliseconds
    objDate = new Date();
    objDate.setTime(mSeconds);
    // compare input date and parts from Date() object
    // if difference exists then date isn't valid
    if (objDate.getFullYear() !== year || objDate.getMonth() !== month || objDate.getDate() !== day) {
        return false;
    }
    // otherwise return true
    return true;
}

function isTime(strTime) {
	var hr, min;
    if (strTime.length !== 5) {return false;}
    if (strTime.substring(2, 3) !== ':'){return false;}
    hr = strTime.substring(0, 2);
    min = strTime.substring(3, 5);
    if (hr > 23 || hr < 0) {return false;}
    if (min > 59 || min < 0) {return false;}
    return true;
}

function cmpTime(str1, str2) {
	var time1, time2;
    time1 = parseInt(str1.replace(":", ""),10);
    time2 = parseInt(str2.replace(":", ""),10);
    if (time1 > time2) {return false;}
    return true;
}

function validateEmailFormat(str)
{
	var RegExp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.([a-z]{2,3}))$/;
	return RegExp.test(str);
}

function validateCellFormat(str)
{
	var RegExp = /^1[0-9]{10}$/;
	return RegExp.test(str);
}