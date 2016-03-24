$(document).ready(function(e) {
    //悬浮窗提醒标题
    _defLogin   = "<h6>登录</h6> <p style=\"margin-left: 10px;\"></p>";
    _xunjia     = "<h6>登录查询此商标出售价格</h6><p>一次登录后可查看全站所有出售商标价格</p>";
    //手机号码验证正则
    mobilereg = /^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d\d\d\d\d\d\d\d$/i;
    //获取验证码
    $('#dl_wjmm,#dl_fsmm').click(function(){
        mobile 	= $.trim($('#loginUser').val());
        //验证手机号
        if(!mobile){
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('请输入手机号码');
            $("#loginTips").attr('flag',1);
            return false;
        }
        if(!mobilereg.test(mobile) || mobile.length!=11){
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('请输入正确的手机号码');
            $("#loginTips").attr('flag',1);
            return false;
        }
        ucNetwork.sendCode(mobile,$(this),'点击获取验证码');
    });
    //用户登录
    $('#dl_sub').click(function(){
        hideLoginTip(0);
        account 	= $.trim($('#loginUser').val());
        password 	= $.trim($('#loginPass').val());
        if(!account){
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('请输入手机号码');
            $("#loginTips").attr('flag',1);
            return false;
        }
        if(!mobilereg.test(account) || account.length!=11){
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('请输入正确的手机号码');
            $("#loginTips").attr('flag',1);
            return false;
        }
        if(!password){
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('请输入密码');
            $("#loginTips").attr('flag',2);
            return false;
        }
        //判断是验证码登录还是密码登录
        if($('#dl_sub').hasClass('yzm')){
            ucNetwork.verifyCode(account,password);//验证码登录
        }else{
            ucNetwork.userLog(account,password);//密码登录
        }
    });


    //登录弹窗失去焦点验证
    $('#loginUser').blur(function(){
        var tel 	= $.trim($('#loginUser').val());
        if(!tel){
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('请输入手机号码');
            $("#loginTips").attr('flag',1);
            return false;
        }
        if(!mobilereg.test(tel) || tel.length!=11){
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('请输入正确的手机号码');
            $("#loginTips").attr('flag',1);
            return false;
        }
        hideLoginTip(1);
    });
    $('#loginPass').blur(function(){
        var pass = $.trim($('#loginPass').val());
        if(!pass){
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('请输入密码');
            $("#loginTips").attr('flag',2);
            return false;
        }
        hideLoginTip(2);
    });

    //搜索框按钮触发
    $(".mj-search-submit").click(function (){

        var input_kw = $("input[name='kw']");

        var kw_value = $.trim($("input[name='kw']").val());

        var special = RegExp(/[(\ )(\～)(\！)(\＠)(\＃)(\￥)(\％)(\……)(\＆)(\×)(\（)(\）)(\？)(\｜)(\｝)(\｛)(\【)(\】)(\＋)(\——)(\－)(\＝)(\；)(\：)(\“)(\”)(\‘)(\’)(\，)(\。)(\《)(\》)(\`)(\~)(\!)(\@)(\#)(\$)(\%)(\^)(\&)(\*)(\()(\))(\-)(\_)(\+)(\=)(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\<)(\>)(\?)(\)]+/);


        if ( kw_value != '输入你想买的商标名称/商标号' && special.test(kw_value) ){

            layer.tips('不要输入特殊字符哦', input_kw, {

                tips: [3, '#fc7d28'],

                time: 2000

            });

            return false;

        }

        if (kw_value != '输入你想买的商标名称/商标号' && kw_value.length > 30){

            layer.tips('只搜索前30个字符哦，请稍等...', input_kw, {

                tips: [3, '#fc7d28'],

                time: 1000

            });

            kw_value = kw_value.substr(0, 30);

            input_kw.val(kw_value);
            return false;
        }
        if ( kw_value == '输入你想买的商标名称/商标号' || kw_value==""){

            layer.tips('请输入你要查询的商标名称/商标号', input_kw, {

                tips: [3, '#fc7d28'],

                time: 1000

            });
            return false;
        }

        $("#searchForm").submit();

    });
});
//成功失败弹窗
function getLayer(obj) {
    $(".mj-bcTips").hide();
    obj.show();
    layer.open({
        type: 1,
        title: false,
        closeBtn: false,
        skin: 'yourclass',
        content: obj
    });
    $(".mj-close").bind("click", function () {
        layer.closeAll();
    });
}

    //询价登录回调
function userLogCallback(Obj,data){
    $.each(Obj,function(i,n){
         //用户登录情况下
        if(n.code==1){
            //弹出成功框
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('登录成功');
            $("#loginTips").attr('flag',2);
        }else{
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('用户名或密码错误');
            $("#loginTips").attr('flag',2);
        }
    });
}

//发送验证码
function sendCodeCallback(Obj,htmlobj,title){
    $.each(Obj,function(i,n){
        if(n.code > 0 ){//发送成功
            $('#dl_sub').addClass('yzm');//添加额外的class yzm 判断为验证码登录
            timer(60, $('.aaaa'), title);//倒计时
        }else{
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text('网络问题请刷新!');
            $("#loginTips").attr('flag',0);
        }
    });
}
//验证验证码是否合法
function verifyCodeCallback(Obj,account,code){
    $.each(Obj,function(i,n){
        if(n.code==1){
            $('.Not_logged').hide();
            $('.logged').show();
            ucNetwork.logCode(account,code);
        }else{
            $(".ms-errorTips2,#loginTips").show();
            $("#loginTips em").text(n.msg);
            $("#loginTips").attr('flag',0);
        }
    });
}
//调用登录弹窗
function getLogin(title,tel,isExist){
    $("#loginUser").val('');
    $("#loginPass").val('');
    //$("#loginUser").parent().parent().find($(".mj-inpuVs")).text("请输入手机号");
    //$("#loginPass").parent().parent().find($(".mj-inpuVs")).text("请输入密码");
    $("#dl_ts").hide();
    $('.reg-tip').hide();
    var title = (title=='')?_defLogin:title;
    if (!isExist){
        //账户不存在
        $("#dl_wjmm").hide();//忘记密码(已存在账号)
        $("#dl_fsmm").show();//发送密码(不存在账号)
        $("#dl_fsmm").addClass('aaaa');//添加类
        //触发点击事件
        $('.mj-inpuVs').html('');
        $('#loginUser').val(tel);
        $('#dl_wjmm').trigger('click');
    }else{
        //账户存在
        $("#dl_wjmm").addClass('aaaa');//添加类
        $('.mj-inpuVs').html('');
        $('#loginUser').val(tel);
    }
    $("#dl_title").html(title);
    //CHOFN.loginShow();
    //弹出登录框
    $('#loginFormDiv').show();
    boxBg();
    reCal($('#loginFormDiv'));
}
//用户询价
function xunjia(obj){
    //用户是否登录
    if(login_mobile){
        //提交到分配系统中
        //组装提交数据
        var mydata = $(obj).closest('li').find('.xunjia_data');
        var remarks = mydata.attr('remarks');
        remarks = remarks + ';电话号码：' + login_mobile;
        var buyData 			= new Array();
        buyData['tel'] 			= login_mobile;
        buyData['tid'] 			= mydata.attr('tid');
        buyData['trademark'] 	= mydata.attr('number');
        buyData['class'] 		= mydata.attr('data_class');
        buyData['subject'] 		= '求购信息';
        buyData['remarks'] 		= remarks;
        //提交数据
        ucBuy.buyAdd(buyData);
    }else if(nick_name){
        //弹出绑定手机框
        getLayer($('#goCenter'));
    }else{
        //弹出登录框
        getLogin(_xunjia,'',1);
    }
    return false;
}
//添加到分配系统回调函数
function buyAddCallback(Obj){
    $.each(Obj,function(i,n){
        //弹窗提示
        if(n.code==1){
            getLayer($('#mj-submitteS'));
            $('.opt_btn').replaceWith("<a href='javascript:;' class='mj-priceTxtt_link'>您已经购买过该商标</a>");
        }else if(n.code==0){
            layer.msg('您已经购买过该商标');
        }else{
            getLayer($('#mj-submitteF'));
        }
    });
}
//聊天函数
function goChat(){
    window.open("http://p.qiao.baidu.com/im/index?siteid=7918603&ucid=1268165");
}
//隐藏登录提示信息
function hideLoginTip(num){
    if($("#loginTips").attr('flag')==num){
        $(".ms-errorTips2").hide();
    }
}