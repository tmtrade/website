<?
/**
 * 网站首页
 *
 * 网站首页
 *
 * @package Action
 * @author  Xuni
 * @since   2016-03-03
 */
class SearchAction extends AppAction
{
    public $pageTitle   = '3.5秒快速查找 - 一只蝉';

    private $_number    = 30;
    private $_searchArr = array();

	
    /**
     * 筛选页功能
     *
     * 处理筛选页筛选功能、不含加载数据
     * 
     * @author  Xuni
     * @since   2016-03-03
     *
     * @return  void
     */
	public function index()
	{
        $page   = $this->input('_p', 'int', 1);//分类
        $params = $this->getSearchParams();
        //处理商标号搜索
        if ( !empty($params['number']) ){
            $this->detail($params['number']);
        }
        if ( empty($params) ){
            $res = array('rows'=>array(),'total'=>0);
        }else{
            $res = $this->load('search')->search($params, $page, $this->_number, 1);
        }
        
        //频道设置
        $channel = $this->load('search')->getChannel($this->mod);
        $this->set('channel', $channel);

        //保存搜索历史，方便搜索框处理
        if ( !empty($res['rows']) ){
            $this->setSearchLog();
        }
        //处理搜索条件
        if ( !empty($this->_searchArr) ){
            foreach ($this->_searchArr as $k => $v) {
                $this->set($k, $v);
                $this->set($k.'_title', $this->getSelectTitle($k, $v, $this->_searchArr));
            }
            $whereStr   = http_build_query($this->_searchArr);
        }
        $classGroup = $this->load('search')->getClassGroup();
        list($_class, $_group) = $classGroup;

		//设置页面TITLE
		//$this->set('TITLE', $this->load('search')->getTitle($params));

        //特价推荐
        $_arr   = array('isOffprice'=>'1');
        $list   = $this->load('search')->getSaleList($_arr, 1, 8);
        $this->set('offpriceList', $list['rows']);

        $this->set('_CLASS', $_class);//分类
        $this->set('_GROUP', $_group);//群组
        $this->set('_DATE', $this->load('search')->getDateList());//注册日期
        $this->set('_NAME', C('SBNAME'));//商标名称
        $this->set('_PLATFORM', C('PLATFORM_IN'));//平台列表
        $this->set('_NUMBER', C('SBNUMBER'));//商标字数
        $this->set('_TYPE', C('TYPES'));//组合类型

        $this->set('searchList', $res['rows']);
        $this->set('total', $res['total']);
        $this->set('has', empty($res['rows']) ? false : true);
        $this->set('_number', $this->_number);
        $this->set('whereStr', $whereStr);
		$this->display();
	}

