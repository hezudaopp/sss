<?php
require_once("../includes/LogInserter.class.php");

LogInserter::logForInsert("插入新的东西");
LogInserter::logForDelete("删除新的东西");
LogInserter::logForEdit("编辑新的东西");

$array = array('materialCode' => '我的材料代码', 'material' => '我的材质', 'thickness' => '厚度', 'width' => '长度');
echo"将要操作这个记录：<br />";
var_dump($array);
echo "<br />";
LogInserter::logForDelete($array);


$twoArray = array($array, $array, $array, $array);
echo "二级数组：<br />";
var_dump($twoArray);
echo "<br />";
LogInserter::logForEdit($twoArray);
?>