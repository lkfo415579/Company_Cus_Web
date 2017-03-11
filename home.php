<?php
  require_once 'lib/FunctionLibrary.php';
  $LIB = new FunctionLibrary();
/*  echo json_encode($LIB->GetBasicInformation());
  exit();*/
?>
<!DOCTYPE html>
<html>
<head>
<title>PRIME</title>
</head>

<script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">
<script src="semantic/semantic.min.js"></script>


<style type="text/css">
/*   .c_name{
  width: 54%;
} */
  .title{
    /* top: -14%;
    left: -7%;
    float: right;
    position: relative; */
    /* height: 23px; */
    display: flex;
    font-size: large;
    font-family: monospace;
    color: rgba(0, 0, 255, 0.74);
    font-weight: bold;
    font-family: 'cwTeXHei', serif;
  }
  .container{
    background-color: #3267a5;;
    /* height: inherit; */
    height: 70vh;
  }
  #menu{
    font-family: 'cwTeXHei', serif;
    height: inherit;
    background-color: #8bb9dc;
    color: white;
  }
  #footer{
    position: relative;
    top: 3%;
    float: right;
    left: -1%;
  }
  .ten.wide.column{
    color:rgba(255, 255, 255, 0.68);
    font-size: large;
  }

  /*mouse overflow*/
  .ten.wide.column .insider{
    height: 100%;overflow: auto;
  }
  div.ten.wide.column{
    width:74.3%!important;
  }
  /*mouse overflow*/

/*modify*/  
.ui.vertical.menu .item{
  color: white;
  font-family: 'cwTeXHei', serif;
  /* font-family: monospace; */
}
.ui.menu .active.item:hover, .ui.vertical.menu .active.item:hover {
    background-color: rgba(0, 0, 0, 0.12);
    color: rgb(63, 97, 133);
}
.ui.vertical.menu .active.item {
    background: rgba(0,0,0,.12);
}
.ui.pointing.menu .active.item:after, .ui.pointing.menu .active.item:hover:after, .ui.vertical.pointing.menu .active.item:after, .ui.vertical.pointing.menu .active.item:hover:after {
    background-color: #7ba3c2;
}
.ui.vertical.pointing.menu .item:after {
    border-top: 1px solid #536d82;
    border-right: 1px solid #536d82;
}

.ui.container {
  margin-left: 0px!important;
  
}
.ui.grid.container{
  width: 100%!important;
  top: -30px;
  /* position: relative; */
  position: static;
}
.ui.small.image{
  display: inline;
}
.ui.huge.header{
  font-family: 'cwTeXHei', serif;
}
.ui.items>.item>.content>.header{
  color:white;
}
.ui.items>.item .meta{
  color:rgba(255, 255, 255, 0.52);
}
.ui.items>.item .extra{
 color:#8282f7; 
}

/*cus*/
.ui.list .list>.item a.header, .ui.list>.item a.header{
  color:rgba(255,255,255,.9)!important;
}
.ui.list .list>.item a.header, .ui.list>.item .description{
  color:rgba(255, 255, 255, 0.48)!important;
}
.ui.list .list>.item a.header:hover, .ui.list>.item a.header:hover {
    color: #ff4141!important;
}
.item:hover {
  background: yellowgreen;
  cursor: pointer;
}

