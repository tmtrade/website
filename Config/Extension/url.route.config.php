<?
/**
 * 重新定义url规则
 */

//定义商标详情规则(旧规则，需要支持)
$rules[] = array(
    '#/d-#',
    array('mod' => 'detail', 'action' => 'view'),
    array(
        'short' => '#((\d+-)([\w-])*)#',
        ),
);
//定义文章相关规则
//详情页
$rules[] = array(
    '#/v-#',
    array('mod' => 'faq', 'action' => 'views'),
    array(
        'short' => '#(\d+-\d+)#',
    ),
);
//列表页
$rules[] = array(
    '#/n-#',
    array('mod' => 'faq', 'action' => 'news'),
    array(
        'short' => '#(\d+(-\d+)?)#',
    ),
);
//特殊需求
$rules[] = array(
    '#/about/$#',
    array('mod' => 'topic', 'action' => 'fourth'),
);
$rules[] = array(
    '#/ryzz/$#',
    array('mod' => 'faq', 'action' => 'ryzz'),
);
$rules[] = array(
    '#/lxwm/$#',
    array('mod' => 'faq', 'action' => 'lxwm'),
);

//定义筛选页规则
$rules[] = array(
    '#/s(-|/)#',
    array('mod' => 'search', 'action' => 'rewriteSearch'),
    array(
        'short' => '#^/s-(([\w-])*)#',
        ),
);

//定义着落页规则
$rules[] = array(
    '#/g(-|/)#',
    array('mod' => 'goods', 'action' => 'rewriteSearch'),
    array(
        'short' => '#^/g-(([\w-])*)#',
        ),
);

//定义商标详情规则(新规则)
// $rules[] = array(
//     '#/goods-#',
//     array('mod' => 'detail', 'action' => 'view'),
//     array(
//         'short' => '#([0-9a-zA-Z]+)(.html)#',
//         ),
// );

?>