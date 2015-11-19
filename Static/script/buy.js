/**********************************************************
 * 登录js
 * author：Xuni
 **********************************************************/

var _sendOnce   = true;
var _buyMobile  = false;
var _buyNeed    = false;
$(document).ready(function(){

    $(".mj-from-btn").click(function (){
        $("#usrMp_popup").blur();
        if ( !_buyMobile ) return false;
        $("#mj-inputl").blur();
        if ( !_buyNeed ) return false;
        var name_ = $("#userName").val();
        if ( name_ != '' && isChinese(name_) && !isEng(name_) ){
            $(".mj-eed").show();
            $(".reg-tip em").html("姓氏必须为中英文");
            return false;
        }
        if ( name_ != '' && (!isChinese(name_) && name_.length > 4) ){
            $(".mj-eed").show();
            $(".reg-tip em").html("姓氏中文为4字以内");
            return false;
        }
        if ( name_ != '' && (isEng(name_) && name_.length > 8) ){
            $(".mj-eed").show();
            $(".reg-tip em").html("姓氏英文为8字母以内");
            return false;
        }
        if ( !_isLogin ){
            $("#buyMsgCode").val('');
            getLayer($('#mj-submitte'));
        }else{
            addBuy();
        }
    });

    //手机验证
    $("#usrMp_popup").blur(function(){
        _buyMobile = false;
        if($("#usrMp_popup").val()==""){
            $(".mj-eed").show();
            $(".reg-tip em").html("手机号不能为空");
        }else if(!/^0?(13[0-9]|15[012356789]|18[0123456789]|14[57])[0-9]{8}$/.test($("#usrMp_popup").val())){
            $(".mj-eed").show();
            $(".reg-tip em").html("手机号码不正确");
        }else{
            _buyMobile = true;
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
        _buyNeed = false;
        var _mj_input = $("#mj-inputl").val();
        if(_mj_input == ""){
            $(".mj-eed").show();
            $(".reg-tip em").text("请填写购买需求");
        }else if(_mj_input.length > 100){
            $(".mj-eed").show();
            $(".reg-tip em").text("购买需求不能超过100字符");
        }else{
            _buyNeed = true;
            $(".mj-eed").hide();
            //$(".reg-tip em").text("输入正确");
        }
    });

    $(".mj-clickable").click(function (){
        if ( !_sendOnce ) return false;
        $(".mj-bcTips").text('');
        $('.mj-bcTips').show();
        var mobile = $("#usrMp_popup").val();
        if (mobile == ''){
            $(".mj-bcTips").text('手机号错误');
            return false;
        }
        $.ajax({
            type: "post",
            url: "/passport/sendMsgCode/",
            data: {m:mobile,r:'n'},
            dataType: "json",
            success: function(data){
                if (data.code == 1){
                    $(".mj-bcTips").text('发送成功');
                    $('.mj-bcTips').show();
                    _sendOnce = false;
                    _timer(60 ,$(".mj-clickable"));
                }else if (data.code == 2){
                    $(".mj-bcTips").text('手机号不正确');
                    $('.mj-bcTips').show();
                }else{
                    $(".mj-bcTips").text('发送失败');
                    $('.mj-bcTips').show();
                }
            }
        });
    });

    $(".mj-determineBtn").click(function (){
        var code    = $.trim($("#buyMsgCode").val());
        var mobile  = $.trim($("#usrMp_popup").val());
        if (mobile == '' || mobile.length != 11){
            $(".mj-bcTips").text('手机号错误');
            $('.mj-bcTips').show();
            return false;
        }
        if ( code == '' || code.length != 4){
            $(".mj-bcTips").text('验证码不正确');
            $('.mj-bcTips').show();
            return false;
        }
        $.ajax({
            type: "post",
            url: "/passport/checkMsgCode/",
            data: {m:mobile,c:code},
            dataType: "json",
            success: function(data){
                if (data.code == 1 || data.code == 11){
                    $(".mj-close").click();
                    addBuy();
                }else if (data.code == 4){
                    $(".mj-bcTips").text('手机号已更改');
                    $('.mj-bcTips').show();
                }else if (data.code == 5){
                    $(".mj-bcTips").text('验证码不正确');
                    $('.mj-bcTips').show();
                }else{
                    $(".mj-bcTips").text('发送失败');
                    $('.mj-bcTips').show();
                }
            }
        });
    });

    $("#closeFailed").click(function (){
        layer.closeAll();
    });

});

//倒计时
function _timer(count, obj)
{
    obj.removeClass('mj-clickable');
    obj.addClass('mj-bclik');
    window.setTimeout (function () {
        count --;
        obj.text(count + "秒后重新获取");
         if(count > -1){
            _timer(count, obj);
         }else{
            _sendOnce = true;
            obj.text('重新获取');
            obj.removeClass('mj-bclik');
            obj.addClass('mj-clickable');
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
    var params = $("#buyForm").serialize();
    $.ajax({
        type: "post",
        url: "/buy/add/",
        data: params,
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                clearBuy();
                getLayer($('#mj-submitteS'));
            }else{
                getLayer($('#mj-submitteF'));
            }
        }
    });

}

function clearBuy()
{
    $("#usrMp_popup").val('');
    $("#mj-inputl").val('');
    $("#userName").val('');
}

function isChinese(temp)
{
    var regExp = /.*[\u4e00-\u9fa5]+.*$/;
    if(regExp.test(temp))
        return false;
    return true;
}

//判断字母(英文)   
function isEng(param){
    var regExp = /[^A-Za-z]/;
    if(regExp.test(param))
        return false;
    return true;
}