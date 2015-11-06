var sendOnce = true;
var _iconE = '<i class="us-icon us-icon19"></i>';
$(document).ready(function(){
    $('#mTips').hide();
    $('#eTips').hide();
    $(".ms-loginBtn").click(function (){
        var _this = $(this);
        if (_this.attr('ctype') == 'm'){
            checkMoblieForm();
        }else if (_this.attr('ctype') == 'e'){
            checkEmailForm();
        }
        return false;
    });

    $(".ms-sent").click(function (){
        var _this = $(this);
        var umobile  = $.trim($("#umobile").val());
        if (umobile == ''){
            $('#mTips').html(_iconE+'请填写手机号');
            $('#mTips').show();
            return false;
        }
        if (umobile.length != 11){
            $('#mTips').html(_iconE+'手机号不正确');
            $('#mTips').show();
            return false;
        }
        if ( !sendOnce ) return false;
        $.ajax({
            type: "post",
            url: "/passport/sendMsgCode",
            data: {m:umobile},
            dataType: "json",
            success: function(data){
                if (data.code == 1){
                    sendOnce = false;
                    timer(60, _this);
                    $('#mTips').html(_iconE+'验证码已发送');
                    $('#mTips').show();
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
        return false;
    });

});

function loginout()
{
    //var _url = window.location;
    _url = "/passport/loginout/";
    window.location = _url;
}

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
             obj.text('重新获取验证码');
         }
     },1000);
}

function checkEmailForm()
{
    var uemail  = $.trim($("#uemail").val());
    var upass   = $("#upass").val();
    if (uemail == '' || uemail == '请填写邮箱或手机号'){
        $('#eTips').html(_iconE+'请填写邮箱或手机号');
        $('#eTips').show();
        return false;
    }
    if (upass == '' || upass == '请填写密码'){
        $('#eTips').html(_iconE+'请填写密码');
        $('#eTips').show();
        return false;
    }

    $.ajax({
        type: "post",
        url: "/passport/login",
        data: {uname:uemail, upass:upass},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                $('#eTips').hide();
                window.location.reload();
            }else if (data.code == 2){
                $('#eTips').html(_iconE+'账号或密码为空');
                $('#eTips').show();
            }else if (data.code == 3){
                $('#eTips').html(_iconE+'请填写邮箱或手机号');
                $('#eTips').show();
            }else if (data.code == 4){
                $('#eTips').html(_iconE+'密码不正确');
                $('#eTips').show();
            }else if (data.code == 5){
                $('#eTips').html(_iconE+'该账号未注册');
                $('#eTips').show();
            }else{
                $('#eTips').html(_iconE+'登录失败，请稍后登录');
                $('#eTips').show();
            }
        }
    });
}

function checkMoblieForm()
{
    var umobile  = $.trim($("#umobile").val());
    var ucode   = $.trim($("#ucode").val());
    if (umobile == ''){
        $('#mTips').html(_iconE+'请填写手机号');
        $('#mTips').show();
        return false;
    }
    if (umobile.length != 11){
        $('#mTips').html(_iconE+'手机号不正确');
        $('#mTips').show();
        return false;
    }
    if (ucode == ''){
        $('#mTips').html(_iconE+'请填写验证码');
        $('#mTips').show();
        return false;
    }
    if (ucode.length != 6){
        $('#mTips').html(_iconE+'验证码不正确');
        $('#mTips').show();
        return false;
    }

    $.ajax({
        type: "post",
        url: "/passport/fastLogin",
        data: {uname:umobile, upass:ucode},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                $('#mTips').hide();
                window.location.reload();
            }else if (data.code == 2){
                $('#mTips').html(_iconE+'账号或密码为空');
                $('#mTips').show();
            }else if (data.code == 3){
                $('#mTips').html(_iconE+'手机号不正确');
                $('#mTips').show();
            }else if (data.code == 4){
                $('#mTips').html(_iconE+'请重新获取验证码');
                $('#mTips').show();
            }else if (data.code == 5){
                $('#mTips').html(_iconE+'验证码不正确或已过期');
                $('#mTips').show();
            }else{
                $('#mTips').html(_iconE+'登录失败，请稍后登录');
                $('#mTips').show();
            }
        }
    });
}