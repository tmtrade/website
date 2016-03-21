<?
/**
 * 首页左侧导航着落页
 *
 * 显示群组、商品代号为条件的商标数据
 *
 * @package Action
 * @author  Xuni
 * @since   2016-03-15
 */
class GoodsAction extends AppAction
{
    public $pageTitle   = '3.5秒快速查找 - 一只蝉';

    private $_number    = 30;
    private $_searchArr = array();

    /**
     * 筛选页功能
     *
     * 处理筛选页筛选功能、不含加载数据
     * 
     * @author  Xuni
     * @since   2016-03-03
     *
     * @return  void
     */
	public function index()
	{
        $_groupGoods    = $this->input('s', 'string', '');
        $groupGoods     = $this->strToArr($_groupGoods);

        if ( !empty($groupGoods) ){
            $_params    = implode(',', $groupGoods);
            $result     = $this->load('Goods')->search($_params, $this->_number, 1);
        }else{
            $result = array('rows'=>array(),'total'=>0);
        }
        //特价列表
        $_arr   = array('isOffprice'=>'1');
        $list   = $this->load('search')->getSaleList($_arr, 1, 8);
        $this->set('offpriceList', $list['rows']);

        $_title = array_merge($result['groupName'], $result['goodsName']);
        $_title = implode(',', $_title);
        if ($result['className']) $_title = $result['className'].','.$_title;

        $classGroup = $this->load('search')->getClassGroup();
        list($_class, $_group) = $classGroup;

        $this->set('_CLASS', $_class);//分类
        $this->set('_GROUP', $_group);//群组
        
        $this->set('baoTitle', $_title);
        $this->set('has', empty($result['rows']) ? false : true);
        $this->set('whereStr', http_build_query(array('s'=>$_groupGoods)));
        $this->set('list', $result);
		$this->display();
	}

    public function getMore()
    {
        $page           = $this->input('_p', 'int', 1);
        $_groupGoods    = urldecode( $this->input('s', 'string', '') );
        $groupGoods     = $this->strToArr($_groupGoods);

        if ( !empty($groupGoods) ){
            $_params    = implode(',', $groupGoods);
            $result     = $this->load('Goods')->search($_params, $this->_number, $page);
        }else{
            $result = array('rows'=>array(),'total'=>0);
        }
        if ( !empty($result['rows']) ){            
            $classGroup = $this->load('search')->getClassGroup();
            list($_class, $_group) = $classGroup;
            $this->set('_CLASS', $_class);//分类
            $this->set('_GROUP', $_group);//群组
        }

        $this->set('list', $result);
        $this->display();
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