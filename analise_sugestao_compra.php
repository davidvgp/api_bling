<?php
require_once( "session.php" );
require_once( "config.php" );


$Class = new classMasterApi();
$sql = new Sql();


$conta = $Class->getContas( $_SESSION[ "idUsuario" ] );

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>
<title>Compras</title>
</head>
<body>
<script>
    
$(document).ready(function(){
     
      carregaFornecedores(0);
    
    
$("#btBuscar_analise_compra").on("click", function () {

    efeitoload("in");
    
    var post = $("#form1").serialize();
  
    open_analise_sugestao_compra(post);    
 
});
    

function open_analise_sugestao_compra(post) {      
     
            efeitoload("in");
    
         $.post("analise_sugestao_compra_load.php", post, function (dados) {

          $("#divCarregado").html(dados).slideDown();

           efeitoload('out'); 

        });
  } 
        
    
    $("#btListaPedidos").on("click", function(){    
    
    $("#divContainer").animate({  scrollTop: $("#divContainer")[0].scrollHeight }, 1000); // 1000 milissegundos = 1 segundo
  
    efeitoload("in");
        
     var conta =  $("#fornec").val();
    var fornec = $("#conta").val();    
    
        exibePedido(conta,fornec); // esta função está dentro do arquivo 'js_geral.js'
    
});
    
    
    
    $("#conta").on("change", function () {
    
        var conta  = $("#conta").val();
        var fornec = $("#fornec").val();
        var busca  = $("#FiltraProd").val();  
    
     efeitoload('in');
                        
   carregaProfFornCodigo(conta, fornec);

      });   
   
 

     $("#fornec").on("change", function () {
       
        efeitoload('in');
      
        var conta  = $("#conta").val();
         
        if(conta.length == 0)  { 
            $('#conta').find('option').prop('selected', true);  
            var conta  = $("#conta").val(); 
        }

        var fornec = $("#fornec").val();
        var busca  = $("#FiltraProd").val();
         
        carregaProfFornCodigo(conta, fornec);
     
        FiltraProd(conta,fornec,busca);

    });   
     
 
   $("#FiltraProd").on("keyup",function(){
  
      var conta  = $("#conta").val();
      var fornec = $("#fornec").val();
      var busca  = $("#FiltraProd").val();
        
       if(conta == 0)  {  $('#conta').find('option').prop('selected', true) }   
   
     if( busca.length > 2 ){
      
       FiltraProd(conta,fornec,busca);
         
     }
       
 
   });
    
     $("#btAtualizaForn").on("click", function () {
       
        var conta  = $("#conta").val();
        var fornec = $("#fornec").val();
        var prod   = $("#produtos").val();
   
        if (fornec.length > 0) {

         $("#notaRodape").html("Atualizando base de fornecedor.").fadeIn();
            
                $.post("carrega_atualiza_produtos_fornecedor.php", {

                    atualiza: "S",
                    conta: conta,
                    fornec: fornec,
                    prod: prod
                
                }, function (dados) {

                $("#notaRodape").html(dados).fadeIn();

                carregaAnuncios(conta,fornec);
                 
                var post = $("#form1").serialize();
  
                open_analise_sugestao_compra(post); 
                
                }
                  
            ); 
 
        }
    });

   /********************* ATUALIZAR PRODUTOS DETALHES ************************/
     
    $("#btAtualizaProd").on("click", function () {

     $("#notaRodape").html("<Br>Atualizando cadastro de produtos").fadeIn();
     
     //   efeitoload('in');
        
        let produtos = $("#produtos").val().length;
        let busca = $("#FiltraProd").val().length;
    
         if (produtos == 0) {
            $('#produtos').find('option').prop('selected', true);
        }

            $.post("carrega_atualiza_produtos_detalhes.php", {
            
            func: "atualiza_cadastro",
            contas: $("#conta").val(),
            produtos: $("#produtos").val()

            }, function (dados) {
     
                $("#notaRodape").html(dados).fadeIn();
            
    //    carregFreteLojas();
                
    //    efeitoload('out');
                
            });

        });  
     
     
     $("#btAtualizaEstoqProd").on("click", function () {

     $("#notaRodape").html("<Br>Atualizando estoque de produtos").fadeIn();
     
     //   efeitoload('in');
        
        let produtos = $("#produtos").val().length;
        let busca = $("#FiltraProd").val().length;
    
         if (produtos == 0) {
            $('#produtos').find('option').prop('selected', true);
        }

            $.post("carrega_atualiza_produtos_detalhes.php", {
            
            func: "atualiza_estoque",    
            contas: $("#conta").val(),
            produtos: $("#produtos").val()

            }, function (dados) {
     
                $("#notaRodape").html(dados).fadeIn();
            
    //    carregFreteLojas();
                
    //    efeitoload('out');
                
            });

        });  
     
      
    
});    
   
