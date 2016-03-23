//购买提交
$('#subBuy').click(function(){
	var content = $('#buyNeed').val();
        var pttype = $('#pttype').val();
	if(!content || content == '请输入专利用途' ||  content == '专利号 / 专利描述'){
		$('#buyNeed').focus();
                if(pttype=="求购"){
                    layer.msg('请输入专利用途');
                }else{
                    layer.msg('请输入专利号 / 专利描述');
                }
		
		return false;
	}
	var mobile = $('#buyMoblie').val();
	if(mobile == '' || mobile == "请输入联系电话"){
		$('#buyMoblie').focus();
		layer.msg('手机号不能为空');
		return false;
	}else if(!verifyPhoneNum(mobile)){
		$('#buyMoblie').focus();
                layer.msg('手机号码不正确');
		return false;
	}
	addBuy();
})
function addBuy()
{
    var mobile  = $.trim( $("#buyMoblie").val() );
    var need    = $.trim( $("#buyNeed").val() );
    var pttype  = $.trim( $("#pttype").val() );
    var chk_value =[]; 
    $('input[type=checkbox]:checked').each(function(){
        chk_value.push($(this).val()); 
    });
    var ptype = chk_value.join(',');
    var data	= new Array();
    data['name'] 	= '';
    data['tel'] 	= mobile;
    data['subject'] = need;
    data['remarks'] = need;
    data['pttype'] = pttype;
    data['type'] = "7";
    data['ptype'] = ptype;
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

$(".mj-boBtn1").bind("click",function(){
                layer.closeAll();
});