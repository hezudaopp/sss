<?php
require_once("../includes/AllFileStateAccountant.class.php");

$accountant = new AllFileStateAccountant(true);
$accountant->execute();
echo "sql:".$accountant->getSql();
echo "filename: <br />";
var_dump($accountant->getFilename());
var_dump($accountant->getUploadTime());
var_dump($accountant->getDistinctMaterialCodeCount());
var_dump($accountant->getFinishedCount());
var_dump($accountant->getUnFinishedCount());
?>