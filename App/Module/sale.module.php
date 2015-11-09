<?
/**
 * 交易商标
 *
 * 交易商标
 * 
 * @package	Module
 * @author	Xuni
 * @since	2015-11-05
 */
class SaleModule extends AppModule
{
	public $class   = array();
	
	/**
     * 引用业务模型
     */
    public $models = array(
        'sale'			=> 'sale',
		'member'		=> 'member',
		'proposer'		=> 'proposer',
        'saletrademark' => 'saletrademark',
		'group'			=> 'group',
    );
	
	/**
     * 初始化变量值
     * @author	Jeany
     * @since	2015-07-22
     */
    public function __construct()
    {
      
		$this->classes = C('CLASSES');
    }
		
    /**
     * 通过条件查询商标信息--首页使用
     * 
     * @author  Jeany
     * @since   2015-11-09
     *
     * @access  public
     * @param   string  $param      查询条件
     * @param   int     $num        查询数量
     *
     * @return  array   $data       查询数据
     */
	public function getSaleList($param, $num ,$c=1 , $page=1)
	{
		
		if($param){
			foreach($param as $key => $val){
				if($key == 'notId'){
					$r['notIn']  = array('id'=>$val);//可出售商标
				}else{
					$r['ft'][$key] = $val;
				}	
			}
		}
		$r['eq']['area']  = 1;//可出售商标
		$r['raw']  = "status not in(2,3,4,6)";
		$r['page']        	= $page;
        $r['limit']         = $num;
        $r['order']         = array('type' => 'asc','date' => 'desc');
        $data = $this->import('sale')->findAll($r);
        foreach($data['rows'] as $k => $item){
			$data['rows'][$k]['imgurl'] = $this->load('saletrademark')->getImg($item['id']); 
			$data['rows'][$k]['url'] = "/trademark/view/?id=".$item['id']."&c=".$c; 
            $data['rows'][$k]['source'] = isset( $this->source[$item['source']] ) ? $this->source[$item['source']] : $item['source'];
			$data['rows'][$k]['classes']  = isset( $this->classes[$item['class']] ) ? $this->classes[$item['class']] : $item['class'];
			$data['notId'][] = $item['id'];
        }
		return $data;
	}

}
?>