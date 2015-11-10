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
        $saleList = $this->getSaleList($params, $start, $limit);
        if ( count($saleList) == $limit ){

        }
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
            $r['like'] = array('name'=>$params['keyword']);
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
        $r['group'] = array('tid'=>'asec');
        $r['index'] = array($start, $limit);
        $r['col']   = array('tid', 'number', 'class');
        
        $res    = $this->import('sale')->find($r);
        $list   = $this->load('sale')->getListTips($res);
        return $list;
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
    public function getTmList()
    {
        $res = $this->searchLike('五粮液');

        header("Content-type: text/html; charset=utf-8");
        debug($res);
    }

    public function searchLike($keyword, $class='', $page=1, $number=100)
    {
        if ( empty($keyword) ) return array();

        $params = array(
            'keyword'   => $keyword,
            );
        if ($class > 0 && $class <= 45){
            $params['class'] = $class;
        }

        $res = $this->importBi('trademark')->searchLike($params, $page, $number);
        return $res;
    }
	
}
?>