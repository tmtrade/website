<?
/**
 * 网站首页
 *
 * 网站首页
 *
 * @package Action
 * @author  Xuni
 * @since   2015-11-05
 */
class IndexAction extends AppAction
{
 	//public $caches  	= array('index');
	public $cacheId 	= 'redisHtml';
	public $expire  	= 3600;//1小时

	public function index()
	{
		//清除缓存（注意：清除前要关闭缓存配置，否则不会执行到这里）
		//$this->com('qcache')->select(5)->clear();

		//天猫
		$paramTM = array('platform' => 2,'label' => '4');
		$dataTM = $this->load('internal')->getSaleList($paramTM,8);
		//京东
		$notIdArr = $dataTM['notId'];
		$paramJD = array('platform' => 1,'label' => '4');
		if (!empty($dataTM['notId'])) $paramJD['notId']    = $notIdArr;
		$dataJD = $this->load('internal')->getSaleList($paramJD,8);
		
		//大型超市
		if($notIdArr && $dataJD['notId']){
			$notIdArr = array_merge_recursive($notIdArr, $dataJD['notId']);  
		}elseif($dataJD['notId']){
			$notIdArr = $dataJD['notId'];
		}
		$paramDXCS = array('platform' => 7,'label' => '4');
		if (!empty($notIdArr)) $paramDXCS['notId'] = $notIdArr;
		$dataDXCS = $this->load('internal')->getSaleList($paramDXCS,8);
		
		//潜力
		$paramQL['label'] = "2";
		$dataQL = $this->load('internal')->getSaleList($paramQL,8);
		
		//精品
		$paramJP['label'] = array('1','2');
		$notIdArrQL = $dataQL['notId'];
		if (!empty($dataQL['notId'])) $paramJP['notId'] = $notIdArrQL;
		$dataJP = $this->load('internal')->getSaleList($paramJP,8);

		//特色
		if($notIdArrQL && $dataJP['notId']){
			$notIdArrQL = array_merge_recursive($notIdArrQL, $dataJP['notId']);  
		}elseif($dataJP['notId']){
			$notIdArrQL = $dataJP['notId'];
		}
		$paramTS['label'] = array('3','2');
		if (!empty($notIdArrQL)) $paramTS['notId'] = $notIdArrQL;
		$dataTS = $this->load('internal')->getSaleList($paramTS,8);
		
		//最新购买需求
		$buyInfo = $this->load('buy')->getNewsBuy(10);
		$this->set('buyInfo',$buyInfo);
		//最新交易记录
		$tradeInfo = $this->load('buy')->getNewsTrade(10);
		$this->set('tradeInfo',$tradeInfo);

		$params = array(
			'isBargain' => '2',
			);
		$offprice	= $this->load('search')->getSaleList($params, 0, 4);

		$this->set('offpriceList', $this->load('internal')->getIndexOffprice());
		$_news = $this->com('redis')->get('_news_tmp');
		if(empty($_news)){
			$news['page']	= $this->load('faq')->newsList(array('c'=>50,'limit'=>5));
			$news['faq']	= $this->load('faq')->newsList(array('c'=>45,'limit'=>5));
			$news['link']	= $this->load('faq')->newsList(array('c'=>47,'limit'=>20));
			$this->com('redis')->set('_news_tmp', $news, 3600);
		}else{
			$news = $_news;
		}

		$this->set('dataTM',$dataTM);
		$this->set('dataJD',$dataJD);
		$this->set('dataDXCS',$dataDXCS);
		$this->set('dataQL',$dataQL);
		$this->set('dataJP',$dataJP);
		$this->set('dataTS',$dataTS);
		$this->set('classes',C('CLASSES'));
		$this->set('news',$news);
		$this->display();
	}

	public function loginInfo()
	{
		$user = array();
		if ( $this->isAjax() ){
			$user['nickname'] 	= $this->nickname;
			$user['userMobile']	= $this->userMobile;
			$user['isLogin']	= $this->isLogin;
			$this->returnAjax($user);
		}
		$this->returnAjax(array('isLogin'=>false));
	}
        //获取新闻
	public function newlist()	
	{
		$news['page']	= $this->load('faq')->newsList(50, 0, 5);
		$news['faq']	= $this->load('faq')->newsList(45, 0, 5);
		echo json_encode($news);
		exit;
	}
	//获取友情链接
	public function links()
	{
		$link	= $this->load('faq')->newsList(47, 0, 10);
		echo json_encode($link);
		exit;
	}
}
?>