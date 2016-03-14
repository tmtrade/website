
//分类弹框

//$(".mj-models").hide(); //初始ul隐藏



var CHOFN = (function($,chofn){

    var __loginBox = $('#loginFormDiv');

    chofn.loginShow = function(obj){

        __loginBox.show();

        boxBg();

        reCal(__loginBox);

    };

    var __loginBoxs = $('#buyFormDiv');

    chofn.BuyShow = function(obj){
        alert(1);

        __loginBoxs.show();

        boxBg();

        reCal(__loginBoxs);

    };

    var __verifyBoxs = $('#verifyMoblie');

    chofn.VerifyShow = function(obj){

        __verifyBoxs.show();

        boxBg();

        reCal(__verifyBoxs);

    };

    return chofn;

})(jQuery,CHOFN || {});





//ms-hoverList  //免费通知

$('.ms-imglist-ul li,.ms-call').hover(function(){

    $(this).addClass('current');

},function(){

    $(this).removeClass('current');

});

//当点击跳转链接后，回到页面顶部位置

$(".mj-topIcon").click(function(){

    $('body,html').animate({scrollTop:0},1000);

    return false;

});



var isOpen = 0;

//弹出层居中

function reCal(obj){

    var screenWidth,screenHeigth,scollTop,objLeft,objTop;

    function box(){

        screenWidth = $(window).width(); //当前窗口的宽度

        screenHeigth = $(window).height();  //当前窗口的高度

        scollTop = $(document).scrollTop(); //获取滚动条距顶部的偏移

        objLeft = (screenWidth - obj.outerWidth()) / 2; //弹出框距离左侧距离

        objTop = (screenHeigth - obj.outerHeight()) / 2; //弹出框距离顶部的距离

        //css定位弹出层

        obj.css({

            left: objLeft,

            top:  objTop

        });

    };

    box();

    isOpen = 1; //弹出框设定一个变量 置为1 说明弹出框是打开状态

    //当浏览器窗口大小改变时或者滚动条滚动时...

    $(window).on('resize scroll',function(){

        //判断此变量是否为1 如果是就执行

        if(isOpen == 1){

            box();

        }

    });

};

//生成弹层背景

function boxBg(){

    var boxbg = $(document.createElement('div')),

        docheight = $(document).height();

    boxbg.addClass('ms-boxBg');

    $('body').append(boxbg);

    $('.ms-boxBg').css({

        opacity: "0.6",

        height: docheight

    });

};

//关闭弹层

function boxHide(objId){

    $('.'+objId).hide();

    $('.ms-boxBg').remove();

    isOpen = 0;

    doBuyFunc = '';

};



$("input").each(function(){

    var spanT= $(this).parent().parent().find($(".mj-inpuVs")).text();

    $(this).focus(function(){

        $(this).parent().parent().find($(".mj-inpuVs")).text("");

    });

    $(this).blur(function(){

        if($(this).val()===""){

            $(this).parent().parent().find($(".mj-inpuVs")).text(spanT);

        }else{

            $(this).parent().parent().find($(".mj-inpuVs")).text("");

        }

    });

});

/*

 $(function(){

 $.ajax({

 type: "GET",

 url: "/index/links/",

 dataType: "json",

 success: function(data){

 var page = '';

 for(i=0; i<data.length;i++){

 page += '<li><a href="' + data[i]['content'] + '" target="_blank">' + data[i]['title'] + '</a></li>';

 }

 $("#links").html(page);

 }

 });

 });

 */

$("#mj-stclose").click(function(){

    $(".mj-stclose").hide(300);

    $(".mj-sidebarCon").hide(300);

    $(".mj-siright").show(300);

});

$(".mj-siright").click(function(){

    $(".mj-stclose").show(300);

    $(".mj-sidebarCon").show(300);

    $(".mj-siright").hide(300);

});



if($(window).width() > 1320){

    $(".mj-siright").hide();

    $(".mj-stclose").show();

    $(".mj-sidebarCon").show();

}

if($(window).width() < 1320){

    $(".mj-siright").show();

    $(".mj-stclose").hide();

    $(".mj-sidebarCon").hide();

}

var $wind = $(window);//将浏览器加入缓存中



var win = $wind.outerWidth()//首先获取浏览器的宽度



$wind.resize(function(){

//浏览器变化宽度的动作。

    var newW = $wind.outerWidth();

    if(newW<1320){

        $(".mj-siright").show();

        $(".mj-stclose").hide();

        $(".mj-sidebarCon").hide();

        $("#mj-stclose").click(function(){

            $(".mj-stclose").hide(300);

            $(".mj-sidebarCon").hide(300);

            $(".mj-siright").show(300);

        });

        $(".mj-siright").click(function(){

            $(".mj-stclose").show(300);

            $(".mj-sidebarCon").show(300);

            $(".mj-siright").hide(300);

        });

    }else{

        $(".mj-siright").hide();

        $(".mj-stclose").show();

        $(".mj-sidebarCon").show();

        $("#mj-stclose").click(function(){

            $(".mj-stclose").hide(300);

            $(".mj-sidebarCon").hide(300);

            $(".mj-siright").show(300);

        });

        $(".mj-siright").click(function(){

            $(".mj-stclose").show(300);

            $(".mj-sidebarCon").show(300);

            $(".mj-siright").hide(300);

        });

    }



});





