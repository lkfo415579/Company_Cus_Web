<?php
//一
// list of sub-pages
?>
<!DOCTYPE html>
<html>
<head>
	<title>案例</title>
	<script src="jquery.js"></script>
	<script>
	try{
	
		var backend = 'api.php';
		$(document).ready(function(){
			Initialization();
		});
		
		function Initialization(){
			S_GetCaseList();
		}
		
		function S_GetCaseList(){
			$.post(backend,{'action':'all_cases'},function(response){
				var data = JSON.parse(response);
				if(data['err'] == 0){
					var info = data['data'];
					for(var key in info){
						var row = info[key];
						var case_id = row['case_id'];
						var visible = row['visible'];
						var start_date = row['start_date'];
						var title = row['title'];
						
						var tr = $('<tr />');
						tr.attr({'id':('row_case_'+String(case_id))});
						
						// td for visible
						var td = $('<td />');
						var checkBox = $('<input type="checkbox" value="1">');
						checkBox.click(S_CaseDisplay);
						checkBox.attr({'data-case_id':case_id});
						checkBox.prop('checked',visible?true:false);
						td.append(checkBox);
						tr.append(td);
						
						//td for date
						var td2 = $('<td />');
						td2.text(start_date);
						tr.append(td2);
						
						// td for title
						var td3 = $('<td />');
						td3.text(title);
						tr.append(td3);
						
						// td for edit
						var td4 = $('<td />');
						td4.html('<a target="_blank" href="case_editor.php?case_id='+String(case_id)+'"><img src="icon/edit.png" alt="修改" title="修改" width="16px" height="16px"></a> ');
						var deleteBtn = $('<img />');
						deleteBtn.attr({'data-case_id':case_id,'src':'icon/delete.png','alt':'delete','title':'delete','width':'16px','height':'16px'});
						deleteBtn.css({'cursor':'pointer'});
						deleteBtn.click(S_DeleteCase);
						td4.append(deleteBtn);
						tr.append(td4);
						
						$('#html_CasesList').append(tr);
					}
				}else{
					throw data['data'];
				}
			});
		}
		
		function S_CreateNewCase(){
			var title  = prompt('請輸入標題');
			if(title){
				var uploadData = {'action':'add_c','title':title,'content':'','icon':'','start_date':''};
				$.post(backend,uploadData,function(response){
					var data = JSON.parse(response);
					if(data['err'] == 0){
						location.reload();
					}else{
						throw data['data'];
					}
				});
			}
		}
		
		function S_DeleteCase(){
			if(confirm('確定刪除此項目嗎？')){
				var case_id = $(this).attr('data-case_id');
				var uploadData = {'action':'delete_c','case_id':case_id};
				$.post(backend,uploadData,function(response){
					var data = JSON.parse(response);
					if(data['err'] == 0){
						$('#row_case_'+String(case_id)).remove();
					}else{
						throw data['data'];
					}
				});
			}
		}
		
		function S_CaseDisplay(){
			var case_id = $(this).attr('data-case_id');
			var visible = $(this).prop('checked')?1:0;
			var uploadData = {'action':'display_c','case_id':case_id,'visible':visible};
			$.post(backend,uploadData,function(response){
				var data = JSON.parse(response);
				if(data['err'] == 0){
				}else{
					throw data['data'];
				}
			});
		}
		
	}catch(err){
		alert(err.message);
	}
	</script>
	
	<style>
		#table_Content{
			border:1px solid green;
			border-collapse:collapse;
			text-align:center;
		}
		#table_Content>tbody>tr:hover{
			background-color:#bbb;
		}
		#table_Content th{
			padding:4px;
		}
		#table_Content td{
			padding:4px;
		}
	</style>
</head>
<body>
<h1>案例</h1>
<p><a href="javascript:" onclick="S_CreateNewCase()"><img src="icon/add.png" alt="" title="新增案例">新增案例</a></p>
<table border="1" id="table_Content">
	<thead>
		<tr><th>公開顯示</th><th>日期</th><th>標題</th></tr>
	</thead>
	<tbody id="html_CasesList">
	</tbody>
</table>
</body>
</html>