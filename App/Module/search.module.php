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
     * 获取国际分类包含的所有群组信息
     * (只包含群组号与中文名称)
     * 
     * @author  Xuni
     * @since   2015-11-05
     *
     * @access  public
     * @param   int     $class      国际分类(1-45)
     *
     * @return  array   $list       群组号对应群组中文名称的数组
     */
    public function search($params, $start=0, $limit=18)
    {
        

        $r['col']   = array('group', 'cn_name');
        $r['eq']    = array('class'=>$class);
        $r['limit'] = 100;
        $data = $this->import('group')->find($r);
        $list = arrayColumn($data, 'cn_name', 'group');
        return $list;
    }

    public function getSaleList($params, $start, $limit)
    {
        if ( !empty($params['keyword']) ){
            $r['like'] = array('name'=>$params['keyword']);
        }
        if ( !empty($params['class']) ){
            $r['eq'] = array('class'=>$params['class']);
        }
        if ( !empty($params['group']) ){
            $r['ft']['group'] = $params['group']);
        }
        if ( !empty($params['platform']) ){
            $r['ft']['platform'] = $params['platform']);
        }

        
    }

    public function getTmList()
    {

    }
	
}
?>