<?
/**
 * 特价商标
 *
 * 网站首页
 *
 * @package Action
 * @author  Xuni
 * @since   2015-11-05
 */
class OffpriceAction extends AppAction
{
    public $pageTitle   = '特价商标 - 一只蝉';
    private $_number    = 20;
    private $_col       = array('id', 'tid', 'number', 'class', 'name', 'guideprice', 'salePrice');

    public function index()
    {
        $class      = $this->input('c', 'int', 0);
        $group      = $this->input('g', 'string', 0);
        $platform   = $this->input('p', 'int', 0);
        $start      = $this->input('n', 'int', 0);
        $type       = $this->input('t', 'int', 0);
        $number     = $this->input('sn', 'int', 0);

        if( $group > 0 && strlen($group) == 4 && !in_array($class, range(1, 45))){
            $class = (int)substr($group, 0, 2);
        }
        if ( $class > 0 ){
            $groupList = $this->load('group')->getClassGroups($class);
            $this->set('groupList', $groupList);
        }else{
            $this->set('groupList', array());
        }
        $params = array(
            'class'     => $class,
            'group'     => $group,
            'isBargain' => '2',//这次不上
            'platform'  => $platform == 99 ? 0 : $platform,//99自营为全部0
            'types'     => $type,
            'sblength'  => $number,
            );
        $res            = $this->load('search')->getSaleList($params, $start, $this->_number, $this->_col);
        $res['rows']    = $this->getOffpriceList($res['rows']);
        $strArr         = $this->getFormData();
        $whereStr       = http_build_query($strArr);

        $this->set('c', $class);
        $this->set('g', $group);
        $this->set('p', $platform);
        $this->set('t', $type);
        $this->set('sn', $number);
        $this->set('kw', '');
        $this->set('searchList', empty($res['rows']) ? array() : $res['rows']);
        $this->set('has', empty($res['rows']) ? false : true);
        $this->set('_number', $this->_number);
        $this->set('whereStr', $whereStr);
        $this->display();
    }

    public function getMore()
    {
        $class      = $this->input('c', 'int', 0);
        $group      = $this->input('g', 'string', 0);
        $platform   = $this->input('p', 'int', 0);
        $start      = $this->input('n', 'int', 0);
        $type       = $this->input('t', 'int', 0);
        $number     = $this->input('sn', 'int', 0);

        $params = array(
            'class'     => $class,
            'group'     => $group,
            'isBargain' => '2',//这次不上
            'platform'  => $platform == 99 ? 0 : $platform,//99自营为全部0
            'types'     => $type,
            'sblength'  => $number,
            );
        $list           = $this->load('search')->getSaleList($params, $start, $this->_number, $this->_col);
        $list['rows']   = $this->getOffpriceList($list['rows']);
        $this->set('searchList', empty($list['rows']) ? array() : $list['rows']);
        $this->display();
    }

    private function getOffpriceList($data)
    {
        if ( empty($data) ) return array();
        foreach ($data as $k => $v) {
            $img        = $this->load('saletrademark')->getOffpriceImg($v['id']);
            $imgUrl    = empty($img['bzpic']) ? $img['tjpic'] : $img['bzpic'];

            $data[$k]['imgUrl'] = empty($imgUrl) ? $v['imgUrl'] : TRADE_URL.$imgUrl;
        }
        return $data;
    }

}
?>