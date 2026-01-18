<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "config.php" );
    
$Class = new classMasterApi();
$sql = new Sql();


?>

<!doctype html>
<html>
<head>
 <?php require_once("conteudo_header.php");  ?>
    
<title>Template</title>    
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>  
</head>
    
<script>
    
$(document).ready(function(){
    

    
    
    
    
    
});    
    
    
    
</script>    
<body>
<div id="header">
  <?php require_once("header.php");  ?>
</div>
    
    
<div id="divMenu" class="col-2">
  <?php require_once("menu.php");   ?>
</div>
    
    
    
<div id="divContainer" class="col-10">
    
    
<div id="" class="divBlocoGrande">


    
</div>
    
    
    
 
</div>
    
   <!--/* --------------CONTEUDO OCULTO OU QUE SERÁ CARREGADO POSTERIORMENTE---------------------------*/-->
 <div id="absolut">
  <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape">
 </div>   
</body>
</html>