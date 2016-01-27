$(function () {
    $(".classify ").on("click", "span", function () {
        //$(".classify ul").toggle();
		classList = $.trim($(".classify ul").html());
		if(!classList){
			setWarn('请先输入商标号');
		}else{
			$(".classify ul").show();
		}
    }).on("click", "li", function () {
        var $this 	= $(this);
		cid 		= $this.data('cid');
		if(cid > 0){
			$('.sbclassid').val(cid);
		}
        $(".classify span").html($this.html());
        $this.parent().hide();
    });
    $("#form").on("click",".submit",function(){
        //$("#wrap").addClass("current").siblings().removeClass("current");
    })
});
$(document).ready(function(e) {
	setDefault();
	//离开输入框检查
	$(document).on('blur','.trademarkName',function(){
		getTrInfo();
	});
	//END
	//点击提交
	$(document).on('click','.submit',function(){
		tradSubmit();
	});
	//END
	//分享
	$(document).on('mouseover','.jiathis',function(){
		fenxiang();
	});
	//END
	$(document).on('click','.back',function(){
		window.location = '/anquan/';
	});
});
 
//开始
var tradId	= 0;
var sbNum	= 13;
var atNum	= 7.6;
var nber	= 1;
var _tipTxt = '';
//提交开始检查
function tradSubmit(){
	isTid = getTrInfo();
	if( !isTid ){
		return false;
	}
	optNum	=  $('.classlist li').length;
	if( optNum == 1 ){
		optVal	= $('.classlist li:eq(0)').data('cid');
		$('.sbclassid').val(optVal);
	}else if( optNum >= 2 ){
		optVal = $('.sbclassid').val();
		if( optVal == 0){
			setWarn('该商标号有多条分类，请选择具体类别');
			return false;
		}
	}else{
		return false;
	}
	if( optVal > 0 ){
		$('.wrapindex').hide();
		$('.wrapongoing').show();
		animateAjax(tradname,optVal,sbNum,0,nber);
	}
	return false;	
}
var _MYOBJ = '';
var zcount = 100;
function animateAjax(tradname,optVal,num,isdata,nber){
		sbNum = parseInt(num);
		//atNum = atnum + 7.6;
		//setTradContent(1);
		$.ajax({
			 type		: "get",
			 url		: "/anquan/checkscoreajax/?tradname="+tradname+"&class="+optVal+"&steps="+sbNum+"&isdata="+isdata,
			 dataType	: "json",
			 beforeSend : function(){
				 
			 },
			 success	: function(data){
				if( !_MYOBJ ){
					_MYOBJ = eval(data);
					checkstr = _MYOBJ.check;
					$('.sbcontent').html('('+_MYOBJ.info.trademark+'&nbsp;第'+_MYOBJ.info.class_id+'类)');
				}else{
					chObj  = eval(data);
					checkstr = chObj.check;
				}
				if( sbNum > 0 ){
					setTimeout(function(){
						koufen  	= _MYOBJ.result.points;
						sbNum 		= sbNum-1;
						check 		= nber < 10 ? '0'+nber : nber;
						if( nber > 1 ){
							checkon	= nber-1;
							checkon = checkon < 10 ? '0'+checkon : checkon;
							$('.check-'+checkon).parent().removeClass('active');
						}
						$('.check-'+check).parent().addClass('active');
						scoreVal = pointsCheck(parseInt(nber),koufen);
						//console.log(score+"===="+isin+"===="+parseInt(nber),koufen);
						if(scoreVal > 0){
							$('.check-'+check).parent().addClass('red');
							//已发现问题
							problemVal 	= $('.problem').text();
							problemVal	= parseInt(problemVal)+1;
							$('.problem').text(problemVal);
							//扣分
							scoreOld 	= $('.score').text();
							//console.log(scoreOld+"==="+scoreVal);
							scoreOld	= scoreOld - scoreVal;
							$('.score').text(scoreOld);
							setJc(scoreOld);							
						}
						nber 		= nber+1;
						$('.zhiliang').html(checkstr);						
						animateAjax(tradname,optVal,sbNum,1,nber);
					}, 800);
				}else{
					$('.check-13').parent().removeClass('active');
					setPage(_MYOBJ);
					//setContent(_MYOBJ);
				}
				//$('.sbcontent').html('('++')');
			 }
		 });
}
//获取商标检查结果数据包
var tradId = 0;
function getTrInfo(){
	isTmId = tradAlert();
	if(!isTmId){
		return false;
	}
	tradname = $.trim($('.trademarkName').val());
	if( tradId == tradname ){
		return true;
	}
	var tId = 0;
	$.ajax({
		 type		: "post",
		 async		: false,
		 data		: {"tradname" : tradname},
		 url		: "/anquan/checkareajax/",
		 dataType	: "json",
		 success	: function(data){
			if(data){
				var myobj=eval(data);
				if(myobj.total==0){
					msgString = '未找到该商标，请检查商标号是否正确。';
					setWarn(msgString);
				}else{
					setWarn();
					num = myobj.total > 1 ? 1 : 0;
					setSelect(num,myobj.rows);
				}
				tId = myobj.total;
			}
			tradId = tradname;
		 }
	 });
	if( tId > 0 ){
		return true;
	}else{
		return false;
	}
}
//验证信息
function tradAlert(){
	tradname	= $.trim($('.trademarkName').val());
	if( !tradname ){
		setWarn('请输入商标号');
		return false;
	}
	istrname	= checkTradName(tradname);
	if( !istrname ){
		setWarn('请输入正确的商标号');
		return false;
	}
	return true;
}
//验证商标号是否正确
function checkTradName(snum){
	//tradname = $.trim($('.trademarkName').val());
	isTrue	 = /^([a-z0-9]){1,10}$/i.test(snum);
	return isTrue;
}
//获取时间
function getDates(){
	var myDate = new Date();
	Y = myDate.getFullYear();    //获取完整的年份(4位,1970-????)
	M = myDate.getMonth();       //获取当前月份(0-11,0代表1月)
	D = myDate.getDate();        //获取当前日(1-31)
	ymd = Y+'年'+M+'月'+D+'日';
	return  ymd;
}
//获取url的参数
function getQueryString(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return decodeURI(r[2]); return null;
}
//从详细页面点击过来
function setDefault(){
	_nid   = getQueryString('nid');
	_class = getQueryString('class');
	if( _nid ){
		$('.trademarkName').val(_nid);
		defObj = $('.defineselect');
		optString		= '';
		for(var i=0;i<1;i++){
			optString = $("<option>").val(_class).text("第"+_class+"类");
			$(defObj).append(optString);
		}
		tradSubmit();
	}
}
//设置select的值
function setSelect(isHide,optionObj){
	_class = getQueryString('class');
	defObj = $('.classlist');
	if( isHide == 1 ){
		$(defObj).css({'visibility' :'visible'});//显示
	}else{
		$(defObj).css({'visibility' :'hidden'});//隐藏
	}
	var optionObjs	= eval(optionObj);
	optString		= '';
	var isCz		= false;
	for(var i=0;i<optionObjs.length;i++){
		classId		= optionObjs[i].class_id;
		optString 	+= "<li data-cid="+classId+">第"+classId+"类</li>";
		if( classId == _class && _class > 0){//检查url传来值是否合法
			isCz = true;	
		}
	}
	$('.classlist').html(optString);
	if(optionObjs.length==1){
		setClasslist();
	}
	if( isCz == true ){//
		$(".defineselect option[value='"+_class+"']").attr("selected", true); 
	}
}
function setClasslist(){
	optTest = $('.classlist li:eq(0)').html();
	$('.msgclass').html(optTest);
	optVal	= $('.classlist li:eq(0)').data('cid');
	$('.sbclassid').val(optVal);
}
//检查数字是为数组key
function pointsCheck(num,array){
	score = 0;
	if( array[num] ){
		score = array[num];
	}
	return score;
}	

