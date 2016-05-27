$(function(){
	var jBgnav = $('#con_nav_1');
	var tiemOut = null;
	jBgnav.on('click', '.add', function(){
		var _parent = $(this).parent().parent().parent();
		var _copy = _parent.clone();
		_copy.find(".remove").removeAttr('style');
		_copy.find('.add').css('display','none');
		_copy.find('.error').css("display","none")
                _copy.find('.sbtable').css("display","none")
		_parent.after(_copy);
		_copy.find('input').val('');
		_copy.find('.g-td2').html('');
		priceCheck();
	}).on('click', '.remove', function(){
		$(this).parent().parent().parent().remove();
	});
	jBgnav.on('blur', '.input-number', function(){
		var _val = '';
		var _this = $(this);
		var tip = _this.parent().next();
         
		if($.trim(_this.val()) == ''){
			tip.html('<img src="/Static/1.2/images/pt-sell-err.png">请输入商标号');
                        tip.show();
        	$(this).addClass('errorIp');
			return false;
		}
		var key = 0;
		var table =  _this.parent().next().next();
		/**检查是否重复**/
		$('.input-number').each(function(index){
			if($.trim($(this).val()) != '' && $.trim($(this).val()) == $.trim(_this.val())){
				key ++ ;
			}
		})
		if(key > 1){
			tip.html('<img src="/Static/1.2/images/pt-sell-err.png">您已提交了该商标，请验证后重新输入。');
			tip.show();
			table.hide();
			_this.val('');
			$(this).addClass('errorIp');
			return false;
		}
		/**检查是否重复**/
		clearTimeout(tiemOut);
		tiemOut = setTimeout(function(){
			_val = $.trim(_this.val());
			$.ajax({
				url : '/sell/getselldata/',
				data : {number:_val},
				method: 'post',
				dataType:"json",
			}).done(function(data){
				var obj = data;
				if(obj['status'] == '2'){
					tip.html('<img src="/Static/1.2/images/pt-sell-err.png">商标信息不存在,请重新填写');
					tip.show();
					table.hide();
					_this.val('');
					$(this).addClass('errorIp');
					return false;
				}
				if(obj['status'] == '0'){
					tip.html("<i class=\"us-icon uj_icon44\"></i>"+obj['statusValue']+'的商标不可出售');
					tip.show();
					$(this).addClass('errorIp');
					table.hide();
					_this.val('');
					return false;
				}
				if(obj['status'] == '-1'){
					tip.html('<img src="/Static/1.2/images/pt-sell-err.png">您已经提交了此商标，不能重复提交');
					tip.show();
					$(this).addClass('errorIp');
					table.hide();
					_this.val('');
					return false;
				}
				
				table.removeAttr('style');
				tip.hide();
				$(this).removeClass('errorIp');
				$.each(obj,function(item,value){
					if(item == 'imgurl'){
						if(value==""||value==undefined)
						{
							value='/Static/images/img1.png';
						}
						table.find('.imgurl').html('<img  style="width:120px;height:100px;border:0;" onerror="this.src=\'/Static/images/img1.png\'" src="'+value+'"/>');
					}else{
						table.find('.'+item).html(value);
					}
				})
			}).error(function(){
			});
		}, 100);
	});

        var jBgnav_p = $('#con_nav_2');
	var tiemOut_p = null;
	jBgnav_p.on('click', '.add', function(){
		var _parent = $(this).parent().parent().parent();
		var _copy = _parent.clone();
		_copy.find(".remove").removeAttr('style');
		_copy.find('.add').css('display','none');
		_copy.find('.error').css("display","none")
                _copy.find('.sbtable').css("display","none")
		_parent.after(_copy);
		_copy.find('input').val('');
		_copy.find('.g-td2').html('');
		priceCheck_p();
	}).on('click', '.remove', function(){
		$(this).parent().parent().parent().remove();
	});
	jBgnav_p.on('blur', '.patent-number', function(){
		var _val = '';
		var _this = $(this);
		var tip = _this.parent().next();
               
		if($.trim(_this.val()) == ''){
			tip.html('<img src="/Static/1.2/images/pt-sell-err.png">请输入专利号');
                        tip.show();
                      	$(this).addClass('errorIp');
			return false;
		}
		var key = 0;
		var table =  _this.parent().next().next();
		/**检查是否重复**/
		$('.patent-number').each(function(index){
			if($.trim($(this).val()) != '' && $.trim($(this).val()) == $.trim(_this.val())){
				key ++ ;
			}
		})
		if(key > 1){
			tip.html('<img src="/Static/1.2/images/pt-sell-err.png">您已提交了该商标，请验证后重新输入。');
			tip.show();
			$(this).addClass('errorIp');
			table.hide();
			_this.val('');
			return false;
		}
		/**检查是否重复**/
		clearTimeout(tiemOut_p);
		tiemOut_p = setTimeout(function(){
			_val = $.trim(_this.val());
			$.ajax({
				url : '/patent/getselldata/',
				data : {number:_val},
				method: 'post',
				dataType:"json",
			}).done(function(data){
				var obj = data;
				if(obj['status'] == '2'){
					tip.html('<img src="/Static/1.2/images/pt-sell-err.png">专利信息不存在,请重新填写');
					tip.show();
					$(this).addClass('errorIp');
					table.hide();
					_this.val('');
					return false;
				}
				if(obj['status'] == '0'){
					tip.html('<img src="/Static/1.2/images/pt-sell-err.png">该专利已在出售中');
					tip.show();
					$(this).addClass('errorIp');
					table.hide();
					_this.val('');
					return false;
				}
				if(obj['status'] == '-1'){
					tip.html('<img src="/Static/1.2/images/pt-sell-err.png">您已经提交了此专利，不能重复提交');
					tip.show();
				    $(this).addClass('errorIp');
					table.hide();
					_this.val('');
					return false;
				}
				
				table.removeAttr('style');
				tip.hide();
				$(this).removeClass('errorIp');
				$.each(obj,function(item,value){
					if(item == 'imgurl'){
						if(value==""||value==undefined)
						{
							value='/Static/images/img1.png';
						}
						table.find('.imgurl').html('<img  style="width:120px;height:100px;border:0;" onerror="this.src=\'/Static/images/img1.png\'"  src="'+value+'" />');

					}else{
						table.find('.'+item).html(value);
					}
				})
			}).error(function(){
			});
		}, 100);
	});
        
	//手机验证码输入确认
	$(".mj-determineBtn").click(function (){
		var code    = $.trim($("#buyMsgCode").val());
		var mobile  = $.trim($("#usrMp_popup").val());
		if (mobile == '' || mobile.length != 11){
			$(".mj-bcTips").text('手机号错误');
			$('.mj-bcTips').show();
			return false;
		}
		if ( code == '' || code.length != 4){
			$(".mj-bcTips").text('验证码不正确');
			$('.mj-bcTips').show();
			return false;
		}
		$.ajax({
			type: "post",
			url: "/passport/checkMsgCode/",
			data: {m:mobile,c:code},
			dataType: "json",
			success: function(data){
				if (data.code == 1 || data.code == 11){
					$(".mj-close").click();
					addSell();
				}else if (data.code == 5){
					$(".mj-bcTips").text('手机号已更改');
					$('.mj-bcTips').show();
				}else if (data.code == 4){
					$(".mj-bcTips").text('验证码不正确');
					$('.mj-bcTips').show();
				}else{
					$(".mj-bcTips").text('发送失败');
					$('.mj-bcTips').show();
				}
			}
		});
	});

	//发送手机验证码
	$(".mj-clickable").click(function (){
		var _sendOnce = true;
		if ( !_sendOnce ) return false;
		$('.mj-bcTips').show();
		var mobile = $("#usrMp_popup").val();
		$.ajax({
			type: "post",
			url: "/passport/sendMsgCode/",
			data: {m:mobile,r:'n'},
			dataType: "json",
			success: function(data){
				if (data.code == 1){
					$(".mj-bcTips").text('发送成功');
					$('.mj-bcTips').show();
					_sendOnce = false;
					_timer(60 ,$(".mj-clickable"));
				}else if (data.code == 2){
					$(".mj-bcTips").text('手机号不正确');
					$('.mj-bcTips').show();
				}else{
					$(".mj-bcTips").text('发送失败');
					$('.mj-bcTips').show();
				}
			}
		});
	});
	
	priceCheck();
	//价格循环检查
	function priceCheck(){
		$('.input-price').bind("blur",function(){
			var thisval = $(this).val();
			var preg = /^[1-9][\d]{0,7}$/;
			var tip = $(this).parent().parent().next();
			if(!preg.test(thisval)){
				$(this).val('');
				tip.html('<img src="/Static/1.2/images/pt-sell-err.png">商标出售底价不正确');
				tip.show();
				$(this).parent().addClass('errorIp');
			}else{
				tip.hide();
				$(this).parent().removeClass('errorIp');
			}
		})
	}

	$('.input-phone').bind("blur",function(){
		var thisval = $(this).val();
		var preg = /^1[3|4|5|7|8][0-9]\d{8}$/;
		var tip = $(this).parent().next();
		if(!preg.test(thisval)){
			$(this).val('');
			tip.html("<img src='/Static/1.2/images/pt-sell-err.png'>请您输入正确的联系电话");
			tip.show();
			$(this).addClass('errorIp');
		}else{
			tip.hide();
			$(this).removeClass('errorIp');
		}
	})
        
        priceCheck_p();
	//价格循环检查
	function priceCheck_p(){
		$('.patent-price').bind("blur",function(){
			var thisval = $(this).val();
			var preg = /^[1-9][\d]{0,7}$/;
			var tip = $(this).parent().parent().next();
			if(!preg.test(thisval)){
				$(this).val('');
				tip.html('<img src="/Static/1.2/images/pt-sell-err.png">专利出售底价不正确');
				tip.show();		
				$(this).parent().addClass('errorIp');

			}else{
				tip.hide();
				$(this).parent().removeClass('errorIp');
			}
		})
	}

	$('.patent-phone').bind("blur",function(){
		var thisval = $(this).val();
		var preg = /^1[3|4|5|7|8][0-9]\d{8}$/;
		var tip = $(this).parent().next();
		if(!preg.test(thisval)){
			$(this).val('');
			tip.html("<img src='/Static/1.2/images/pt-sell-err.png'>请您输入正确的联系电话");
			tip.show();
			$(this).addClass('errorIp');

		}else{
			tip.hide();
			$(this).removeClass('errorIp');
		}
	})
        
	//验证姓名，只能输入数字和英文
	$('.name').bind("blur",function(){
		contact($(this));
	});
});

