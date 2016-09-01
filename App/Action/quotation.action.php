<?php
/**
 * 报价单
 * Created by PhpStorm.
 * User: dower
 * Date: 2016/8/25 0021
 * Time: 下午 14:10
 */
class QuotationAction extends AppAction{

    public $pageTitle   = "商品报价单-一只蝉出售者平台";
    public $ptype = 18;

    /**
     * 商品报价单首页
     */
    public function index(){
        $id = $this->input('id','int');
        $uid = $this->input('u','int');
        if(!$id || !$uid) exit('参数不合法');
        //得到详情数据
        $res = $this->load('quotation')->getDetail($id,$uid);
        $this->set('list',$res);
        $this->set('label',C('QUOTATION_LABEL'));
        if($res['style']==1){
            $this->display();
        }else{
            $this->display('quotation/quotation.index1.html');
        }
    }

    /**
     * 报价单模板
     */
    public function templet(){
        $id = $this->input('id','int');
        if($id==1){
            $this->display('quotation/quotation.templet1.html');
        }else{
            $this->display('quotation/quotation.templet0.html');
        }
    }
}