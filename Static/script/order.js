
_sendOnceVm     = true;
_checkNumber    = true;
$(function(){
	
	$(".qd-close").bind("click",function(){
		layer.closeAll();
	});
	
	$(".mj-close").bind("click",function(){
		layer.closeAll();
	});

    $("#vmNumber").blur(function (){
        checkMobile();
    })

    $("#vm_sub").click(function (){
        bindMoblie();
    });
    
    $("#vm_hqyzm").click(function (){
        if ( !_sendOnceVm ) return false;
        var _this   = $("#vmNumber");
        var _val    = $.trim(_this.val());

        _checkMobile();
        if ( !_checkNumber ) return false;

        //通过验证，可发送密码
        $.ajax({
            type: "post",
            url: "/passport/sendBindCode/",
            data: {m:_val},
            dataType: "json",
            success: function(data){
                if (data.code == 1){
                    _sendOnceVm = false;
                    vm_timer(60, $("#vm_hqyzm"));
                    $('#vmTips > em').html('验证码已发送');
                    $('#vmTips').show();
                }else if (data.code == 2){
                    $('#vmTips > em').html('请输入正确的手机号');
                    $('#vmTips').show();
                    _sendOnceVm = true;
                }else if (data.code == 3){
                    $('#vmTips > em').html('该手机已经绑定，请更换其他号码');
                    $('#vmTips').show();
                    _sendOnceVm = true;
                }else{
                    $('#vmTips > em').html('发送失败');
                    $('#vmTips').show();
                    _sendOnceVm = true;
                }
            }
         });
        return false;
    });
})

$("#telInput2").bind("blur",function(){
	//电话号码验证
	checkCallPhone($(this));
	
})

function bindMoblie()
{
    var _this   = $("#vmNumber");
    var _pass   = $("#vmCode");
    var _val    = $.trim(_this.val());
    var _pw    = $.trim(_pass.val());

    _checkMobile();
    if ( !_checkNumber ) return false;

    if (_pw == '' || _pw == '请输入手机验证码') {
        $('#vmTips > em').html('请输入手机验证码');
        $('#vmTips').show();
        return false;
    }

    $.ajax({
        type: "post",
        url: "/passport/bindMobile/",
        data: {mobile:_val,code:_pw},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                $('#vmTips > em').html('绑定成功');
                $('#vmTips').show();
                //绑定成功
                setTimeout(function(){
                    window.location.reload();
                    return false;
                }, 100);
            }else if (data.code == 2){
                $('#vmTips > em').html('请输入手机号');
                $('#vmTips').show();
            }else if (data.code == 3){
                $('#vmTips > em').html('请输入正确的手机号');
                $('#vmTips').show();
            }else if (data.code == 4){
                $('#vmTips > em').html('请输入正确的校验码');
                $('#vmTips').show();
            }else if (data.code == 5){
                $('#vmTips > em').html('手机号已修改');
                $('#vmTips').show();
            }else if (data.code == 6){
                $('#vmTips > em').html('该手机已经绑定，请更换其他号码');
                $('#vmTips').show();
            }else{
                $('#vmTips > em').html('发送失败');
                $('#vmTips').show();
            }
        }
    });
}

function checkMobile()
{
    var _this   = $("#vmNumber");
    var _val    = $.trim(_this.val());
    _checkMobile();
    if ( !_checkNumber ) return false;
    $.ajax({
        type: "post",
        url: "/passport/existAccount/",
        data: {account:_val},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                _checkNumber = false;
                $('#vmTips > em').html('该手机已经绑定，请更换其他号码');
                $('#vmTips').show();
            }else if (data.code == 2){//空
                _checkNumber = false;
                $('#vmTips > em').html('请输入手机号');
                $('#vmTips').show();
            }else if (data.code == 3){//账号不正确（不是邮箱或手机号）
                _checkNumber = false;
                $('#vmTips > em').html('请输入正确的手机号');
                $('#vmTips').show();
            }else if (data.code == -1){//未注册
                _checkNumber = true;
                //$('#vmTips > em').html('该号尚未注册，点击发送密码，我们将会以短信形式将密码发送到该手机号');
                $('#vmTips').hide();
            }else{
                //请求失败
                _checkNumber = false;
                $('#vmTips > em').html('操作失败，请稍后重试');
                $('#vmTips').show();
            }
        }
    });
}

function _checkMobile()
{
    var _this   = $("#vmNumber");
    var _val    = $.trim(_this.val());
    if (_val == '' || _val == '请输入手机号'){
        _checkNumber = false;
        $('#vmTips > em').html('请输入手机号');
        $('#vmTips').show();
        return false;
    }
    if (isNaN(_val) || _val.length != 11){
        _checkNumber = false;
        $('#vmTips > em').html('请输入正确的手机号');
        $('#vmTips').show();
        return false;
    }
    _checkNumber = true;
    return true;
}

