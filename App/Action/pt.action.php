<?
/**
 * 专利页面
 *
 * @package Action
 * @author  Martin
 * @since   2015/12/2
 */
class PtAction extends AppAction
{
    
    public $ptype = 10;
    //我要买
    public function index()
    {
        $this->pageTitle 	= '专利购买,专利转让,买卖专利信息网-- 一只蝉专利转让网';

        $this->pageKey          = '专利购买,专利转让,专利转让信息,买卖专利,专利买卖信息';

        $this->pageDescription  = '一只蝉为你提供购买专利,专利转让信息,买卖专利信息,专利申请等服务，一只蝉是超凡集团资产交易平台。多年专利行业经验，为你提供专业的专利转让买卖服务。';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('keywords', $this->pageKey);//页面keywords
        $this->set('description', $this->pageDescription);//页面description
        //$this->setSeo(4);

        $page   = $this->input('page', 'int', 1);
        $type   = $this->input('t', 'string', '');
        $class  = $this->input('c', 'string', '');

        $_type  = array_filter( array_unique( explode(',', $type) ) );
        $_class = array_filter( array_unique( explode(',', $class) ) );

        if ( empty($type) && empty($class) ){
            $params = array();
        }else{
            $params = array(
                'type'      => count($_type) > 1 ? $_type : current($_type),
                'class'     => implode(',', $_class),
                );
        }
        //debug($params);
        $_whereArr = array(
            't'     => $type,
            'c'     => $class
            );
        $whereStr = http_build_query($_whereArr);

        $list = $this->load('pt')->getPtList($params, $page, $this->rowNum);

        $ptType     = C('PATENT_TYPE');
        $ptOne      = C('PATENT_ClASS_ONE');
        $ptTwo      = C('PATENT_ClASS_TWO');

        if ( count($_type) == 1 ){
            (current($_type) == 3) ? $this->set('_CLASS', $ptTwo) : $this->set('_CLASS', $ptOne);
        }
        
        list($t_title, $c_title) = $this->getWhereTitle($type, $class);
        $this->set('t_title', $t_title);
        $this->set('c_title', $c_title);

        if ( !empty($list['rows']) ){
            $this->set('has', true);
        }
        //得到推荐专利
        $tj = $this->load('pdetail')->getRandPT();
        $this->set("tj", $tj);
        $this->set('t', $type);
        $this->set('c', $class);
        $this->set('whereStr', $whereStr);//专利类型
        $this->set('list', $list['rows']);//专利类型
        $this->set('total', $list['total']);//专利类型
        $this->set('_TYPE', $ptType);//专利类型
        $this->display();
    }

    protected function getWhereTitle($type, $class)
    {
        $ptType     = C('PATENT_TYPE');
        $ptOne      = C('PATENT_ClASS_ONE');
        $ptTwo      = C('PATENT_ClASS_TWO');

        $_type      = array_filter( array_unique( explode(',', $type) ) );
        $_class     = array_filter( array_unique( explode(',', $class) ) );
        $_className = $_typeName = array();
        if ( count($_type) == 1 ){
            $_ty = current($_type);
            array_push($_typeName, $ptType[$_ty]);
            foreach ($_class as $k => $v) {
                $_ty == 3 ? array_push($_className, $ptTwo[$v]) : array_push($_className, $ptOne[$v]);
            }
        }else{
            foreach ($_type as $k => $v) {
                array_push($_typeName, $ptType[$v]);
            }
        }

        return array(implode(',', $_typeName), implode(',', $_className));
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
        $page   = $this->input('_p', 'int', 2);
        $type   = $this->input('t', 'string', '');
        $class  = $this->input('c', 'string', '');

        $_type  = array_filter( array_unique( explode(',', $type) ) );
        $_class = array_filter( array_unique( explode(',', $class) ) );

        if ( empty($type) && empty($class) ){
            $params = array();
        }else{
            $params = array(
                'type'      => implode(',', $_type),
                'class'     => implode(',', $_class),
                );
        }
        $res = $this->load('pt')->getPtList($params, $page, $this->rowNum);
        $this->set('list', $res['rows']);
        $this->set('page', $page);
        $this->display();
    }
    
    //我要卖
    public function sell()
    {
        $this->pageTitle 	= '专利出售,专利转让,卖专利-- 一只蝉专利转让网';

        $this->pageKey          = '专利出售,专利转让,卖专利,专利买卖,买卖专利信息';

        $this->pageDescription  = '卖专利去哪里？一只蝉专利转让网,是超凡集团旗下知产交易平台,多年买卖专利经验。为你提供最专业的买卖专利服务。';
        $this->set('title', $this->pageTitle);//页面title
        $this->set('keywords', $this->pageKey);//页面keywords
        $this->set('description', $this->pageDescription);//页面description
        $this->set('ptype',13);
        $this->setSeo(5);
        $this->display();
    }
}
?>