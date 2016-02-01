//购买提交
$('#subBuy').click(function(){
	content = $('#buyNeed').val();
	if(!content || content == '对商标的特殊要求  如：25类鞋子'){
		$('#buyNeed').focus();
		$('#buyNeedTip').show();
		return false;
	}else{
		$('#buyNeedTip').hide();
	}

	mobile = $('#buyMoblie').val();
	if(mobile == '' || mobile == "请填写你的联系电话，方便我们联系你"){
		$('#buyMoblie').focus();
		$('#buyMoblieTip').text('手机号不能为空');
		$('#buyMoblieTip').show();
		return false;
	}else if(!verifyPhoneNum(mobile)){
		$('#buyMoblie').focus();
		$('#buyMoblieTip').text('手机号码不正确');
		$('#buyMoblieTip').show();
		return false;
	}else{
		$('#buyMoblieTip').hide();
	}
	addBuy();
})

// $('#buyMoblie').blur(function(){
	// mobile = $('#buyMoblie').val();
	// if(!mobile){
		// $('#buyMoblieTip').text('手机号不能为空');
		// $('#buyMoblieTip').show();
		// return false;
	// }else if(!verifyPhoneNum(mobile)){
		// $('#buyMoblieTip').text('手机号码不正确');
		// $('#buyMoblieTip').show();
		// return false;
	// }else{
		// $('#buyMoblieTip').hide();
	// }
// })

function addBuy()
{
    var mobile  = $.trim( $("#buyMoblie").val() );
    var need    = $.trim( $("#buyNeed").val() );
    var sid     = $.trim( $("#sid").val() );
    var area    = $.trim( $("#area").val() );
    $.ajax({
        type: "post",
        url: "/buy/add/",
        data: {mobile:mobile,content:need,name:'',sid:sid,area:area,source:2},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                clearBuy();
                //getLayer($('#mj-submitteS'));
            }else{
               // getLayer($('#mj-submitteF'));
            }
        },
        error: function(data){
            alert('服务器错误！');
        }
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