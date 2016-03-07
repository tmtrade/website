<?
/**
 * 快速筛选功能组件
 *
 * 快速筛选出商标信息，主要从出售信息列表中搜索数据，次要从商标库里搜索数据
 * 
 * @package Module
 * @author  Xuni
 * @since   2015-11-09
 */
class SearchModule extends AppModule
{
    private $_service = array();

    /**
     * 引用业务模型
     */
    public $models = array(
        'sale'      => 'sale',
        'second'    => 'secondStatus',
        'group'     => 'group',
        'class'     => 'tmClass',
    );
    
    //获取查询的TITLE
    public function getTitle($data)
    {   
        $titleArr  = array();
        if(!empty($data['class'])){
            $titleArr[] = $data['class']."类";
        }
        if(!empty($data['group'])){
            $titleArr[] = $this->load('group')->getGroupName($data['group']);
        }
        if(!empty($data['types'])){
            $typeconfig = C('TYPES');
            $titleArr[] = $typeconfig[$data['types']];
        }
        if(!empty($data['sblength'])){
            $sbconfig   = C('SBNUMBER');
            $titleArr[] = $sbconfig[$data['sblength']];
        }
        if(!empty($data['platform'])){
            $sbplatform = C('PLATFORM_IN');
            $titleArr[] = $sbplatform[$data['platform']];
        }
        if(!empty($data['keyword'])){
            $keyword = "";
            $titleArr[] = $data['keyword'];
        }
        $titleArr[] = "商标转让|买卖|交易 – 一只蝉";
        return implode("_", $titleArr);
    }

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
    public function search($params, $page=1, $limit=30, $type=1)
    {
        if ( $type == 1 ){
            return $this->getSaleList($params, $page, $limit);
        }elseif ( $type == 2 ){
            $list = $this->getTmList($params, $page, $limit);
            return array('rows'=>$list);
        }

        $result = array(
            'rows'  => array(),
            'total' => 0,
            );
        return $result;
    }

    /**
     * 获取适用服务筛选条件
     * 
     * @author  Xuni
     * @since   2015-11-09
     *
     * @access  public
     * @param   string      $keyword        关键字
     *
     * @return  array       $list           类别与群组，多类别时，只返回类别
     */
    public function getServiceCondition($keyword)
    {
        if ( empty($keyword) ) return array();

        $name   = $this->_getService($keyword, 1);
        $title  = $this->_getService($keyword, 2);
        $label  = $this->_getService($keyword, 3);

        return $this->_service;
    }

