<!DOCTYPE html>
<html>
<head>
<title>PrimeMPS</title>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
</head>
<style type="text/css">
	
	body{
		background: black;
		color: white;
	}
	.cen{
		position: absolute;
		font-size: x-large;
		/* left: 50%; */
		top: 50%;
		/* margin: -4vw -3vw; */
		padding: 0vw 0vw;
		width: 99%;
	}
	#f2{
		display:none;
		line-height: 65px;
	    font-size: 41px;
	    position: relative;
		/* margin: -5vw 7vw; */
	    top: -15vh;
	    padding-left: 25px;
	    /*left: 20%;*/
	    /*top: -2em;*/
	}
	#f{
		line-height: 40px;
		font-size: xx-large;
	    position: relative;
   		/*left: -12%;*/
   		top:-10vh;
	}
	#f3{
		display: none;
	}
	img#f3 {
	    /* margin: -8vw 4vw; */
	    width: 17%;
	    position: relative;
	    top: -21vh;
		left:-1vh;
	}
	video {
	    min-width: 100%;
	    min-height: 100%;
	    position: fixed;
	    left: 0;
	    top: 0;
	    z-index: -1;
	    opacity: 0.3;
	}
	#skip{
	    /* float: right; */
		opacity: 0.80;
		/**/
	    position: absolute;
	    font-size: xx-large;
	    top: 64%;
	    /* margin: -4vw 40vw; */
	    /* padding: 0vw 0vw; */
	    left: 48.5%;
	    width: auto;
	}
	#skip:hover{
		opacity: 1;
		cursor:pointer;

	}

</style>

<script type="text/javascript">
function animation(){
	$("#f").fadeIn(800);
	//return;
	$.when($("#f").fadeOut(800)).done(function(){
		$("#f2").fadeIn(800);
		//return;
		$.when($("#f2").fadeOut(800)).done(function(){
			//show logo
			$.when($("#f3").fadeIn(800)).done(function(){
				//return;
				setTimeout(function(){ window.location = "home.php"; }, 3000);
				
			});
		});
	});
	
	//
}
$( document ).ready(function() {
  //$("#cen").hide();	
  setTimeout(function(){ animation(); }, 500);
  $("#skip").click(function(){
  	window.location = "home.php";
  });
});	

</script>
<body>

<video autoplay loop>
  <source src="https://dl.dropboxusercontent.com/u/3465596/loops/loop.mp4" type="video/mp4">
  <source src="https://dl.dropboxusercontent.com/u/3465596/loops/loop.ogg" type="video/ogg">
  <source src="https://dl.dropboxusercontent.com/u/3465596/loops/loop.webm" type="video/webm">
  Your browser does not support the <code>video</code> element.
</video>

<div style="text-align:center;" class="cen">
　<div id="f" style="margin:0 auto;">專業策劃 為你導航<br>Professional Management to Guide your Success</div>
  <div id="f2" >MICE Planner<br>Evenet Management<br>PR Consultant</div>
  <img id="f3" src="logo_none.png">
</div>
<div id="skip">
	Skip
</div>

</body>
</html>