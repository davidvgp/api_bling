<?php
session_start();


require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM `tb_user_api` WHERE 1" );


?>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_oculta_menu.js"></script> 


<script>
    
    $(document).ready(function(){
 
  $("#conta").on("change",function() { 
      
      $(".load").fadeIn();
      $("#absolut").fadeIn();

     var conta = $("#conta option:selected").val();
      
   $.post("carrega_dinamicos_html.php", 
    {
      func: 'nome_lojas',   
      conta: conta 
    
    },  function(dados){
     
    $("#lojas").html(dados);
      $(".load").fadeOut();
      $("#absolut").fadeOut();   
       
  });
         
}) ;    
        

    
$("#btBuscar").on("click", function(){
    
$('.load').slideDown();
    
 $('#divCarregado').slideUp( function(){   
    
    var post = $("#form1").serialize();
    
$.post("carrega_atualiza_produtos_lojas.php", post , function(dados){
    
    $("#divCarregado").html(dados); 
    $('#divCarregado').slideDown(900, function(){
        
    $('.load').slideUp();
    
}) ; 
 
 }) ;   
 
 }) ; 


}) ;   
    
        
    
});    
        
</script>

  <div class='divBlocoGrande'>
    <div class="divMiniBloco width_30p">
        
        <div class="div_titulos size20">Base Produtos Lojas</div>
        
        <hr>
        
        
      <form action="#"  id="form1"  class="formPadrao1"  >
        
          
        <table align="center"> 
            
        <tr>
            <td><select id="conta" name="id_conta" class="inputSizeM"  >
          <option value="0">Conta</option>
          <?php foreach($sel_conta as $id_conta ) { ?>
          <option value="<?php echo $id_conta['id']  ?>"> <?php echo $id_conta['conta']  ?> </option>
          <?php } ?>
        </select>
            </td>
            <td> 
        <select id="lojas" name="lojas" class="inputSizeM"  >
          <option value="0">Lojas</option>
        </select>
            </td>
            <td>
        <input type="button" id="btBuscar" value="Buscar" ></td>
            </tr>    
   
      
       
        </table>   
      </form>
    </div>
  </div>
  <div class='divBlocoGrande'>
    <div id="divCarregado">
      
    </div>
    <div id="absolut">
    <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>  
    </div>  
      
  </div>

