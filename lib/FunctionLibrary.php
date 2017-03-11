<?php
//一
require_once 'DBConnectionPDO.class.php';
require_once 'config.php';

class FunctionLibrary{
	private $db;
	
	public function __construct(){
		$this->db = new DBConnectionPDO(DB_Name,DB_Account,DB_Password,DB_Host);
	}
	
	// @param specifiedProperty: string
	public function GetBasicInformation($specifiedProperty=null){
		$db = &$this->db;
		if($specifiedProperty === null){
			$query = 'select * from `basic`';
			$sth = $db->ExecuteQuery($query);
		}else{
			$query = 'select * from `basic` where `property`=?';
			$sth = $db->PrepareQuery($query);
			$db->ExecutePreparedQuery($sth,array($specifiedProperty));
		}
		$result = array();
		while($row = $db->GetNextRow($sth)){
			$property = $row['property'];
			$content = $row['content'];
			$result[$property] = $content;
		}
		
		return $result;
	}
	
	public function GetMenuList($includeDisableItem=false){
		$db = &$this->db;
		$sqlIncludeDisable = $includeDisableItem?'':' where `enable`=1 ';
		$query = 'select `mid`,`enable`,`removable`,`sequence`,`subkey`,`title` from `menu`'.$sqlIncludeDisable.'order by `sequence` asc';
		$sth = $db->ExecuteQuery($query);
		return $db->GetAllRows($sth);
	}
	
	public function GetSpecifiedMenuItem($menu_id){
		$db = &$this->db;
		$query = 'select `title`,`content` from `menu` where `mid`=?';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($menu_id));
		if($row = $db->GetNextRow($sth)){
			$result = array();
			$result['title'] = $row['title'];
			$result['content'] = $row['content'];
			return $result;
		}else{
			throw new Exception('Invalid Menu ID');
		}
	}
	
	public function ModifyMenuSpecifiedProperty($menu_id,$property,$content){
		$db = &$this->db;
		$query = 'update `menu` set `'.$property.'`=? where `mid`=?';
		$sth = $db->PrepareQuery($query);
		return $db->ExecutePreparedQuery($sth,array($content,$menu_id));
	}
	
	public function UpdateMenuItemContent($menu_id,$newTitle,$newContent){
		$db = &$this->db;
		$query = 'update `menu` set `title`=?,`content`=? where `mid`=? and `subkey`<=>null';
		$sth = $db->PrepareQuery($query);
		return $db->ExecutePreparedQuery($sth,array($newTitle,$newContent,$menu_id));
	}
	
	public function CreateMenuItem($title){
		$db = &$this->db;
		/* $sql1 = 'select max(`sequence`) as `last_sequence` from `menu`';
		$sth1 = $db->ExecuteQuery(sql1);
		if($row = $db->GetNextRow($sth1)){
			$new_sequence = $row['last_sequence'] + 1;
		}else{
			$new_sequence = 1;
		} */
		$new_sequence = 0;
		$query = 'insert into `menu`(`enable`,`removable`,`sequence`,`title`) values(?,?,?,?)';
		$sth = $db->PrepareQuery($query);
		return $db->ExecutePreparedQuery($sth,array(0,1,$new_sequence,$title));
	}
	
	public function DeleteMenuItem($menu_id){
		$db = &$this->db;
		$query = 'delete from `menu` where `mid`=? and `removable`=1';
		$sth = $db->PrepareQuery($query);
		return $db->ExecutePreparedQuery($sth,array($menu_id));
	}
	
	// @param $includeInvisibleCases: true or false
	// @param specifiedDate: string format ('Y-m-d')
	public function GetAllCasesContent($includeInvisibleCases=false,$specifiedStartDate=null){
		$db = &$this->db;
		if($specifiedStartDate === null){
			$sqlVisible = $includeInvisibleCases?'':' where visible=1';
			$query = 'select * from `case`'.$sqlVisible.' order by start_date desc';
			$sth = $db->ExecuteQuery($query);
		}else{
			$sqlVisible = $includeInvisibleCases?'':' and visible=1';
			$query = 'select * from `case` where start_date>=?'.$sqlVisible.' order by start_date desc';
			$sth = $db->PrepareQuery($query);
			$db->ExecutePreparedQuery($sth,array($specifiedStartDate));
		}
		
		return $db->GetAllRows($sth);
	}
	
	public function GetSpecifiedCase($case_id){
		$db = &$this->db;
		$query = 'select * from `case` where case_id=?';
		$sth = $db->PrepareQuery($query);
		$db->ExecutePreparedQuery($sth,array($case_id));
		if($row = $db->GetNextRow($sth)){
			return $row;
		}else{
			throw new Exception('Invalid Case ID.');
		}
	}
	
	public function ModifyBasicInformation($data){
		$name = strval($data['name']);
		$introduction = strval($data['introduction']);
		$cooperation = strval($data['cooperation']);
		$contact = strval($data['contact']);
		$db = &$this->db;
		$query = 'update `basic` set `content`=? where `property`=?';
		$sth = $db->PrepareQuery($query);
		$flag = $db->ExecutePreparedQuery($sth,array($name,'name'));
		if(!$flag){ throw new Exception('不能更新機構名稱'); }
		$flag = $db->ExecutePreparedQuery($sth,array($introduction,'introduction'));
		if(!$flag){ throw new Exception('不能更新機構介紹'); }
		$flag = $db->ExecutePreparedQuery($sth,array($cooperation,'cooperation'));
		if(!$flag){ throw new Exception('不能更新合作單位'); }
		$flag = $db->ExecutePreparedQuery($sth,array($contact,'contact'));
		if(!$flag){ throw new Exception('不能更新聯絡資料'); }
	}
	
	//@return new case_id
	public function NewCase($title,$content,$icon,$start_date){
		$db = &$this->db;
		$query = 'insert into `case`(`title`,`content`,`icon`,`start_date`) values(?,?,?,?)';
		$sth = $db->PrepareQuery($query);
		$flag = $db->ExecutePreparedQuery($sth,array($title,$content,$icon,$start_date));
		if($flag){
			return $db->GetLastInsertID();
		}else{
			return null;
		}
	}
	
	public function ModifyCase($case_id,$title,$content,$icon,$start_date){
		$db = &$this->db;
		$query = 'update `case` set `title`=?,`content`=?,`icon`=?,`start_date`=? where `case_id`=?';
		$sth = $db->PrepareQuery($query);
		return $db->ExecutePreparedQuery($sth,array($title,$content,$icon,$start_date,$case_id));
	}
	
	public function ModifyCaseSpecifiedProperty($case_id,$property,$content){
		$db = &$this->db;
		$query = 'update `case` set `'.$property.'`=? where `case_id`=?';
		$sth = $db->PrepareQuery($query);
		return $db->ExecutePreparedQuery($sth,array($content,$case_id));
	}
	
	public function DeleteCase($case_id){
		$db = &$this->db;
		$query = 'delete from `case` where case_id=?';
		$sth = $db->PrepareQuery($query);
		return $db->ExecutePreparedQuery($sth,array($case_id));
	}
	
}
?>