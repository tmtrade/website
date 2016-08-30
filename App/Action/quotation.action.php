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
    public $size = 20;
    public $ptype = 18;

    /**
     * 商品报价单首页
     */
    public function index(){
        exit('还没有完成呢');
        $id = $this->input('id','input');
        if(!$id) exit('参数不合法');
        //得到详情数据
        $res = $this->load('quotation')->getDetail($id);
        $this->assign('list',$res);
        $this->display();
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