$(document).ready(function(){

    var doc=document,inputs=doc.getElementsByTagName('input'),supportPlaceholder='placeholder'in doc.createElement('input'),placeholder=function(input){var text=input.getAttribute('placeholder'),defaultValue=input.defaultValue;

        if(defaultValue==''){

            input.value=text}

        input.onfocus=function(){

            if(input.value===text){this.value=''}};

        input.onblur=function(){if(input.value===''){this.value=text}}};

    if(!supportPlaceholder){

        for(var i=0,len=inputs.length;i<len;i++){var input=inputs[i],text=input.getAttribute('placeholder');

            if(input.type==='text'&&text){placeholder(input)}}}});



var _manger_url = "http://i.yizhchan.com/";

var _isLogin = false;

var _mobile  = "";



$("#_login_yes").hide();

$(document).ready(function(){

    //js加载user

    /*$.getJSON('/index/loginInfo/', function(data){

        if ( data == '' || data == undefined ) return false;

        if ( data.isLogin ) {

            $(".xs_login_").hide();

            $("#_login_no").hide();

            _isLogin = true;

            _mobile  = data.userMobile;

            $("#_nickname").text(data.nickname);

            $("#_login_yes").show();

        }else{

            $("#_login_yes").hide();

            _isLogin = false;

            _mobile  = '';

            $("#_login_no").show();

            $(".xs_login_").show();

        }

    });*/

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

        }

        if ( kw_value == '输入你想买的商标名称/商标号' ){

            input_kw.val('');

        }

        $("#searchForm").submit();

    });



    $(".mj-search-box li").click(function(){

        $(".mj-models").show();

    });

    //关闭弹框

    $(".mj-close").click(function(){

        $(".mj-models").hide();

    });



    //左边菜单栏

    $(".mj-dd dd").hover(function(){

        $(this).addClass("mj-ddahoverc").siblings().removeClass("mj-ddahoverc");

    },function(){

        $(this).removeClass("mj-ddahoverc");

    });



    //点击弹框添加样式

    $(".mj-models li").hover(function(){

        $(".mj-models").css({"border":"solid 1px #fc7d28"});

    },function(){

        $(".mj-models").css({"border":"solid 1px #79797a","box-shadow":"-1px 2px 2px #9a9a9b"});

    });

    $(".mj-models li").click(function(){

        $(this).addClass("cur").siblings().removeClass("cur");

        $(".select_box span").text($(this).text());

        var _cid = $(this).attr('cid');

        $("#publicClassSet").val(_cid);

        $(".mj-models").hide();

    });

});

//交易无缝滚动
jQuery(".mj-newjy").slide({mainCell:".bd ul",autoPlay:true,effect:"topMarquee",vis:2,interTime:50,trigger:"click"});
//banner焦点图
jQuery(".mj-bannerIimg").slide({titCell:".hd ul li",mainCell:".bd ul",autoPlay:true});
//活动推荐点击切换
jQuery(".yzc-Activities").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:5,easing:"easeOutCirc"});
//商标分类选项卡
jQuery(".yzc-Brand").slide({effect:"fold"});
//类别展示选项卡和焦点图
jQuery(".slideBox").slide({titCell:".shd a",mainCell:".sbd ul",prevCell:".sprev",nextCell:".snext",effect:"leftLoop",autoPlay:true});
jQuery(".yzc-category").slide({titCell:".parHd li",mainCell:".parBd",effect:"fold",pnLoop:false});
//输入框下拉

$("#searchForm").find("input[name='kw']").focus(function(){
    $(".mj-pull-down").each(function(){
        $(this).slideDown();
    })
    $(this).blur(function(){
        $(".mj-pull-down").each(function(){
            $(this).slideUp();
        })
    })

})
$("#searchForm").find("input[name='kw']").keyup(function(){
    if(($(this).val()).length>0){
//        $(".mj-pull-down li").text(($(this).val()));
        $(".mj-pull-down").show();
    }else{
        $(".mj-pull-down").hide();
    }
})
//漂浮楼层
var oflr=$("#float-floor");
var sbzs=$(".sbzs>div");
var oflis= $("#float-floor li");
var owf=document.body.clientWidth;
var owh=$(window).height();
var oft=(owf-1180)/2-65;
var ofh=owh/2-190;
oflr.css({"left":oft,"top":ofh})
$(window).scroll(function(){


    var w_top = $( window ).scrollTop();
    if( w_top+$( window).height()>$("#cloth").offset().top&&w_top<$("#succal").offset().top){
        oflr.show();
        if(owf<1180){
            oflr.hide();
        }

    }
    else{
        oflr.hide();
    }
    for(var i=0;i<$("#float-floor li").length;i++){

        if( w_top>$(".sbzs>div").eq(i).offset().top &&  w_top< $(".sbzs>div").eq(i+1).offset().top){
            $(oflis[i+1]).find("a.etitle").show();
        }else{
            $(oflis[i+1]).find("a.etitle").hide();
        }
    }

});
//    $("#float-floor").find("li").mouseover(function(){
//       $(this).find("a.etitle").show();
//        $(this).mouseleave(function(){
//            $(this).find("a.etitle").hide();
//        })
//   })

$("#float-floor li").each(function(i){
    $(this).click(function(){
        var t=$(".sbzs>div").eq(i).offset().top;
        $("html, body").animate({"scrollTop":t});
    });
});

//简单选项卡
function jc(name,curr,n)
{
    for(var i=1;i<=n;i++)
    {
        var menu=document.getElementById(name+i);
        menu.className=i==curr ? "up" : "";
    }
}
$(".contUstit").each(function(i){
    $(this).click(function(){
        $(".contUstit").find('img').attr('src',"/Static/images/cont-rg.png");
        var imgsrc = $(this).find('img');
        if(imgsrc.attr('src')=="/Static/images/cont-rg.png"){
                imgsrc.attr('src',"/Static/images/cont-dw.png");
        }
    });
});