//验证姓名
function contact(obj){
	var pregName = /^[\u0391-\uFFE5A-Za-z]+$/;
	var tip = obj.parent().next();
	var result = true;
	if(obj.val()){
		if(!pregName.test(obj.val())){
			tip.html("<img src='/Static/1.2/images/pt-sell-err.png'>您的姓氏只能输入中文或者英文");
			tip.show();
		   $('#contact').addClass('errorIp');
			result = false;
			return false;
		}else if(obj.val().length > 8){
			tip.html("<img src='/Static/1.2/images/pt-sell-err.png'>您的姓氏不能大于8个字符");
			tip.show();
			$('#contact').addClass('errorIp');
			result = false;
			return false;
		}else{
			tip.hide();
			$('#contact').addClass('errorIp');
		}
	}else{
		tip.hide();
		$('#contact').removeClass('errorIp');
	}
	return result;
}

//商标检查提交数据
function submitSell(){
	var flag = checks($('.input-number'),1);
	if(flag){
		flag = checks($('.input-price'),1);
	}
        if(flag){
		flag = checksPhone($('.input-phone'));
	}
	if(flag){
		flag = contact($('.name'));
	}
	if(flag === true){
		//if ( !_isLogin ){
		//	getLayer($('#mj-submittel'));
		//}else{
			addSell();
		//}
	}else{
		return flag;
	}
}

