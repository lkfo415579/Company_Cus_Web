<?php

  require_once 'FunctionLibrary.php';
  $LIB = new FunctionLibrary();
  echo json_encode($LIB->GetSpecifiedMenuItem($_REQUEST['id']));
?>