//倒计时
function vm_timer(count, obj)
{
     window.setTimeout (function () {
         count --;
         obj.text(count + "s");
         if(count > -1){
             vm_timer(count, obj);
         }else{
            _sendOnceVm = true;
            obj.text('重新获取');
         }
     },1000);
}

//验证打电话的电话输入
function checkCallPhone(obj){
	var value = obj.val();
	var result = true;
	if(value.length == 11 ){
		if(verifyPhoneNum(value)){
			var state = getOrderState(value);
			if(state ==  -2){ //订单已经存在
				getLayer($('#bugphone'));	
				result = false;	
				return false;				
			}
		}else{	
			obj.val("");
			obj.attr("placeholder","请输入正确的电话号码");
			result = false;	
			return false;
		}
	}else{
		obj.val("");
		obj.attr("placeholder","请输入正确的电话号码");
		result = false;	
		return false;
	}
	return result;
}

//打电话验证并添加订单
$('#l-button').bind('click',function(){
	var flag = checkCallPhone($('#telInput2'));
	if(flag){
		createOrderByPhone($('#telInput2').val());
	}
	
})

//继续打电话
$(".callphone").on("click",function(){
	lxb.call(document.getElementById("telInput2"));
})

//检查订单是否存在
function getOrderState(phone){
	var result = 1; //
	if(_tid){
		$.ajax({
            type: "post",
            url: "/trademark/getOrderState",
            data: {tid:_tid,phone:phone},
			async:false,
            dataType: "json",
            success: function(data){
				result = data;
            }
        });
	}
	return result;
}


//生成我要买订单
function createOrderFromTrak(){
	if(_tid > 0){
        if ( _mobile == '' ){
            getVerify();
            return false;
        }
		var sid     = $('#sid').val();
		var sidArea = $('#area').val();
		var result = false ;
		$.ajax({
            type: "post",
            url: "/trademark/addBuyByPhone",
            data: {tid:_tid,sid:sid,sidArea:sidArea},
            dataType: "json",
			async:false,
            success: function(data){
				if (data > 0){
					result = true;
					sellok();          
                }else if (data.code == -2){
					str = "您已购买过该商品！";
                    sellNo(str);
                }else if (data.code == -4){
					str = "商标数据不存在";
                    sellNo(str);
                }else{
					str = "操作失败";
                    sellNo(str);
                }
            }
        });
	}
	
}

//生成我要买订单
function createOrderByPhone(phone){
	if(_tid > 0){
		var sid     = $('#sid').val();
		var sidArea = $('#area').val();
		var result = false ;
		$.ajax({
            type: "post",
            url: "/trademark/addBuyByPhone",
            data: {tid:_tid,phone:phone,sid:sid,sidArea:sidArea},
            dataType: "json",
			async:false,
            success: function(data){
				if (data > 0){
					result = true;
					sellok();
					lxb.call(document.getElementById("telInput2"));
					//$('#telInput2').val('');               
                }else if (data.code == -2){
					str = "您已购买过该商品！";
                    sellNo(str);
                }else if (data.code == -4){
					str = "商标数据不存在";
                    sellNo(str);
                }else{
					str = "操作失败";
                    sellNo(str);
                }
            }
        });
	}
	
}

//生成我要买订单
function createOrder(saleid){
	$('#addorder').attr('href',buyUrl);
	var sid     = $('#sid').val();
	var sidArea = $('#area').val();
	if(saleid > 0){
        if ( _mobile == '' ){
            getVerify();
            return false;
        }
		$.ajax({
            type: "post",
            url: "/trademark/addBuy",
            data: {saleid:saleid,sid:sid,sidArea:sidArea},
            dataType: "json",
            success: function(data){
				if (data > 0){
                    sellok();
                }else if (data.code == -2){
					str = "您已购买过该商品！";
                    sellNo(str);
                }else if (data.code == -3){
					str = "未登录";
                    sellNo(str);
				}else if (data.code == -4){
					str = "商标数据不存在";
                    sellNo(str);
                }else{
					str = "操作失败";
                    sellNo(str);
                }
            }
        });
	}
}

//提交成功
function sellok(){
	layer.open({
		type: 1,
		title: false,
		closeBtn: false,
		skin: 'yourclass',
		content: $('#mj-submitte')
	});
	//setTimeout(function(){
	//	layer.closeAll();
		$('#addorder').attr('href',"javascript:void(0)");
		$('#addorder').html("商标已经成功添加！");
	//},5000);
	
}

//提交失败
function sellNo(str){
	$('.errorReason').html(str);
	layer.open({
		type: 1,
		title: false,
		closeBtn: false,
		skin: 'yourclass',
		content: $('#mj-error')
	});
	setTimeout(function(){
		layer.closeAll();
	},5000);
}

function verifyPhoneNum(num){
	return /^0?(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8}$/.test(num);
}

function getLayer(obj){
	layer.open({
		type: 1,
		title: false,
		closeBtn: false,
		skin: 'yourclass',
		content: obj
	});
	$(".mj-close").bind("click",function(){
		layer.closeAll();
	});
}

function getVerify()
{
    CHOFN.VerifyShow();
}