/*------------------------------------------ajax部分----------------------------------*/
function QueryDataAll(url, param, async, type, datatype, callback,
		contentType,competecallback, errorcallback, beforecallback) {
	// /<summary>Ajax提交完整版</summary>
	// /<param>参数</param>
	// /<param>true异步false同步</param>
	// /<param>post,get</param>
	// /<param>json,text</param>
	// /<param>成功后回发数据</param>
	$.ajax( {
		type : type, // 使用GET或POST方法访问后台
		dataType : datatype, // 返回json格式的数据
		contentType : "application/json; charset=utf-8",
		url : url, // 要访问的后台地址
		data : param, // 要发送的数据
		async : async,
		cache : false,
		error : function() {
			alert('fail');
		},
		success : function(msg) {// msg为返回的数据，在这里做数据绑定
			callback(msg);
		}
	});
}
//URL         地址'http://xxx.xxx.xxx'
//param       参数{'key':'value'}
//dataType    数据类型json/text
//type        发送类型post/get
//callback    成功回调
function queryData (url, param,dataType,type,callback){
	QueryDataAll(url, param, true, dataType, type, callback,
		function(XMLHttpRequest, v) {},
		function(XMLHttpRequest, v) {},
		function(XMLHttpRequest) {});
}

function queryDataJsonP(url,params,type,callBack){
	//http://192.168.0.131/SuperLive/wx/nba_qa.php
	$.ajax({
		type : type,
		url : url,
		data : params,//请求参数
		dataType : "jsonp",
		jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(默认为:callback)
		jsonpCallback:"jsonpCallBack",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名
		success : function(json){
		    callBack(json);
		},
		error:function(){
		    alert('fail');
		}
	});
}

/*------------------------------------------cookie部分------------------------------------------------------*/
/*设置cookie*/
function setCookie(name,value,expiredays){
	var exdate=new Date()
	exdate.setDate(exdate.getDate()+expiredays)
	document.cookie=name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
}
/*获取cookie*/
function getCookie(c_name){
	if (document.cookie.length>0){
  		c_start=document.cookie.indexOf(c_name + "=")
  		if (c_start!=-1){ 
    		c_start=c_start + c_name.length+1 
    		c_end=document.cookie.indexOf(";",c_start)
    		if (c_end==-1) c_end=document.cookie.length
    		return unescape(document.cookie.substring(c_start,c_end))
    	}
  	}
	return ""
}
/*检查cookie*/
function checkCookie(){
	username=getCookie('username')
	if (username!=null && username!=""){
  		alert('Welcome again '+username+'!')}
	else{
  		username=prompt('Please enter your name:',"")
  		if (username!=null && username!=""){
    		setCookie('username',username,365)
    	}
  	}
}



/*获取地址栏参数*/
function getQueryURL(name){
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}
/*获取字节数*/
function getBt(str){
    var char = str.match(/[^\x00-\xff]/ig);
    return str.length + (char == null ? 0 : char.length);
}
//判断手机qq和微信客户端
//iphone的qq内置浏览器中判断为iphone&&qq
function isMicromessageOrQQbrowser(){
	var _UA=navigator.userAgent.toLowerCase();
	if (_UA.indexOf('mqqbrowser')>-1||_UA.indexOf('micromessenger')>-1||(_UA.indexOf('iphone')>-1&&_UA.indexOf('qq')>-1)){
		return true;
	}
	else{
		return false;
	}
}
//仅为微信
function isMicromessenger(){
	var _UA=navigator.userAgent.toLowerCase();
	if (_UA.indexOf('micromessenger')>-1){
		return true;
	}
	else{
		return false;
	}
}
/*阻止冒泡事件*/
function stopEventBubble(event){
    var e=event || window.event;
    if (e && e.stopPropagation){
        e.stopPropagation();    
    }
    else{
        e.cancelBubble=true;
    }
}
/*touch事件、touchStart事件、touchEnd事件*/
function touch(elemnt,callback){//element可以为.class、#id

	$(elemnt).bind('touchmove', function(){
		callback();
	});
	/* try {
        var touch = evt.touches[0]; //获取第一个触点
        var x = Number(touch.pageX); //页面触点X坐标
        var y = Number(touch.pageY); //页面触点Y坐标
        //判断滑动方向 上下
        if (y - startY > 100) {
            swipeDown();//你自己的方法 我是用来翻页的一样的
        } else if(y - startY < -100){
            swipeUp();//你自己的方法
        }
    } catch (e) {
        alert('touchMoveFunc：' + e.message);
    }*/
}
function touchStart(elemnt,callback){
	$(elemnt).bind('touchstart', function(){
		callback();
	});
	/* try {
        var touch = evt.touches[0]; //获取第一个触点
        var x = Number(touch.pageX); //页面触点X坐标
        var y = Number(touch.pageY); //页面触点Y坐标
        //记录触点初始位置
        startX = x;
        startY = y;
    } catch (e) {
        alert('touchSatrtFunc：' + e.message);
    }*/
}
function touchEnd(elemnt,callback){
	$(elemnt).bind('touchend', function(){
		callback();
	});
}
function isSupportTouch(){
	try{
		document.createEvent("TouchEvent");
		callback();
	}
	catch(e){
		alert('该设备不支持触动事件');
	}
}

/*调用父iframe方法
window.parent.function_name();*/

/*调用子iframe方法
document.getElementById('ID').contentWindow.function_name();*/


