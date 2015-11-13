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
                    $('#mTips').html(_iconE+'数据已经存在');
                    $('#mTips').show();
                }else if (data.code == -3){
                    $('#mTips').html(_iconE+'未登录');
                    $('#mTips').show();
				}else if (data.code == -4){
                    $('#mTips').html(_iconE+'商标数据不存在');
                    $('#mTips').show();
                }else{
                    $('#mTips').html(_iconE+'操作失败');
                    $('#mTips').show();
                }
                // if (data == 1){
                    // sendOnce = false;
                    // timer(60, _this);
                    // $('#mTips').html(_iconE+'订单提交');
                    // $('#mTips').show();
                // }else if (data.code == 2){
                    // $('#mTips').html(_iconE+'数据已经存在');
                    // $('#mTips').show();
                // }else if (data.code == 3){
                    // $('#mTips').html(_iconE+'未登录');
                    // $('#mTips').show();
				// }else if (data.code == 4){
                    // $('#mTips').html(_iconE+'商标数据不存在');
                    // $('#mTips').show();
                // }else{
                    // $('#mTips').html(_iconE+'操作失败');
                    // $('#mTips').show();
                // }
            }
        });
	}
}


function sellok(){
	layer.open({
		type: 1,
		title: false,
		closeBtn: false,
		skin: 'yourclass',
		content: $('#mj-submitte')
	});
	$(".mj-close").bind("click",function(){
		layer.closeAll();
	});
}