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
		$tid = $this->input("tid","int");
		$class = $this->input("class","int");

		if ( $tid <= 0 || $class <= 0 || !in_array($class, range(1, 45)) ){
			MessageBox::halt('未找到相关数据2！');
		}
		//出售列表里面有的数据
		$data   =  $detail  = array();
        $data    = $this->load("sale")->getDetail($tid);
		$detail  = $this->load("sale")->getTrademarkDetail($data['id']);
		$this->set("platformIn",C('PLATFORM_IN'));
		//原始商标数据里面的数据
		$info = $this->load('trademark')->trademarks($tid,$class);
		$infoDetail = $this->load('trademark')->trademarkDetail($info);
		
		if($info){
			$classes = C('CLASSES') ;
			$info['name'] = $info['trademark'];
			$info['classValue'] = $classes[$info['class']];
		}
		
		if ( empty($info)  && empty($data) ){
			MessageBox::halt('未找到相关数据3！');
		}
		//读取推荐商标
		$tj  = $this->load("sale")->getDetailtj($class,6,$data['id']);
		$data    = $data ? $data : $info  ;
		$detail  = $detail ? $detail : $infoDetail ;
		$this->set("data",$data);
		$this->set("detail",$detail);
		$this->set("info",$info);
		$this->set("tj",$tj);
		$this->display();
	}
}
?>