<?
/**
 * 重新定义url规则
 */

//定义商标详情规则
$rules[] = array(
    '#/d-#',
    array('mod' => 'detail', 'action' => 'view'),
    array(
        'd-view' => '#([0-9a-zA-Z]+)(.html)#',
        ),
);

?>