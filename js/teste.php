<!doctype html>
<html>
<head>
<meta charset="utf-8">
<script src="jquery-3.7.1.js"></script>
<title>Documento sem título</title>
</head>
<script>
    
    $(document).ready(function(){
 
  $("#conta").on("change",function() {  

    //   alert($("#conta").val());
      
 
   $.post("selects.php", 
    {
      conta: $("#conta").val()
    
    },  function(dados){
     

    $("#fornec").html(dados); 
       
  });
    
     
}) ;    
      
        
      
        
});
    
    
    </script>
<body>
<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1" );

?>
<div class='divBlocoGrande'>
  <div class="divBloco">
    <form action="custo_prod_forn.php" method="post" >
      <label> Selecionar fornecedor </label>
      <br>
      <select id="conta" name="conta" >
        <?php foreach($sel_conta as $id_conta ) { ?>
        <option value="<?php echo $id_conta['id']  ?>"> <?php echo $id_conta['conta']  ?> </option>
        <?php } ?>
      </select>
      <div id="fornec"></div>
      <input type="submit" value="Buscar">
    </form>
  </div>
</div>
</body>
</html>