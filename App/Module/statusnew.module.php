<?
/**
 * 申请人
 *
 * 获取出售信息，添加出售信息
 * 
 * @package	Module
 * @author	JEANY
 * @since	2015-09-15
 */
class StatusnewModule extends AppModule
{
    /**
     * 引用业务模型
     */
    public $models = array(
        'statusnew'		=> 'Statusnew',
        );


	/**
	 * 获取代理人信息
	 * @author	Xuni
	 *
	 * @access	public
	 * @param	array 	$id			申请人newID
	 *
	 * @return	array
	 */
    public function getStatusnew($number,$class,$name)
    {
    	$r['eq'] = array('trademark_id'=>$number,"class"=>$class);
		$r['limit'] = 100;
		$data = $this->import('statusnew')->findAll($r);
		$result = array();
		if($data['rows']){
			foreach($data['rows'] as $k => $v){
				$result[] = "【".$v['status']."】" .$v['date']. " 商标【".$name."】（商标号：".$number."）".$v['status'];
			}		
		}
    	return $result;
    }

	
}
?>