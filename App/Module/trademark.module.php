<?
/**
 * 商标相关信息
 *
 * 查询商标基础信息、图片、状态等
 * 
 * @package	Module
 * @author	Xuni
 * @since	2015-10-22
 */
class TrademarkModule extends AppModule
{
	public $models = array(
		'img'		=> 'imgurl',
		'tm'		=> 'trademark',
		'second'	=> 'secondstatus',
		'third'		=> 'statusnew',
		'proposer'	=> 'proposer',
	);
	
	/**
	 * 通过商标号获取商标信息
	 */
	public function getTrademarks($number)
	{
		$r['eq']	= array('id' => $number);
		$r['limit']	= 100;
		$data		= $this->import('trademark')->findAll($r);
		if(empty($data)){
			return array();
		}else{
			foreach($data['rows'] as $key => $item){
				
				$data['rows'][$key]['imgUrl'] = $this->load('imgurl')->getUrl($item['tid']);
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

	/**
	 * 通过商标号获取商标信息
	 */
	public function trademarks($number)
	{
		$data = $this->getInfo($number);
		return $data;
	}
	
	
	/**
	 * 通过商标号获取商标信息
	 */
	public function trademarkDetail($item)
	{
		$data['imgurl'] 	= $this->getImg($item['tid']);
		
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

	/**
	 * 获取商标tid（主键）
	 * 
	 * @author	Xuni
	 * @since	2015-10-22
	 *
	 * @access	public
	 * @return	void
	 */
	public function getTid($number, $class)
	{
		$r['eq'] = array(
				'id' 	=> $number,
				'class'	=> $class,
			);
		$r['col'] = array('auto as `tid`');
		$info = $this->find($r);
		return empty($info) ? '' : $info['tid'];
	}

	/**
	 * 获取商标的基础model方法调用
	 * 
	 * @author	Xuni
	 * @since	2015-10-22
	 *
	 * @access	public
	 * @return	void
	 */
	public function find($role)
	{
		return $this->import('tm')->find($role);
	}

	/**
	 * 商标基础信息
	 * 
	 * @author	Xuni
	 * @since	2015-10-22
	 *
	 * @access	public
	 * @return	void
	 */
	public function getInfo($tid, $field = '')
	{
		if ( intval($tid) <= 0 ) return array();

		$r['eq'] 	= array('auto'=>$tid);
		if ( !empty($field) &&  is_array($field) ){
			$r['col'] = $field;
		}

		return $this->import('tm')->find($r);
	}


	/**
	 * 获取商标图片地址
	 * 
	 * @author	Xuni
	 * @since	2015-10-22
	 *
	 * @access	public
	 * @return	void
	 */
	public function getImg($tid)
	{
		$default = '/Static/images/img1.png';
		if ( intval($tid) <= 0 ) return $default;

		$r['eq'] 	= array('tid'=>$tid);
		$img 		= $this->import('img')->find($r);

		return empty($img) ? $default : $img['url'];
	}

	/**
	 * 商标二级状态数据列表
	 * 
	 * @author	Xuni
	 * @since	2015-10-22
	 *
	 * @access	public
	 * @return	void
	 */
	public function getSecond($tid)
	{
		if ( intval($tid) <= 0 ) return array();

		$r['eq'] 	= array('tid'=>$tid);
		$second 	= $this->import('second')->find($r);
		if ( empty($second) ) return array();

		$list 		= array();
		$Seconds 	= C("SecondStatus");
		foreach (range(1, 28) as $v) {
			$key = 'status'.$v;
			if ($second[$key] == 1){
				array_push($list, $Seconds[$v]);
			}
		}
		return $list;
	}

	/**
	 * 商标三级状态数据列表
	 * 
	 * @author	Xuni
	 * @since	2015-10-22
	 *
	 * @access	public
	 * @return	void
	 */
	public function getThird($tid)
	{
		$r['eq'] 	= array('tid'=>$tid);
		$r['order']	= array('date'=>'desc');
		$r['limit'] = 100;

		return $this->import('third')->find($r);
	}
	
}
?>