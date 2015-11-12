<?
/**
 * 快速筛选功能组件
 *
 * 快速筛选出商标信息，主要从出售信息列表中搜索数据，次要从商标库里搜索数据
 * 
 * @package	Module
 * @author	Xuni
 * @since	2015-11-09
 */
class SearchModule extends AppModule
{
    /**
     * 引用业务模型
     */
    public $models = array(
        'sale'      => 'sale',
        'second'    => 'secondStatus',
    );

    /**
     * 快速筛选结果
     * 
     * @author  Xuni
     * @since   2015-11-09
     *
     * @access  public
     * @param   int     $class      国际分类(1-45)
     *
     * @return  array   $list       群组号对应群组中文名称的数组
     */
    public function search($params, $start=0, $limit=30)
    {
        $res = $this->getSaleList($params, $start, $limit);
        if ( count($res['rows']) >= $limit ){
            return $res['rows'];
        }
        $need       = $limit - count($res['rows']);
        $started    = ($start - $res['total']) < 0 ? 0 : ($start - $res['total']);
        $tmList     = $this->getTmList($params, $started, $need);

        $list       = array_merge($res['rows'], $tmList);
        return $list;
    }

    /**
     * 获取出售信息相关数据
     * 
     * @author  Xuni
     * @since   2015-11-10
     *
     * @access  public
     * @param   int     $class      国际分类(1-45)
     *
     * @return  array   $list       群组号对应群组中文名称的数组
     */
    public function getSaleList($params, $start, $limit)
    {
        if ( !empty($params['keyword']) ){
            $r['raw'] = " tid > 0 and (`name` LIKE '%".$params['keyword']."%' OR `number` = '".$params['keyword']."') ";
        }else{
            $r['raw'] = " tid > 0 ";
        }
        if ( !empty($params['class']) ){
            $r['eq'] = array('class'=>$params['class']);
        }
        if ( !empty($params['group']) ){
            $r['ft']['group'] = $params['group'];
        }
        if ( !empty($params['platform']) ){
            $r['ft']['platform'] = $params['platform'];
        }
        $r['group'] = array('tid'=>'asc');
        $r['index'] = array($start, $limit);
        $r['col']   = array('tid', 'number', 'class', 'name');
        $r['order'] = array('date'=>'desc');

        //debug($r);
        $count  = $this->import('sale')->count($r);
        $res    = $this->import('sale')->find($r);
        $list   = $this->getListTips($res);

        $result = array(
            'rows'  => $list,
            'total' => $count,
            );

        return $result;
    }

    /**
     * 获取商标信息相关数据
     * 
     * @author  Xuni
     * @since   2015-11-10
     *
     * @access  public
     * @param   int     $class      国际分类(1-45)
     *
     * @return  array   $list       群组号对应群组中文名称的数组
     */
    public function getTmList($params, $start, $limit)
    {
        if ( !empty($params['keyword']) ){
            $class = empty($params['class']) ? 0 : $params['class'];
            $res = $this->searchLike($params['keyword'], $class);
            if ($res['header']['code'] = '101' && $res['body']['status'] == 1){
                $total  = $res['body']['data']['total'];
                $rows   = arrayColumn($res['body']['data']['rows'], 'id');
            }
            $_r['eq'] = array(
                'trademark_id'   => $params['keyword'],
                'status3'       => 0,
                'status26'      => 0,
                );
            if ( !empty($params['class']) ) $r['eq']['class_id'] = $params['class'];

            $_r['col']   = array('tid');
            $_r['limit'] = 100;
            $res2 = $this->import('second')->find($_r);
            $tids = arrayColumn($res2, 'tid');
            //最终所有tid
            $ids = array_unique(array_merge($tids, $rows));
            if ( !empty($ids) ) $r['in'] = array('tid'=>$ids);
        }

        if ( !empty($params['class']) ){
            $r['eq'] = array(
                'class_id'     => $params['class'],
                'status3'   => 0,
                'status26'  => 0,
                );
            if ( !empty($params['platform']) ){
                $pItems = C('PLATFORM_ITEMS');
                if ( !empty($pItems[$params['platform']]) && !in_array($params['class'], $pItems[$params['platform']])){
                    return array();
                }
            }
        }elseif ( !empty($params['platform']) ){
            $pItems = C('PLATFORM_ITEMS');
            if ( !empty($pItems[$params['platform']]) ){
                $r['in']['class_id'] =$pItems[$params['platform']];
            }
        }

        if ( !empty($params['group']) ){
            $r['ft']['group'] = $params['group'];
        }
        if ( empty($r['eq']) ){
            $r['eq'] = array(
                'status3'   => 0,
                'status26'  => 0,
                );
        }
        $r['index'] = array($start, $limit);
        $r['col']   = array('tid', 'trademark_id as number', 'class_id as class', 'trademark as name');
        $r['order'] = array('tid'=>'desc');

        $res3   = $this->import('second')->find($r);
        $list   = $this->getListTips($res3);
        return $list;
    }

    public function searchLike($keyword, $class='', $page=1, $number=1000)
    {
        if ( empty($keyword) ) return array();

        $params = array(
            'keyword'   => $keyword,
            );
        if ($class > 0 && $class <= 45){
            $params['classId'] = $class;
        }

        $res = $this->importBi('trademark')->searchLike($params, $page, $number);
        return $res;
    }


    public function getListTips($data)
    {
        if ( empty($data) ) return array();
        if ( !is_array(current($data)) ){
            $_tmp = array($data);
        }else{
            $_tmp = $data;
        }
        
        foreach ($_tmp as $k => $v) {
            $data[$k] = $this->getTips($v);
        }
        return $data;
    }

    public function getTips($data)
    {
        $data['isOffprice'] = false;
        $data['isBest']     = false;
        $data['isLicence']  = false;

        if ( empty($data['tid']) ) return $data;

        $data['imgUrl'] = $this->load('trademark')->getImg($data['tid']);
        
        $r['limit'] = 100;
        $r['eq'] = array(
            'area'  => 1,
            'tid'   => $data['tid'],
        );
        $r['notIn']   = array('status'=>array(2,3,4,6));
        $res = $this->import('sale')->find($r);
        if ( empty($res) ) return $data;
        
        foreach ($res as $k => $v) {
            if ( !$data['isOffprice'] && $v['salePrice'] > 0 ){
                $data['isOffprice'] = true;
            }
            if ( !$data['isBest'] && strpos($v['label'], '1') !== false ){
                $data['isBest'] = true;
            }
            if ( !$data['isLicence'] && $v['saleType'] == 2 ){
                $data['isLicence'] = true;
            }
        }
        return $data;
    }
	
}
?>