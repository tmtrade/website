/**********************************************************
 * 登录js
 * author：Xuni
 * 
 **********************************************************/
var _loginType  = true;
var _loginSend  = false;
var _sendOnce   = true;
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
        if (_val == '' || _val == '请输入手机号')
        {
            _loginSend = false;
            $('#loginTips > em').html('请输入手机号');
            $('#loginTips').show();
            return false;
        }
        if (isNaN(_val) || _val.length != 11)
        {
            _loginSend = false;
            $('#loginTips > em').html('只有手机号才能使用忘记密码功能');
            $('#loginTips').show();
            return false;
        }
        checkLoginUser();
        if ( !_loginSend ) return false;

        $("#dl_ts").show();
        //通过验证，可发送密码
        $.ajax({
            type: "post",
            url: "/passport/sendMsgCodeNew",
            data: {m:_val},
            dataType: "json",
            success: function(data){
                if (data.code == 1){
                    _sendOnce = false;
                    timer(60, $("#dl_wjmm"));
                    $('#loginTips > em').html('临时密码已发送');
                    $('#loginTips > em').show();
                }else if (data.code == 2){
                    $('#loginTips > em').html('请输入正确的手机号');
                    $('#loginTips > em').show();
                    _sendOnce = true;
                }else{
                    $('#loginTips > em').html('发送失败');
                    $('#loginTips > em').show();
                    _sendOnce = true;
                }
            }
         });
        return false;
    });

    $("#dl_fsmm").click(function (){
        if ( !_sendOnce ) return false;
        var _this   = $("#loginUser");
        var _val    = $.trim(_this.val());
        if (_val == '' || _val == '请输入手机号')
        {
            _loginSend = false;
            $('#loginTips > em').html('请输入手机号');
            $('#loginTips').show();
            return false;
        }
        if (isNaN(_val) || _val.length != 11)
        {
            _loginSend = false;
            $('#loginTips > em').html('只有手机账号才能使用忘记密码功能');
            $('#loginTips').show();
            return false;
        }
        checkLoginUser();
        if ( !_loginSend ) return false;

        $("#dl_ts").show();
        $.ajax({
            type: "post",
            url: "/passport/sendRegCode",
            data: {m:_val},
            dataType: "json",
            success: function(data){
                if (data.code == 1){
                    _sendOnce = false;
                    timer(60, _this);
                    $('#loginTips > em').html('密码已发送');
                    $('#loginTips > em').show();
                }else if (data.code == 2){
                    $('#loginTips > em').html('请输入正确的手机号');
                    $('#loginTips > em').show();
                    _sendOnce = true;
                }else if (data.code == 3){
                   $('#loginTips > em').html('该账号已存在，请输入密码直接登录。');
                   $('#loginTips > em').show();
                }else{
                    $('#loginTips > em').html('发送失败');
                    $('#loginTips > em').show();
                    _sendOnce = true;
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
    if (_val == '' || _val == '请输入手机号')
    {
        $('#loginTips > em').html('请输入手机号');
        $('#loginTips').show();
        return false;
    }
    if (!isNaN(_val) && _val.length != 11)
    {
        $('#loginTips > em').html('请输入正确的手机号');
        $('#loginTips').show();
        return false;
    }
    var _em = /([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a(www.111cn.net)-z]{2,3}([.][a-z]{2})?/i;
    if (isNaN(_val) && !_em.test(_val) )
    {
        $('#loginTips > em').html('请输入正确的手机号s');
        $('#loginTips').show();
        return false;
    }
    if (_pw == '' || _pw == '请输入密码')
    {
        $('#loginTips > em').html('请输入密码');
        $('#loginTips').show();
        return false;
    }
    $.ajax({
        type: "post",
        url: "/passport/login",
        data: {uname:_val,upass:_pw},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                $('#loginTips > em').html('登录成功');
                $('#loginTips > em').show();
                //登录成功
                setTimeout(function(){
                    if ( doBuyFunc == '') window.location.reload();
                    var func = doBuyFunc;
                    boxHide('mj-loginModel');
                    window[func](_val);
                }, 200);
            }else if (data.code == 2){
                $('#loginTips > em').html('请输入手机号或密码');
                $('#loginTips > em').show();
                _sendOnce = true;
            }else if (data.code == 3){
                $('#loginTips > em').html('请输入正确的手机号');
                $('#loginTips > em').show();
                _sendOnce = true;
            }else if (data.code == 4){
                $('#loginTips > em').html('密码不正确');
                $('#loginTips > em').show();
                _sendOnce = true;
            }else if (data.code == 5){
                $('#loginTips > em').html('手机号已修改');
                $('#loginTips > em').show();
                _sendOnce = true;
            }else if (data.code == 6){
                $('#loginTips > em').html('该账号未注册');
                $('#loginTips > em').show();
                _sendOnce = true;
            }else{
                $('#loginTips > em').html('发送失败');
                $('#loginTips > em').show();
                _sendOnce = true;
            }
        }
    });
}

function checkLoginUser()
{
    var _this   = $("#loginUser");
    var _val    = $.trim(_this.val());
    if (_val == '' || _val == '请输入手机号'){
        _loginSend = false;
        $('#loginTips > em').html('请输入手机号');
        $('#loginTips').show();
        return false;
    }
    if (!isNaN(_val) && _val.length != 11){
        _loginSend = false;
        $('#loginTips > em').html('请输入正确的手机号');
        $('#loginTips').show();
        return false;
    }
    var _em = /([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a(www.111cn.net)-z]{2,3}([.][a-z]{2})?/i;
    if (isNaN(_val) && !_em.test(_val) ){
        _loginSend = false;
        $('#loginTips > em').html('请输入正确的手机号');
        $('#loginTips').show();
        return false;
    }
    $.ajax({
        type: "post",
        url: "/passport/existAccount",
        data: {account:_val},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                _loginSend = true;
                $("#dl_wjmm").show();//忘记密码(已存在账号)
                $("#dl_fsmm").hide();//发送密码(不存在账号)
                $('#loginTips > em').html('该账号已存在，请输入密码直接登录。');
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
                $('#loginTips > em').html('该号尚未注册，点击发送密码，我们将会以短信形式将密码发送到该手机号。并将刚手机号注册为账号。');
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
function timer(count, obj)
{
     window.setTimeout (function () {
         count --;
         obj.text(count + "s");
         if(count > -1){
             timer(count, obj);
         }else{
             _sendOnce = true;
             obj.text('重新获取');
         }
     },1000);
}
