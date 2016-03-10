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
    public function getList($id=0, $page, $limit=20,$order=array('sort'=>'asc'))
    {
        $r = array();
        if($id>0){
             $r['notIn']  = array('id' => array($id));
        }
        $r['eq'] = array('isUse'=>1);
        $r['page']  = $page;
        $r['limit'] = $limit;
        $r['order'] = $order;
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

    /**
     * 根据商标获得其参加的专题信息
     * @param $number
     * @return array|bool
     */
    public function getTopicByNumber($number){
        $r['eq']['number'] = $number;
        $r['col'] = array('topicId');
        $r['limit'] = 6;
        $rst = $this->import('topicitems')->find($r);
        $data = array();
        if($rst){
            $r = array();
            foreach($rst as $item){
                $r['eq']['id'] = $item['topicId'];
                $r['eq']['isUse'] = 1;
                $r['col'] = array('title','id');
                $res = $this->import('topic')->find($r);
                if($res){
                    $res['topicUrl'] = '/zhuanti/view/?id='.$res['id'];
                    $data[] = $res;
                }
            }
            return $data;
        }else{
            return false;
        }
    }
}
?>