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
	public function index()
	{
		
		//天猫
		$paramTM = array('platform' => 2,'label' => '4');
		$dataTM = $this->load('sale')->getSaleList($paramTM,8);
		
		//京东
		$notIdArr = $dataTM['notId'];
		$paramTM = array('platform' => 1,'label' => '4');
		if (!empty($dataTM['notId'])) $paramJD['notId']    = $notIdArr;
		$dataJD = $this->load('sale')->getSaleList($paramJD,8);
		
		
		if($notIdArr && $dataJD['notId']){
			$notIdArr = array_merge_recursive($notIdArr, $dataJD['notId']);  
		}elseif($dataJD['notId']){
			$notIdArr = $dataJD['notId'];
		}
		$paramTM = array('platform' => 7,'label' => '4');
		if (!empty($dataJD['notId'])) $paramDXCS['notId'] = $notIdArr;
		$dataDXCS = $this->load('sale')->getSaleList($paramDXCS,8);

		
		//潜力
		$paramQL['label'] = "3";
		$dataQL = $this->load('sale')->getSaleList($paramQL,8);
		//精品
		$paramJP['label'] = "1,3";
		if (!empty($dataQL['notId'])) $paramJP['notId'] = $dataQL['notId'];
		$dataJP = $this->load('sale')->getSaleList($paramJP,8);
		//特色
		$paramTS['label'] = "2,3";
		if (!empty($dataJP['notId'])) $paramTS['notId'] = $dataJP['notId'];
		$dataTS = $this->load('sale')->getSaleList($paramTS,8);
		//最新购买需求
		$buyInfo = $this->load('buy')->getNewsBuy(8);
		$this->set('buyInfo',$buyInfo);
		//最新交易记录
		$tradeInfo = $this->load('buy')->getNewsTrade(8);
		$this->set('tradeInfo',$tradeInfo);

		$params = array(
			'isBargain' => '2',
			);
		$offprice = $this->load('search')->getSaleList($params, 0, 4);
		$this->set('offpriceList', $this->load('sale')->getIndexOffprice());
		
		$this->set('dataTM',$dataTM);
		$this->set('dataJD',$dataJD);
		$this->set('dataDXCS',$dataDXCS);
		$this->set('dataQL',$dataQL);
		$this->set('dataJP',$dataJP);
		$this->set('dataTS',$dataTS);
		$this->display();
	}
}
?>