</script>
<div id="header">
    <?php require_once("header.php");  ?>
</div>
    <div id="main">
<div id="divMenu">
    <?php require_once("menu.php");  ?>
</div>
<div id="divContainer">
<div class='divBlocoGrande'>
    <div class="divBloco">
        <div class="div_titulos">Analise de estoque e sugestão de compra</div>
        <form name="form1" id="form1" class="formPadrao1"  accept-charset="UTF-8" >
            <table align="center" cellpadding="3" cellspacing="3">
                <tr>
                    <td align="left" valign="bottom">Contas</td>
                    <td  align="left" valign="bottom">Fornecedores</td>
                    <td align="left" valign="bottom">Filtrar produtos
                        <input type="text" name="FiltraProd" id="FiltraProd" value="" class="inputSizeG">
                    </td>
                    <td align="left" valign="bottom">&nbsp;</td>
                </tr>
                <tr>
                    <td height="191" align="left" valign="top">
                        <select name="conta[]" size="3" multiple="MULTIPLE" class="inputSizeM" id="conta" >
                            <?php foreach($conta as $id_conta ) { ?>
                            <option value="<?php echo $id_conta['id']  ?>"> <?php echo $id_conta['conta']  ?></option>
                            <?php } ?>
                        </select>
                        <br>
                        Status Pedido
                        <br>
                        <select name="statusPedidos[]" size="7" multiple="MULTIPLE" class="inputSizeM" id="statusPedidos">
                     </select>
                        <br>
                    </td>
                    <td  align="left" valign="top">
                        <select name="fornec[]" size="8" multiple="MULTIPLE" class="inputSizeXG" id="fornec">
                        </select>
                        <br>
                        <div align="right"  class="size12 divBotaoM" id="btAtualizaForn">atualizar base</div>
                    </td>
                    <td align="left" valign="top">
                        <select name="produtos[]" size="8" multiple="MULTIPLE" class="inputSizeXXG" id="produtos">
                        </select>
                        <br>
                        <div align="right"  class="size10 divBotaoM" id="btAtualizaProd">atualizar cadastro</div>
                    <div align="right"  class="size10 divBotaoM" id="btAtualizaEstoqProd">atualizar estoque</div></td>
                    <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left" valign="top"><br>
                    </td>
                    <td  align="left" valign="top"><br>
                       
                    </td>
                    <td colspan="2" align="center" valign="top">
                        <input type="button" class="inputSizeM" id="btBuscar_analise_compra" title="Exibir relatório análise de compra" value="Analise">
                        <input type="button" class="inputSizeM" id="btListaPedidos" title="Lista os pedidos" value="Exibir pedidos">
                    
                    </td>
                </tr>
            </table>
        </form>
        
    </div>

    <div id="divCarregado"> </div>
    <div id="exibePedido" class="exibePedido"> </div>
</div>
</div>
</div>
    <div id="rodape"></div>
<div id="absolut">
    <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div id="notaRodape"> </div>
</body>
</html>