#logo{
    width: 35%;
    left: 50%;
    position: relative;
}
/*font*/
@font-face {
  font-family: 'cwTeXHei';
  font-style: normal;
  font-weight: 500;
  src: url(//fonts.gstatic.com/ea/cwtexhei/v3/cwTeXHei-zhonly.eot);
  src: url(//fonts.gstatic.com/ea/cwtexhei/v3/cwTeXHei-zhonly.eot?#iefix) format('embedded-opentype'),
       url(//fonts.gstatic.com/ea/cwtexhei/v3/cwTeXHei-zhonly.woff2) format('woff2'),
       url(//fonts.gstatic.com/ea/cwtexhei/v3/cwTeXHei-zhonly.woff) format('woff'),
       url(//fonts.gstatic.com/ea/cwtexhei/v3/cwTeXHei-zhonly.ttf) format('truetype');
}
body{
  font-family: 'cwTeXHei', serif;
}

/*customeize*/
.ui.little.image, .ui.little.images .image, .ui.little.images img, .ui.little.images svg {
    width: 100px;
    height: auto;
    font-size: .92857143rem;
}


</style>

<script>
$(document).ready(function() {

  function update_menu(){
    $menu_list = JSON.parse('<?php echo json_encode($LIB->GetMenuList());?>');
    $("#menu").html("");
    $.each($menu_list, function (index, value) {
      //console.log(value);
      $("#menu").append('<a class="item mm" id="'+value.mid+'">'+value.title+"</a>");
      //add view
      $(".container").append('<div id="context'+value.mid+'" class="ten wide column" style="width:74.8%!important;"><div class="ui sizer vertical insider" style="height: 100%;overflow: auto;"></div></div>');
    });
    $("#1").addClass('active');
    //get_menu_data(1);
    //
    $('.item.mm').on('click', function() {
        $('.ui .item').removeClass('active');
        $(this).addClass('active');
        //
        var id = $(this).attr('id');
        //console.log($("context"+id));
        $(".ten").hide();
        $("#context"+id).show();
        //update context
        if (id == "1"){
          update_case();
          //overflow-y for ccac
          //console.log($("#context1 .insider").html());
          //console.log($("#context1 .insider").offset());
          $("#context1 .insider").scrollTop($last_scroll_v);
          return;
        }
        get_menu_data(id);    
    });


    //
    //update_case();
  }



  function get_menu_data(id){
    
    $data = '';
    $.ajax({
              url: "lib/menu_get.php",
              type: "POST",
              data:{
                    id : id
                    },
              dataType:'json',
              success: function(data) {
                //console.log(data);
                //console.log($company_info);
                var tmp_context = "";
                //introduction
                //tmp_context += '<div style="color:white"class="ui huge header">公司簡介</div><div style="color:#8282f7" class="ui medium header">服務範圍</div>';
                if (data['content'] == null)
                  data['content'] = "";
                tmp_context += data['content'];
                $("#context"+id+" .insider").html();
                $("#context"+id+" .insider").html(tmp_context);
                //alert("Done");
              },
              error: function(eer) {
                 console.log("error" + eer);
              }
          });
    //console.log($data);
    //$company_info = JSON.parse($data);
    //console.log($company_info);
    /*var tmp_context = "";
    //introduction
    tmp_context += '<div style="color:white"class="ui huge header">公司簡介</div><div style="color:#8282f7" class="ui medium header">服務範圍</div>';
    tmp_context += $company_info['introduction'];
    $("#context1").html();
    $("#context1 .insider").html(tmp_context);
    //cooperation
    tmp_context = "";
    tmp_context += '<div style="color:white"class="ui huge header">我們的伙伴</div>';
    tmp_context += $company_info['cooperation'];
    $("#context3").html();
    $("#context3 .insider").html(tmp_context);*/
  }
  $case_list = "";
  function update_case(){
    //console.log();
    $case_list = JSON.parse('<?php echo addslashes(json_encode($LIB->GetAllCasesContent()));?>');
    var tmp_context = '<div class="ui massive relaxed divided list">';

    $.each($case_list, function (index, value) {
      tmp_context += '<div class="item ccac" id="h_'+index+'"><img class="little ui image" src="'+value.icon+'"><div class="content"><a class="header">'+value.title+'</a><div class="description">'+value.start_date+'</div></div></div>';
    });
    tmp_context += "</div>";
    //console.log($("#context1 .insider"));
    //$("#context1 .insider").html(tmp_context);
    $("#context1").html('<div class="ui sizer vertical insider" style="height: 100%;overflow: auto;">'+tmp_context+'</div>');
    //console.log($case_list);
    //registry for headers
    $('.ccac').on('click', function() {
      var id = $(this).attr("id").slice(-1);
      $("#context1 .insider").html();
      $("#context1 .insider").html($case_list[id]['content']);
      //record scrobar value
      //console.log('999 ',$scroll_v);
      $last_scroll_v = $scroll_v;
    });
    //return tmp_context;
    //
    //scroll bar registry
    $('#context1 .insider').scroll(function(e){
      //console.log();
      $scroll_v = $("#context1 .insider").scrollTop();
      //console.log($scroll_v);
      //e.preventDefault();
    });
    //
  }
  $scroll_v = 0;
  $last_scroll_v = 0;
  /*****INITIALIZATION*******/
  function initial(){


    update_menu();
    update_case();
    //interface display
    $(".ten").hide();
    $("#context1").show();
    //JSON.stringify
    //JSON.parse
    //console.log();
 /*   $data = '<?php echo addslashes(json_encode($LIB->GetBasicInformation()));?>';
    //console.log($data);
    $company_info = JSON.parse($data);
    //console.log($company_info);
    var tmp_context = "";
    //introduction
    tmp_context += '<div style="color:white"class="ui huge header">公司簡介</div><div style="color:#8282f7" class="ui medium header">服務範圍</div>';
    tmp_context += $company_info['introduction'];
    $("#context1").html();
    $("#context1 .insider").html(tmp_context);
    //cooperation
    tmp_context = "";
    tmp_context += '<div style="color:white"class="ui huge header">我們的伙伴</div>';
    tmp_context += $company_info['cooperation'];
    $("#context3").html();
    $("#context3 .insider").html(tmp_context);*/
/*    $.each($company_info, function (index, value) {
        tmp_context = "";
        if (value['property'] == 'introduction'){
          tmp_context += '<div style="color:white"class="ui huge header">公司簡介</div><div style="color:#8282f7" class="ui medium header">服務範圍</div>';
          tmp_context += value['content'];
          $("#context1").html();
          $("#context1 .insider").html(tmp_context);
        }
        if (value['property'] == 'cooperation'){
          tmp_context += '<div style="color:white"class="ui huge header">我們的伙伴</div>';
          tmp_context += value['content'];
          $("#context3").html();
          $("#context3 .insider").html(tmp_context);
        }
        //////
    });*/

  }
  initial();

});
</script>

<body>


<div style="cursor: pointer;display:-webkit-inline-box" onclick="window.location='index.php';">
  <img class="ui small image" src="asset/image/logo.png">
  <!--img class="ui small image" src="asset/image/prime_QR.png"-->
  <img class="ui image" id="logo" src="asset/image/featured.png">
</div>
<div class="title">
  <p class="c_name" style="margin-right: 40px;">派意市場推廣服務有限公司</p>
  <p class="c_name">派意國際會議展覽(北京)有限公司</p>
</div>
<!-- <p>This is a paragraph.</p> -->



<div class="container ui four column very stackable grid">
  <div id="menu" class="ui vertical pointing menu massive column">
    <a class="item active mm" id="1">
      我們是誰
    </a>
    <a class="item mm" id="2">
      我們的案例
    </a>
    <a class="item mm" id="3">
      我們的伙伴
    </a>
  </div>
  <!-- <div id="context1" class="ten wide column">
      <div class="ui sizer vertical insider">
        <div style="color:white" class="ui huge header">公司簡介</div>
        <div style="color:#8282f7" class="ui medium header">服務範圍</div>
        <p>派意是一間專業從事國際會議展覽、活動統籌、市場推廣和廣告公關的策劃公司，總部位於澳門。自一九九二年創業以來，公司憑著專業理念、創新設計，以敏銳的市場觸覺、專業的傳播策略、高度的統籌能力、強大的資源網絡，為政府機構、專營公司、中外企業以及商務組織等提供優質、熱誠、到位的服務，包括籌辦展覽會、研討會、商務會議、文化/娛樂活動、市場推廣及宣傳活動等，贏得了客戶的支持及良好的聲譽。
          </p>
        <p>派意成立至今，始終秉持務實、高效之作風，不斷求變，致力推動業務多元化。 【內地與澳門關於建立更緊密經貿關係的安排】（CEPA）實施後，2005年1月，派意成為澳門首家成功取得【澳門服務提供者證明書】之會展公司，同年3月，於北京成立的獨資會展公司隆重開幕，市場發展空間進一步拓展。</p>
      </div>
  </div>
  
  <div id="context2" class="ten wide column">
    <div class="ui sizer vertical insider">
        <div style="color:white"class="ui huge header">我們的案例</div>
        
        <div class="ui items">
          <div class="item">
            <div class="ui small image" >
              <img src="asset/upload_image/icon/201702181211073998121331.jpg">
            </div>
            <div class="content">
              <div class="header">Arrowhead Valley Camp</div>
              <div class="meta">
                <span class="date">1 Month</span>
              </div>
              <div class="description">
                <p></p>
              </div>
              <div class="extra">
                Additional Details
              </div>
            </div>
          </div>
          <div class="item">
            <div class="ui small image">
              <img src="asset/upload_image/icon/201702181211073998121331.jpg">
            </div>
            <div class="content">
              <div class="header">Buck's Homebrew Stayaway</div>
              <div class="meta">
                <span class="date">2 Weeks</span>
              </div>
              <div class="description">
                <p></p>
              </div>
              <div class="extra">
                Additional Details
              </div>
  
            </div>
          </div>
        </div>
  
  
    </div>
  </div>
  
  <div id="context3" class="ten wide column">
    <div class="ui sizer vertical insider">
        <div style="color:white"class="ui huge header">我們的伙伴</div>
    </div>
  </div> -->

</div>

<div id="footer">
<p class="foot">Copyright@2017 Prime Marketing and promotional Services Co., Ltd. All rights reserved.</p>
</div>

</body>
</html>