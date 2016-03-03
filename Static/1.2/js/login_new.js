/**********************************************************
 * 登录js
 * author：Xuni
 * 
 **********************************************************/
var _loginSend  = false;
var _sendOnce   = true;
var _sendOnce2  = true;
var doBuyFunc   = '';
var _defLogin   = "<h6>登录</h6> <p style=\"margin-left: 10px;\"></p>";
var _wantTitle  = "<h6>登录提交购买意向</h6><p>我们10分钟向你确认需求，你也可登录查看商标转让进度</p>";
var _viewTitle  = "<h6>登录查看此商标出售价格</h6><p>一次登录后可查看全站所有出售商标价格</p>";

$(document).ready(function(){

    $("#fastLogin").click(function(){
        getLogin();
    });

	$("#loginUser").blur(function (){
		checkLoginUser();
	})

    $("#dl_sub").click(function (){ 
        letLogin();
    });
	
    $("#dl_wjmm").click(function (){
        if ( !_sendOnce ) return false;
        var _this   = $("#loginUser");
        var _val    = $.trim(_this.val());

        checkUser();
        if ( !_loginSend ) return false;

        if (isNaN(_val) || _val.length != 11)
        {
            $('#loginTips > em').html('请输入手机号找回密码');
            $('#loginTips').show();
            return false;
        }
        //通过验证，可发送密码
        $.ajax({
            type: "post",
            url: "/passport/sendMsgCodeNew/",
            data: {m:_val},
            dataType: "json",
            success: function(data){
                if (data.code == 1){
                    _sendOnce = false;
                    timer(60, $("#dl_wjmm"), 'wjmm', '忘记密码');
                    $('#loginTips > em').html('动态密码已发送');
                    $('#loginTips').show();
                    $("#dl_ts").show();
                }else if (data.code == 2){
                    $('#loginTips > em').html('请输入正确的手机号');
                    $('#loginTips').show();
                    _sendOnce = true;
                }else if (data.code == 3){
                    $('#loginTips > em').html('该号尚未注册，点击发送密码，我们将会以短信形式将密码发送到该手机号');
                    $('#loginTips').show();
                    _sendOnce = true;
                }else{
                    $('#loginTips > em').html('发送失败');
                    $('#loginTips').show();
                    _sendOnce = true;
                }
            }
         });
        return false;
    });

    $("#dl_fsmm").click(function (){
        if ( !_sendOnce2 ) return false;
        var _this   = $("#loginUser");
        var _val    = $.trim(_this.val());

        checkUser();
        if ( !_loginSend ) return false;

        if (isNaN(_val) || _val.length != 11)
        {
            $('#loginTips > em').html('请输入手机号找回密码');
            $('#loginTips').show();
            return false;
        }

        //通过验证，可发送密码
        $.ajax({
            type: "post",
            url: "/passport/sendRegCode/",
            data: {m:_val},
            dataType: "json",
            success: function(data){
                if (data.code == 1){
                    _sendOnce2 = false;
                    timer(60, $("#dl_fsmm"), 'fsmm', '发送密码');
                    $('#loginTips > em').html('密码已发送');
                    $('#loginTips').show();
                    $("#dl_ts").show();
                }else if (data.code == 2){
                    $('#loginTips > em').html('请输入正确的手机号');
                    $('#loginTips').show();
                    _sendOnce2 = true;
                }else if (data.code == 3){
                    $('#loginTips > em').html('该账号已存在，请输入密码直接登录');
                    $('#loginTips').show();
                    _sendOnce2 = true;
                }else{
                    $('#loginTips > em').html('发送失败');
                    $('#loginTips').show();
                    _sendOnce2 = true;
                }
            }
         });
        return false;
    });

});

