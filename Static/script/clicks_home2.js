/*统计*/
$(document).ready(function() {
	 var
                _icon = $(".sp-2 .icon");
        _icon.hover(
                function(){
                    var
                            _this = $(this),
                            _class = _this.attr("data-class");
                    if(_class) _this.addClass(_class);
                },
                function(){
                    var
                            _this = $(this),
                            _class = _this.attr("data-class");
                    if(_class) _this.removeClass(_class);
                }
        );
        var jScrollBox = $(".jScrollBox");
        var jScrollBox1 = $(".jScrollBox1");
        var scrollTimer;
	var jScrollBox = $(".jScrollBox");
	var jScrollBox1 = $(".jScrollBox1");
	var scrollTimer;
	list(jScrollBox,20,1);
	list(jScrollBox1,8,2);
	        jScrollBox.hover(
                function(){
                    clearInterval(scrollTimer);
                },
                function(){
                    scrollTimer = setInterval(function(){
                        scrollNews( jScrollBox );
                    }, 2000 );
                }).trigger("mouseout");
        jScrollBox1.hover(
                function(){
                    clearInterval(scrollTimer);
                },
                function(){
                    scrollTimer = setInterval(function(){
                        scrollNews( jScrollBox1 );
                    }, 2000 );
                }).trigger("mouseout");
});
function list(obj,num,type){
	typestr = type == 1 ? '想买' : '出售';
	html    = ' <p><span class="listtel">138****{****}</span>&nbsp;&nbsp;&nbsp;<span class="listmai" style="color:#FFF;">'+typestr+'</span><span class="listname">{name}<span></p>';
	string1 = string2 = '';
	for($i=0;$i<num;$i++){
		zlname  = setname();
		telnum  = randoms(1000,9999);
		string1 = html.replace('{****}',telnum);
		string2+= string1.replace('{name}',zlname+'<span style="color:#FFF;">专利</span>');
	}
	$(obj).find('div').html(string2);
}
function randoms(mins,maxs){
    return Math.floor(mins+Math.random()*(maxs-mins));
}

function scrollNews(obj) {
	var $self = obj.find("div");
	var lineHeight = $self.find("p:first").height();
	$self.animate({ "margin-top": -lineHeight + "px" }, 600, function () {
		$self.css({"margin-top": "0px"}).find("p:first").appendTo($self);
	});
}
var isti=false
var
		IE = $.browser.msie;
function formFun(obj, type){
	if(isti==true){
		return false;			
	}
	var _hasSubmit = true;
	var checkedNum = 0;	
	switch (type){
		case 1 :
			var
					_miaoshu = $.trim(obj.subject.value),
					_iphone = $.trim(obj.tel.value);
			$('input[name="pttype[]"]').each(function() {
				if($(this).attr('checked')){
					checkedNum = checkedNum+1;	
				}
			});
			if(checkedNum==0){
				alert('请选择专利类型');
				return false;
			}
			if( _miaoshu == '' || _miaoshu == '专利号 / 专利描述' ){
				obj.subject.value = (IE) ? '专利号 / 专利描述' : '';
				alert('请输入专利号 / 专利描述');
				return false;
			}
			if( _iphone == '' || _iphone == '输入联系方式' ){
				obj.tel.value = (IE) ? '输入联系方式' : '';
				alert('输入联系方式');
				return false;
			}
			istel = isphone(_iphone);
			if(!istel){
				alert('电话错误');
				return false;
			}
			break;
	}
	return true;
}
		
		
function setname()
{
	var name = '';
name +='|蒸汽烘干系统'
			+'|一种电源插座'
			+'|手摇式坐浴装置'
			+'|粉粒状肥料施肥器'
			+'|一种尿桶'
			+'|治疗肝郁的中药组合物'
			+'|一种治疗头晕症的中药'
			+'|一种辅助刹车装置'
			+'|防疲劳眼镜'
			+'|电动机定子线圈拉机'
			+'|一种户外跑步机'
			+'|提脸美容礼帽'
			+'|一种可控制流量的毛笔'
			+'|车载式垃圾焚烧炉'
			+'|一种大花红景天防晒霜'
			+'|变速手摇钻'
			+'|鼹鼠捕捉器'
			+'|一种新型磷酸钙生物活性陶瓷'
			+'|一种三角直柄麻花钻'
			+'|治疗胃病的中药'
			+'|一种杂质污水泵'
			+'|电动涡轮增压器'
			+'|防冻节水器'
			+'|整体壁挂式太阳能热水器'
			+'|制冷制热水新型空调'
			+'|插线板'
			+'|不断管开背三通'
			+'|防臭防鼠盖板'
			+'|治疗风湿病的药酒'
			+'|天旋磨'
			+'|天旋梯'
			+'|一种壁挂式空调送风分配装置';
			array = name.split('|');
			count = array.length;
			num  = randoms(0,count);
			str = array[num] ? array[num] : '汽车储水箱';
			return str;
			
}
function isphone(phone){
	var mobile,plane,four,eight;
	mphone= phone.match(/^1[3|4|5|7|8]{1}([0-9]{9})$/i);
	zphone= phone.match(/^0[0-9]{2,4}-([0-9]{7,15})$/i);
	ephone= phone.match(/^800[0-9]{8,10}$/i);
	fphone= phone.match(/^400[0-9]{7}$/i);
	if(fphone){
		return true;
	}else if(ephone){
		return true;
	}else if(mphone){
		return phoneRepeat(mphone[1]);
	}else if(zphone){
		return phoneRepeat(zphone[1]);
	}
	return false;
}
function phoneRepeat(str){
	var preg=/(\d)*(\d)\2{8}(\d)*/g;
	if(preg.test(str)){
		return false;
	}else{
		return true;
	}
}