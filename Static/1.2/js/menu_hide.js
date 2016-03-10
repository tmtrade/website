/**
 * Created by dower on 2016/3/9 0009.
 */
$(function(){
    $('.mj-dd').hide();
    $('.mj-dtCon').mousemove(function(){
        $('.mj-dd').show();
    });
    $('.mj-dt').mouseleave(function(){
        $('.mj-dd').hide();
    });
});




