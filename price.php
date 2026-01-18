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
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" />    
<link rel="stylesheet" href="css/style_menu.css" />    
    
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_oculta_menu.js"></script>  
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Precificar</title>
</head>
    
<script>
    
    $(document).ready(function(){
        
    $('#div2b').hide(); 
        
 $(".bt_salvar").on("click", function(){
     
        
     $('#div2b').slideUp();
       
     var c = $(this).attr("id");
     

    var post = $("#"+c).serialize(); 
     
   $.post("salva_config_cron.php", post,  function(dados){
    
    $("#div2b").html(dados); 
    $('#div2b').slideDown();  
     });
  
    });
     
     
  $('.link').on('click',function() {  
    $('#div2b').slideUp();
    var lk = $(this).attr('value');
  
   
     $('#div2b').load(lk); 
     $('#div2b').slideDown(); 
         
}) ;    
      
});
    
    
    </script>    
    
    
<body>
<body>
<div class="header">
  <?php require_once("header.php");  ?>
</div>
<div id="divMenu" class="col-2 menu">
  <?php require_once( "menu.php" ); ?>
</div>
<div id="divContainer" class="col-10">
<?php

$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1" );

?>
<div class='divBlocoGrande'>
  <div class="divMiniBloco width_50p">
    <form action="custo_prod_forn.php" method="post" >
      <div class="div_titulos size16"> Produto </div>
      <label>Contas</label>
      <select name="conta" class="input" >
        <option value="T" >Todos</option>
        <?php foreach($sel_conta as $id_conta ) { ?>
        <option  value="<?php echo $id_conta['id']; ?>"> <?php echo $id_conta['conta']; ?> </option>
        <?php } ?>
      </select>
      <label> Busca </label>
      <input type="text" name="busca" class="input" value="" size="30">
      <input type="submit" class="input" value="Buscar">
    </form>
  </div>
</div>
<div class='divBlocoGrande'>
<div class='divBloco oculta" >
    
    
    </div>
</div>
</body>
</html>
