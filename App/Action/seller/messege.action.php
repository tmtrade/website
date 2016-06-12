<?php
/**
 * Created by PhpStorm.
 * User: dower
 * Date: 2016/6/12 0012
 * Time: 下午 17:54
 */
class MessegeAction extends SellerAction{

    public $size = 20;

    /**
     * 站内信列表
     */
    public function index(){
        $p = $this->input('p','int',1);
        //得到所有的站内信信息
        $rst = $this->load('messege')->getMsg($p-1,$this->size);
        $count = $rst['total'];
        $data = $rst['data'];
        $this->display();
    }

    /**
     * 站内信详情
     */
    public function view(){
        $id = $this->input('id','int');
        $msginfo = $this->load('messege')->viewMsg($id);
        $this->set('msginfo',$msginfo);
    }

    /**
     * 删除指定站内信
     */
    public function remove(){
        $id = $this->input('id','int');
        $rst = $this->load('messege')->deleteMsg($id);
        if($rst){
            $this->returnAjax(array('code'=>0));
        }else{
            $this->returnAjax(array('code'=>1,'msg'=>'删除失败'));
        }
    }
}