<?
/**
 * 商标详情界面
 *
 * 商标详情界面
 *
 * @package Action
 * @author  Jeany
 * @since   2015-11-10
 */
class TrademarkAction extends AppAction
{
	public function index()
	{
		
		$this->display();
	}
	
	public function view()
	{
		$id = "4497";
		//$number     = $this->input("number","sting");
		//$data       = $this->load("trademark")->trademarks($number);
        $data    = $this->load("sale")->getDetail($id);
		$detail  = $this->load("sale")->getTrademarkDetail($id);
		
		$this->set("platformIn",C('PLATFORM_IN'));
		
		//读取推荐商标
		$tj  = $this->load("sale")->getDetailtj($data['class'],6,$data['id']);
		
		$this->set("data",$data);
		$this->set("detail",$detail);
		$this->set("tj",$tj);
		$this->display();
	}
}
?>