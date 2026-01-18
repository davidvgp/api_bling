<div id="absolut">
<!doctype html>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script>
    
$(document).ready(function(){
    
    $('#absolut').slideDown(800);
    
         
 $("#btLogin").on("click", function(){
  
 $('.load').show();
       
     
  var post = $("#form_loing").serialize(); 
     
     
      $.post("valida_login.php", post,  function(dados){ 
     
      if(dados == true){
            
     $('#absolut').slideUp(800, function(){
        
       $('.load').hide();
     
         });   
         
         }else{
             $("#msgLogin").html(dados); 
             $('.load').hide();
         }
  
       
  
   }); 
       
 
    });
    
    }); 

</script>


  <div class="divMini bkgrd_32475f div_centro"><div class="div_titulos">MVJOB</div>
    <form id="form_loing" class="formPadrao1" >
      <table width="200" border="0">
        <tbody>
          <tr>
            <td>Login</td>
            <td><input type="text" name="user" value="" class="inputSizeM"></td>
          </tr>
          <tr>
            <td>Senha</td>
            <td><input type="password" name="senha" value="" class="inputSizeM"></td>
          </tr>
          <tr>
            <td></td>
            <td align="left" ><input type="button" id="btLogin" value="Login" class="inputSizeP"></td>
          </tr>
        </tbody>
      </table>
    </form>
    <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
    <div id="divCarregado"> </div>
    <div id="msgLogin"></div>
  </div>
</div>
