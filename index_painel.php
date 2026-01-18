<?php
require_once( "session.php" );

if ( empty( $_SESSION[ "idUsuario" ] ) ) {

 // header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}



?>

<!doctype html>
<html>
<head>
    
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">    
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />
    
<title>Painel API</title>
    
</head>
<body>
    
<div id="header">
  <?php require_once("header.php");  ?>
</div>
    
    
<div id="divMenu" class="col-2">
    
  <?php require_once("menu_api.php");   ?>
    
</div>
    
    
    
<div id="divContainer" class="col-10">
    
    
<div id="" class="divBlocoGrande">
    
<div id="carrega_conteudo">

</div>    

    
    
</div>

 
</div>
    

<?php  ?>

    
    
    
    
</body>
</html>