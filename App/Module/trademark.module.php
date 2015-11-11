<?
/**
 * 商标模块
 *
 * 商标详情
 * 
 * @package	Module
 * @author	martin
 * @since	2015-07-22
 */
class TrademarkModule extends AppModule
{
	/**
	 * 引用业务模型
	 */
	public $models = array(
		'trademark'		=> 'Trademark',
		'proposer'		=> 'proposer',
		'agent'			=> 'agent',
		'secondstatus' => 'secondstatus',
		);

	/**
	 * 通过商标号获取商标信息
	 */
	public function trademarks($number)
	{
		$r['eq']	= array('id' => $number);
		$r['limit']	= 100;
		$data		= $this->import('trademark')->findAll($r);
		if(empty($data)){
			return array();
		}else{
			foreach($data['rows'] as $key => $item){
				
				$data['rows'][$key]['imgUrl'] = $this->load('imgurl')->getUrl($item['id']);
				$w['eq']				= array('id' => $item['proposer_id']);
				$w['limit']				= 1;
				$proposer				= $this->import('proposer')->find($w);
				$data['rows'][$key]['newId']    	= $proposer['newId'];
				$data['rows'][$key]['proposerName']	= $proposer['name'];
				$status					= $this->load("secondstatus")->details($number, $item['class']);
				$data['rows'][$key]['status']		= $status;
			}
		} 
		return $data;
	}
	
	


}
?>