<?php
//一
require_once dirname(__DIR__).'/lib/FunctionLibrary.php';

try{
	$lib = new FunctionLibrary();
	$menu_id = strval($_REQUEST['menu_id']);
	if($menu_id == ''){ throw new Exception('Menu ID is required.'); }
	$data = $lib->GetSpecifiedMenuItem($menu_id);
}catch(Exception $e){
	echo $e->getMessage();
	exit;
}
//var_dump($data);exit;
?>
<!DOCTYPE html>
<html>
<head>
	<title>菜單編輯</title>
	<script src="tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
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
	
  toolbar: "insertfile undo redo | styleselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
	
  // ===========================================
  // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
  // ===========================================
	
  relative_urls: false});
function get_editor_content() {
  // Get the HTML contents of the currently active editor
  //console.debug(tinyMCE.activeEditor.getContent());

  //method1 getting the content of the active editor
  alert(tinyMCE.activeEditor.getContent());
  //method2 getting the content by id of a particular textarea
  alert(tinyMCE.get('myarea1').getContent());
}
$( document ).ready(function() {


  $( "#submit" ).click(function() {
    $html = tinyMCE.activeEditor.getContent();
    $date = $("#date").val();
    $title = $("#title").val();
    $m_id = "<?php echo $menu_id; ?>";

    $.ajax({
              url: "api.php",
              type: "POST",
              data:{
                    action : 'menu_update',
                    menu_id : $m_id,
                    title : $title,
                    content : $html
                    },
              success: function(data) {
                console.log(data);
                alert("Done");
              },
              error: function(eer) {
                 //console.log("error" + eer);
              }
          });
        });


  
});
    </script>

</head>
<style type="text/css">
	
	#event_img{
		max-width:128px;
		max-height:128px;
	}
	#icon_preview{
		border: 1px solid blue;
	}
   #mceu_20{
  display:none;
  } 
	#mce_0_ifr{
		min-height: 500px;
	}
</style>

<body>
<!-- <div>菜單標題:<?php echo $data['title']; ?></div>
<div>菜單內容:<?php echo $data['content']; ?></div> -->

<div id="upper">

		<h>菜單標題: </h> <input id="title" value="<?php echo $data['title'];?>"></input>
	<br><br>

</div>
	<textarea><?php echo $data['content'];?></textarea>
	<br>
	<div><button id="submit" />提交修改菜單</div>
	<br>
</body>
</html>