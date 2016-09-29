<?
/**
 *
 * 商标打包页
 * @package Action
 * @author  Dower
 * @since   2016-03-08
 */
class PackageAction extends AppAction
{
	public $ptype = 19;

	/**
	 * 商标详情
	 */
	public function index(){
		//得到打包信息
		$id = $this->input('id','int');
		$all = $this->load('pack')->getAll($id);
		if(!$all){
			$this->redirect('未找到页面', '/index/error');
		}
		//得到推荐商标
		$tj = $this->load('faq')->getTm(3);
		//得到用户订单的need字段
		$need = "打包商标id:".$id.",打包名:{$all['title']}";
		//加载页面
		$this->set("need", $need);
		$this->set('all',$all);
		$this->set('tj',$tj);
		$this->display();
	}

}