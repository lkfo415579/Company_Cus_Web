<?php
//一
// list of sub-pages
?>
<!DOCTYPE html>
<html>
<head>
	<title>基本資料</title>
	<script src="jquery.js"></script>
	<script src="tinymce/js/tinymce/tinymce.min.js"></script>
	<script>
	try{
	
		var backend = 'api.php';
		
		$(document).ready(function(){
			Initialization();
		});
		
		function Initialization(){
			S_GetData();
		}
		
		function S_GetData(){
			$.post(backend,{'action':'info'},function(response){
				var data = JSON.parse(response);
				if(data['err'] == 0){
					var info = data['data'];
					$('#txt_name').val(info['name']);
					$('#txt_contact').val(info['contact']);
					$('#txt_cooperation').val(info['cooperation']);
					$('#txt_introduction').val(info['introduction']);
				}else{
					throw data['data'];
				}
			});
		}
		
		function S_SubmitData(){
			for (i=0; i < tinyMCE.editors.length; i++){
			    var content = tinyMCE.editors[i].getContent();
				switch(tinyMCE.editors[i].id) {
				    case 'txt_introduction':
				        var introduction = tinyMCE.editors[i].getContent();
				        break;
				    case 'txt_contact':
				        var contact = tinyMCE.editors[i].getContent();
				        break;
				    case 'txt_cooperation':
				        var cooperation = tinyMCE.editors[i].getContent();
				        break;
				    default:
				        break;
				}
			    //console.log('Editor-Id(' + tinyMCE.editors[i].id + '):' + content);
			}
			//console.log($html);
			//return;
			var name = $('#txt_name').val();

			//var introduction = $('#txt_introduction').val();
			//var contact = $('#txt_contact').val();
			//var cooperation = $('#txt_cooperation').val();
			
			$.post(backend,{'action':'edit_desc','name':name,'introduction':introduction,'cooperation':cooperation,'contact':contact},function(response){
				var data = JSON.parse(response);
				if(data['err'] == 0){
					alert('成功更新資料');
				}else{
					throw data['data'];
				}
			});
		}
	}catch(err){
		alert(err.message);
	}
	</script>
	
	<script type="text/javascript">
    tinymce.init(
	  	{
  selector: 'textarea',  // change this value according to your HTML

  // ===========================================
  // INCLUDE THE PLUGIN
  // ===========================================
	
  plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen textcolor",
    "insertdatetime media table contextmenu paste jbimages"
  ],
	
  // ===========================================
  // PUT PLUGIN'S BUTTON on the toolbar
  // ===========================================
	
  toolbar: "undo redo | styleselect forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
	
  // ===========================================
  // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
  // ===========================================
	
  relative_urls: false});		


	</script>



	<style>
		textarea{
			min-width:400px;
			min-height:200px;
			margin:4px;
		}
		
		input[type=text]{
			min-width:400px;
			margin:4px;
		}
		#mceu_55-open{
			display:none;
		}
	</style>
</head>
<body>
<h1>基本資料</h1>
<table border="0">
	<tbody>
		<tr><th>機構名稱: </th><td><input type="text" id="txt_name"></td></tr>
		<tr><th>機構介紹: </th><td><textarea id="txt_introduction"></textarea></td></tr>
		<tr><th>聯絡資料: </th><td><textarea id="txt_contact"></textarea></td></tr>
		<tr><th>合作單位: </th><td><textarea id="txt_cooperation"></textarea></td></tr>
		<tr><th>&nbsp;</th><td><input type="button" value="提交更新" onclick="S_SubmitData()" style="height:50px;"></td></tr>
	</tbody>
</table>
</body>
</html>