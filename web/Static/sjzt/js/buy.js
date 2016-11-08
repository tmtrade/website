
_buyMobile  = false;
_buyNeed    = false;
$(document).ready(function(){

    $(".mj-from-btn").click(function (){
        $("#buyMoblie").blur();
        if ( !_buyMobile ) return false;
        $("#buyNeed").blur();
        if ( !_buyNeed ) return false;       

        addBuy();
    });

    //手机验证
    $("#buyMoblie").blur(function(){
        var _this = $(this);
        _buyMobile = false;
        if( _this.val()=="" ){
            $("#labelMobile").show();
            $("#labelMobile").html("手机号不能为空");
        }else if(!/^0?(13[0-9]|15[012356789]|18[0123456789]|14[57])[0-9]{8}$/.test(_this.val())){
            $("#labelMobile").show();
            $("#labelMobile").html("手机号码不正确");x
        }else{
            _buyMobile = true;
            $("#labelMobile").hide();
        }
    });

    //类别验证
    $("#buyNeed").blur(function(){
        var _this = $(this);
        _buyNeed = false;
        var _mj_input = $.trim( _this.val() );
        if(_mj_input == ""){
            $("#labelNeed").show();
            $("#labelNeed").text("请填写购买需求");
        }else if(_mj_input.length > 100){
            $("#labelNeed").show();
            $("#labelNeed").text("购买需求不能超过100字符");
        }else{
            _buyNeed = true;
            $("#labelNeed").hide();
        }
    });

});

function addBuy()
{
    var mobile  = $.trim( $("#buyMoblie").val() );
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
                $("#labelMobile").show();
                $("#labelMobile").html("提交成功");
                setTimeout(function(){
                    $("#tck").hide();
                }, 1000);
            }else{
                $("#labelMobile").show();
                $("#labelMobile").html("提交失败");
            }
        },
        error: function(data){
            $("#labelMobile").show();
            $("#labelMobile").html("提交失败");
        }
    });
}
