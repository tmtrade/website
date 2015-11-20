$(function(){
	var jBgnav = $('#j-bgnav');
	var tiemOut = null;
	jBgnav.on('click', '.add', function(){
		var _parent = $(this).parent().parent().parent();
		var _copy = _parent.clone();
		_copy.find(".remove").removeAttr('style');
		_parent.after(_copy);
		_copy.find('input').val('');
		_copy.find('.g-td2').html('');
	}).on('click', '.remove', function(){
		$(this).parent().parent().parent().remove();
	});
	jBgnav.on('blur', '.input-number', function(){
		var _val = '';
		var _this = $(this);
		var tip = _this.parent().parent().next();
		if(_this.val() == ''){
			tip.show();
			return false;
		}
		var table = _this.parent().parent().next().next();
		clearTimeout(tiemOut);
		tiemOut = setTimeout(function(){
			_val = $.trim(_this.val());
			$.ajax({
				url : '/sell/getselldata/',
				data : {number:_val},
				method: 'post'
			}).done(function(data){
				var obj = eval('(' + data + ')');	
				if(obj == ''){
					tip.html('<i class="us-icon uj_icon44"></i>商标信息不存在,请重新填写');
					tip.show();
					table.hide();
					_this.val('');
					return false;
				} 
				if(obj['status'] == '0'){
					tip.html("<i class=\"us-icon uj_icon44\"></i>"+obj['statusValue']+'的商标不可出售');
					tip.show();
					table.hide();
					_this.val('');
					return false;
				}
				table.removeAttr('style');
				tip.hide();
				$.each(obj,function(item,value){		
					if(item == 'imgurl'){
						table.find('.imgurl').html('<img src="'+value+'" style="width:120px;height:100px;border:0;" />');
					}else{
						table.find('.'+item).html(value);
					}
				})
			}).error(function(){
			});
		}, 100);
	})
	
	//手机验证码输入确认
	$(".mj-determineBtn").click(function (){
		var code    = $.trim($("#buyMsgCode").val());
		var mobile  = $.trim($("#usrMp_popup").val());
		if (mobile == '' || mobile.length != 11){
			$(".mj-bcTips").text('手机号错误');
			$('.mj-bcTips').show();
		}
		if ( code == '' || code.length != 6){
			$(".mj-bcTips").text('验证码不正确');
			$('.mj-bcTips').show();
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
				}else if (data.code == 4){
					$(".mj-bcTips").text('手机号已更改');
					$('.mj-bcTips').show();
				}else if (data.code == 5){
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
        if (mobile == ''){
            $(".mj-bcTips").text('手机号错误');
        }
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
	
	$('.input-price').bind("blur",function(){
		var thisval = $(this).val();
		var preg = /^[1-9][\d]{0,7}$/;
		var tip = $(this).parent().parent().next();
		if(!preg.test(thisval)){
			$(this).val('');
			tip.html('<i class="us-icon uj_icon44"></i>商标出售底价不正确');
			tip.show();	
		}else{
			tip.hide();	
		}
	})
	
	$('.input-phone').bind("blur",function(){
		var thisval = $(this).val();
		var preg = /^1[3|5|7|8][0-9]\d{8}$/;
		var tip = $(this).parent().parent().next();
		if(!preg.test(thisval)){
			$(this).val('');
			tip.html("<i class='us-icon uj_icon44'></i>请您输入正确的联系电话");
			tip.show();	
		}else{
			tip.hide();	
		}
	})
	
	//验证姓名，只能输入数字和英文
	$('#contact').bind("blur",function(){
		var _this = $(this).val();
		var pregName = /^[\u0391-\uFFE5A-Za-z]+$/;
		var tip = $(this).parent().parent().next();
		if(!pregName.test(_this)){
			$(this).val('');
			tip.html("<i class='us-icon uj_icon44'></i>您的姓氏只能输入中文或者英文");
			tip.show();
		}else{
			tip.hide();
		}
	});
});
	
	
		
//检查提交数据
function submitSell(){

	var flag = checks($('.input-number'));
	if(flag){
		flag = checks($('.input-price'));
	} 
	if($('.input-phone').val() == '' && flag){
		$('.input-phone').focus();
		flag = false;
	}
	
	if(flag === true){
		if ( !_isLogin ){
			getLayer($('#mj-submittel'));
		}else{
			addSell();
		}
	}else{
		return flag;
	}
}
	
function addSell(){
	var content = $('#addsell').serialize();
	$.ajax({
		type: "post",
		url: "/sell/addsell",
		data: content,
		dataType: "json",
		success: function(data){
			if (data > 0){
				sellok(data);
			}else if (data.code == -2){
				str = "商标不存在";
				sellNo(str);
			}else if (data.code == -3){
				str = "提交的数据不正确";
				sellNo(str);
			}else{
				str = "操作失败";
				sellNo(str);
			}
		}
	});
}
	
function checks(obj){
	var result = true;
	obj.each(function(){
		if($(this).val() == '' || $(this).val() == obj.attr('placeholder')){
			$(this).focus();
			result = false;
		}
	})
	return result;
}
	
//提交成功
function sellok(number){
	$('.allsell').html(number);
	layer.open({
		type: 1,
		title: false,
		closeBtn: false,
		skin: 'yourclass',
		content: $('#mj-submitte')
	});
	setTimeout(function(){
		layer.closeAll();
		location.reload() 
	},5000);
	$(".mj-close").bind("click",function(){
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
	