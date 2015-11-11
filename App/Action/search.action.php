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
    private $_number = 30;

	public function index()
	{
        $keyword    = $this->input('kw', 'string', '');
        $class      = $this->input('c', 'int', 0);
        $group      = $this->input('g', 'int', 0);
        $platform   = $this->input('p', 'int', 0);
        $start      = $this->input('n', 'int', 0);

        $strArr = $this->getFormData();

        if ( $class > 0 ){
            $groupList = $this->load('group')->getClassGroups($class);
            $this->set('groupList', $groupList);
        }
        //if ($keyword || $class || $group){
        $params = array(
            'keyword'   => $keyword,
            'class'     => $class,
            'group'     => $group,
            'platform'  => $platform,
            );
        $res = $this->load('search')->search($params, $start, $this->_number);
        //}

        $whereStr = http_build_query($strArr);

        $this->set('c', $class);
        $this->set('kw', $keyword);
        $this->set('g', $group);
        $this->set('p', $platform);
        $this->set('searchList', $res);
        $this->set('_number', $this->_number);
        $this->set('whereStr', $whereStr);
		$this->display();
	}

    public function getMore()
    {
        $keyword    = $this->input('kw', 'string', '');
        $class      = $this->input('c', 'int', 0);
        $group      = $this->input('g', 'int', 0);
        $start      = $this->input('n', 'int', 0);

        $params = array(
            'keyword'   => $keyword,
            'class'     => $class,
            'group'     => $group,
            );
        $list   = $this->load('search')->search($params, $start, $this->_number);
        
        $this->set('searchList', $list);
        $this->display();
    }

    private function makeParams(&$item, $key)
    {
        $item = $key.'='.$item;
    }
}
?>