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

	//筛选页
	public function index()
	{
        $page = $this->input('_p', 'int', 1);//分类

        // $_rr = array(
        //     'keyword'       => '不不',
        //     'classId'       => '',
        //     'groupCode'     => '',
        //     );
        // $res = $this->load('search')->searchLike($_rr, 1, 100);
        // debug($res);

        $params = $this->getSearchParams();

        //debug($this->_searchArr); 
        //debug($params);

        $res    = $this->load('search')->search($params, $page, $this->_number, 1);
        //debug($res);
        if ( !empty($this->_searchArr) ){
            foreach ($this->_searchArr as $k => $v) {
                $this->set($k, $v);
            }
            $whereStr   = http_build_query($this->_searchArr);
        }
        $_cls = empty($this->_searchArr['c']) ? 0 : intval($this->_searchArr['c']);
        $classGroup = $this->load('search')->getClassGroup($_cls, 1);
        
        list($_class, $_group) = $classGroup;

        //$strArr     = $this->getFormData();
        //debug($whereStr);
		//设置页面TITLE
		//$this->set('TITLE', $this->load('search')->getTitle($params));

        if ( !empty($_class) ){
            $this->set('_CLASS', $_class);
        }elseif ( !empty($_group) ){
            $this->set('_GROUP', $_group);
        }

        
        $this->set('PLATFORM', C('PLATFORM_IN'));//平台列表
        $this->set('NUMBER', C('SBNUMBER'));//商标字数
        $this->set('searchList', $res['rows']);
        $this->set('total', $res['total']);
        $this->set('has', empty($res) ? false : true);
        $this->set('_number', $this->_number);
        $this->set('whereStr', $whereStr);
		$this->display();
	}

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
        $_name      = $this->strToArr($name);
        $_type      = $this->strToArr($type);
        $_length    = $this->strToArr($length);
        $_date      = $this->strToArr($date);
        $_platform  = $this->strToArr($platform);

        $params = array(
            'status' => 1,
        );
        $this->_searchArr['kw'] = urldecode($keyword);
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
            }
            //商标字数
            if ( !empty($_length) && count($_length) != count(C('SBNUMBER')) ){
                $params['length'] = implode(',', $_length);
                $this->_searchArr['sn'] = $params['length'];
            }
            //高级筛选，注册日期
            if ( !empty($_date) ){
                $params['date'] = implode(',', $_date);
                $this->_searchArr['d'] = $params['date'];
            }
            //平台入驻
            if ( !empty($_platform) && count($_platform) != count(C('PLATFORM_IN')) ){
                $params['platform'] = implode(',', $_platform);
                $this->_searchArr['p'] = $params['platform'];
            }
            return $params;
        }
        //有关键词，无搜索项类型或搜索项类型为3：适用服务
        if ( empty($keytype) || $keytype == 3 || !in_array($keytype, range(1,3)) ){
            $res = $this->load('search')->getServiceCondition($keyword);
            //无数据并没有搜索项（走商标名称搜索）
            if ( empty($res) && $keytype != 3 ){
                $params['keytype']  = 1;
                $params['name']     = $keyword;
                $this->_searchArr['kt'] = $params['keytype'];
                return $params;
            }
            //选择搜索项为3时，无数据直接返回空，没有相关数据可查询
            if ( $keytype == 3 && empty($res) ) return array();

            //判断适用服务是否可用
            if ( count($res['class']) > 1 ){
                $params['class'] = implode(',', $res['class']);
            }elseif ( count($res['class']) == 1 ){
                if ( empty($res['group']) ){
                    return array();
                }else{
                    //$groupList = $this->load('group')->getClassGroups(current($res['class']));
                    //$this->set('groupList', $groupList);
                    $params['group'] = implode(',', $res['group']);
                }
            }

            //组合类型
            if ( !empty($_type) ){
                $params['type'] = implode(',', $_type);
                $this->_searchArr['t'] = $params['type'];
            }
            //商标字数
            if ( !empty($_length) ){
                $params['length'] = implode(',', $_length);
                $this->_searchArr['sn'] = $params['length'];
            }
            //高级筛选，注册日期
            if ( !empty($_date) ){
                $params['date'] = implode(',', $_date);
                $this->_searchArr['d'] = $params['date'];
            }
            //平台入驻
            if ( !empty($_platform) ){
                $params['platform'] = implode(',', $_platform);
                $this->_searchArr['p'] = $params['platform'];
            }
            $params['keytype']  = 3;
            $this->_searchArr['kt'] = $params['keytype'];

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
            if ( !empty($_name) ){
                $params['_name'] = implode(',', $_name);
                $this->_searchArr['n'] = $params['_name'];
            }
            //组合类型
            if ( !empty($_type) ){
                $params['type'] = implode(',', $_type);
                $this->_searchArr['t'] = $params['type'];
            }
            //商标字数
            if ( !empty($_length) ){
                $params['length'] = implode(',', $_length);
                $this->_searchArr['sn'] = $params['length'];
            }
            //高级筛选，注册日期
            if ( !empty($_date) ){
                $params['date'] = implode(',', $_date);
                $this->_searchArr['d'] = $params['date'];
            }
            //平台入驻
            if ( !empty($_platform) ){
                $params['platform'] = implode(',', $_platform);
                $this->_searchArr['p'] = $params['platform'];
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
        $this->set('c', $class);
        $this->display();
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