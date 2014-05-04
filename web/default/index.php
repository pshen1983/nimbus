<?php
include_once ("../utils/configuration.inc.php");
include_once ("../utils/CommonUtil.php");
include_once ("../utils/SecurityUtil.php");
include_once ("../utils/SessionUtil.php");

session_start();
if(!isset($_SESSION['_language'])) {
	CommonUtil::setSessionLanguage();
	SessionUtil::initLocation();
}
if (isset($_SESSION['_userId']) || SecurityUtil::cookieLogin())
{
	header( 'Location: ../home/index.php' );
	exit;
}

//============================================= Language ==================================================

if($_SESSION['_language'] == 'en') {
	$l_title = "ConfOne";
	$l_remember_pw = "Remember me";
	$l_forget_pw = "Forget password?";
	$l_hide_user = "Email";
	$l_hide_pass = "Password";
	$l_new_to = "New to ConfOne?";
	$l_reg_now = "Join today!";
	$l_reg_mess = "Go to ConfOne Registration Page.";
	$l_reg_name = "Full Name";
	$l_reg_email = "Email";
	$l_reg_pass = "Password";
	$l_reg_check = "Check Code";
	$l_bu_login = "Sign in";
	$l_bu_reg = "Sign up";
	$l_search = "Search ConfOne";
	$l_follow = "New Experience for your Events";
	$l_update = "Create and join events of your interests<br />ConfOne takes you into a whole new world of events";
	$l_web = "Web Site: ";
	$l_mweb = "Mobile Web: ";
	$l_app = "Phone Apps: ";
	$l_soon = " ( Coming Soon ... )";
	$l_t_code = "Test Code";
	$l_t_info = "<a style='color:#fff' href='../about/test.php'>Apply</a> for a Test Code.";
	$l_suggest = "Suggested Broswer: ";
	$l_broswer = "<a class='down' target='_blank' href='http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html'>Chorme</a>, 
				  <a class='down' target='_blank' href='http://firefox.com.cn/'>Firefox</a>, 
				  <a class='down' target='_blank' href='http://www.apple.com.cn/safari/'>Safari</a>, 
				  <a class='down' target='_blank' href='http://info.msn.com.cn/ie9/'>IE9</a>";
	$l_brow = "";
}
else if($_SESSION['_language'] == 'zh') {
	$l_title = "会云网";
	$l_remember_pw = "下次自动登录";
	$l_forget_pw = "忘记密码？";
	$l_hide_user = "邮箱";
	$l_hide_pass = "密码";
	$l_new_to = "刚到会云网？";
	$l_reg_now = "立刻注册！";
	$l_reg_mess = "转到会云网注册页";
	$l_reg_name = "真实姓名";
	$l_reg_email = "帐号/邮箱";
	$l_reg_pass = "密码";
	$l_reg_check = "验证码";
	$l_bu_login = "登录";
	$l_bu_reg = "注册";
	$l_search = "搜索会云网";
	$l_follow = "倡导数字化会议活动新体验";
	$l_update = "创建，参与您喜欢的会议，会务，交流等活动。<br />结识新朋友，了解新动态，会云伴您与数字化一起飞。";
	$l_web = "传统网页：";
	$l_mweb = "手机网页：";
	$l_app = "原生 App：";
	$l_soon = "即将推出";
	$l_t_code = "内测码";
	$l_t_info = "参加内测，<a style='color:#fff' href='../about/test.php'>获得内测码！</a>";
	$l_suggest = "建议使用：";
	$l_broswer = "<a class='down' target='_blank' href='http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html'>Chorme</a>, 
				  <a class='down' target='_blank' href='http://firefox.com.cn/'>Firefox</a>, 
				  <a class='down' target='_blank' href='http://www.apple.com.cn/safari/'>Safari</a>, 
				  <a class='down' target='_blank' href='http://info.msn.com.cn/ie9/'>IE9</a>";
	$l_brow = " 浏览器";
}

