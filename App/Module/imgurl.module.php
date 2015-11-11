<?
/**
 * 商标图像
 *
 * 商标图像
 * 
 * @package	Module
 * @author	martin
 * @since	2015-07-22
 */
class ImgurlModule extends AppModule
{
	/**
	 * 消息模版
	 */
	public $models = array(
		'imgurl' => 'imgurl',
	);


	/**
	 * 获取商标图像
	 * @author	martin
	 * @since	2015-07-22
	 *
	 * @access	public
	 * @param	int		$tId	商标id
	 * @return	array
	 */
	public function getUrl( $trademarkId)
	{
		if( empty($trademarkId) ){
			return "/Static/images/img1.png";
		}
		$w['eq']			= array('trademark_id' => $trademarkId);
		$w['col']			= array('url');
		$w['limit']			= 1;
		$data				= $this->import("imgurl")->find($w);
		
		if ( !empty( $data['url'] )){
			$url = $data['url'];
		}else{
			$url = '/Static/images/img1.png';
		}
		
		return $url;
	}
}
?>