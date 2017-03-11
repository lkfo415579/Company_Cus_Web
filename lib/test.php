<?php
//一
require_once 'FunctionLibrary.php';
$lib = new FunctionLibrary();
//$data = ($lib->NewCase($title='title4',$content='Peter',$icon='/icon/xxx.jpg',$start_date='2017-01-10'));
//$data = $lib->ModifyCase(3,'title2017','2017MIECF','/icon/yy.jpg','2017-03-29');
//$data = $lib->GetAllCasesContent();
$data = $lib->GetBasicInformation();
//var_dump($data);exit;
echo json_encode($data);
?>