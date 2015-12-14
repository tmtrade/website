<?php
/**
* 检测工具
*
* 网站首页
*
* @package Action
* @author  haydn
* @since   2015-12-06
*/
class DetectionAction extends AppAction
{

    public $magic = array(
        1 => '商标存在<em>被无效</em>风险，若要购买，建议在协议中进行责任约定，例如：商标被无效后的赔偿条款，将风险降到最低。',
        2 => '商标存在<em>已无效</em>风险，若要购买，请核实状态的真实性，与卖方确认后续是否有行政诉讼流程的相关资料，确保商标有效后再行购买。',
        3 => '商标存在<em>被无效</em>风险，若要购买，建议在协议中进行责任约定，例如：商标被无效后的赔偿条款，将风险降到最低。',
        4 => '商标存在<em>被无效</em>风险，若要购买，建议在协议中进行责任约定，例如：商标被无效后的赔偿条件，将风险降到最低。',
        5 => '商标存在<em>已无效</em>风险，若要购买，请核实状态的真实性，与卖方核实后续是否有行政诉讼流程的相关资料，确保商标有效后再行购买。',
        6 => '商标存在<em>被无效</em>风险，若要购买，建议在协议中进行责任约定，例如：商标被无效后的赔偿条款，将风险降到最低。',
        7 => '商标存在<em>被无效</em>风险，若要购买，建议在协议中进行责任约定，例如：商标被无效后的赔偿条件，将风险降到最低。',
        8 => '商标存在<em>已无效</em>风险，若要购买，请核实状态的真实性，与卖方确认后续是否有行政诉讼流程的相关资料，确保商标有效后再行购买。',
        9 => '商标存在<em>被无效</em>风险，若要购买，建议与卖家沟通需马上终止注销流程以确保商标有效。并在协议中进行责任约定，例如：商标被无效后的赔偿条款，将风险降到最低。',
        10 => '商标存在<em>被无效</em>风险，若要购买，请与卖家沟通注销的部分商品/服务分别是哪些，确保购买商标的有效性后再行购买。',
        11 => '商标存在<em>已无效</em>风险，若要购买，请核实状态的真实性，与卖方确认后续是否有行政诉讼流程的相关资料，确保商标有效后再行购买。',
        12 => '商标存在<em>权利不稳定</em>风险，若要购买，建议低价购买，或在协议中进行责任约定，例如：商标注册不成功的赔偿条款，将风险降到最低。',
        13 => '商标存在<em>已无效</em>风险，若要购买，请核实状态的真实，确保商标有效后再行购买。',
        14 => '商标存在<em>质押</em>风险，若要购买，请与卖方确认质押是否解除，建议在协议中进行责任约定，将风险降到最低。',
        15 => '商标存在<em>质押</em>风险，若要购买，请与卖方确认质押是否解除，建议在协议中进行责任约定，将风险降到最低。',
        16 => '商标存在<em>冻结</em>风险，若要购买，请与卖方确认冻结是否解除，建议在协议中进行责任约定，将风险降到最低。',
        17 => '商标存在<em>转让</em>流程，若要购买，请与卖方确认转让流程是否完结，避免一标多卖的情况，建议在协议中进行责任约定，将风险降到最低。',
        18 => '商标存在<em>转让</em>流程，若要购买，请与卖方确认转让流程是否完结，避免一标多卖的情况，建议在协议中进行责任约定，将风险降到最低。',
        19 => '商标存在<em>许可</em>流程，若要购买 ，请与卖方确认许可合同条约是否限制转让，并将转让事宜通知被许可方，建议在协议中进行责任约定，将风险降到最低。',
        20 => '商标存在<em>许可</em>流程，若要购买 ，请与卖方确认许可合同条约是否限制转让，并将转让事宜通知被许可方，建议在协议中进行责任约定，将风险降到最低。',
        21 => '商标存在<em>许可</em>流程，若要购买 ，请与卖方确认许可合同条约是否限制转让，并将转让事宜通知被许可方，建议在协议中进行责任约定，将风险降到最低。',
        22 => '商标存在<em>续展</em>流程，若要购买，建议在协议中进行责任约定，例如：若续展不成功，导致商标无效的赔偿条款，将风险降到最低。',
        23 => '商标存在<em>近似</em>风险，若要购买，建议将近似商标一起购买/注销，将购买风险降到最低。',
        24 => '商标存在<em>变更</em>流程，若要购买，建议确定商标所有权归卖方后再行购买。',
    );
    public $checkTyep   = array(
        1 => '争议检测',
        2 => '撤三检测',
        3 => '撤销注册复审',
        4 => '注销检测',
        5 => '申请中检测',
        6 => '无效检测',
        7 => '质押检测',
        8 => '冻结检测',
        9 => '转让检测',
        10 => '许可检测',
        11 => '续展期检测',
        12 => '近似检测',
        13 => '变更检测',
    );
    public $totalmsg   = array(
        1  => '总评：商标存在<span>被无效/已无效</span>风险，建议根据报告规避风险。',
        2  => '总评：商标存在<span>被转让/冻结/质押</span>风险，建议根据报告规避风险。',
        3  => '总评：商标存在<span>许可/续展/近似</span>风险，建议根据报告规避风险。',
        4  => '总评：恭喜，此商标状态安全。',
    );
    public function index()
    {
        $count  = $this->load('checkcount')->getAllCount();
        $sj     = date('Y年m月d日',time());
		$host	= 'http://'.$_SERVER['HTTP_HOST'].'/Static/style/img/mj-qrcode.gif';//'http://www.tmmark.com/images/mj-qrcode.gif';//
        $this->set('count',$count);
        $this->set('sj',$sj);
		$this->set('hostpic',$host);
        $this->display();
    }
    /**
    * 检测商标号是否存在
    * @since    2015-12-06
    * @author   haydn
    * @return   json    返回检查结果数据包
    */
    public function checkAreAjax()
    {
        //!$this->isAjax() && $this->redirect('', '/');
        $tradid     = $this->input('tradname', 'string', '');
        $trad       = $this->load('trademark')->getTrademarkByIds($tradid);
        $this->returnAjax($trad);
    }
    /**
    * 检测商标号的分数
    * @since    2015-12-06
    * @author   haydn
    *
    */
    public function checkScoreAjax()
    {

        //!$this->isAjax() && $this->redirect('', '/');
        $info       = $array = array();
        $tradid     = $this->input('tradname', 'string', '');
        $class      = $this->input('class', 'int', '');
        $steps      = $this->input('steps', 'int', '');
        $isdata     = $this->input('isdata', 'int', '');
        if( $isdata == 0 ){
            $id         = $this->load('trademark')->getTid($tradid,$class);
            $this->load('checkcount')->click($tradid);
            if( $id > 0 ){
                $info       = $this->getTmInfo($id);
                $twoArr     = $this->load('trademark')->getTwoStageInfo($tradid,$class);
                $threeArr   = $this->load('trademark')->getThreeStageInfo($tradid,$class);
                $alikeCount = $this->load('trademark')->getAlikeBrand($tradid,$class);
                $array      = $this->getScoreInfo($twoArr,$threeArr,$alikeCount);
                $check      = $this->getDynamic($steps);
                $result     = array('info' => $info,'result' => $array,'check' => $check);
            }
        }else{
            $check  = $this->getDynamic($steps);
            $result = array('check' => $check);
        }
        $this->returnAjax($result);
    }
    /**
    * 争议检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules1($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'无效宣告完成');
        if( $is1 == true ){//规则1
            $three_status = $twoArr['three_status'];
            $three_status == 3 && $result[] = 2;
        }else{//规则2
            $is2  = $this->getStatus($threeArr,'无效宣告中');
            $noArr= array('无效宣告完成');
            $isNO = $this->getNoStatus($threeArr,$noArr);
            if( $is2 == true && $isNO == false ){
                $result[] = 1;
            }
        }
        return $result;
    }
    /**
    * 撤三检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules2($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'撤销连续三年停止使用注册商标申请完成');
        $is2    = $this->getStatus($threeArr,'撤销三年不使用审理完成');
        if( $is1 == true || $is2 == true ){//规则1
            $three_status = $twoArr['three_status'];
            $three_status == 3 && $result[] = 5;
        }else{//规则2
            $is3  = $this->getStatus($threeArr,'撤销连续三年停止使用注册商标中');
            $is4  = $this->getStatus($threeArr,'撤销三年不使用待审中');

            $noArr= array('撤销连续三年停止使用注册商标申请完成','撤销三年不使用审理完成');
            $isNO = $this->getNoStatus($threeArr,$noArr);

            if( ($is3 == true || $is4 == true) && $isNO == false ){
                $result[] = 3;
            }
        }
        return $result;
    }
    /**
    * 撤销注册复审规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules3($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'撤销注册商标复审完成');
        $is2    = $this->getStatus($threeArr,'撤销注册复审完成');
        $is3    = $this->getStatus($threeArr,'撤销注册不当复审完成');
        if( $is1 == true || $is2 == true  || $is3 == true ){//规则1
            $three_status = $twoArr['three_status'];
            $three_status == 3 && $result[] = 8;
        }else{//规则2
            $is4  = $this->getStatus($threeArr,'撤销注册复审待审中');
            $is5  = $this->getStatus($threeArr,'撤销注册不当复审待审中');
            $is6  = $this->getStatus($threeArr,'撤销注册商标复审中');
            $noArr= array('撤销注册商标复审完成','撤销注册复审完成','撤销注册不当复审完成');
            $isNO = $this->getNoStatus($threeArr,$noArr);
            if( ($is4 == true || $is5 == true || $is6 == true) && $isNO == false ){
                $result[] = 6;
            }
        }
        return $result;
    }
    /**
    * 注销检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules4($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'商标注销申请完成');
        $is2    = $this->getStatus($threeArr,'商标注销完成');
        $is3    = $this->getStatus($threeArr,'消亡注销完成');
        $is4    = $this->getStatus($threeArr,'注册人死亡/终止注销商标申请完成');
        if( $is1 == true || $is2 == true  || $is3 == true  || $is4 == true ){//规则1
            $three_status = $twoArr['three_status'];
            $three_status == 3 && $result[] = 11;
        }else{
            $is4  = $this->getStatus($threeArr,'部分注销');

            $is5  = $this->getStatus($threeArr,'商标注销申请中');
            $is6  = $this->getStatus($threeArr,'消亡注销待审中');
            $is7  = $this->getStatus($threeArr,'注册人死亡/终止注销商标申请中');
            $is8  = $this->getStatus($threeArr,'部分注销、期满未续展注销商标中');

            $noArr= array('商标注销申请完成','商标注销完成','消亡注销完成','注册人死亡/终止注销商标申请完成');
            $isNO = $this->getNoStatus($threeArr,$noArr);

            if( $is4 == true  ){//规则2
                $result[] = 10;
            }elseif( ($is5 == true || $is6 == true || $is7 == true || $is8 == true) && $isNO == false ){//规则3
                $result[] = 9;
            }
        }
        return $result;
    }
    /**
    * 申请中检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules5($twoArr,$threeArr)
    {
        $result = array();
        if( $twoArr['three_status'] == 1 ){
            $result[] = 12;
        }
        return $result;
    }
    /**
    * 无效检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules6($twoArr,$threeArr)
    {
        $result = array();
        $noArr  = array('无效宣告完成','撤销连续三年停止使用注册商标申请完成','撤销三年不使用审理完成',
            '撤销注册商标复审完成','撤销注册复审完成','撤销注册不当复审完成','商标注销申请完成','商标注销完成','消亡注销完成','注册人死亡/终止注销商标申请完成');
        $isNO   = $this->getNoStatus($threeArr,$noArr);
        if( $twoArr['three_status'] == '3' && $isNO == false ){
            $result[] = 13;
        }
        return $result;
    }
    /**
    * 质押检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules7($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'注册商标质押登记中');
        $is2    = $this->getStatus($threeArr,'注册商标质押登记完成');
        if( $is1 == true || $is2 == true ){
            $result[] = 14;
        }
        return $result;
    }
    /**
    * 冻结检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules8($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'商标冻结中');
        $is2    = $this->getStatus($threeArr,'冻结商标中');
        $is3    = $this->getStatus($threeArr,'冻结商标完成');
        if( $is1 == true || $is2 == true  || $is3 == true ){
            $result[] = 16;
        }
        return $result;
    }
    /**
    * 转让检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules9($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'商标转让中');
        $is2    = $this->getStatus($threeArr,'商标转让待审中');
        $noArr  = array('商标转让完成');
        $isNO   = $this->getNoStatus($threeArr,$noArr);
        if( ($is1 == true || $is2 == true) && $isNO == false ){
            $result[] = 17;
        }
        return $result;
    }
    /**
    * 许可检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules10($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'商标使用许可备案中');
        $is2    = $this->getStatus($threeArr,'许可合同备案待审中');
        $is3    = $this->getStatus($threeArr,'商标使用许可备案完成');
        $is4    = $this->getStatus($threeArr,'许可合同备案完成');
        if( $is1 == true || $is2 == true || $is3 == true || $is4 == true ){
            $result[] = 19;
        }
        return $result;
    }
    /**
    * 续展期检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules11($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'商标续展中');
        $noArr  = array('商标续展完成');
        $isNO   = $this->getNoStatus($threeArr,$noArr);
        if( $is1 == true && $isNO == false ){
            $result[] = 22;
        }
        return $result;
    }
    /**
    * 近似检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    int     $twoArr   申请相同数量
    * @return   array
    */
    public function rules12($alikeCount)
    {
        $result = array();
        $alikeCount > 0 ? $result[] = 23 : '';
        return $result;
    }
    /**
    * 变更检测规则
    * @since    2015-12-06
    * @author   haydn
    * @param    array $twoArr   二级数组
    * @param    array $threeArr 三级数组
    * @return   array
    */
    public function rules13($twoArr,$threeArr)
    {
        $result = array();
        $is1    = $this->getStatus($threeArr,'变更商标申请人/注册人名义/地址中');
        $noArr  = array('变更商标申请人/注册人名义/地址完成','商标变更完成','变更完成');
        $isNO   = $this->getNoStatus($threeArr,$noArr);
        if( $is1 == true && $isNO == false ){
            $result[] = 24;
        }
        return $result;
    }
    /**
    * 获取分数信息
    * @since    2015-12-06
    * @author   haydn
    * @param    array   $twoArr     二级数组
    * @param    array   $threeArr   三级数组
    * @param    int     $alikeCount 申请人相同名称的商标数量
    * @return   array
    */
    public function getScoreInfo($twoArr,$threeArr,$alikeCount)
    {
        $total      = 100;//总分
        $fiftyArr   = array(1,2,4,5,6);//五十
        $thirtyArr  = array(7,8,9);//三十
        $tenArr     = array(10,11,12,13);//十
        $array      = $arr = $mark = array();
        $threeArr   = array_sort($threeArr,'date','desc');
        for( $i == 1; $i < 14; $i++ ){
            $funName    = 'rules'.$i;
            $isExis     = method_exists($this,$funName);
            if( $isExis == true ){
                $arr[$i] = $funName == 'rules12' ? $this->$funName($alikeCount) : $this->$funName($twoArr,$threeArr);
            }
        }
        $arr = array_filter($arr);
        if( count($arr) > 0 ){
            $a = $b = $c = 0;
            foreach( $arr as $k => $v ){
                if( in_array($k,$fiftyArr) ){
                    $a = 1;
                }elseif( in_array($k,$thirtyArr) ){
                    $b = 1;
                }elseif( in_array($k,$tenArr) ){
                    $c = 1;
                }
                $mark[] = $this->magic[$v[0]];
            }
            $a == 1 && $total = $total - 50;
            $b == 1 && $total = $total - 30;
            $c == 1 && $total = $total - 10;
        }
        $msg   = $this->getTotalMsg($total);
        $array = array('total' => $total,'mark' => $mark,'msg' => $msg);
        return $array;
    }
    /**
    * 获取评测项
    * @since    2015-12-06
    * @author   haydn
    * @param    int     $steps  当前检查步骤
    * @return   string  $string
    */
    public function getDynamic($steps)
    {
        $string     = '';
        $checkTyep  = array_reverse($this->checkTyep);
        $steps      = $steps - 1;
        if( array_key_exists($steps,$checkTyep) ){
            $string = $checkTyep[$steps];
        }
        return $string;
    }

