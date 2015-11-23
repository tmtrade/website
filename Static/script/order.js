$(function(){
	
	$(".qd-close").bind("click",function(){
		layer.closeAll();
	});
	
	$(".mj-close").bind("click",function(){
		layer.closeAll();
	});
})

$("#telInput").bind("blur",function(){
	//电话号码验证
	checkCallPhone($(this));
	
})

//验证打电话的电话输入
function checkCallPhone(obj){
	var value = obj.val();
	if(value.length == 11 ){
		if(verifyPhoneNum(value)){
			var state = getOrderState(value);
			if(state ==  -2){ //订单已经存在
				getLayer($('#bugphone'));	
				return false;				
			}
		}else{	
			obj.val("");
			obj.attr("placeholder","请输入正确的电话号码");
			return false;
		}
	}else{
		obj.val("");
		obj.attr("placeholder","请输入正确的电话号码");
		return false;
	}
}

//打电话验证并添加订单
$('#l-button').bind('click',function(){
	checkCallPhone($('#telInput'));
	createOrderByPhone($('#telInput').val());
})

//继续打电话
$(".callphone").on("click",function(){
	lxb.call(document.getElementById("telInput"));
})

//检查订单是否存在
function getOrderState(phone){
	var result = 1; //
	if(_saleid){
		$.ajax({
            type: "post",
            url: "/trademark/getOrderState",
            data: {saleid:_saleid,phone:phone},
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
function createOrderByPhone(phone){
	if(_saleid > 0){
		$.ajax({
            type: "post",
            url: "/trademark/addBuyByPhone",
            data: {saleid:_saleid,phone:phone},
            dataType: "json",
			async:false,
            success: function(data){
				if (data > 0){
					$('#telInput').val('');
                   lxb.call(document.getElementById("telInput"));
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
	$('#addorder').attr('href',"javascript:void(0)");
	if(saleid > 0){
		$.ajax({
            type: "post",
            url: "/trademark/addBuy",
            data: {saleid:saleid},
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