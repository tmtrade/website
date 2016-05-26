/**
 * Created by dower on 2016/5/24 0024.
 */
//提交访问信息
//cookie相关函数
function aCookie(objName,objValue,time){
    var str = objName + "=" + escape(objValue);
    if(time > 0){
        var date 	= new Date();
        var ms 		= time;
        date.setTime(date.getTime() + ms);
        str += "; expires=" + date.toGMTString();
    }
    var cook=str+";path=/";
    document.cookie = cook;
}
function gCookie(name){
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    if(arr != null) return unescape(arr[2]); return '';
}
//提交数据
function sendCount(args){
    $.ajax(
        {
            type:'get',
            url : 'http://tr2.chofn.net/Count/index?'+args,
            async: false,
            dataType : 'jsonp',
            jsonp:"yzctj",
            success  : function(data) {
                if(data.code==1){
                    aCookie('yzcdata',data.msg,315360000000);//更新cookie信息
                }
            },
        }
    );
}
//提交行为数据
function sendBehavior(webid,t,x,y){
    var t = t || 1;
    x = x || 0;
    y = y || 0;
    webid = webid || '';
    var args = 'yzc=2&cookie='+gCookie('yzcdata');
    if(window && window.screen) {
        args += '&w=' + (window.screen.width || 0);
        args += '&h=' + (window.screen.height || 0);
    }
    args += '&web_id='+webid;
    args += '&type='+t;
    args += '&x='+x;
    args += '&y='+y;
    sendCount(args);
}
$(function(){
    (function(){
        //获得数据
        var params = {};
        if(document) {
            params.host = document.domain || '';
            params.url = document.URL || '';
            params.referrer = document.referrer || '';
            if(params.referrer.indexOf(params.host)==-1){ //来自其他网站
                params.issem = 1;
            }
        }
        if(login_mobile){ //登录账户
            params.tel = login_mobile;
        }
        //得到客户端来源
        if(navigator) {
            var userAgentInfo = navigator.userAgent;
            var Agents = ["Android", "iPhone","SymbianOS", "Windows Phone","iPad", "iPod"];
            params.device = 0;
            for (var v = 0; v < Agents.length; v++) {
                if (userAgentInfo.indexOf(Agents[v]) > 0) {
                    params.device = 1;//手机端
                    break;
                }
            }
        }
        //得到cookie--zycdata
        params.cookie = gCookie('yzcdata');
        //拼接参数串
        var args = 'yzc=1';
        for( var i in params) {
            args += '&' + i + '=' +encodeURIComponent(params[i]);
        }
        sendCount(args);
    })();
    //绑定超链接事件
    $(document).on('click','a',function(e){
        var webid = $(this).attr('webid');
        if(webid){
            if(typeof ptype == 'undefined'){//每个页面设置全局的type变量--区分页面
                ptype = 0;
            }
            sendBehavior(webid,ptype, e.pageX, e.pageY);
        }
    });
    //离开事件
    //$(window).bind('beforeunload',function(){

    //});
});