    /**
    * 获取状态是否在数组里面
    * @since    2015-12-06
    * @author   haydn
    * @param    array   $array  三级数组
    * @param    string  $name   检查字符串
    * @param    string  $name   检查字符串
    * @return   bool	true|false
    */
    public function getStatus($array,$name)
    {
        foreach( $array as $k => $v ){
            if( $v['status'] == $name ){
                return true;
            }
        }
        return false;
    }
    /**
    * 获取状态不在数组里面
    * @since    2015-12-06
    * @author   haydn
    * @param    array   $array  三级数组
    * @param    string  $name   检查字符串
    * @param    string  $name   检查字符串
    * @return   bool    true|false
    */
    public function getNoStatus($array1,$array2)
    {
        $status = array();
        foreach( $array1 as $k => $v ){
            $status[] = $v['status'];
        }
        if( count($status) > 0 ){
            foreach( $array2 as $k => $v ){
                if( in_array($v,$status) ){
                    return true;
                }
            }
        }
        return false;
    }



    /**
    * 获取商标状态
    * @since    2015-12-06
    * @author   haydn
    * @param    int     $id     注册信息表主键ID
    * @return   array   $info   商标信息
    */
    public function getTmInfo($id)
    {
        $info   = $this->load('trademark')->getInfo($id,array('auto','class','trademark','pid','proposer_id','goods','id'));
        $info['class_id'] = $info['class'];
		$info['trademark']= !empty($info['trademark']) ? $info['trademark'] : '商标号：'.$info['id'];
        if( $info['pid'] > 0 ){
            $proArr = $this->load('proposer')->get($info['proposer_id']);//获取申请人
            $img    = $this->load('trademark')->getImg($info['auto']);//获取图片
            $plat   = $this->load('sale')->getTrademarkPlatform($info['class']);//获取平台
            if( !empty($plat) ){
                $clo        = array('clog','clob','cloq','cloz','clozs','clozy','clof','cloo');//标签云的class
                $platIn     = C("PLATFORM_IN");
                $platArr    = explode(',',$plat);
                foreach( $platArr as $k => $v ){
                    if( array_key_exists($v,$platIn) ){
                        $rand       = array_rand($clo,1);
                        $platform[] = array('classname' => $clo[$rand],'name' => $platIn[$v]);
                    }
                }
            }
            $info['pname']  = !empty($proArr['name']) ? $proArr['name'] : '无';
            $info['imgurl'] = !empty($img) ? $img : '/Static/images/img1.png';
            $info['plat']   = $platform;
        }
        return $info;
    }
    /**
    * 获取商标分数
    * @since    2015-12-06
    * @author   haydn
    * @param    int     $num   分数
    * @return   string  $msg   分数对应的评语
    */
    public function getTotalMsg($num)
    {
        $msg = '';
        if( $num <= 50 ){
            $msg = $this->totalmsg[1];
        }else if( $num > 50 && $num <= 80 ){
            $msg = $this->totalmsg[2];
        }else if( $num > 80 && $num < 100 ){
            $msg = $this->totalmsg[3];
        }else{
           $msg = $this->totalmsg[4];
        }
        return $msg;
    }
}
?>