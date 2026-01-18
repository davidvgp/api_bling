<?php
session_start();
require_once( "config.php" );

if ( isset( $_GET[ "sair" ] ) ) {

 if ( isset( $_SESSION ) ) {
  session_destroy();

 }

}

?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>
<title>MV+</title>
</head>
<script>
    
  function carregaLogin(){ 
      $.post("login_load.php", {pagina:"login Carregado"},  function(dados){ 
       $("#divCarregado").html(dados).fadeIn();
        //  notaRodape('Algo saiu errado!'); 
      }); 
   }
$(document).ready(function(){
  
  carregaLogin(); 
    
   }); 
       
</script>
<body>
<div id="divCarregado"> </div>
<div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
<div id="msgLogin"></div>
</div>
<div id="notaRodape"></div>
</body>    
</html>
