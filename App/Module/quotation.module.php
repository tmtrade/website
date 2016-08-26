<?
/**
 * 商品报价单
 * 
 * 查询、创建
 *
 * @package	Module
 * @author	dower
 * @since	2016-08-25
 */
class quotationModule extends AppModule
{
	
    /**
     * 引用业务模型
     */
    public $models = array(
        'quotation'         => 'quotation',
        'quotationItems'    => 'quotationItems',
        'userImage'         => 'userImage',
    );

    /**
     * 得到报价单详细信息
     * @param $id
     * @return array
     */
    public function getDetail($id){
        $r = array();
        $r['eq']['id'] = $id;
        $rst = $this->import('quotation')->find($r);
        if($rst){
            //得到报价单详细数据
            $r = array();
            $r['eq']['qid'] = $id;
            $r['limit'] = 100;
            $rst['data'] = $this->import('quotationItems')->find($r);
        }
        return $rst?:array();
    }
}
?>