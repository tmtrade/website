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
		$tag = $this->input('short', 'string', '');
		if ( $tag ){
			if ( strpos($tag, '-') === false ) $this->redirect('未找到页面', '/index/error');
			list($tid, $class) = explode('-', $tag);
		}else{
			$tid 	= $this->input("tid","int");
			$class 	= $this->input("class","int");
		}

		if ( empty($tid) || empty($class) ){
			$this->redirect('未找到页面', '/index/error/');
		}

		$info = $this->load('trademark')->getInfo($tid, array('`id` as `number`','`class`'));
		if ( empty($info) || empty($info['number']) ){
			$this->redirect('未找到页面', '/index/error/');
		}elseif ( !in_array($class, range(1,45)) ){
			$this->redirect('未找到页面', '/index/error/');
		}

		$this->redirect('', '/goods-'.$info['number'].'.html');
	}

}
?>