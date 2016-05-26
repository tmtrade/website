/**********************************************************
 * 登录js
 * author：Xuni
 **********************************************************/
var _buyMobile  = false;
var _buyNeed    = false;
var _buyTitle   = "<h6>亲，提交成功了</h6><p>系统已将登录密码发送到你手机，登录即可查看求购进展。我们也会在10分钟向你确认需求</p>";

$(document).ready(function(){

    $(".mj-from-btn").click(function (){
        $("#buyMoblie").blur();
        if ( !_buyMobile ) return false;
        $("#buyNeed").blur();
        if ( !_buyNeed ) return false;
        var name_ = $("#buyName").val();
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
        var _mobile = $("#buyMoblie").val();
        if ( !_isLogin ){
            $("#buyPass").val('');
            $.ajax({
                type: "post",
                url: "/passport/existAccount/",
                data: {account:_mobile},
                dataType: "json",
                success: function(data){
                    if (data.code == 1){
                        getLogin();
                        $("#dl_ts").hide();
                        doBuyFunc = 'doBuy';
                        $("#loginUser").val(_mobile);
                        $("#loginUser").blur();
                    }else if (data.code == 2){//空
                        $(".reg-tip em").html("请输入手机号");
                        $(".mj-eed").show();
                    }else if (data.code == 3){//账号不正确（不是邮箱或手机号）
                        $(".reg-tip em").html("请输入正确的手机号");
                        $(".mj-eed").show();
                    }else if (data.code == -1){//未注册
                        addTempBuy();
                    }else{
                        //请求失败
                        $(".reg-tip em").html("操作失败，请稍后重试");
                        $(".mj-eed").show();
                    }
                },
                error: function(data){
                    getLayer($('#mj-submitteF'));
                }
            });
        }else{
            addBuy();
        }
    });

    //手机验证
    $("#buyMoblie").blur(function(){
        var _this = $(this);
        _buyMobile = false;
        if( _this.val()=="" ){
            $(".mj-eed").show();
            $(".reg-tip em").html("手机号不能为空");
        }else if(!/^0?(13[0-9]|15[012356789]|18[0123456789]|14[57])[0-9]{8}$/.test(_this.val())){
            $(".mj-eed").show();
            $(".reg-tip em").html("手机号码不正确");
        }else{
            _buyMobile = true;
            $(".mj-eed").hide();
            //$(".reg-tip em").html("输入正确");
        }
    });
    $("#buyMoblie").focus(function (){
        if ( !_isLogin ) return false;
        if ( $(this).val() == '' ){
            $(this).val(_mobile);
        }
    });

    //类别验证
    $("#buyNeed").blur(function(){
        var _this = $(this);
        _buyNeed = false;
        var _mj_input = $.trim( _this.val() );
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

    $("#closeFailed").click(function(){
        $(".mj-close").click();
    });

});

function addTempBuy()
{
    var mobile  = $.trim( $("#buyMoblie").val() );
    var need    = $.trim( $("#buyNeed").val() );
    var name    = $.trim( $("#buyName").val() );
    var sid     = $.trim( $("#sid").val() );
    var area    = $.trim( $("#area").val() );
    sendBehavior(1,12,0,0,'电话:'+mobile+';内容:'+need);//发送统计数据
    $.ajax({
        type: "post",
        url: "/buy/addTemp/",
        data: {mobile:mobile,content:need,name:name,sid:sid,area:area},
        dataType: "json",
        success: function(data){
            if (data.code == 1){//成功
                getLogin(_buyTitle);
                doBuyFunc = 'doBuy';
                $("#dl_ts").show();
                $("#loginUser").val(mobile);
                $("#loginUser").parent().parent().find($(".mj-inpuVs")).text("");
                $("#dl_wjmm").hide();//忘记密码(已存在账号)
                $("#dl_fsmm").show();//发送密码(不存在账号)
                $("#dl_fsmm").click();
            }else{
                getLayer($('#mj-submitteF'));
            }
        },
        error: function(data){
            getLayer($('#mj-submitteF'));
        }
    });
}

function addBuy()
{
    var mobile  = $.trim( $("#buyMoblie").val() );
    var need    = $.trim( $("#buyNeed").val() );
    var name    = $.trim( $("#buyName").val() );
    var sid     = $.trim( $("#sid").val() );
    var area    = $.trim( $("#area").val() );

    var index = layer.load(1, {
        shade: [0.6,'#000000'] //0.1透明度的白色背景
    });
    $.ajax({
        type: "post",
        url: "/buy/add/",
        data: {mobile:mobile,content:need,name:name,sid:sid,area:area},
        dataType: "json",
        success: function(data){
            layer.close(index);
            if (data.code == 1){
                clearBuy();
                getLayer($('#mj-submitteS'));
            }else{
                getLayer($('#mj-submitteF'));
            }
        },
        error: function(data){
            layer.close(index);
            getLayer($('#mj-submitteF'));
        }
    });
}

function doBuy(mobile)
{
    var need    = $.trim( $("#buyNeed").val() );
    var name    = $.trim( $("#buyName").val() );
    var sid     = $.trim( $("#sid").val() );
    var area    = $.trim( $("#area").val() );

    $.ajax({
        type: "post",
        url: "/buy/add/",
        data: {mobile:mobile,content:need,name:name,sid:sid,area:area},
        dataType: "json",
        success: function(data){
            if (data.code == 1){
                clearBuy();
                //window.location = _manger_url + "buyer/ready/";
                getLayer($('#mj-submitteS'));
            }else{
                getLayer($('#mj-submitteF'));
            }
        },
        error: function(data){
            getLayer($('#mj-submitteF'));
        }
    });
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

function clearBuy()
{
    $("#buyMoblie").val('');
    $("#buyNeed").val('');
    $("#buyName").val('');
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