    private function _getService($str, $type)
    {
        switch ($type) {
            case '1':
                $r['like'] = array('name'=> $str);
                break;            
            case '2':
                $r['like'] = array('title'=> $str);
                break;
            case '3':
                $r['ft'] = array('label'=> $str);
                break;
        }
        if ( empty($r) ) return array();

        $r['limit'] = 1000;
        $res        = $this->import('class')->find($r);
        if ( empty($res) ) return array();

        $class = $group = array();
        foreach ($res as $k => $v) {
            if ( $v['parent'] == 0 ){
                array_push($class, $v['number']);
            }else{
                array_push($class, $v['parent']);
                array_push($group, $v['number']);
            }
        }
        $class = array_unique($class);
        $group = array_unique($group);

        if ( !empty($class) ){
            $this->_service['class'] = empty($this->_service['class']) ? $class : array_merge($this->_service['class'], $class);
            $this->_service['class'] = array_filter( array_unique($this->_service['class']) );
        }
        if ( !empty($group) ){
            $this->_service['group'] = empty($this->_service['group']) ? $group : array_merge($this->_service['group'], $group);
            $this->_service['group'] = array_filter( array_unique($this->_service['group']) );
        }
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
    public function getSaleList($params, $page=1, $limit=30, $col=array())
    {
        $result = array(
            'rows'  => array(),
            'total' => 0,
            );
        $r['raw'] = ' 1 ';
        if ( !empty($params['name']) ){
            switch ( $params['_name'] ) {
                case '1'://相同
                    $r['eq']['name'] = $params['name'];
                    break;
                case '2'://前包含
                    $r['lLike']['name'] = $params['name'];
                    break;
                case '9'://近似
                    $_arr = array('keyword'=>$params['name']);
                    if ( !empty($params['class']) ){
                        $_arr['classId'] = $params['class'];
                        unset($params['class']);
                        if ( !empty($params['group']) ){
                            $_arr['groupCode'] = $params['group'];
                            unset($params['group']);
                        }
                    }
                    $_res = $this->searchLike($_arr, '31,32', 1, 100);
                    if ( empty($_res['rows']) ) return $result;
                    $numberList = array_unique( arrayColumn($_res['rows'], 'code') );
                    if ( empty($numberList) ) return $result;
                    $r['in']['number'] = $numberList;
                    break;
                default:
                    $r['eq']['name'] = $params['name'];
                    break;
            }
        }
        if ( !empty($params['class']) && empty($params['group']) ){
            $r['ft']['class'] = $params['class'];
        }
        if (  !empty($params['class']) && !empty($params['group']) ){
            $r['ft']['group'] = $params['group'];
        }
        if ( !empty($params['platform']) ){
            $r['ft']['platform'] = $params['platform'];
        }
        if ( !empty($params['label']) ){
            $r['ft']['label'] = $params['label'];
        }
        if ( !empty($params['type']) ){
            $r['ft']['type'] = $params['type'];
        }
        if ( !empty($params['length']) ){
            $r['ft']['length'] = $params['length'];
        }
        //这次不上
        if ( !empty($params['isOffprice']) ){
            $r['raw'] .= ' `priceType` = 1 AND `isOffprice` = 1 AND (`salePriceDate` = 0 OR `salePriceDate` > unix_timestamp(now())) ';
        }

        $r['index'] = array($start, $limit);
        if ( empty($col) ){
            $r['col']   = array('id', 'tid', 'number', 'class', 'name', 'group');
        }else{
            $r['col']   = $col;
        }
        $r['eq']['status']  = 1;
        $r['eq']['isSale']  = 1;

        $r['page']      = $page;
        $r['limit']     = $limit;
        $r['order']     = array('isTop' => 'desc');
        $res            = $this->import('sale')->findAll($r);
        $res['rows']    = $this->getListTips($res['rows']);

        return $res;
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
    public function getTmList($params, $page=1, $limit=30)
    {
        $r['eq'] = array('isShow'=>1);
        //判断类别        
        if ( !empty($params['class']) ){
            $_class = explode(',', $params['class']);
            if ( !empty($params['platform']) ){
                $pItems = C('PLATFORM_ITEMS');
                if ( !empty($pItems[$params['platform']]) )
                foreach ($_class as $k => $v) {
                    if ( !in_array($v, $pItems[$params['platform']]) ) unset($_class[$k]);
                }
                if ( empty($_class) ) return array();
            }
            $r['in']['class_id'] = $_class;
        }elseif ( !empty($params['platform']) ){
            $pItems = C('PLATFORM_ITEMS');
            if ( !empty($pItems[$params['platform']]) ){
                $r['in']['class_id'] = $pItems[$params['platform']];
            }
        }
        //如果有类别条件，则参数类别使用它
        if ( !empty($r['in']['class_id']) ) $params['class'] = implode(',', $r['in']['class_id']);

        if ( !empty($params['name']) ){
            switch ( $params['_name'] ) {
                case '1'://相同
                    $r['eq']['trademark'] = $params['name'];
                    break;
                case '2'://前包含
                    $r['lLike']['trademark'] = $params['name'];
                    break;
                case '9'://近似
                    $_arr = array('keyword'=>$params['name']);
                    if ( !empty($params['class']) ){
                        $_arr['classId'] = $params['class'];
                        unset($params['class'],$r['in']['class_id']);
                        if ( !empty($params['group']) ){
                            $_arr['groupCode'] = $params['group'];
                            unset($params['group']);
                        }
                    }
                    $_res = $this->searchLike($_arr, '31,32', 1, 100);
                    if ( empty($_res['rows']) ) return $result;
                    $numberList = array_unique( arrayColumn($_res['rows'], 'tid') );
                    if ( empty($numberList) ) return $result;
                    $r['in']['tid'] = $numberList;
                    break;
                default:
                    $r['eq']['trademark'] = $params['name'];
                    break;
            }
        }

        $isInGroup = false;
        if ( !empty($params['group']) ){
            $r['ft']['group'] = $params['group'];
            //$isInGroup = true;
            //$r['page']  = 1;
            //$r['limit'] = 10000;
        }

        $r['index'] = array(($page - 1) * $limit, $limit);
        $r['limit'] = $limit;
        
        if ( !empty($params['type']) ){
            $r['eq']['type'] = $params['type'];
        }
        if ( !empty($params['length']) ){
            $r['eq']['nums'] = $params['length'];
        }

        $r['col']   = array('tid', 'trademark_id as `number`', 'class_id as `class`', 'trademark as `name`', 'group');

        $list   = $this->import('second')->find($r);
        $list   = $this->getListTips($list);
        //处理MySQL不走索引而效率慢的问题
        //if ( $isInGroup && !empty($list['rows']) ){
        //    $list['total']  = count($list['rows']);
        //    $start          = ($page - 1) * $limit;
        //    $items          = $this->makeInGroup($list['rows'], $params['group']);
        //    $list['rows']   = array_splice($items, $start, $limit);
        //}
        //$list['rows']   = $this->getListTips($list['rows']);

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
            $_group = array_filter( explode(',', $group) );
            if ( empty($_group) ) continue;
            foreach ($_group as $g) {
                if ( strpos($v['group'], $g) !== false ){
                    $rows[] = $v;
                    break;
                }
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
    public function searchLike($params, $page=1, $number=1000)
    {
        if ( empty($params) ) return array();

        $params['page'] = $page;
        $params['num']  = $number;

        $res = $this->importBi('trademark')->search($params);
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
        //$data['isOffprice'] = false;
        //$data['isBest']     = false;
        //$data['isLicense']  = false;

        if ( empty($data['tid']) ) return $data;
        
        if ( $data['id'] ){
            $data['imgUrl'] = $this->load('internal')->getViewImg($data['id']);
        }else{
            $data['imgUrl'] = $this->load('trademark')->getImg($data['number']);
        }
        $_class = current( explode(',', $data['class']) );
        $data['viewUrl'] = '/d-'.$data['tid'].'-'.$_class.'.html';
        $data['safeUrl'] = 'http://jingling.yizhchan.com/?nid='.$data['number'].'&class='.$data['class'];
        
        //if ( empty($data['id']) ) return $data;

        //$sale = $this->load('internal')->getSaleInfo($data['id'], 0, 0);
        //if ( empty($sale) ) return $data;
        
        // if ( $sale['priceType'] == 1 && $sale['isOffprice'] == 1 && ($sale['salePriceDate'] == 0 || $sale['salePriceDate'] >= time()) ){
        //     $data['isOffprice'] = true;
        // }
        // if ( strpos($sale['label'], '1') !== false ){
        //     $data['isBest'] = true;
        // }
        // if ( $sale['isLicense'] == 1 ){
        //     $data['isLicense'] = true;
        // }

        return $data;
    }


    public function getClassGroup($class=0, $group=1)
    {
        if ( $class == 0 && $group != 1 ){
            $r['eq'] = array('parent'=>0);
        }elseif ( $class != 0 && $group == 1 ){
            $r['eq'] = array('parent'=>$class);
        }
        $r['order'] = array('parent'=>'asc','number'=>'asc');
        $r['limit'] = 1000;

        $_class = $_group = array();
        $res    = $this->import('class')->find($r);
        if ( empty($res) ) return array();

        foreach ($res as $k => $v) {
            if ( $v['parent'] == '0' ){
                $_class[$v['number']] = empty($v['title']) ? $v['name'] : $v['title'];
            }elseif ( $v['parent'] != 0 ){
                $_group[$v['parent']][$v['number']] = empty($v['title']) ? $v['name'] : $v['title'];
            }
        }
        return array($_class, $_group);
    }
    
}
?>