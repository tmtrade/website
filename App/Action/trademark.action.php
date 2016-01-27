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
	public function view()
	{
		$tid 	= $this->input("tid","int");
		$class 	= $this->input("class","int");

		if ( empty($tid) || empty($class) ){
			$this->redirect('未找到页面', '/index/error/');
		}

		//$info = $this->load('trademark')->getInfo($tid, array('`id` as `number`','`class`'));
		//if ( empty($info) || empty($info['number']) ){
		//	$this->redirect('未找到页面1', '/index/error/');
		//}else
		if ( !in_array($class, range(1,45))  ){
			$this->redirect('未找到页面2', '/index/error/');
		}

		$this->redirect('', '/d-'.$tid.'-'.$class.'.html');
	}

}
?>