function setWarn(string){
	if(string){
		$('.error').html(string);
		$('.error').show();
	}else{
		$('.error').html('');
		$('.error').hide();
	}
}
//设置检查完后的页面
function setPage(obj){
	number  = obj.result.total;
	myArray = getBjinfo(number);
	$('.result').addClass(myArray[1]);
	$('.scorezong').html('<strong>'+number+'</strong>'+myArray[0]);
	$('.wrapongoing').hide();//隐藏检查页面
	//显示错误列表
	if(myArray[2]=='style2'){
		$('.result').removeClass('safe');//删除默认色
		$('.le').html(obj.result.msg);//总评
		$('.risk-ul li').each(function(index, element) {
            riskId 	= $(this).attr('id');
			riskArr = riskId.split('che_');
			checkId = pointsCheck(riskArr[1],obj.result.points);//具体显示错误类型
			console.log(riskId+"----"+checkId+"----"+riskArr[1]);
			if( checkId <= 0 ){
				$(this).remove();
			}
        });
		$('.style2').show();
	}else{
		$('.style1').show();
	}
	//$('.cicle').addClass(myArray[4]);//字体颜色
	$('.result').addClass(myArray[1]);//背景颜色
	$('.gunimg').attr('src','/Static/style/newcheck/images/'+myArray[3]);
	$('.result').show();
}
function fenxiang(){
	var jiathis_config={
		siteNum:4,
		sm:"qzone,tsina,weixin,tqq",
		summary:"",
		boldNum:3,
		shortUrl:false,
		hideMore:true,
		pic:"http://www.yizhchan.com/Static/style/img/mj-qrcode.gif"
	}
}
//设置检查状态的css
function setJc(number){
	myArray = getBjinfo(number)
	$('.wrapongoing').removeClass('safe');//删除默认色
	$('.wrapongoing').addClass(myArray[1]);//添加背景颜色
	$('.gunimg').attr('src','/Static/style/newcheck/images/'+myArray[3]);
	$('#cicle').addClass(myArray[4]);//添加得分色
}
function getBjinfo(number){
	var myArray = new Array()
	if( number <= 50 ){
		myArray[0] 	= '高级风险';
		myArray[1]	= 'risk';//总体样式
		myArray[2]	= 'style2';
		myArray[3]	= 'risk-c.png';//滚动图片
		myArray[4]	= 'red';
	}else if( number > 50 && number <= 80 ){
		myArray[0] 	= '中级风险';
		myArray[1]	= 'pr';
		myArray[2]	= 'style2';
		myArray[3]	= 'safe-c.png';
		myArray[4]	= 'cicles';
	}else if( number > 80 && number < 100 ){
		myArray[0] 	= '低级风险';
		myArray[1]	= 'blue';
		myArray[2]	= 'style2';
		myArray[3]	= 'safe-c.png';
		myArray[4]	= 'cicles';
	}else{
		myArray[0] 	= '非常安全';
		myArray[1]	= 'safe';
		myArray[2]	= 'style1';
		myArray[3]	= 'safe-c.png';
		myArray[4]	= 'cicles';
	}
	return myArray;
}
//从详细页面点击过来
function setDefault(){
	_nid   = getQueryString('nid');
	_class = getQueryString('class');
	if( _nid ){
		$('.trademarkName').val(_nid);
		$('.sbclassid').val(_class);
		tradSubmit();
	}
}