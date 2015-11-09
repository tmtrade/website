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
class SearchAction extends AppAction
{
	public function index()
	{
        $keywords   = $this->input('kw', 'string', '');
        $class      = $this->input('c', 'int', 0);
        $group      = $this->input('g', 'int', 0);
        $platform   = $this->input('p', 'int', '');

        if ( $class > 0 ){
            $groupList = $this->load('group')->getClassGroups($class);
            $this->set('groupList', $groupList);
        }

        $this->set('c', $class);
        $this->set('kw', $keywords);
        $this->set('g', $group);
        $this->set('p', $platform);
		$this->display();
	}
}
?>