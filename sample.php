<?php
//require_once 'lib/FunctionLibrary.php';
//$lib = new FunctionLibrary();
//$data = $lib->GetBasicInformation(); // data format: array(property=>content)
//$contact = htmlentities($data['contact'], ENT_QUOTES, "UTF-8");
//$cooperation = htmlentities($data['cooperation'], ENT_QUOTES, "UTF-8");
//$introduction = htmlentities($data['introduction'], ENT_QUOTES, "UTF-8");
//$name = htmlentities($data['name'], ENT_QUOTES, "UTF-8");
//$cases = $lib->GetAllCasesContent();
?>
<!DOCTYPE html>
<html>
<head>
<title>_</title>

<script src="jquery.js"></script>
<script>
	var basicInfo = {};
	var casesList = {};
	
	$(document).ready(function(){
		Initialization();
	});
	
	function Initialization(){
		try{
			$.ajaxSetup({async: false});
			// get basic info
			$.post('basic_api.php',{'action':'info'},function(response){
				var jsonData = JSON.parse(response);
				if(jsonData['err'] == 0){
					basicInfo = jsonData['data'];
				}else{
					throw '';
				}
			});
			// get cases list
			$.post('basic_api.php',{'action':'all_cases'},function(response){
				var jsonData = JSON.parse(response);
				if(jsonData['err'] == 0){
					casesList = jsonData['data'];
				}else{
					throw '';
				}
			});
			var name = basicInfo['name'];
			$(document).prop('title',name);
			$('#org_name').html(name);
			DisplayIntro();
		}catch(e){
			alert('讀取數據時發生錯誤，本網頁的內容暫時不能查看。');
		}
	}
	
	function DisplayCases(){
		$('#main_menu a').removeClass('selected_menu');
		$('#btn_Cases').addClass('selected_menu');
		var container = $('<div />');
		for(var key in casesList){
			var row = casesList[key];
			var case_id = row['case_id'];
			var start_date = row['start_date'];
			var title = row['title'];
			var icon = row['icon'];
			var content = row['content'];
			
			var divCase = $('<div />');
			divCase.css({'vertical-align':'top'});
			var divCaseIcon = $('<img />');
			divCaseIcon.attr({'src':'admin/'+icon});
			divCaseIcon.css({'max-width':128,'max-height':128});
			divCaseIcon.addClass('inline-obj');
			divCase.append(divCaseIcon);
			var divCaseTitle = $('<div>'+String(start_date)+'<br>'+String(title)+'</div>');
			divCaseTitle.addClass('inline-obj');
			divCase.append(divCaseTitle);
			container.append(divCase);
			container.append('<hr>');
		}
		$('#main_content').html(container);
		
	}
	
	function DisplayIntro(){
		$('#main_menu a').removeClass('selected_menu');
		$('#btn_Intro').addClass('selected_menu');
		$('#main_content').html(basicInfo['introduction']);
	}
	
	function DisplayCooperator(){
		$('#main_menu a').removeClass('selected_menu');
		$('#btn_Cooperator').addClass('selected_menu');
		$('#main_content').html(basicInfo['cooperation']);
	}
	
	function DisplayContact(){
		$('#main_menu a').removeClass('selected_menu');
		$('#btn_Contact').addClass('selected_menu');
		$('#main_content').html(basicInfo['contact']);
	}
</script>

<style>
	.basic-block{
		
	}
	
	.clear-spacing{
		margin:0;
		padding;
		line-height:0;
	}
	
	.inline-obj{
		display:inline-block;
	}
	
	.logo{
		width:160px;
		height:160px;
	}
	
	#main_menu{
		background-color:#8bb9dc;
		color:white;
		line-height:32px;
	}
	.menu_item{
		display:inline-block;
		background:rgba(0,0,0,.12);
		color:white;
		font-weight:bold;
		text-decoration:none;
		font-size:16px;
		padding:8px;
		margin:1px 1px;
	}
	.selected_menu{
		background-color: orange;
	}
	
	#main_content{
		background-color:#CCE2FF;
		color:rgba(0,0,0,.87);
		padding:10px;
		min-height:400px;
	}
	.content_date{
	}
	.content_title{
	}
</style>
</head>
<body>
	<div class="basic-block" id="org_logo"><img class="logo" src="asset/image/logo.png"></div>
	<!--div class="basic-block" id="org_qr" style="position:absolute;top:8px;left:0px;width:100%;text-align:right;"><img class="logo" src="asset/image/prime_QR.png" style="margin-right:10px;"></div-->
	<div class="basic-block" id="org_name" style="color:rgba(0, 0, 255, 0.74); font-size:20px; font-weight:bold; margin:4px 0px;"></div>
	<div class="basic-block" id="main_menu"><a id="btn_Intro" href="javascript:" class="menu_item" onclick="DisplayIntro()">我們是誰</a><a id="btn_Cases" href="javascript:" class="menu_item" onclick="DisplayCases()">我們的案例</a><a id="btn_Cooperator" href="javascript:" class="menu_item" onclick="DisplayCooperator()">我們的伙伴</a><a id="btn_Contact" href="javascript:" class="menu_item" onclick="DisplayContact()">聯絡我們</a></div>
	<div class="basic-block" id="main_content"></div>
	<div class="basic-block" id="org_ref" style="text-align:right;">Copyright@2017 Prime Marketing and promotional Services Co., Ltd. All rights reserved.</div>
</body>
</html>