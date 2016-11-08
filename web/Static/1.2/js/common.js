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


$(document).ready(function(){

    $(".mj-search-box li").click(function(){

        $(".mj-models").show();

    });

    //关闭弹框

    $(".mj-close").click(function(){

        $(".mj-models").hide();

    });



    //左边菜单栏

    // $(".mj-dd dd").hover(function(){

    //     $(this).addClass("mj-ddahoverc").siblings().removeClass("mj-ddahoverc");

    // },function(){

    //     $(this).removeClass("mj-ddahoverc");

    // });



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

//简单选项卡
 function jc(name,curr,n)
    {
        for(i=1;i<=n;i++)
        {
            var menu=document.getElementById(name+i);
            var cont=document.getElementById("con_"+name+"_"+i);
            menu.className=i==curr ? "up" : "";
            if(i==curr){
                cont.style.display="block";    
            }else{
                cont.style.display="none";    
            }
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

