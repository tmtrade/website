<?
/**
 * 应用公用表单组件
 *
 * 表单数据收集
 *
 * @package	Form
 * @author	Xuni
 * @since	2015-11-11
 */
class TrademarkaddsellForm extends AppForm
{
	
	/**
     * 字段映射(建立表单字段与程序字段或数据表字段的关联)
     */
    protected $map = array(
        'name'  	=> array( 'field' => 'name', 'method' => 'fieldString', ),
        'price'    	=> array( 'field' => 'price', 'method' => 'fieldInt', ),
        'contact'   => array( 'field' => 'contact', 'method' => 'fieldInt', ),
        'phone'  	=> array( 'field' => 'phone', 'method' => 'fieldInt', ),
    );

    /**
     * 处理字符串
 	 * @author	Xuni
 	 * @since	2015-11-11
     *
     * @access	public
     * @param	array	$value	字符串
     * @return	string
     */
    public function fieldString($value)
    {
        return empty($value) ? '' : htmlspecialchars(trim($value));
    }

    /**
     * 处理数字
 	 * @author	Xuni
 	 * @since	2015-11-11
     *
     * @access	public
     * @param	array	$value	字符串
     * @return	string
     */
    public function fieldInt($value)
    {
        return intval(trim($value));
    }
}
?>