$(function(){
	
	$(".qd-close").bind("click",function(){
		layer.closeAll();
	});
	
	$(".mj-close").bind("click",function(){
		layer.closeAll();
	});
})

//生成我要买订单
function getorder(saleid){
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