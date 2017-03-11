<?php
//一
//1. upload media file
//2. add/edit/delete project view
//3. edit webpage description

try{
	require_once dirname(__DIR__).'/lib/FunctionLibrary.php';
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
			$response_data['data'] = $lib->GetAllCasesContent(true,$specifiedStartDate);
			break;
		case 'display_c':
			$case_id = strval($_REQUEST['case_id']);
			$visible = intval($_REQUEST['visible']);
			$status = $visible>0?1:null;
			$response_data['data'] = $lib->ModifyCaseSpecifiedProperty($case_id,$property='visible',$status);
			break;
		case 'add_c':
			$title = strval($_REQUEST['title']);
			$content = strval($_REQUEST['content']);
			$icon = strval($_REQUEST['icon']);
			$start_date = strval($_REQUEST['start_date']); // date format: "Y-m-d"
			if($icon == ''){ $icon = null; }
			if($start_date == ''){ $start_date = '2099-01-01'; }
			$response_data['data'] = $lib->NewCase($title,$content,$icon,$start_date);
			break;
		case 'modify_c':
			$case_id = strval($_REQUEST['case_id']);
			$title = strval($_REQUEST['title']);
			$content = strval($_REQUEST['content']);
			$icon = strval($_REQUEST['icon']);
			$start_date = strval($_REQUEST['start_date']); // date format: "Y-m-d"
			$response_data['data'] = $lib->ModifyCase($case_id,$title,$content,$icon,$start_date);
			break;
		case 'delete_c':
			$case_id = strval($_REQUEST['case_id']);
			$response_data['data'] = $lib->DeleteCase($case_id);
			break;
		case 'edit_desc':
			$data = array();
			$data['name'] = strval($_REQUEST['name']);
			$data['introduction'] = strval($_REQUEST['introduction']);
			$data['cooperation'] = strval($_REQUEST['cooperation']);
			$data['contact'] = strval($_REQUEST['contact']);
			// $data['introduction'] = htmlentities($_REQUEST['introduction'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
			// $data['cooperation'] = htmlentities($_REQUEST['cooperation'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
			// $data['contact'] = htmlentities($_REQUEST['contact'], ENT_QUOTES | ENT_IGNORE, "UTF-8");
			$response_data['data'] = $lib->ModifyBasicInformation($data);
			break;
		case 'menu_list':
			$response_data['data'] = $lib->GetMenuList(true);
			break;
		case 'menu_item':
			$menu_id = strval($_REQUEST['menu_id']);
			$response_data['data'] = $lib->GetSpecifiedMenuItem($menu_id);
			break;
		case 'menu_update':
			$menu_id = strval($_REQUEST['menu_id']);
			$title = strval($_REQUEST['title']);
			$content = strval($_REQUEST['content']);
			$response_data['data'] = $lib->UpdateMenuItemContent($menu_id,$title,$content);
			break;
		case 'menu_title':
			$menu_id = strval($_REQUEST['menu_id']);
			$title = strval($_REQUEST['title']);
			$response_data['data'] = $lib->ModifyMenuSpecifiedProperty($menu_id,'title',$title);
			break;
		case 'menu_add':
			$title = strval($_REQUEST['title']);
			$response_data['data'] = $lib->CreateMenuItem($title);
			break;
		case 'menu_delete':
			$menu_id = strval($_REQUEST['menu_id']);
			$response_data['data'] = $lib->DeleteMenuItem($menu_id);
			break;
		case 'menu_display':
			$menu_id = strval($_REQUEST['menu_id']);
			$enable = intval($_REQUEST['enable']);
			$status = $enable>0?1:null;
			$response_data['data'] = $lib->ModifyMenuSpecifiedProperty($menu_id,'enable',$status);
			break;
		default:
			throw new Exception('Invalid operation.');
	}
}catch(Exception $e){
	$response_data['err'] = 1;
	$response_data['data'] = $e->getMessage();
}
echo json_encode($response_data);
exit;
?>