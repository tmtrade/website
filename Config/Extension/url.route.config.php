<?
/**
 * 重新定义url规则
 */

//定义商标详情规则(旧规则，需要支持)
$rules[] = array(
    '#/d-#',
    array('mod' => 'detail', 'action' => 'view'),
    array(
        'short' => '#((\d+-)([\w_-])*)#',
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