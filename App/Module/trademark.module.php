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
		$r['eq']	= array('auto' => $number);
		$r['limit']	= 1;
		$data		= $this->import('trademark')->find($r);
		return $data;
	}
	
	
	/**
	 * 通过商标号获取商标信息
	 */
	public function trademarkDetail($item)
	{
		
		$data['imgurl'] = $this->load('imgurl')->getUrl($item['id']);
		$w['eq']			= array('id' => $item['proposer_id']);
		$w['limit']			= 1;
		$proposer			= $this->import('proposer')->find($w);
		$data['newId']    	= $proposer['newId'];
		$data['proposer']	= $proposer['name'];
		$status				= $this->load("secondstatus")->details($item['id'], $item['class']);
		$data['status']		= $status;
		$data['goods']		= $item['goods'];
		return $data;
	}
	
	


}
?>