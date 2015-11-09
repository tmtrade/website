<?
/**
 * 无
 *
 * 无
 * 
 * @package	Module
 * @author	Xuni
 * @since	2015-11-09
 */
class GroupModule extends AppModule
{
    /**
     * 引用业务模型
     */
    public $models = array(
        'group' => 'group',
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
    public function getClassGroups($class)
    {
        if ($class <= 0 || $class > 45) return array();

        $r['col']   = array('group', 'cn_name');
        $r['eq']    = array('class'=>$class);
        $r['limit'] = 100;
        $data = $this->import('group')->find($r);
        $list = arrayColumn($data, 'cn_name', 'group');
        return $list;
    }
	
}
?>