    /**
     * 获取搜索参数
     *
     * 获取筛选提交的参数并分析
     * 
     * @author  Xuni
     * @since   2016-03-04
     *
     * @return  void
     */
    protected function getSearchParams()
    {
        $keyword    = $this->input('kw', 'string', '');//搜索项
        $keytype    = $this->input('kt', 'int', '');//搜索项类型 1：商标名称，2：商标号，3：适用服务

        $class      = $this->input('c', 'string', '');//分类
        $group      = $this->input('g', 'string', '');//群组
        $name       = $this->input('n', 'string', '');//商标名称
        $type       = $this->input('t', 'string', '');//组合类型
        $length     = $this->input('sn', 'string', '');//商标字数
        $date       = $this->input('d', 'string', '');//注册日期
        $platform   = $this->input('p', 'string', '');//平台

        $_class     = $this->strToArr($class);
        $_group     = $this->strToArr($group);
        //$_name      = $this->strToArr($name);
        $_type      = $this->strToArr($type);
        $_length    = $this->strToArr($length);
        //$_date      = $this->strToArr($date);
        $_platform  = $this->strToArr($platform);

        $params = array(
            'status' => 1,
        );
        $this->_searchArr['kw'] = $keyword = urldecode($keyword);
        //未搜索关键词
        if ( $keyword == '' ){
            //类别
            if ( !empty($_class) && count($_class) != 45 ){
                $params['class'] = implode(',', $_class);
                $this->_searchArr['c'] = $params['class'];
                //类别包含的群组
                if ( count($_class) == 1 && !empty($_group) ){
                    $_cls = intval($params['class']);
                    list(,$group_) = $this->load('search')->getClassGroup($_cls, 1);
                    if ( count($group_[$_cls]) != count($_group) ){
                        $params['group'] = implode(',', $_group);
                    }
                    $this->_searchArr['g'] = implode(',', $_group);
                }
            }
            //组合类型
            if ( !empty($_type) ){
                $params['type'] = implode(',', $_type);
                $this->_searchArr['t'] = $params['type'];
                if ( count($_type) == count(C('TYPES')) ){
                    unset($params['type']);
                }
            }
            //商标字数
            if ( !empty($_length) ){
                $params['length'] = implode(',', $_length);
                $this->_searchArr['sn'] = $params['length'];
                if ( count($_length) == count(C('SBNUMBER')) ){
                    unset($params['length']);
                }
            }
            //高级筛选，注册日期
            if ( !empty($date) ){
                //$params['date'] = implode(',', $_date);
                $params['date']         = $date;
                $this->_searchArr['d']  = $params['date'];
            }
            //高级筛选，平台入驻
            if ( !empty($_platform) ){
                $params['platform'] = implode(',', $_platform);
                $this->_searchArr['p'] = $params['platform'];
                if ( count($_platform) == count(C('PLATFORM_IN')) ){
                    unset($params['platform']);
                }
            }
            return $params;
        }
        //有关键词，无搜索项类型或搜索项类型为3：适用服务
        if ( empty($keytype) || $keytype == 3 || !in_array($keytype, range(1,3)) ){
            $res = $this->load('search')->getServiceCondition($keyword);
            //适用服务无数据并没有搜索项（走商标名称搜索）
            if ( empty($res) && $keytype != 3 ){
                $params['keytype']  = 1;
                $params['name']     = $keyword;
                $params['_name']    = 2;
                $this->_searchArr['n'] = 2;
                $this->_searchArr['kt'] = 1;
                return $params;
            }
            $params['keytype']      = 3;
            $this->_searchArr['kt'] = $params['keytype'];

            //选择搜索项为3时，无数据直接返回空，没有相关数据可查询
            if ( $keytype == 3 && empty($res) ) return array();

            //判断适用服务是否可用
            if ( count($res['class']) > 0 ){
                $params['class'] = implode(',', $res['class']);
                if ( !empty($res['group']) ){
                    $params['group'] = implode(',', $res['group']);
                }
            }else{
                return array();
            }

            //组合类型
            if ( !empty($_type) ){
                $params['type'] = implode(',', $_type);
                $this->_searchArr['t'] = $params['type'];
                if ( count($_type) == count(C('TYPES')) ){
                    unset($params['type']);
                }
            }
            //商标字数
            if ( !empty($_length) ){
                $params['length'] = implode(',', $_length);
                $this->_searchArr['sn'] = $params['length'];
                if ( count($_length) == count(C('SBNUMBER')) ){
                    unset($params['length']);
                }
            }
            //高级筛选，注册日期
            if ( !empty($date) ){
                //$params['date'] = implode(',', $_date);
                $params['date']         = $date;
                $this->_searchArr['d']  = $params['date'];
            }
            //高级筛选，平台入驻
            if ( !empty($_platform) ){
                $params['platform'] = implode(',', $_platform);
                $this->_searchArr['p'] = $params['platform'];
                if ( count($_platform) == count(C('PLATFORM_IN')) ){
                    unset($params['platform']);
                }
            }

            return $params;
        }elseif ( $keytype == 1 ){//有关键词，搜索项类型为1：商标名称
            $params['name'] = $keyword;
            //类别
            if ( !empty($_class) ){
                $params['class'] = implode(',', $_class);
                $this->_searchArr['c'] = $params['class'];
                //类别包含的群组
                if ( count($_class) == 1 && !empty($_group) ){
                    $params['group'] = implode(',', $_group);
                    //unset($params['class']);
                }
                $this->_searchArr['g'] = $params['group'];
            }
            //商标名称筛选
            if ( !empty($name) ){
                //$params['_name'] = implode(',', $_name);
                $params['_name'] = $name;
                $this->_searchArr['n'] = $params['_name'];
            }else{
                $params['_name'] = 2;
                $this->_searchArr['n'] = 2;
            }
            //组合类型
            if ( !empty($_type) ){
                $params['type'] = implode(',', $_type);
                $this->_searchArr['t'] = $params['type'];
                if ( count($_type) == count(C('TYPES')) ){
                    unset($params['type']);
                }
            }
            //商标字数
            if ( !empty($_length) ){
                $params['length'] = implode(',', $_length);
                $this->_searchArr['sn'] = $params['length'];
                if ( count($_length) == count(C('SBNUMBER')) ){
                    unset($params['length']);
                }
            }
            //高级筛选，注册日期
            if ( !empty($date) ){
                //$params['date'] = implode(',', $_date);
                $params['date']         = $date;
                $this->_searchArr['d']  = $params['date'];
            }
            //高级筛选，平台入驻
            if ( !empty($_platform) ){
                $params['platform'] = implode(',', $_platform);
                $this->_searchArr['p'] = $params['platform'];
                if ( count($_platform) == count(C('PLATFORM_IN')) ){
                    unset($params['platform']);
                }
            }
            $this->_searchArr['kt'] = $keytype;

            return $params;
        }elseif ( $keytype == 2 ){//有关键词，搜索项类型为2：商标号
            $params['number']       = $keyword;
            $this->_searchArr['kt'] = $keytype;
            return $params;
        }
        return $params;
    }

