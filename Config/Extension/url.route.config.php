<?
/**
 * 重新定义url规则
 */

//定义商标详情规则
$rules[] = array(
    '#/d-#',
    array('mod' => 'trademark', 'action' => 'view'),
    array(
        'd-view' => '#((\d+-)([\w_-])*)#',
        ),
);

?>