function addSell(){
	var sid  = $('#sid').val();
	var area = $('#area').val();
	var content = $('#addsell').serialize()+"&sid="+sid+"&area="+area;
	sendBehavior(1,13,0,0,content);//发送统计数据
	var index = layer.load(1, {
	    shade: [0.1,'#fff'] //0.1透明度的白色背景
	});
	$.ajax({
		type: "post",
		url: "/sell/addsell",
		data: content,
		dataType: "json",
		success: function(data){
			layer.close(index);
			if (data.state == 1){
				sellok(data);
			}else if (data.state == -2){
				var msg = data.msg == undefined ? '提交的数据不正确' : data.msg;
				sellNo(msg);
			}else{
				str = "操作失败，请稍后重试";
				sellNo(str);
			}
		}
	});
}

//专利检查提交数据
function submitPatentSell(){
	var flag = checks($('.patent-number'),2);
	if(flag){
		flag = checks($('.patent-price'),2);
	}
	if(flag){
		flag = checksPhone($('.patent-phone'));
	}
	if(flag){
		flag = contact($('.name'));
	}
	if(flag === true){
			patentSell();
	}else{
		return flag;
	}
}

function patentSell(){
	var content = $('#patentsell').serialize();
	sendBehavior(2,13,0,0,content);//发送统计数据
	var index = layer.load(1, {
	    shade: [0.1,'#fff'] //0.1透明度的白色背景
	});
	$.ajax({
		type: "post",
		url: "/patent/addsell",
		data: content,
		dataType: "json",
		success: function(data){
			layer.close(index);
			if (data.state == 1){
				sellok(data);
			}else if (data.state == -2){
				var msg = data.msg == undefined ? '提交的数据不正确' : data.msg;
				sellNo(msg);
			}else{
				str = "操作失败，请稍后重试";
				sellNo(str);
			}
		}
	});
}
function checks(obj,type){
	var result = true;
	obj.each(function(){
		if($(this).val() == '' || $(this).val() == obj.attr('placeholder')){
			$(this).focus();
                        var tip = $(this).parent().next();
                        if($(this).attr("name")=="price[]"){
                             var tip = $(this).parent().parent().next();
                             tip.html('<img src="/Static/1.2/images/pt-sell-err.png">出售底价不正确');
                             	$(this).parent().removeClass('errorIp');
                        }else{
                             var tip = $(this).parent().next();
                             if(type==1){
                                    tip.html('<img src="/Static/1.2/images/pt-sell-err.png">请输入商标号');
                                    $(this).addClass('errorIp');
                                }else{
                                    tip.html('<img src="/Static/1.2/images/pt-sell-err.png">请输入专利号');
                                    $(this).addClass('errorIp');
                            }
                        }
                        
                        tip.show();
			result = false;
		}
	})
	return result;
}

