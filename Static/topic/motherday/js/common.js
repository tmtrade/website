/**
 * Created with JetBrains PhpStorm.
 * User: Administrator
 * Date: 16-5-4
 * Time: 上午10:46
 * To change this template use File | Settings | File Templates.
 */
//$(".moreFl").toggle(
//    function(){
//        $(".brand-fl").show();
//        $(".moreFl").css("background-image","url(images/sel-ico1.png)")
//
//    },
//    function(){
//        $(".brand-fl").hide();
//        $(".moreFl").css("background-image","url(images/sel-ico.png)")
//    });


//    设置slect样式

$("a").attr("target","_blank");
//    悬浮样式
var wd_w=$(window).width();
var flt_rg=(wd_w-1100)/2-140;
var wd_h=$(window).height();
var flt_tp=(wd_h-$("#flt-less").height())/2+200;
$("#flt-less").css({"right":flt_rg,"top":flt_tp});
$(window).resize(function(){
    var wd_w=$(window).width();
    var flt_rg=(wd_w-1100)/2-140;
    var wd_h=$(window).height();
    var flt_tp=(wd_h-$("#flt-less").height())/2-150;
    $("#flt-less").css({"right":flt_rg,"top":flt_tp});
})
//    跳转指定区域
var bltp1=$("#clothing").offset().top;
var bltp2=$("#makeUp").offset().top;
var bltp3=$("#infant").offset().top;
var bltp4=$("#child").offset().top;
var bltp5=$("#gem").offset().top;
var bltp6=$("#gift").offset().top;
var lvtop=$("#flt-less").css("top").split("px")[0];
var arrbltp=[bltp1,bltp2,bltp3,bltp4,bltp5,bltp6];
$(window).scroll(function(){
    if($(this).scrollTop()+lvtop>arrbltp[0]&&$(this).scrollTop()<arrbltp[1]-lvtop){
        $(".flNav li a").removeClass("on");
        $(".flNav li a").eq(0).addClass("on");
    }
    else if($(this).scrollTop()+lvtop>arrbltp[1]&&$(this).scrollTop()<arrbltp[2]-lvtop){
        $(".flNav li a").removeClass("on");
        $(".flNav li a").eq(1).addClass("on");
    }
    else if($(this).scrollTop()+lvtop>arrbltp[2]&&$(this).scrollTop()<arrbltp[3]-lvtop){
        $(".flNav li a").removeClass("on");
        $(".flNav li a").eq(2).addClass("on");
    }
    else if($(this).scrollTop()+lvtop>arrbltp[3]&&$(this).scrollTop()<arrbltp[4]-lvtop){
        $(".flNav li a").removeClass("on");
        $(".flNav li a").eq(3).addClass("on");
    }
    else if($(this).scrollTop()+lvtop>arrbltp[4]&&$(this).scrollTop()<arrbltp[5]-lvtop){
        $(".flNav li a").removeClass("on");
        $(".flNav li a").eq(4).addClass("on");
    }
    else  if($(this).scrollTop()+lvtop>arrbltp[5]&&$(this).scrollTop()<arrbltp[5]+$("#makeUp").height()-lvtop){
        $(".flNav li a").removeClass("on");
        $(".flNav li a").eq(5).addClass("on");
    }
    else{
        $(".flNav li a").removeClass("on");
    }
})
$(".flNav li").each(function(i){
    $(this).click(function(){
        $('html,body').animate({"scrollTop":arrbltp[i]})
    })
})


//手机验证

$("#buyMoblie").blur(function(){

    var _this = $(this);

    _buyMobile = false;

    if( _this.val()=="" ){
        $(".error").html("手机号不能为空");
    }else if(!/^0?(13[0-9]|15[012356789]|18[0123456789]|14[57])[0-9]{8}$/.test(_this.val())){
        $(".error").html("手机号码不正确");

    }else{

        _buyMobile = true;
        $(".error").hide();
    }

});

