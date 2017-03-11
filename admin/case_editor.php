<?php
//一
require_once dirname(__DIR__).'/lib/FunctionLibrary.php';
$lib = new FunctionLibrary();

$case_id = strval($_REQUEST['case_id']);
try{
$data = $lib->GetSpecifiedCase($case_id);
}catch(Exception $e){
	echo $e->getMessage();
	exit;
}
//var_dump($data);
//var_dump($_SERVER['DOCUMENT_ROOT']);
?>
<!DOCTYPE html>
<html>
<head>
	<title>案例編輯</title>
	  <script src="tinymce/js/tinymce/tinymce.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
    <script type="text/javascript">
    $( function() {
        $( "#date" ).datepicker();
        $( "#date" ).datepicker( "option", "dateFormat", "yy-mm-dd");
        $("#date").val("<?php echo $data['start_date'];?>");
      } );
    </script>
	  <script>

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
  function get_image_name(){
        filename = $("#fileToUpload").val().split("\\");
        //console.log(filename);
        filename = filename.pop();
        return "asset/upload_image/icon/"+filename;
  }

  //update icon


  $( "#submit" ).click(function() {
    $html = tinyMCE.activeEditor.getContent();
    $date = $("#date").val();
    $title = $("#title").val();
    $case_id = "<?php echo $case_id; ?>";
    //console.log($("#fileToUpload"));
    var filename = "<?php echo $data['icon']; ?>";
    //$("#fileToUpload").val()
/*    if ($("#fileToUpload").val() !== ""){
        $icon = get_image_name();
    }else{
      $icon = filename;
    }*/
    if ($("#fileToUpload").val() == "")
      $icon = filename;
    else
      $icon = $icon.substring(3);

    $.ajax({
              url: "api.php",
              type: "POST",
              data:{
                    action : 'modify_c',
                    case_id : $case_id,
					start_date: $date,
                    title : $title,
                    content : $html,
                    icon : $icon
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

  $( "#fileToUpload" ).change(function() {
      $("#upload").submit();
  });

$('form').submit(function(){
  //console.log($(this)[0]);
  var formData = new FormData($('#uploadForm')[0]);
  $.ajax({  
    type: "POST",  
    url: "upload.php",  
    data: formData,
    contentType: false,
    processData: false,
    success: function(data) {  
      //console.log(data);
      //$icon = get_image_name();
      $icon = data;
      console.log($icon);
      //"../"
      $("#event_img").attr('src',$icon);
    }  
  });

});

  /*
    $action = 'modify_c'   
      $case_id = strval($_REQUEST['case_id']);
      $title = strval($_REQUEST['title']);
      $content = strval($_REQUEST['content']);
      $icon = strval($_REQUEST['icon']);
      $start_date = strval($_REQUEST['start_date']); // date format: "Y-m-d"
   */
  
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
	<div id="upper">
		<h>日期: </h><input id="date" value="<?php echo $data['start_date'];?>"></input>
		<br>
		<h>標題: </h> <input id="title" value="<?php echo $data['title'];?>"></input>
	<br><br>
    <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
        上傳圖片修改ICON:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input id="upload" type="submit" value="上傳圖片" name="submit" style="display:none;">
    </form>
		<div id="icon_preview">ICON 預覽:<br><img id="event_img" src="../<?php echo $data['icon'];?>"></img></div>
		<br>
	</div>
	<textarea><?php echo $data['content'];?></textarea>
	<br>
	<div><button id="submit" />提交修改案例</div>
	<br>
</body>
</html>