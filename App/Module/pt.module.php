<?
/**
 * 快速筛选功能组件
 *
 * 快速筛选出商标信息，主要从出售信息列表中搜索数据，次要从商标库里搜索数据
 * 
 * @package Module
 * @author  Xuni
 * @since   2015-11-09
 */
class PtModule extends AppModule
{
    /**
     * 引用业务模型
     */
    public $models = array(
        'pt'          => 'patent',
    );
    
    /**
     * 获取出售信息相关数据
     * 
     * @author  Xuni
     * @since   2016-03-04
     *
     * @access  public
     * @param   int     $class      国际分类(1-45)
     *
     * @return  array   $list       群组号对应群组中文名称的数组
     */
    public function getPtList($params, $page=1, $limit=30, $col=array())
    {
        $result = array(
            'rows'  => array(),
            'total' => 0,
            );
        $r['raw'] = ' 1 ';

        //分类
        if ( !empty($params['type']) && empty($params['type']) ){
            $r['eq']['type'] = $params['type'];
        }

        //群组
        if ( !empty($params['class']) && !empty($params['class']) ){
            $r['ft']['class'] = $params['class'];
        }

        $r['eq']['status']  = 1;
        $r['eq']['isSale']  = 1;

        if ( empty($col) ){
            $r['col']   = array('id', 'number', 'code', 'class', 'type', 'title', 'price');
        }else{
            $r['col']   = $col;
        }
        $r['page']      = $page;
        $r['limit']     = $limit;

        $r['order']     = array('isTop' => 'desc');

        $res            = $this->import('pt')->findAll($r);
        $res['rows']    = $this->getListTips($res['rows']);

        return $res;
    }

    /**
     * 处理列表数据
     *
     * @author  Xuni
     * @since   2016-03-04
     *
     * @return  array
     */
    public function getListTips($data)
    {
        if ( empty($data) ) return array();
        if ( !is_array(current($data)) ){
            $_tmp = array($data);
        }else{
            $_tmp = $data;
        }
        // if ( !empty($this->loginId) ){
        //     $numbers    = arrayColumn($_tmp, 'number');
        //     $lookList   = $this->load('usercenter')->existLook($numbers);
        // }        
        foreach ($_tmp as $k => $v) {
            // if ( isset($lookList[$v['number']]) ){
            //     $v['isLook'] = $lookList[$v['number']];                
            // }else{
            //     $v['isLook'] = 2;
            // }
            $data[$k] = $this->getTips($v);
        }
        return $data;
    }

    /**
     * 处理列表数据
     *
     * @author  Xuni
     * @since   2016-03-04
     *
     * @return  array
     */
    public function getTips($data)
    {
        //$data['viewUrl'] = '/d-'.$data['tid'].'-'.$_class.'.html';
        

        $ptType     = C('PATENT_TYPE');
        $ptOne      = C('PATENT_ClASS_ONE');
        $ptTwo      = C('PATENT_ClASS_TWO');

        $_class     = array_filter( array_unique( explode(',', $data['class']) ) );
        $_classArr  = $_className = array();
        foreach ($_class as $k => $v) {
            if ( in_array($data['type'], array(1, 2)) ) {
                array_push($_classArr, chr($v));
                if ( isset($ptOne[chr($v)]) ) array_push($_className, $ptOne[chr($v)]);
            }else{
                array_push($_classArr, $v);
                if ( isset($ptTwo[chr($v)]) ) array_push($_className, $ptTwo[chr($v)]);
            }
        }

        $data['typeName']   = $ptType[$data['type']];
        $data['className']  = implode(',', $_className);

        return $data;
    }



}
?>