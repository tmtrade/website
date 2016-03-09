<?
/**
 * 无
 *
 * 无
 * 
 * @package	Module
 * @author	Xuni
 * @since	2016-3-08
 */
class ZhuantiModule extends AppModule
{
    public $models = array(
        'topic'		=> 'topic',
        'topicitems'	=> 'topicitems',
    );
    
    //专题列表
    public function getList($id=0, $page, $limit=20)
    {
        $r = array();
        if($id>0){
             $r['notIn']  = array('id' => array($id));
        }
        $r['page']  = $page;
        $r['limit'] = $limit;
        $r['order'] = array('sort'=>'asc');
        $res = $this->import('topic')->findAll($r);
        return $res;
    }
    
    //模块基础内容
    public function getTopicInfo($moduleId)
    {
        $r = array();
        $r['eq']['id']  = $moduleId;
        $r['limit'] = 1;
        $res = $this->import('topic')->find($r);
        return $res;
    }
	
    //首页模块子分类列表信息
    public function getTopicClassList($topicId)
    {
        $r = array();
        $arr = array();
        $r['eq']['topicId'] = $topicId;
        $r['limit'] = 100;
        $r['order'] = array('sort'=>'asc');
        $data = $this->import('topicitems')->findAll($r);
        foreach ($data['rows'] as $k=>$v){
            $v['sale']      = $this->load('sale')->getSaleInfo($v['number']);
            $v['tminfo']    = $this->load('trademark')->getTminfo($v['number']);
            $arr[$k] = $v;
        }
        return $arr;
    }
    
    
}
?>