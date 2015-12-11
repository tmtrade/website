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
    public $pageTitle   = '3.5秒快速查找 - 一只蝉';
    private $_number    = 30;

	public function getTitle($data)
	{	
		$titleArr  = array();
		if(!empty($data['class'])){
			$titleArr[] = $data['class']."类";
		}
		if(!empty($data['group'])){
			$titleArr[] = $this->load('group')->getGroupName($data['group']);
		}
		if(!empty($data['types'])){
			$typeconfig	= C('TYPES');
			$titleArr[] = $typeconfig[$data['types']];
		}
		if(!empty($data['sblength'])){
			$sbconfig	= C('SBNUMBER');
			$titleArr[] = $sbconfig[$data['sblength']];
		}
		if(!empty($data['platform'])){
			$sbplatform	= C('PLATFORM_IN');
			$titleArr[] = $sbplatform[$data['platform']];
		}
		if(!empty($data['keyword'])){
			$keyword = "";
			$titleArr[] = $data['keyword'];
		}
		$titleArr[] = "商标转让|买卖|交易 – 一只蝉";
		return implode("_", $titleArr);
	}
	
	public function index()
	{
        $keyword    = $this->input('kw', 'string', '');
        $class      = $this->input('c', 'int', 0);
        $group      = $this->input('g', 'string', 0);
        $platform   = $this->input('p', 'int', 0);
        $start      = $this->input('n', 'int', 0);
        $type       = $this->input('t', 'int', 0);
        $number     = $this->input('sn', 'int', 0);

        if( $group > 0 && strlen($group) == 4 && !in_array($class, range(1, 45))){
            $class = (int)substr($group, 0, 2);
        }
        if ( $class > 0 ){
            $groupList = $this->load('group')->getClassGroups($class);
            $this->set('groupList', $groupList);
        }
        $params = array(
            'keyword'   => $keyword,
            'class'     => $class,
            'group'     => $group,
            'platform'  => $platform == 99 ? 0 : $platform,//99自营为全部0
            'types'     => $type,
            'sblength'  => $number,
            );
        $res        = $this->load('search')->search($params, $start, $this->_number);
        $strArr     = $this->getFormData();
        $whereStr   = http_build_query($strArr);
        //debug($whereStr);
		//设置页面TITLE
		$this->set('TITLE', $this->getTitle($params));
        $this->set('c', $class);
        $this->set('kw', $keyword);
        $this->set('g', $group);
        $this->set('p', $platform);
        $this->set('t', $type);
        $this->set('sn', $number);
        $this->set('searchList', $res);
        $this->set('has', empty($res) ? false : true);
        $this->set('_number', $this->_number);
        $this->set('whereStr', $whereStr);
		$this->display();
	}

    public function getMore()
    {
        $keyword    = $this->input('kw', 'string', '');
        $class      = $this->input('c', 'int', 0);
        $group      = $this->input('g', 'string', 0);
        $platform   = $this->input('p', 'int', 0);
        $start      = $this->input('n', 'int', 0);
        $type       = $this->input('t', 'int', 0);
        $number     = $this->input('sn', 'int', 0);

        $params = array(
            'keyword'   => urldecode($keyword),
            'class'     => $class,
            'group'     => $group,
            'platform'  => $platform == 99 ? 0 : $platform,//99自营为全部0
            'types'     => $type,
            'sblength'  => $number,
            );
        $list   = $this->load('search')->search($params, $start, $this->_number);
        
        $this->set('searchList', $list);
        $this->display();
    }

}
?>