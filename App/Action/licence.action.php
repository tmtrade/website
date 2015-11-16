<?
/**
 * 可许可商标
 *
 * 可许可的商标列表
 *
 * @package Action
 * @author  Xuni
 * @since   2015-11-12
 */
class LicenceAction extends AppAction
{
    private $_number = 30;

    public function index()
    {
        $class      = $this->input('c', 'int', 0);
        $group      = $this->input('g', 'string', 0);
        $platform   = $this->input('p', 'int', 0);
        $type       = $this->input('t', 'int', 0);
        $number     = $this->input('sn', 'int', 0);

        if( $group > 0 && strlen($group) == 4 && !in_array($class, range(1, 45))){
            $class = (int)substr($group, 0, 2);
        }
        if ( $class > 0 ){
            $groupList = $this->load('group')->getClassGroups($class);
            $this->set('groupList', $groupList);
        }
        $params = array(
            'class'     => $class,
            'group'     => $group,
            'saleType'  => '2',
            'platform'  => $platform == 99 ? 0 : $platform,//99自营为全部0
            'types'     => $type,
            'sblength'  => $number,
            );
        $res        = $this->load('search')->getSaleList($params, 0, $this->_number);
        $strArr     = $this->getFormData();
        $whereStr   = http_build_query($strArr);

        $this->set('c', $class);
        $this->set('g', $group);
        $this->set('p', $platform);
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
            'saleType'  => '2',
            'platform'  => $platform == 99 ? 0 : $platform,//99自营为全部0
            'types'     => $type,
            'sblength'  => $number,
            );
        $list   = $this->load('search')->getSaleList($params, $start, $this->_number);
        
        $this->set('searchList', empty($list['rows']) ? array() : $list['rows']);
        $this->display();
    }

}
?>