//=========================================================================================================

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $l_title?></title><link rel="shortcut icon" href="../image/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/generic.css" />
<link type="text/css" rel="stylesheet" href="../css/default.css" />
<script type="text/JavaScript" src="../js/common.js"></script>
<style type="text/css">
html,body{margin:0;padding:0;}
label.shadow{text-shadow: 1px 1px 1px #555;}
a.down{text-decoration:none;}
a.down:hover{text-decoration:underline;}
</style>
<!--  script type="text/javascript" src="http://api.map.baidu.com/api?key=&v=1.1&services=true"></script -->
</head>
<body>
<div style="width:100%;">
<div style="background-color:#589ED0;height:435px;
background: -webkit-gradient(radial, 35% 50%, 0, 35% 50%, 300, from(#A8CBE3), to(#589ED0));
background: -moz-radial-gradient(35% 50%, circle, #A8CBE3 0px, #589ED0 300px);
background: -o-radial-gradient(35% 50%, circle, #A8CBE3 0px, #589ED0 300px);">
<div style="position:relative;top:130px;height:1px;opacity:.8;-webkit-box-shadow:0 0,inset 0 1px 0px #BBB;-moz-box-shadow:0 0,inset 0 1px 0px #BBB;box-shadow:0 0,inset 0 1px 0px #BBB;"></div>
<div class="stand_width">
<div style="margin-left:40px;"><img src="../image/logo1.png" style="position:absolute;top:20px;"/><h1 style="display:none;"><?php echo $l_title?></h1></div>
<table>
<tr>
<td>
</td>
<td>
<form style="margin-bottom:20px;" method="post" enctype="multipart/form-data" action="<?php echo $HTTP_BASE?>/default/login.php" name="login_form" id="login_form" accept-charset="UTF-8">
<table>
<tr>
<td><label id="ul" class="log_hi"><?php echo $l_hide_user;?></label></td>
<td><label id="pl" class="log_hi"><?php echo $l_hide_pass?></label></td>
</tr>
<tr>
<td><input name="log_name" type="text" class="login_input round_border_4" onfocus="inputOnFocus('ul');" onblur="inputOffFocus('ul', this);"/></td>
<td><input name="log_pass" type="password" class="login_input round_border_4" onfocus="inputOnFocus('pl');" onblur="inputOffFocus('pl', this);" /></td>
<td><button type="submit" class="login round_border_4"><span class="input"><?php echo $l_bu_login?></span></button></td>
</tr>
<tr>
<td>
<input type="hidden" name="log" value="log" />
<input name="remember" id="remember" type="checkbox"><label for="remember" style="font-size:.8em;color:#333;"><?php echo $l_remember_pw?></label></td>
<td><a style="font-size:.8em;color:#000080;" href="../default/forget.php"><?php echo $l_forget_pw?></a></td>
</tr>
</table>
</form>
</td>
</tr>
<tr>
<td>
<div style="margin: 40px 0 0 20px;">
<div style="width:500px;height:128px;">
<p style="font-size:1.4em;font-weight:bold;color:white"><label class="shadow"><?php echo $l_follow?></label></p>
<p style="line-height:1.5em;width:450px;font-weight:bold;font-size:.9em;opacity:.5;"><label><?php echo $l_update?></label></p>
</div>
<div style="margin-left:20px;color:#fff;opacity:.75;">
<table>
<tr><td><label class="shadow"><b><?php echo $l_web?></b></label></td><td><label class="shadow">www.confone.com</label></td></tr>
<tr><td><label class="shadow"><b><?php echo $l_mweb?></b></label></td><td><label class="shadow">m.confone.com</label></td></tr>
<tr><td><label class="shadow"><b><?php echo $l_app?></b></label></td><td><label class="shadow">iPhone/iPad/Andriod <?php echo $l_soon?></label></td></tr>
</table>
<div style="margin-top:30px;font-size:.9em;"><?php echo $l_suggest.$l_broswer.$l_brow?></div>
</div>
<!-- form method="post" enctype="multipart/form-data" action="../search/index.php" accept-charset="UTF-8">
<input id="sein" name="search" type="text" class="search_input" onfocus="inputOnFocus('se');" onblur="inputOffFocus('se', this);"/>
<input id="se_but" type="submit" value=""/>
<label id="se" class="hi" onclick="hideOnFocus(this, 'sein');"><?php echo $l_search?></label>
</form -->
</div>
</td>
<td>
<form id="reg_form" method="post" enctype="multipart/form-data" action="<?php echo $HTTP_BASE?>/default/register.php" name="reg_form" accept-charset="UTF-8">
<table style="margin-top:40px;">
<tr>
<td style="padding-bottom:3px;"><label class="new_to"><?php echo $l_new_to?></label>
<a class="reg_now" href="../default/register.php" title="<?php echo $l_reg_mess?>"><?php echo $l_reg_now?></a></td>
<td></td>
</tr>
<tr>
<td><input id="fnin" name="fn_in" type="text" class="reg_input round_border_4" onfocus="inputOnFocus('fn');" onblur="inputOffFocus('fn', this);"/></td>
<td class="hi_la"><label id="fn" class="hi" onclick="hideOnFocus(this, 'fnin');"><?php echo $l_reg_name;?></label></td>
</tr>
<tr>
<td><input id="unin" name="le_in" type="text" class="reg_input round_border_4" onfocus="inputOnFocus('un');" onblur="inputOffFocus('un', this);" /></td>
<td class="hi_la"><label id="un" class="hi" onclick="hideOnFocus(this, 'unin');"><?php echo $l_reg_email?></label></td>
</tr>
<tr>
<td><input id="pwin" name="pw_in" type="password" class="reg_input round_border_4" onfocus="inputOnFocus('pw');" onblur="inputOffFocus('pw', this);" /></td>
<td class="hi_la"><label id="pw" class="hi" onclick="hideOnFocus(this, 'pwin');"><?php echo $l_reg_pass?></label></td>
</tr>
<tr>
<td><input id="tein" name="te_in" type="text" style="width:115px;" class="reg_input round_border_4" onfocus="inputOnFocus('te');" onblur="inputOffFocus('te', this);" />
<label style="color:#fff;font-size:.8em;text-shadow: 1px 1px 1px #555;opacity:.8;">&#9668; <?php echo $l_t_info?></label></td>
<td class="hi_la"><label id="te" class="hi" onclick="hideOnFocus(this, 'tein');"><?php echo $l_t_code?></label></td>
</tr>
<tr>
<td style="float:right;">
<input type="hidden" name="where" value="ind" />
<button type="submit" class="reg round_border_4"><span class="reg"><?php echo $l_bu_reg?></span></button>
</td>
<td></td>
</tr>
</table>
</form>
</td>
</tr>
</table>
</div>
</div>
<div style="position:relative;height:4px;opacity:.8;-webkit-box-shadow:0 0,inset 0 1px 5px orange;-moz-box-shadow:0 0,inset 0 1px 5px orange;box-shadow:0 0,inset 0 1px 5px orange;"></div>
<!-- div style="position:absolute;z-index:2;left:0px;top:334px;width:100%;overflow:hidden;">
<ul style="width:2000px;text-align:center;margin-left:-80px;list-style-type:none;white-space:nowrap;">
<li style="float:left;"><a href=""><img src="../image/meet1.jpg" /></a></li>
</ul>
</div>
<center>
<div style="margin-top:5px;width:697px;height:280px;border:#ccc solid 1px;" id="dituContent"></div>
</center -->
</div>
<?php include_once '../common/footer.inc.php';?>
</body>
<!-- script type="text/javascript">
    //创建和初始化地图函数：
    function initMap(){
        createMap();//创建地图
        setMapEvent();//设置地图事件
        addMapControl();//向地图添加控件
    }
    
    //创建地图函数：
    function createMap(){
        var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
        var point = new BMap.Point(116.401969,39.917591);//定义一个中心点坐标
        map.centerAndZoom(point,12);//设定地图的中心点和坐标并将地图显示在地图容器中
        map.addOverlay(new BMap.Marker(new BMap.Point(116.321969,39.857591)));                     // 将标注添加到地图中 ;
        window.map = map;//将map变量存储在全局//
    }
    
    //地图事件设置函数：
    function setMapEvent(){
        map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
        map.disableScrollWheelZoom();//禁用地图滚轮放大缩小，默认禁用(可不写)
        map.disableDoubleClickZoom();//禁用鼠标双击放大
        map.disableKeyboard();//禁用键盘上下左右键移动地图，默认禁用(可不写)
    }
    
    //地图控件添加函数：
    function addMapControl(){
        //向地图中添加缩放控件
	var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
	map.addControl(ctrl_nav);
                //向地图中添加比例尺控件
	var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
	map.addControl(ctrl_sca);
    }
 // 定义自定义覆盖物的构造函数  
    function SquareOverlay(center, length, color){  
     this._center = center;  
     this._length = length;  
     this._color = color;  
    }  
    // 继承API的BMap.Overlay  
    SquareOverlay.prototype = new BMap.Overlay(); 
 // 实现初始化方法  
    SquareOverlay.prototype.initialize = function(map){  
    // 保存map对象实例  
     this._map = map;      
     // 创建div元素，作为自定义覆盖物的容器  
     var div = document.createElement("div");  
     div.style.position = "absolute";      
     // 可以根据参数设置元素外观  
     div.style.width = this._length + "px";  
     div.style.height = this._length + "px";  
     div.style.background = this._color;    
     // 将div添加到覆盖物容器中  
     map.getPanes().markerPane.appendChild(div);    
     // 保存div实例  
     this._div = div;    
     // 需要将div元素作为方法的返回值，当调用该覆盖物的show、  
     // hide方法，或者对覆盖物进行移除时，API都将操作此元素。  
     return div;  
 }
 // 实现绘制方法  
    SquareOverlay.prototype.draw = function(){  
    // 根据地理坐标转换为像素坐标，并设置给容器  
     var position = this._map.pointToOverlayPixel(this._center);  
     this._div.style.left = position.x - this._length / 2 + "px";  
     this._div.style.top = position.y - this._length / 2 + "px";  
    }
 // 实现显示方法  
    SquareOverlay.prototype.show = function(){  
     if (this._div){  
       this._div.style.display = "";  
     }  
    }    
    // 实现隐藏方法  
    SquareOverlay.prototype.hide = function(){  
     if (this._div){  
       this._div.style.display = "none";  
     }  
    }
 // 添加自定义方法  
    SquareOverlay.prototype.toggle = function(){  
     if (this._div){  
       if (this._div.style.display == ""){  
         this.hide();  
       }  
       else {  
         this.show();  
       }  
     }  
    }
    initMap();//创建和初始化地图
</script -->
</html>