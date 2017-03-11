<?php
//一
//1. upload media file
//2. add/edit/delete project view
//3. edit webpage description

try{
	require_once 'lib/FunctionLibrary.php';
	$lib = new FunctionLibrary();
	
	$response_data = array('err'=>0,'data'=>'');
	$action = strval($_REQUEST['action']);
	switch($action){
		case 'info':
			$specifiedProperty = isset($_REQUEST['property'])?strval($_REQUEST['property']):null;
			$response_data['data'] = $lib->GetBasicInformation($specifiedProperty);
			break;
		case 'case':
			$case_id = strval($_REQUEST['case_id']);
			$response_data['data'] = $lib->GetSpecifiedCase($case_id);
			break;
		case 'all_cases':
			// date format: "Y-m-d"
			$specifiedStartDate = isset($_REQUEST['date'])?strval($_REQUEST['date']):null;
			$response_data['data'] = $lib->GetAllCasesContent(false,$specifiedStartDate);
			break;
	}
}catch(Exception $e){
	$response_data['err'] = 1;
	$response_data['data'] = $e->getMessage();
}
echo json_encode($response_data);
exit;
?>