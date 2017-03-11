<?php
require_once 'Common_Name.php';
require_once 'DBConnectionPDO.class.php';
require_once 'AES.class.php';

define('LocalStorageLabel',null);
define('MaxUploadFileSize',10485760);

class FileSharingModule{
	public $media_type = array('jpg'=>'image','jpeg'=>'image','jpe'=>'image','jfif'=>'image','gif'=>'image','bmp'=>'image','dib'=>'image','png'=>'image','tif'=>'image','tiff'=>'image','mp4'=>'video','avi'=>'video');

	public function GetAllLocalFolderList($project_id){
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'select folder_name,parent_folder from Multimedia_Folder where project_id=?';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($project_id));
		return $db->GetAllRows($sth);
	}
	
	public function AddLocalFolder($project,$folder_name,$parent_folder){
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'insert into Multimedia_Folder(project_id,folder_name,parent_folder) values(?,?,?)';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($project,$folder_name,$parent_folder));
		return 1;
	}
	
	public function GetFileListByTag($tag){
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'select * from `Multimedia_Resource` where `tag`=?';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($tag));
		return $db->GetAllRows($sth);
	}
	
	public function GetLocalFileIcon($media_id){
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'select icon_data from Multimedia_Data where media_src_id=?';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($media_id));
		if($row = $db->GetNextRow($sth)){
			return $row['icon_data'];
		}else{
			return null;
		}
	}
	
	public function GetLocalFileData($media_id){
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'select media_data from Multimedia_Data where media_src_id=?';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($media_id));
		if($row = $db->GetNextRow($sth)){
			return $row['icon_data'];
		}else{
			return null;
		}
	}
	
	public function SaveFileToLocalDB($project_id,$tag,$media_type,$media_title,$media_desc,$file_path,$icon_path=null){
		$src_location = LocalStorageLabel;
		if(file_exists($file_path)){
			$media_size = filesize($file_path);
		}else{
			$media_size = null;
		}
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'insert into `Multimedia_Resource`(`project_id`,`src_location`,`tag`,`media_type`,`media_title`,`media_desc`,`media_size`,`upload_time`) values(?,?,?,?,?,?,?,now())';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($project_id,$src_location,$tag,$media_type,$media_title,$media_desc,$media_size));
		$media_id = $db->GetLastInsertID();
		
		$sql2 = 'insert into `Multimedia_Data`(`media_src_id`,`icon_data`,`media_data`) values(:media_src_id,:icon_data,:media_data)';
		$sth2 = $db->PrepareQuery($sql2);
		$sth2->bindParam(':media_src_id',$media_id);
		
		$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("1")');
		if(file_exists($icon_path)){
			$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("2")');
			if($icon_data = fopen($icon_path,'rb')){
			$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("3")');
				$sth2->bindParam(':icon_data',$icon_data,PDO::PARAM_LOB);
			}else{
			$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("4")');
				$sth2->bindValue(':icon_data',null, PDO::PARAM_INT);
			}
		}else{ $sth2->bindValue(':icon_data',null, PDO::PARAM_INT); }
		$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("5")');
		
		if(file_exists($file_path)){
		$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("'.$file_path.'")');
			if($media_data = fopen($file_path,'rb')){
		$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("'.$media_data.'")');
				$sth2->bindParam(':media_data',$media_data,PDO::PARAM_LOB);
			}else{
		$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("8")');
				$sth2->bindValue(':media_data',null, PDO::PARAM_INT);
			}
		}else{ $sth2->bindValue(':media_data',null, PDO::PARAM_INT); }
		
		$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("9")');
		$sth2->execute();
		$db->ExecuteQuery('insert into wechat_service.`WarningMessage(testing_table)`(`content`) values("10")');
	}
	
	public function AddRemoteFileToDB($project_id,$tag,$media_type,$media_title,$media_desc,$remote_location_name,$icon_location,$file_location){
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'insert into `Multimedia_Resource`(`project_id`,`src_location`,`tag`,`media_type`,`media_title`,`media_desc`,`icon_path`,`stored_path`,`upload_time`) values(?,?,?,?,?,?,?,?,now())';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($project_id,$remote_location_name,$tag,$media_type,$media_title,$media_desc,$icon_location,$file_location));
	}
	
	public function CreateMediaTag($project_id,$identity){
		return $this->GenerateAccessFolderName($project_id.'_'.$identity);
	}
	
	public function GetFileType($file_path){
		$file_type_list = array('jpg'=>'image','jpeg'=>'image','jpe'=>'image','jfif'=>'image','gif'=>'image','bmp'=>'image','dib'=>'image','png'=>'image','tif'=>'image','tiff'=>'image','mp4'=>'video','avi'=>'video');
		$extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
		if(isset($file_type_list['$extension'])){
			return $file_type_list['$extension'];
		}else{
			return $extension;
		}
	}

	/**
		@return string - the directory for storing file
	*/
	public function PrepareDirectory($base_dir,$access_key,$sub_folder){
		$access_folder = $this->GenerateAccessFolderName($access_key);
		$src_dir = rtrim($base_dir,'/').'/'.$access_folder;
		
		if(!file_exists($src_dir)){
			mkdir($src_dir,0744);
		}
		
		$upload_dir = $src_dir.'/'.$sub_folder;
		if(!file_exists($upload_dir)){
			mkdir($upload_dir,0744);
		}
		
		return $upload_dir;
	}
	
	public function UploadFileToPath($file_pool,$project_id,$tag,$media_title=null,$media_desc=null,$upload_user=null,$remarks=null,$use_original_name=true){
		$doc_dir_base = $project_id;
		if(!file_exists(UploadProjectDocDir.$doc_dir_base)){
			mkdir(UploadProjectDocDir.$doc_dir_base);
		}
		$folder_name = $tag;
		if($folder_name===null || $folder_name===''){
			$doc_dir_base .= '/default';
		}else{
			$doc_dir_base .= '/'.$folder_name;
		}
		if(!file_exists(UploadProjectDocDir.$doc_dir_base)){
			mkdir(UploadProjectDocDir.$doc_dir_base);
		}
		
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'insert into Multimedia_Resource(project_id,media_type,icon_path,stored_path,src_location,tag,media_title,media_desc,media_size,upload_user,upload_time,remarks,original_name) values(?,?,?,?,?,?,?,?,?,?,now(),?,?)';
		$sth = $db->PrepareQuery($query);
		$sql_drop = 'delete from Multimedia_Resource where project_id=? and tag=? and original_name=?';
		$sth_drop = $db->PrepareQuery($sql_drop);
		
		$result = array();
		if(is_array($file_pool['tmp_name'])){
			$file_pool_name = $file_pool['name'];
			$file_pool_path = $file_pool['tmp_name'];
			$file_pool_error = $file_pool['error'];
			$file_pool_size = $file_pool['size'];
			for($i=0; $i<count($file_pool_path); $i++) {
				if($file_pool_error[$i] > 0 || $file_pool_size[$i] > MaxUploadFileSize){ continue; }
				$tmpFilePath = $file_pool_path[$i];
				$tmpFileName = $file_pool_name[$i];
				$fInfo = pathinfo($tmpFileName);
				$original_name = $fInfo['basename'];
				$doc_name = $use_original_name ? $fInfo['filename'] : ($this->CreateRandomFileName());
				$extension = $fInfo['extension'];
				if($extension===null || $extension===''){
					$doc_dir = $doc_dir_base.'/'.$doc_name;
				}else{
					$doc_dir = $doc_dir_base.'/'.$doc_name.'.'.$extension;
				}
				$target_dir = UploadProjectDocDir.$doc_dir;
				if(move_uploaded_file($tmpFilePath, $target_dir)){
					//$result &= true;
					$local_dir = '/'.ProjectDocDir.$doc_dir;
					$result[$file_pool_name[$i]] = $local_dir;
					
					$media_type = (isset($this->media_type[$extension]))?($this->media_type[$extension]):'doc';
					$icon_path = null;
					if($use_original_name){
						$db->ExecutePreparedQuery($sth_drop,array($project_id,$tag,$original_name));
					}
					$media_title = $original_name;
					$db->ExecutePreparedQuery($sth,array($project_id,$media_type,$icon_path,$local_dir,'prime',$tag,$media_title,$media_desc,$file_pool_size[$i],$upload_user,$remarks,$original_name));
					
				}
			}
		}else{
			$media_size = $file_pool['size'];
			$fInfo = pathinfo($file_pool['name']);
			$extension = $fInfo['extension'];
			$original_name = $fInfo['basename'];
			$doc_name = $use_original_name ? $fInfo['filename'] : ($this->CreateRandomFileName());
			if($extension===null || $extension===''){
				$doc_dir = $doc_dir_base.'/'.$doc_name;
			}else{
				$doc_dir = $doc_dir_base.'/'.$doc_name.'.'.$extension;
			}
			$target_dir = UploadProjectDocDir.$doc_dir;
			if(move_uploaded_file($file_pool["tmp_name"], $target_dir)){
				//$result = true;
				$local_dir = '/'.ProjectDocDir.$doc_dir;
				$result[$file_pool['name']] = $local_dir;
				
				$media_type = (isset($this->media_type[$extension]))?($this->media_type[$extension]):'doc';
				$icon_path = null;
				if($use_original_name){
					$db->ExecutePreparedQuery($sth_drop,array($project_id,$tag,$original_name));
				}
				$media_title = $original_name;
				$db->ExecutePreparedQuery($sth,array($project_id,$media_type,$icon_path,$local_dir,'prime',$tag,$media_title,$media_desc,$media_size,$upload_user,$remarks,$original_name));
				
			}else{
				//$result = false;
			}
		}
		
		return $result;
	}
	
	public function CopyFileToPath($file,$path){
		return copy($file,$path);
	}
	
	public function MoveFileToPath($file,$path){
		return rename($file,$path);
	}
	
	public function DeleteDiskFile($stored_path,$project_id=null,$tag=null){
		$db = new DBConnectionPDO(MultimediaDatabase);
		$sql_project = ($project_id === null)?'':(' and project_id="'.addslashes($project_id).'"');
		$sql_tag = ($sql_tag === null)?'':(' and `tag`="'.addslashes($sql_tag).'"');
		$query = 'delete from Multimedia_Resource where stored_path=?'.$sql_project.$sql_tag;
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($stored_path));
		$physical_path = rtrim(ServerDirectoryRoot,'/').$stored_path;
		if(file_exists($physical_path)){
			unlink($physical_path);
		}
	}
	
	public function ChangeFileName($media_id,$name){
		$db = new DBConnectionPDO(MultimediaDatabase);
		$query = 'update Multimedia_Resource set media_title=? where media_id=?';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($name,$media_id));
	}
	
	public function MakeRandomFileName($src_file_name){
		
		$doc_name = date('YmdHis');
		for($i=0; $i<4; $i++){
			$doc_name .= substr(RandomStringBase,mt_rand(0,RandomStringLength),1);
		}
		$extension = pathinfo($src_file_name, PATHINFO_EXTENSION);
		if(empty($extension)){
			return $doc_name;
		}else{
			return $doc_name.'.'.$extension;
		}
	}
	
	public function GenerateAccessFolderName($access_key){
		$aes = new AES(Global_AES_Key);
		return urlencode(base64_encode($aes->encrypt($access_key)));
	}
	
	public function ExtractAccessFolderName($folder_name){
		$aes = new AES(Global_AES_Key);
		$data = base64_decode(urldecode($folder_name));
		return $aes->decrypt($data);
	}
	
	public function CreateRandomFileName(){
		$doc_name = date('His');
		for($i=0; $i<12; $i++){
			$doc_name .= substr(RandomStringBase,mt_rand(0,RandomStringLength),1);
		}
		return $doc_name;
	}
}

?>