//循环验证电话号码
function checksPhone(obj){
	var result = true;
	obj.each(function(){
                $(this).focus();
                var tip = $(this).parent().next();
                var preg = /^1[3|4|5|7|8][0-9]\d{8}$/;
                if(!preg.test($(this).val())){
                        $(this).val('');
                        tip.html("<img src='/Static/1.2/images/pt-sell-err.png'>请您输入正确的联系电话");
                        tip.show();
                        result = false;
                }else{
                        tip.hide();
                }
	})
	return result;
}
//提交成功
function sellok(data){
	$('.allsell').html(data['all']);
	$('.oldsell').html(data['old']);
	$('.newsell').html(data['num']);
	$('.errorell').html(data['error']);
	var obj ;
	if(data['old'] == 0){ //全部是新的
		obj = $("#mj-submitok");
	}else if(data['old'] > 0 && data['num']){	//有部分提交过的
		obj = $("#mj-submitte");
	}else{//全部都是旧的
		obj = $("#mj-error");
	}
	layer.open({
		type: 1,
		title: false,
		closeBtn: false,
		area: ['500px', '240px'],
		content: obj
	});
	$(".mj-close,.mj-boBtn1").bind("click",function(){
		layer.closeAll();
		location.reload()
	});

}

//提交失败
function sellNo(str){
	$('.errorReason').html(str);
	layer.open({
		type: 1,
		title: false,
		closeBtn: false,
		skin: 'yourclass',
		content: $('#mj-error')
	});
	setTimeout(function(){
		layer.closeAll();
	},5000);
	$(".mj-close").bind("click",function(){
		layer.closeAll();
	});
}

function getLayer(obj){
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

//倒计时
function _timer(count, obj){
	obj.removeClass('mj-clickable');
	obj.addClass('mj-bclik');
	window.setTimeout (function () {
		count --;
		obj.text(count + "秒后重新获取");
		if(count > -1){
			_timer(count, obj);
		}else{
			_sendOnce = true;
			obj.text('重新获取');
			obj.removeClass('mj-bclik');
			obj.addClass('mj-clickable');
		}
	},1000);
}
	