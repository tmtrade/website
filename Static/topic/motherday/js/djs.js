/**
 * Created with JetBrains PhpStorm.
 * User: Administrator
 * Date: 16-5-4
 * Time: 下午4:05
 * To change this template use File | Settings | File Templates.
 */
window.onload=function ()
{
    var oDiv=document.getElementById('div1');
    var aTime=[];

    var last=[];
    last[0]="00";
    last[1]="00";
    last[2]="00";
    last[3]="00";

    for(var i=0;i<4;i++)
    {

        var oBox=document.createElement('div');
        oBox.className='box';
        aTime.push(oBox);
        oBox.innerHTML=
            '<span>00</span>'+
                '<div class="top"><span>00</span></div>'+
                '<div class="tran move">'+
                '<div class="front"><span>00</span></div>'+
                '<div class="back"><span>00</span></div>'+
                '</div>';

        oDiv.appendChild(oBox);
    }

    function inner()
    {
        function toDou(n){return n<10?'0'+n:''+n;}
        var oDate=new Date();
        var ts = (new Date(2016,4,8)) - (new Date());//计算剩余的毫秒数
        var dd = parseInt(ts / 1000 / 60 / 60 / 24, 10);//计算剩余的天数
        var hh = parseInt(ts / 1000 / 60 / 60 % 24, 10);//计算剩余的小时数
        var mm = parseInt(ts / 1000 / 60 % 60, 10);//计算剩余的分钟数
        var ss = parseInt(ts / 1000 % 60, 10);//计算剩余的秒数
        var now=[];
        now[0]=toDou(dd);
        now[1]=toDou(hh);
        now[2]=toDou(mm);
        now[3]=toDou(ss);
        for(var i=0;i<now.length; i++)
        {
            if(now[i]!=last[i])
            {
                aTime[i].className='box';
                aTime[i].innerHTML=
                    '<span>'+last[i]+'</span>'+
                        '<div class="top"><span>'+now[i]+'</span></div>'+
                        '<div class="tran move">'+
                        '<div class="front"><span>'+last[i]+'</span></div>'+
                        '<div class="back"><span>'+now[i]+'</span></div>'+
                        '</div>';
                (function (box){
                    setTimeout(function (){
                        box.className='box active';
                    }, 0);
                })(aTime[i]);
            }
        }

        last=now;
    }

    setInterval(inner, 1000);
};

