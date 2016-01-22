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
	// public $caches  	= array('index');
	// public $cacheId  	= 'redis';
	// public $expire  	= 36000;

	public function index()
	{
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
		$news['page']	= $this->load('faq')->newsList(50, 0, 5);
		$news['faq']	= $this->load('faq')->newsList(45, 0, 5);
		$news['link']	= $this->load('faq')->newsList(47, 0, 10);

		$this->set('offpriceList', $this->load('internal')->getIndexOffprice());
		
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

	public function newlist()	
	{
		$news['page']	= $this->load('faq')->newsList(50, 0, 5);
		$news['faq']	= $this->load('faq')->newsList(45, 0, 5);
		echo json_encode($news);
		exit;
	}
	public function links()
	{
		$link	= $this->load('faq')->newsList(47, 0, 10);
		echo json_encode($link);
		exit;
	}
}
?>