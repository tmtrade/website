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
		$actionName = 'article';
		$funName 	= 'getArticleList';
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

		$client 	= new Yar_client( WM_HOST .  $actionName);
		$data 		= $client->$funName($param);
		if(!empty($data['rows'])){
			$list = $this->getList($data['rows'],'cfdt');
		}
		return $list;
	}
	
}
?>