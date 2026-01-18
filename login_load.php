<?php
session_start();


require_once( "config.php" );
    
$Class = new classMasterApi();
$sql   = new Sql();


if ( isset( $_GET[ "sair" ] ) ) {

    session_destroy();
}

?>

<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script> 
    
<?php    

 if(!empty($_POST['pagina'])){

?>

<script>
    
$(document).ready(function(){
         
 $("#btLogin").on("click", function(){
     
    var post = $("#form_loing").serialize(); 
     
      $.post("valida_login.php", post,  function(dados){ 
           
          
        if(dados == true){
            
       $(location).attr('href', 'https://mvjob.com.br/api_bling/home.php');  
     //    $("#notaRodape").html(dados).fadeIn();
         }else{
             $("#msgLogin").html('Usuário não econtrado!'); 
         }
      }); 
    });
 }); 

</script>

  <?php    
    
 }else{
     
?>   
   
<script>
    
$(document).ready(function(){
    
 $("#btLogin").on("click", function(){
     
  var post = $("#form_loing").serialize();
     
      $.post("valida_login.php", post,  function(dados){ 
          
          
          if(dados == true){
              
           $("#absolut_login").fadeOut();
        //   location.reload();
      }else{
             $("#msgLogin").html("Usuário não encontrado."); 
         }
      }); 
 });
}); 

</script>

  <?php    
    
 }
?>

<style>
.dadosLogin {
  display: grid;
  grid-template-columns: auto auto auto;
   gap: 10px;
  padding: 10px;
}

.grid-container > div,input {
  padding: 10px;
  font-size: 16px;

}

.item1 {
  grid-column: 1 ;
    
}

.item2 {
  grid-column: 1;
  grid-row: 2 
}
.item3 {
  grid-column: 1;
  grid-row: 3 
}
    .item4 {
  grid-column: 1;
  grid-row: 4
}
 .item5 {
  grid-column: 1;
  grid-row: 5
}
    .item6 {
  grid-column: 1;
  grid-row: 6
}
    .logoLogin{
        display:flex;
        justify-content: center;
        align-items: center; 
    
    }
    .logoLogin img{
        margin: 0;
        padding: 0;
        width: 140px;
        height: 140px;
        
</style>
  <div class="divBlocologin div_centro">
    <div class="logoLogin"><img src="imgs/logo_app.png"></div>
    <form id="form_loing" class="formPadrao1" >
     <div class="dadosLogin"> 
         <div class="item1 formats">Usuário</div>
         <div class="item2 formats"><input type="text" name="user" value="" class="inputSizeM"></div>
         <div class="item3 formats">Senha</div>
         <div class="item4 formats"><input type="password" name="senha" value="" class="inputSizeM"></div>
         <div class="item5 formats"><input type="button" id="btLogin" value="Login" class="inputSizeM"></div>
         <div class="item6 formats" id="msgLogin"></div>
        </div>    
    </form>
    <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
    <div id="divCarregado"> </div>
    
  </div>

<div id="notaRodape"></div>
