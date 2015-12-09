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
        $notInIds   = arrayColumn($res['rows'], 'tid');
        $need       = $limit - count($res['rows']);
        $started    = ($start - $res['total']) < 0 ? 0 : ($start - $res['total']);
        $tmList     = $this->getTmList($params, $started, $need, $notInIds);

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
    public function getSaleList($params, $start=0, $limit=30, $col=array())
    {
        if ( !empty($params['keyword']) ){
            $r['raw'] = " area = 1 and tid > 0 and (`name` LIKE '%".$params['keyword']."%' OR `number` = '".$params['keyword']."') ";
        }else{
            $r['raw'] = " area = 1 and tid > 0 ";
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
        if ( !empty($params['label']) ){
            $r['ft']['label'] = $params['label'];
        }
        if ( !empty($params['types']) ){
            $r['ft']['types'] = $params['types'];
        }
        if ( !empty($params['sblength']) ){
            $r['ft']['sblength'] = $params['sblength'];
        }
        //这次不上
        if ( !empty($params['isBargain']) ){
            //$r['eq']['isBargain'] = $params['isBargain'];
            $_raw_    = ' `salePrice` > 0 and (`salePriceDate` = 0 OR `salePriceDate` > unix_timestamp(now())) ';
            $r['raw'] = empty($r['raw']) ? $_raw_ : $r['raw'].' and '.$_raw_ ;
        }
        if ( !empty($params['saleType']) ){
            $r['eq']['saleType'] = $params['saleType'];
        }
        $r['group'] = array('tid'=>'asc');
        $r['index'] = array($start, $limit);
        if ( empty($col) ){
            $r['col']   = array('id', 'tid', 'number', 'class', 'name');
        }else{
            $r['col']   = $col;
        }
        $r['order'] = array('isTop' => 'desc','date'=>'desc');
        $r['notIn'] = array('status'=>array(2,3,4,6));

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
    public function getTmList($params, $start, $limit, $notInIds)
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
                'isShow'    => 1,
                );
            if ( !empty($params['class']) ) $r['eq']['class_id'] = $params['class'];

            $_r['col']   = array('tid');
            $_r['limit'] = 100;
            $res2 = $this->import('second')->find($_r);
            $tids = arrayColumn($res2, 'tid');
            //最终所有tid
            $ids = array_unique(array_merge($tids, $rows));
            $ids = array_diff($ids, $notInIds);
            if ( !empty($ids) ) $r['in'] = array('tid'=>$ids);
        }

        if ( !empty($params['class']) ){
            $r['eq'] = array(
                'class_id'     => $params['class'],
                'status3'   => 0,
                'status26'  => 0,
                'isShow'    => 1,
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

        $isInGroup = false;
        if ( !empty($params['group']) ){
            if ( empty($r['in']) ){
                $r['ft']['group'] = $params['group'];
            }else{
                $isInGroup = true;
            }
        }
        if ( !empty($params['types']) ){
            $r['eq']['type'] = $params['types'];
        }
        if ( !empty($params['sblength']) ){
            $r['eq']['nums'] = $params['sblength'];
        }
        if ( empty($r['eq']) ){
            $r['eq'] = array(
                'status3'   => 0,
                'status26'  => 0,
                'isShow'    => 1,
                );
        }
        if ( $isInGroup ){
            $r['limit'] = 1000;
        }else{
            $r['index'] = array($start, $limit);
        }
        $r['col']   = array('tid', 'trademark_id as number', 'class_id as class', 'trademark as name', 'group');
        //$r['order'] = array('tid'=>'desc');

        $res3   = $this->import('second')->find($r);
        //处理MySQL不走索引而效率慢的问题
        if ( $isInGroup ){
            $items  = $this->makeInGroup($res3, $params['group']);
            $res3   = array_splice($items, $start, $limit);
        }
        $list   = $this->getListTips($res3);
        return $list;
    }

    /**
     * 处理带有IN和match条件的数据
     *
     * 把带有IN条件的数据进行PHP字符串匹配处理，完成MySQL不走索引而效率慢的问题。 
     * @author  Xuni
     * @since   2015-11-24
     *
     * @access  public
     * @param   array       $data       原始数据
     * @param   array       $group      群组
     *
     * @return  array       $rows       原始数据匹配$group后的数据
     */
    public function makeInGroup($data, $group)
    {
        $rows = array();
        foreach ($data as $k => $v) {
            if ( strpos($v['group'], $group) !== false ){
                $rows[] = $v;
            }
        }
        return $rows;
    }

    /**
     * 近似查询调用
     *
     * @author  Xuni
     * @since   2015-11-11
     *
     */
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

    /**
     * 处理列表数据
     *
     * @author  Xuni
     * @since   2015-11-11
     *
     */
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

    /**
     * 处理列表数据
     *
     * @author  Xuni
     * @since   2015-11-11
     *
     */
    public function getTips($data)
    {
        $data['isOffprice'] = false;
        $data['isBest']     = false;
        $data['isLicence']  = false;
        $data['isApprove']  = false;

        if ( empty($data['tid']) ) return $data;
		
		if ( $data['id'] ){
			$img	= $this->load('saletrademark')->getOffpriceImg($data['id']);
            $imgUrl	= empty($img['bzpic']) ? $img['tjpic'] : $img['bzpic'];
			if ( empty($imgUrl) ) {
				$data['imgUrl'] = $this->load('trademark')->getImg($data['tid']);
			}else{
				$data['imgUrl'] = TRADE_URL.$imgUrl;
			}
		}else{
			$data['imgUrl'] = $this->load('trademark')->getImg($data['tid']);
		}
        
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
            if ( !$data['isApprove'] && $v['approveStatus'] == 2){
                $data['isApprove'] = true;
            }
        }
        return $data;
    }
	
}
?>