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
class FaqModule extends AppModule
{
	public $source = array();
        public $status   = array();
	public $area   = array();
	public $type   = array();
	public $class   = array();
	public $offPrice   = array();
	public $saletype   = array();
	public $platform   = array();
	public $label   = array();
	public $sblength   = array();
	
	/**
     * 引用业务模型
     */
    public $models = array(
        'sale'			=> 'sale',
		'tminfo'        => 'saleTminfo',
		'member'		=> 'member',
		'proposer'		=> 'proposer',
        'saletrademark' => 'saletrademark',
		'group'			=> 'group',
    );
	

	//调用对应接口
	public function getList($array,$act)
	{
		foreach($array as $k => $v){
			$len 		= mb_strlen($v['title'],'utf-8');
			$thumtitle	= $len > 20 ? mb_substr($v['title'],0,20,'utf-8').'...' : $v['title'];
			$list[$k]['url'] 				= '/faq/views/?id='.$v['id'].'&act='.$act.'&c='.$v['categoryId'];
			$list[$k]['thumtitle']		= $thumtitle;	
			$list[$k]['title']			= $v['title'];
			$list[$k]['img']			= $v['attachmentId'];
			$list[$k]['content']		= $v['content'];
			$list[$k]['title']			= $v['title'];
			$list[$k]['keyword']		= $v['keyword'];
			$list[$k]['introduction']	= $v['introduction'];
			$list[$k]['id']				= $v['id'];
			$list[$k]['time']		= $v['updated'];//发布时间->更新时间
		}
		return $list;
	}


	

	//得到栏目对应的文章
	public function newsList($param)
	{
		$c			= isset($param['c']) ? $param['c'] : 0;
		$id			= isset($param['id']) ? $param['id'] : 0;
		$limit		= isset($param['limit']) ? $param['limit'] : 20;
		$minId		= isset($param['minId']) ? $param['minId'] : 0;
		$maxId		= isset($param['maxId']) ? $param['maxId'] : 0;
		$page		= isset($param['page']) ? $param['page'] : 0;

		$list		= array();
		$param		= array(
			'maxId' 		=> $maxId,
			'minId' 		=> $minId,
			'limit' 		=> $limit,
			'order' 		=> array('showOrder' => 'DESC'),
			'page' 			=> $page,
		);
		if(!empty($c)){
			$param['categoryId'] = $c;
		}
		if(!empty($id)){
			$param['id'] = $id;
		}
		//调用接口
		$data = $this->importBi('faq')->getNewsList($param);

		if(!empty($data['rows'])){
			$list = $this->getList($data['rows'],'cfdt');
		}
		return $list;
	}

	/**
	 * 得到销售中的商标
	 * @param $limit
	 * @return array
	 */
	public function getTm($limit){
		$r['eq']    = array('status'=>1);
		$rand       = rand(0, 6666);
		$r['index'] = array($rand, $limit);
		$res = $this->import('sale')->find($r);
		$res = $this->load('search')->getListTips($res);//处理数据
		return $res;
	}

	/**
	 * 得到3条文章
	 * @param $param
	 * @return mixed
	 */
	public function getNextThree($param){
		$me['order'] = array('showOrder' => 'DESC');
		$me['minId'] = isset($param['id']) ? $param['id'] : 0;
		$me['c'] = isset($param['c']) ? $param['c'] : 0;
		$me['limit'] = 2;
		$data1 = $this->importBi('faq')->getNewsList($me);
//		if(count($data1['rows'])==2){
//			unset($data1['rows'][1]);
//		}
		$me['maxId'] = isset($param['id']) ? $param['id'] : 0;
		$data2 = $this->importBi('faq')->getNewsList($me);
		$data = array_merge($data1['rows'],$data2['rows']);

		if(!empty($data)){
			$list = $this->getList($data,'cfdt');
		}
		return $list;
	}
}
?>