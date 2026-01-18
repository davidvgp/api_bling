
<?php
session_start();

 require_once( "config.php" );

 $Class = new classMasterApi();
 $sql   = new Sql();
?>
<link rel="stylesheet" href="css/style.css" /> 
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script> 

<script src="js/js_pg_despesas.js"></script>

<?php
 if ( empty($_SESSION[ "idUsuario" ] )) {

?>

<script>
    
 $(document).ready(function(){
     
    $.get("login_load.php?sair=s", function(dados){
     
        $("#absolut_login").html(dados).fadeIn();
        
    });
     
}); 
    
</script>

<?php } ?>

<div id="absolut_login">

</div>