function letLogin()
{
    var _this   = $("#loginUser");
    var _pass   = $("#loginPass");
    var _val    = $.trim(_this.val());
    var _pw    = $.trim(_pass.val());
    
    checkUser();
    if ( !_loginSend ) return false;

    if (_pw == '' || _pw == '请输入密码')
    {
        $('#loginTips > em').html('请输入密码');
        $('#loginTips').show();
        return false;
    }
    $.ajax({
        type: "post",
        url: "/passport/login/",
        data: {uname:_val,upass:_pw},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                $('#loginTips > em').html('登录成功');
                $('#loginTips').show();
                //登录成功
                setTimeout(function(){
                    if ( doBuyFunc == '') {
                        window.location.reload();
                        return false;
                    }
                    var func = doBuyFunc;
                    boxHide('mj-loginModel');
                    window[func](_val);
                }, 100);
            }else if (data.code == 2){
                $('#loginTips > em').html('请输入手机号或密码');
                $('#loginTips').show();
                _sendOnce = true;
            }else if (data.code == 3){
                $('#loginTips > em').html('请输入正确的手机号');
                $('#loginTips').show();
                _sendOnce = true;
            }else if (data.code == 4){
                $('#loginTips > em').html('密码不正确');
                $('#loginTips').show();
                _sendOnce = true;
            }else if (data.code == 5){
                $('#loginTips > em').html('手机号已修改');
                $('#loginTips').show();
                _sendOnce = true;
            }else if (data.code == 6){
                $('#loginTips > em').html('该号尚未注册，点击发送密码，我们将会以短信形式将密码发送到该手机号');
                $('#loginTips').show();
                _sendOnce = true;
            }else{
                $('#loginTips > em').html('发送失败');
                $('#loginTips').show();
                _sendOnce = true;
            }
        }
    });
}

function checkUser()
{
    var _this   = $("#loginUser");
    var _val    = $.trim(_this.val());
    if (_val == '' || _val == '请输入手机号'){
        _loginSend = false;
        $('#loginTips > em').html('请输入手机号');
        $('#loginTips').show();
        return false;
    }
    if (isNaN(_val) || _val.length != 11){
        _loginSend = false;
        $('#loginTips > em').html('请输入正确的手机号');
        $('#loginTips').show();
        return false;
    }
    // var _em = /([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a(www.111cn.net)-z]{2,3}([.][a-z]{2})?/i;
    // if (isNaN(_val) && !_em.test(_val) ){
    //     _loginSend = false;
    //     $('#loginTips > em').html('请输入正确的手机号');
    //     $('#loginTips').show();
    //     return false;
    // }
    _loginSend = true;
    return true;
}

function checkLoginUser()
{
    var _this   = $("#loginUser");
    var _val    = $.trim(_this.val());
    checkUser();
    if ( !_loginSend ) return false;
    $.ajax({
        type: "post",
        url: "/passport/existAccount/",
        data: {account:_val},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                _loginSend = true;
                $("#dl_wjmm").show();//忘记密码(已存在账号)
                $("#dl_fsmm").hide();//发送密码(不存在账号)
                $('#loginTips > em').html('该账号已存在，请输入密码直接登录');
                $('#loginTips').show();
            }else if (data.code == 2){//空
                _loginSend = false;
                $('#loginTips > em').html('请输入手机号');
                $('#loginTips').show();
            }else if (data.code == 3){//账号不正确（不是邮箱或手机号）
                _loginSend = false;
                $('#loginTips > em').html('请输入正确的手机号');
                $('#loginTips').show();
            }else if (data.code == -1){//未注册
                _loginSend = true;
                $('#loginTips > em').html('该号尚未注册，点击发送密码，我们将会以短信形式将密码发送到该手机号');
                $('#loginTips').show();
                $("#dl_wjmm").hide();//忘记密码(已存在账号)
                $("#dl_fsmm").show();//发送密码(不存在账号)
            }else{
                //请求失败
                _loginSend = false;
                $('#loginTips > em').html('操作失败，请稍后重试');
                $('#loginTips').show();
            }
        }
    });
}

//调整用弹层方法
function getLogin(title){
    $("#loginUser").val('');
    $("#loginPass").val('');
    $("#loginUser").parent().parent().find($(".mj-inpuVs")).text("请输入手机号");
    $("#loginPass").parent().parent().find($(".mj-inpuVs")).text("请输入密码");
    $("#dl_ts").hide();
    $('#loginTips').hide();
    $("#dl_wjmm").hide();//忘记密码(已存在账号)
    $("#dl_fsmm").show();//发送密码(不存在账号)
    if (title){
        $("#dl_title").html(title);
    }else{
        $("#dl_title").html(_defLogin);
    }
    CHOFN.loginShow();
}

function loginout()
{
    //var _url = window.location;
    _url = "/passport/loginout/";
    window.location = _url;
}

//倒计时
function timer(count, obj, type, title)
{
     window.setTimeout (function () {
         count --;
         obj.text(count + "s");
         if(count > -1){
             timer(count, obj, type, title);
         }else{
            if (type == 'wjmm'){
                _sendOnce = true;
            }else{
                _sendOnce2 = true;
            }
            obj.text(title);
         }
     },1000);
}