    /**
     * 获取搜索项显示标题
     *
     * 处理单个搜索项的中文显示标题
     * 
     * @author  Xuni
     * @since   2016-03-07
     *
     * @return  void
     */
    protected function getSelectTitle($title, $value, $all)
    {
        if ( empty($value) || empty($title) ) return $value;

        $_arr = array_filter( explode(',', $value) );
        if ( empty($_arr) ) return $value;

        $_str = '';
        switch ($title) {
            case 'kw':
                $S      = C('SBSEARCH');
                $kt     = intval($all['kt']) <= 0 ? 1 : intval($all['kt']);
                $_str   = $S[$kt].':'.$value;
                break;
            case 'c':
                list($cArr,) = $this->load('search')->getClassGroup(0, 0);
                foreach ($_arr as $v) {
                    $_str .= "$v-".$cArr[$v].'|';
                }
                break;
            case 'g':
                if ( empty($all['c']) ) return $value;
                list(,$gArr) = $this->load('search')->getClassGroup(0, 1);
                foreach ($_arr as $v) {
                    $_str .= "$v-".$gArr[$all['c']][$v].'|';
                }
                break;
            case 't':
                $T = C('TYPES');
                foreach ($_arr as $v) {
                    $_str .= "$v-".$T[$v].'|';
                }
                break;
            case 'p':
                $P = C('PLATFORM_IN');
                foreach ($_arr as $v) {
                    $_str .= $P[$v].'|';
                }
                break;
            case 'sn':
                $N      = C('SBNUMBER');
                $_hasN  = false;
                foreach ($_arr as $v) {
                    if ( $v == '1' || $v == '2' ){
                        $_hasN = true;
                    }else{
                        $_str .= $N[$v].'|';
                    }
                }
                if ( $_hasN ) $_str = $N['1,2'].' '.$_str;
                break;
            case 'd':
                $D      = $this->load('search')->getDateList();
                $_str   = $D[$value];
                break;
        }
        return $_str;
    }

