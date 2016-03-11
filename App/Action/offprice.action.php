<?
/**
 * 特价商标
 *
 * 特价商标列表
 *
 * @package Action
 * @author  Xuni
 * @since   2016-03-10
 */
class OffpriceAction extends AppAction
{
    public $pageTitle   = '特价商标 - 一只蝉';
    private $_number    = 30;
    private $_col       = array('id', 'tid', 'number', 'class', 'name', 'price', 'salePrice');
    private $_searchArr = array();

    /**
     * 特价页功能
     *
     * 显示相关特价商品列表
     * 
     * @author  Xuni
     * @since   2016-03-10
     *
     * @return  void
     */
    public function index()
    {
        $class  = $this->input('c', 'int', '');
        $group  = $this->input('g', 'string', '');
        $page   = $this->input('_p', 'int', 1);

        //$_class = $this->strToArr($class);
        //$_group = $this->strToArr($group);

        $this->_searchArr['c']  = $class;
        $this->_searchArr['g']  = $group;
        $this->_searchArr['_p'] = $page;

        $params = array('isOffprice'=>'1');
        if ( in_array($class, range(1,45)) ){
            $params['class'] = $class;
            if ( !empty($page) ){
                $params['group'] = $group;
            }
        }
// debug($params);
        $res = $this->load('search')->getSaleList($params, $page, $this->_number);
// debug($this->_searchArr);
        if ( !empty($this->_searchArr) ){
            foreach ($this->_searchArr as $k => $v) {
                $this->set($k, $v);
                $this->set($k.'_title', $this->getSelectTitle($k, $v, $this->_searchArr));
            }
            $whereStr   = http_build_query($this->_searchArr);
        }
        $classGroup = $this->load('search')->getClassGroup();
        list($_class, $_group) = $classGroup;

        //频道设置
        $channel = $this->load('search')->getChannel($this->mod);        
        $this->set('channel', $channel);
        
        //设置页面TITLE
        //$this->set('TITLE', $this->load('search')->getTitle($params));

        $this->set('_CLASS', $_class);//分类
        $this->set('_GROUP', $_group);//群组
		
        $this->set('searchList', empty($res['rows']) ? array() : $res['rows']);
        $this->set('total', intval($res['total']));
        $this->set('has', empty($res['rows']) ? false : true);
        $this->set('whereStr', $whereStr);
        $this->display();
    }

    /**
     * 获取特价列表加载数据
     *
     * 获取特价列表加载数据
     * 
     * @author  Xuni
     * @since   2016-03-10
     *
     * @return  void
     */
    public function getMore()
    {
        $class  = $this->input('c', 'string', '');
        $group  = $this->input('g', 'string', '');
        $page   = $this->input('_p', 'int', 1);

        $_class = $this->strToArr($class);
        $_group = $this->strToArr($group);
        $params = array('isOffprice'=>'1');
        if ( count($_class) > 1 ){
            $params['class'] = implode(',', $_class);
        }else{
            $params['class'] = implode(',', $_class);
            if ( !empty($_group) ){
                $params['group'] = implode(',', $_group);
            }
        }

        $list = $this->load('search')->getSaleList($params, $page, $this->_number);
        $this->set('searchList', empty($list['rows']) ? array() : $list['rows']);
        $this->display();
    }


    /**
     * 获取搜索项显示标题
     *
     * 处理单个搜索项的中文显示标题
     * 
     * @author  Xuni
     * @since   2016-03-07
     *
     * @return  void
     */
    protected function getSelectTitle($title, $value, $all)
    {
        if ( empty($value) || empty($title) ) return $value;

        $_arr = array_filter( explode(',', $value) );
        if ( empty($_arr) ) return $value;

        $_str = '';
        switch ($title) {
            case 'c':
                list($cArr,) = $this->load('search')->getClassGroup(0, 0);
                foreach ($_arr as $v) {
                    $_str .= "$v-".$cArr[$v].'|';
                }
                break;
            case 'g':
                if ( empty($all['c']) ) return $value;
                list(,$gArr) = $this->load('search')->getClassGroup(0, 1);
                foreach ($_arr as $v) {
                    $_str .= "$v-".$gArr[$all['c']][$v].'|';
                }
                break;
        }
        return $_str;
    }

    /**
     * 将字符串转换为数据并去空去重
     * @author  Xuni
     * @since   2016-03-03
     *
     * @access  protected
     * @param   string      $str        字符串
     * @param   string      $prefix     分隔符
     * @return  array
     */
    protected function strToArr($str, $prefix=',')
    {
        if ( empty($str) ) return array();

        $arr = explode($prefix, $str);
        $arr = array_unique( array_filter($arr) );
        return $arr;
    }

}
?>