/**********************************************************
 * 登录js
 * author：Xuni
 **********************************************************/

$(document).ready(function(){

    $(".mj-clickable").click(function (){
        var mobile = $("#usrMp_popup").val();
        if (mobile == ''){
            $(".mj-bcTips").text('手机号错误');
        }
        $.ajax({
            type: "post",
            url: "/passport/sendMsgCode",
            data: {m:umobile},
            dataType: "json",
            success: function(data){
                if (data.code == 1){
                    
                }else if (data.code == 2){
                    $('#mTips').html(_iconE+'手机号不正确');
                    $('#mTips').show();
                }else if (data.code == 3){
                    $('#mTips').html(_iconE+'该手机号未注册');
                    $('#mTips').show();
                }else{
                    $('#mTips').html(_iconE+'发送失败');
                    $('#mTips').show();
                }
            }
        });
    });

    $(".mj-from-btn").click(function (){
        $("#usrMp_popup").blur();
        $("#mj-inputl").blur();
        if ( $(".mj-eed").css('display') != 'none' ) return false;
        if ( !_isLogin ){
            getLayer($('#mj-submitte'));
        }else{
            addBuy();
        }
    });

    //手机验证
    $("#usrMp_popup").blur(function(){
        if($("#usrMp_popup").val()==""){
            $(".mj-eed").show();
            $(".reg-tip em").html("手机号不能为空");
        }else if(!/^0?(13[0-9]|15[012356789]|18[0236789]|14[57])[0-9]{8}$/.test($("#usrMp_popup").val())){
            $(".mj-eed").show();
            $(".reg-tip em").html("手机号码不正确");
        }else{
            $(".mj-eed").hide();
            //$(".reg-tip em").html("输入正确");
        }
    });
    $("#usrMp_popup").focus(function (){
        if ( !_isLogin ) return false;
        if ( $(this).val() == '' ){
            $(this).val(_mobile);
        }
    });

    //类别验证
    $("#mj-inputl").blur(function(){
        if($("#mj-inputl").val()==""){
            $(".mj-eed").show();
            $(".reg-tip em").text("请填写购买需求");
        }else{
            $(".mj-eed").hide();
            //$(".reg-tip em").text("输入正确");
        }
    });

});


//倒计时
function timer(count, obj)
{
     window.setTimeout (function () {
         count --;
         obj.text(count + "秒后重新获取");
         if(count > -1){
             timer(count, obj);
         }else{
             sendOnce = true;
             obj.text('重新获取');
         }
     },1000);
}

function getLayer(obj)
{
    $(".mj-bcTips").hide();
    obj.show();
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

function addBuy()
{
    alert('123123'); return ;

    var mobile = $("#usrMp_popup").val();
    $.ajax({
        type: "post",
        url: "/passport/existAccount/",
        data: {account:mobile},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                getLayer($('#mj-submitte'));
            }else if (data.code == 2){
                $(".mj-eed").show();
                $(".reg-tip em").html("手机号不能为空");
            }else if (data.code == 3){
                $('#mTips').html(_iconE+'手机号码不正确');
                $('#mTips').show();
            }else{
                addBuy();
            }
        }
    });

    var params = $("#buyForm").serialize();
    $.ajax({
        type: "post",
        url: "/buy/add/",
        data: params,
        dataType: "json",
        success: function(data){
            if (data.code == 1){
               getLogin();return ;
            }else if (data.code == 2){
                $(".mj-eed").show();
                $(".reg-tip em").html("手机号不能为空");
            }else if (data.code == 3){
                $('#mTips').html(_iconE+'手机号码不正确');
                $('#mTips').show();
            }else{
                $("#buyForm").submit();
            }
        }
    });
}
