/*统计与回呼 头部*/

/*百度回呼*/
document.write('<script type="text/javascript"  data-lxb-uid="1268165" data-lxb-gid="139337" src="http://lxbjs.baidu.com/api/asset/api.js?t=' + new Date().getTime() + '" charset="utf-8"></scr' + 'ipt>' );



/*点击免费通话提示框*/
$(function(){
	$("#telInput").focus(function(){
		$(".lxb-cb-tip").css({
			"display" : "block"
		});
	}).blur(function(){
		$(".lxb-cb-tip").css({
			"display" : "none"
		});
	});	
	//侧边与回呼
	$("#callBtn").on("click",function(){
		lxb.call(document.getElementById("telInput"));
	})
});


function consultation(){
    //window.open("http://p.qiao.baidu.com/im/index?siteid=7918603&ucid=1268165");
	goChat();
}

//聊天函数
function goChat(){
	var type = _YZC_ONLINE_;
	if ( type=='yzc' )
	{
		window.open("http://p.qiao.baidu.com/cps/chat?siteId=9503594&userId=21149642");
	}else{
		window.open("http://chat.looyu.com/chat/chat/p.do?c=46344&f=123997&g=51817");
	}
}