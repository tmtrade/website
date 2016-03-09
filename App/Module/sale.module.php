<?php
/**
 * Created by PhpStorm.
 * User: dower
 * Date: 2016/3/3 0003
 * Time: 下午 15:55
 */
class SaleModule extends AppModule{
    public $models = array(
        'saleTminfo'		=> 'SaleTminfo',
        'sale'		=> 'Sale',
        'class' => 'Tmclass',
    );

    /**
     * 根据商标得到sale信息(分类第一个)
     * @author dower
     * @param $number
     * @return mixed
     */
    public function getSaleInfoByNumber($number,$col = array('tid','class')){
        //得到商标的第一个分类
        $r['eq'] = array('number'=>$number);
        $r['col'] = $col;
        $rst = $this->import('sale')->find($r);
        $rst['className'] = $this->import('class')->get(array_pop(explode($rst['class'],',')),'name');
        //返回商标的分类名
        return $rst;
    }

    /**
     * 根据商标得到tminfo
     * @author dower
     * @param $number
     * @param string $field
     * @return array|bool
     */
    public function getSaleTmInfoByNumber($number,$field=''){
        $r['eq'] = array('number'=>$number);
        $info = $this->import('saleTminfo')->find($r);
        if($info==false){
            return false;
        }
        if($field){
            return $info[$field];
        }
        return $info;
    }

    /**
     * 判断商标是否销售中
     * @author dower
     * @param $number
     * @return bool
     */
    public function isSale($number){
        $r['eq'] = array('number'=>$number);
        $r['col'] = array('status');
        $result = $this->import('sale')->find($r);
        if($result && $result['status']==1){
            return true;
        }
        return false;
    }
    
    //获取商品信息
    public function getSaleInfo($number)
    {
        $arr['eq'] = array(
            'number' => $number,
            );
        $info = $this->import('sale')->find($arr);
        if ( empty($info) ) return array();
        return $info;
    }
}