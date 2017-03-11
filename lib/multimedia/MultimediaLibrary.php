<?php
//一
require_once 'Common_Name.php';
require_once 'DBConnectionPDO.class.php';

class MultimediaLibrary{

	public function GetMediaResources($project_id,$tag){
		$db = new DBConnectionPDO(WechatCentralDatabase);
		$query = 'select media_type,icon_path,stored_path,media_title,media_desc from Multimedia_Resource where project_id=? and `tag`=?';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($project_id,$tag));
		$result = $db->GetAllRows($sth);
		return $result;
	}
	
}
?>