<?
/**
 * 商标查询接口
 *
 * 对商标名称进行近似查询
 *
 * @package	Bi
 * @author	Xuni
 * @since	2015-11-10
 */
class TrademarkBi extends Bi
{
	/**
	 * 接口标识
	 */
	//public $apiId = 2;

	/**
	 * 近似查询商标
	 *
	 * @param	array	$param		提交的参数(keyword,classId)
	 * @param	string	$method		0为GET、1为POST
	 * 
	 * @return	array 	$data
	 */
	public function searchLike(array $params, $page=1, $num=20, $method=1)
	{
		if ( empty($params['keyword']) ) return array();

		$params['key'] 	= SEARCH_KEY;
		$params['page'] = $page > 0 ? $page : 1;
		$params['num'] 	= ($num > 0 && $num < 5000) ? $num : 20;

		$tmp 			= $params;
		array_walk($tmp, array($this, 'makeParams'));
		$param 			= implode('&', $tmp);

		$res = $this->httpRequest($param, $method);
		return json_decode($res, true);
	}

	private function makeParams(&$item, $key)
	{
		$item = $key.'='.$item;
	}
	
	/**
	 * 模拟HTTP请求
	 *
	 * @param	string	$param		提交的参数
	 * @param	string	$method		0为GET、1为POST
	 * @param	int		$timeout	超时时间（秒）
	 * @return	string
	 */
	private function httpRequest($param = '', $method = 1, $timeout = 10)
	{
		$userAgent 	= "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
		$ch        	= curl_init();

		$url 		= $method == 0 ? 'trademark/search/?'.$param : 'trademark/search/';
		curl_setopt($ch, CURLOPT_URL, SEARCH_URL.$url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		if ( $method == 1 ) {
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param); 
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt( $ch,CURLOPT_HTTPHEADER, array(
		'Accept-Language: zh-cn',
		'Connection: Keep-Alive',
		'Cache-Control: no-cache',
		));
		$document = curl_exec($ch); 
		$info     = curl_getinfo($ch); 
		if ( $info['http_code'] == "405" ) {
			curl_close($ch);
			return 'error';
		}
		curl_close($ch);
		return $document;
	}

}
?>