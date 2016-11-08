//购买提交
$('#subBuy').click(function(){
    layer.load(1, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
    });
	content = $('#buyNeed').val();
	if(!content || content == '对商标的特殊要求  如：25类鞋子'){
		$('#buyNeed').focus();
		$('#buyNeedTip').show();
        layer.closeAll('loading');
		return false;
	}else{
		$('#buyNeedTip').hide();
	}

	mobile = $('#buyMoblie').val();
	if(mobile == '' || mobile == "请填写你的联系电话，方便我们联系你"){
		$('#buyMoblie').focus();
		$('#buyMoblieTip').text('手机号不能为空');
		$('#buyMoblieTip').show();
        layer.closeAll('loading');
		return false;
	}else if(!verifyPhoneNum(mobile)){
		$('#buyMoblie').focus();
		$('#buyMoblieTip').text('手机号码不正确');
		$('#buyMoblieTip').show();
        layer.closeAll('loading');
		return false;
	}else{
		$('#buyMoblieTip').hide();
	}

	addBuy();
})
function addBuy()
{
    var mobile  = $.trim( $("#buyMoblie").val() );
    var need    = $.trim( $("#buyNeed").val() );
    var data	= new Array();
    data['name'] 	= '';
    data['tel'] 	= mobile;
    data['subject'] = need;
    data['remarks'] = need;
    data['pttype'] = "求购";
    sendBehavior(1,12, 0, 0,'tel:'+mobile+' 需求:'+need);//保存操作信息
    ucNetwork.submitData(data);
    
}
//提交信息回调
function submitDataCallback(Obj){
    console.debug(Obj);
    $.each(Obj,function(i,n){
         clearBuy();
         var obj ;
         //用户登录情况下
        if(n.code==1 || n.code==2){
            //弹出成功框
              obj = $('#mj-submitteS');
        }else{
            //弹出失败框
            obj = $('#mj-submitteF');
        }
        layer.open({
            type: 1,
            title: false,
            closeBtn: false,
            area: ['485px', '226px'],
            content: obj
        });
        $(".mj-close").bind("click",function(){
            layer.closeAll();
        });
        layer.closeAll('loading'); //关闭加载层
    });
}
function clearBuy()
{
    $("#buyMoblie").val('');
    $("#buyNeed").val('');
}

//验证手机号码
function verifyPhoneNum(num){
	return /^0?(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8}$/.test(num);
}