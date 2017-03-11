<!DOCTYPE html>
<html>
<head>
	<title>菜單管理</title>
	<script src="jquery.js"></script>
	<script>
	try{
		var backend = 'api.php';
		$(document).ready(function(){
			Inititalization();
		});
		
		function Inititalization(){
			S_GetMenuList();
		}
		
		function S_GetMenuList(){
			$.post(backend,{'action':'menu_list'},function(response){
				var json = JSON.parse(response);
				if(json['err'] == 0){
					var info = json['data'];
					for(var key in info){
						var row = info[key];
						var menu_id = row['mid'];
						var enable = row['enable'];
						var removable = row['removable'];
						var sequence = row['sequence'];
						var subkey = row['subkey'];
						var title = row['title'];
						
						var tr = $('<tr />');
						tr.attr({'id':('row_menu_'+String(menu_id))});
						
						// td for enable
						var td = $('<td />');
						var checkBox = $('<input type="checkbox" value="1">');
						checkBox.click(S_MenuDisplay);
						checkBox.attr({'data-menu_id':menu_id});
						checkBox.prop('checked',enable==1?true:false);
						td.append(checkBox);
						tr.append(td);
						
						// td for title
						var td3 = $('<td />');
						td3.text(title);
						tr.append(td3);
						
						// td for edit
						var td4 = $('<td />');
						if(subkey){
							td4.html('<a href="javascript:" onclick="S_UpdateTitle(\''+menu_id+'\')"><img src="icon/edit.png" alt="更改標題" title="更改標題" width="16px" height="16px"></a>');
						}else{
							td4.html('<a target="_blank" href="menu_editor.php?menu_id='+String(menu_id)+'"><img src="icon/content.png" alt="修改內容" title="修改內容" width="16px" height="16px"></a> ');
						}
						var deleteBtn = $('<img />');
						deleteBtn.attr({'data-menu_id':menu_id,'src':'icon/delete.png','alt':'delete','title':'delete','width':'16px','height':'16px'});
						deleteBtn.css({'cursor':'pointer'});
						deleteBtn.click(S_DeleteMenu);
						td4.append(deleteBtn);
						tr.append(td4);
						
						if(subkey){
							// td for special item
							var td5 = $('<td />');
							td5.text('Special Item');
							tr.append(td5);
						}
						
						$('#html_MenuList').append(tr);
					}
				}else{
					throw json['data'];
				}
			});
		}
		
		function S_CreateMenu(){
			var title = prompt('Please input the menu title:');
			if(title){
				var uploadData = {'action':'menu_add','title':title};
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
		
		function S_UpdateTitle(menu_id){
			var title = prompt('Please input the new title:');
			if(title){
				var uploadData = {'action':'menu_title','menu_id':menu_id,'title':title};
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
		
		function S_DeleteMenu(){
			if(confirm('確定刪除此項目嗎?')){
				var menu_id = $(this).attr('data-menu_id');
				var uploadData = {'action':'menu_delete','menu_id':menu_id};
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
		
		function S_MenuDisplay(){
			var menu_id = $(this).attr('data-menu_id');
			var enable = $(this).prop('checked')?1:0;
			var uploadData = {'action':'menu_display','menu_id':menu_id,'enable':enable};
			$.post(backend,uploadData,function(response){
				var data = JSON.parse(response);
				if(data['err'] == 0){
				}else{
					throw data['data'];
				}
			});
		}
		
		function S_ReorderMenu(){
		}
	}catch(e){
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
<h1>菜單管理</h1>
<p><a href="javascript:" onclick="S_CreateMenu()"><img src="icon/add.png" alt="" title="新增菜單項目">新增菜單項目</a></p>
<p>更改菜單項目顯示順序</p>
<table border="1" id="table_Content">
	<thead>
		<tr><th>公開顯示</th><th>標題</th><th>操作</th></tr>
	</thead>
	<tbody id="html_MenuList">
	</tbody>
</table>
</body>
</html>