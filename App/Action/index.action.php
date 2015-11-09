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
		$paramTM['platform'] = "2";
		$dataTM = $this->load('sale')->getSaleList($paramTM,8);
		//京东
		$paramJD['platform'] = "1";
		$paramJD['notId']    = $dataTM['notId'];
		$dataJD = $this->load('sale')->getSaleList($paramJD,8);
		//大型超市
		$paramDXCS['platform'] = "7";
		$paramDXCS['notId']    = $dataJD['notId'];
		$dataDXCS = $this->load('sale')->getSaleList($paramDXCS,8);
		
		//潜力
		$paramQL['label'] = "3";
		$dataQL = $this->load('sale')->getSaleList($paramQL,8);
		//精品
		$paramJP['label'] = "1,3";
		$paramJP['notId'] = $dataQL['notId'];
		$dataJP = $this->load('sale')->getSaleList($paramJP,8);
		//特色
		$paramTS['label'] = "2,3";
		$paramTS['notId'] = $dataJP['notId'];
		$dataTS = $this->load('sale')->getSaleList($paramTS,8);
		
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