    /**
     * 商标号查询
     *
     * 处理商标号查询的显示与跳转
     * 
     * @author  Xuni
     * @since   2016-03-10
     *
     * @return  void
     */
    protected function detail($number)
    {
        $res = $this->load('search')->getNumberInfo($number);
        if ( $res['code'] == '1' ){
            //保存搜索历史，方便搜索框处理
            $this->setSearchLog($number, 2);
            $this->redirect('', '/d-'.$res['tid'].'-'.$res['class'].'.html');
            exit;
        }
        //分类显示标题
        $classGroup = $this->load('search')->getClassGroup(0,0);
        list($_class,) = $classGroup;
        $this->set('_CLASS', $_class);//分类
        //商标号错误
        if ( $res == '3' ){
            $title  = '抱歉！没找到相关信息';
            $params = array('isOffprice'=>'1');
            $list    = $this->load('search')->getSaleList($params, 1, 6);
            $url    = "/offprice";
        }else{//商标不出售
            $title  = '抱歉！此商标不出售';
            $info   = $this->load('trademark')->getTmInfo($number);
            $name   = $info['name'];
            $class  = implode(',', $info['class']);
            //获取与它近似商标
            $param  = array('name'=>$info['name'],'_name'=>'9','class'=>$class);
            $_like  = $this->load('search')->getSaleList($param, 1, 6);
            //获取特价商标
            $last   = 6 + 6 - count($_like['rows']);
            $params = array('isOffprice'=>'1');
            $_off   = $this->load('search')->getSaleList($params, 1, $last);

            $list['rows'] = array_merge($_like['rows'], $_off['rows']);
            //判断更多是特价页还是近似筛选页
            if ( count($_like['rows']) == 6 )
                $url    = "/search/?kw=$name&c=$class&kt=1&n=9";
            else
                $url    = "/offprice";
        }
        $this->set('title', $title);
        $this->set('moreUrl', $url);
        $this->set('tmList', $list['rows']);
        $this->display('search/search.detail.html');
        
        exit;
    }

    /**
     * 获取加载数据
     *
     * 处理数据加载时，返回相应数据
     * 
     * @author  Xuni
     * @since   2016-03-08
     *
     * @return  void
     */
    public function getMore()
    {
        $page   = $this->input('_p', 'int', 1);
        $type   = $this->input('_from', 'int', 1);

        $params = $this->getSearchParams();
        if ( empty($params) ){
            $res = array('rows'=>array(),'total'=>0);
        }else{
            $res = $this->load('search')->search($params, $page, $this->_number, $type);
        }

        $this->set('searchList', $res['rows']);
        $this->display();
    }

    /**
     * 保存搜索历史
     *
     * @author  Xuni
     * @since   2016-03-11
     *
     * @access  protected
     */
    protected function setSearchLog($kw='', $kt=0)
    {
        if ( $kw == '' || empty($kt) ){            
            $_title = $this->_searchArr['kw'];
            $_type  = $this->_searchArr['kt'];
        }else{
            $_title = $kw;
            $_type  = $kt;
        }
        //保存搜索项历史
        if ( $_title == '' ) return false;
        switch ($_type) {
            case '1':
                $_title = "$_title -> 按商标名称搜索";
                break;
            case '2':
                $_title = "$_title -> 按商标号搜索";
                break;
            case '3':
                $_title = "$_title -> 按适用服务搜索";
                break;
            default:
                $_title = "$_title -> 按适用服务搜索";
                break;
        }

        $prefix = C('SEARCH_HISTORY');
        $_slog  = (array)unserialize( Session::get($prefix) );      
        $_url   = $_SERVER['REQUEST_URI'];//搜索地址
        $_log   = array(
            'title' => $_title,
            'url'   => $_url,
            );
        if ( !in_array(serialize($_log), $_slog) ){
            array_unshift($_slog, serialize($_log));
        }else{
            $_key = array_search(serialize($_log), $_slog);
            if ( $_key !== false ){
                $_now = $_slog[$_key];
                unset($_slog[$_key]);
                array_unshift($_slog, $_now);
            }
        }
        $_slog = array_slice($_slog, 0, 10);
        Session::set($prefix, serialize($_slog), 0);
        return true;
    }

    /**
     * 将字符串转换为数据并去空去重
     * @author  Xuni
     * @since   2016-03-03
     *
     * @access  protected
     * @param   string      $str        字符串
     * @param   string      $prefix     分隔符
     * @return  array
     */
    protected function strToArr($str, $prefix=',')
    {
        if ( empty($str) ) return array();

        $arr = explode($prefix, $str);
        $arr = array_unique( array_filter($arr) );
        return $arr;
    }

}
?>