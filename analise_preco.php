<?php
require_once( "session.php" );
require_once( "config.php" );

$Class = new classMasterApi();
$conta = $Class->getContas( $_SESSION[ "idUsuario" ] );
//$Class->setIdUserApp($_SESSION[ "idUsuario" ]);

//$Class->getNomeUserApp();

?>
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="js/datatables.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/datatables.js"></script> 
<script src="//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json"></script> 
<script src="js/js_geral.js"></script> 
<script>
    
  function carregaIDLojas() {

       
     $.post("carrega_dinamicos_html.php", {

        func: 'nome_lojas_tipo',
        conta: $("#conta").val()

    }, function (dados) {

        $("#lojas").html(dados);

    });

    
    efeitoload('out');

} 
    
function carregaUltimasNotas() {
    
        if($("#fornec").val().length > 1){
            
           $("#NotasEntrada").html("<option value='' title='Selecione apenas um fornecdor'>NFs</option>");
            notaRodape("Selecion apenas um fornecedor");
            
        }else{
       
     $.post("carrega_dinamicos_html.php", {

        func: 'UltimasNotas',
        conta: $("#conta").val(),
        fornec: $("#fornec").val()
         

    }, function (dados) {

    $("#NotasEntrada").html(dados);

    });

    
    efeitoload('out');

}
}
      

$(document).ready(function(){
             
        //    carregaFornecedores(0);
          //         carregaLojas();   
                    carregaIDLojas();
     //   carregaProfFornCodigo(0,0);   
      
$("#conta").on("change", function() {
    
        let conta  = $("#conta").val();
        let fornec = $("#fornec").val();
          
          efeitoload('in');
      //  carregaLojas();
    
        carregaIDLojas();
    carregaUltimasNotas();
    if(fornec.length >0) {
    
      carregaProfFornCodigo(conta, fornec);
      localizaProduto();
          
    }else{
        
        carregaFornecedores(conta);
    }
 });   
         
 
  
 $("#fornec").on("change", function () {
       
        efeitoload('in');
  
        let conta  = $("#conta").val();
        let fornec = $("#fornec").val();
     
   //   carregaAnuncios(conta, fornec);
      carregaUltimasNotas();
      carregaProfFornCodigo(conta, fornec);
 
    });   
    
    
 $("#NotasEntrada").on("change", function(){
       
    //    efeitoload('in');
    
        let conta  = $("#conta").val();
        let fornec  = $("#fornec").val();
        let NotasEntrada = $("#NotasEntrada").val();
      
     carregaProdNotaEntrada(conta, NotasEntrada, fornec);
   
       efeitoload('out');

    });   
     
     

     
/**************************************************************************************/

    $("#btAtualizaForn").on("click", function () {

        let forn = $("#fornec").val().length;
        let cont = $("#conta").val().length;
        
        notaRodape("Atualizando base de fornecedor");
                
        if (forn > 0 && cont >0) {

            efeitoload('in');
            let conta  = $("#conta").val();
            let fornec = $("#fornec").val();

            $.post("carrega_atualiza_produtos_fornecedor.php", {

                    atualiza: "S",
                    conta : $("#conta").val(),
                    fornec: $("#fornec").val(),
                    prod: $("#produtos").val()
                },
                function (dados) {
                
                notaRodape(dados);   
                carregaProfFornCodigo(conta,fornec);
                efeitoload('out');
                
                });

        }else{
            
             notaRodape("Selececio uma Conta e um Fornecedor da lista");
            
        }
    
    });/**************************************************************************************/

    $("#btAtualizaLoja").on("click", function () {
        
        let lojas  = $("#lojas").val().length;
        let contas = $("#conta").val().length;
   
        notaRodape("Atualizando base Lojas");
       

        if (lojas > 0 && contas >0) {

            efeitoload('in');
            let contas  = $("#conta").val();
            let lojas = $("#lojas").val();

            $.post("carrega_atualiza_produtos_lojas.php", {

                    contas : $("#conta").val(),
                    lojas: $("#lojas").val(),
                 
                },
                function (dados) {

                  notaRodape(dados);   
             
                efeitoload('out');
                });

        }else{
            
            notaRodape("Seleciona uma Conta e uma Loja");
        }
    
    });
    
    $("#btAtualizaNota").on("click", function () {
        
        let conta  = $("#conta").val().length;
        let nota = $("#NotasEntrada").val().length;
   
        notaRodape("Atualizando itens da Nota Fiscal");
      
        if (nota > 1 && conta == 1) {

      
            let contas  = $("#conta").val();
            let nota = $("#NotasEntrada").val();

            $.post("carrega_dinamicos_html.php", {
                
                func: "atualizaUmaNota",
                conta : $("#conta").val(),
                idnota  : $("#NotasEntrada").val()
                 
                },
                function (dados) {

                notaRodape(dados);   
             
            });

        }else{
            
            notaRodape("Seleciona uma Conta e uma Loja");
        }
    
    });

   
/********************* ATUALIZAR PRODUTOS DETALHES ************************/
     
    $("#btAtualizaProdLoja").on("click", function () {

        
     notaRodape("Carregando produtos anunciado");
     
     //   efeitoload('in');
        
        let conta = $("#conta").val().length;
        let produtos = $("#produtos").val().length;
        let busca = $("#FiltraProd").val().length;
        
        
    
         if (produtos == 0) {
            $('#produtos').find('option').prop('selected', true);
        }
        
        if(conta > 1){
            
           
           notaRodape("Seleciona apenas uma conta!");
            
        }else{

            $.post("carrega_dinamicos_html.php", {
            
            func: "atualiza_cadastro",
            conta: $("#conta").val(),
            produtos: $("#produtos").val()

            }, function (dados) {
                
                notaRodape(dados);   
            
    //    carregFreteLojas();
                
    //    efeitoload('out');
                
            });
            
        }// fim do if

        });  
    
    $("#btAtualizaProd").on("click", function () {

        notaRodape("Atualizando cadastro de produtos");   
   
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
                
                notaRodape(dados);   
            
    //    carregFreteLojas();
                
    //    efeitoload('out');
                
            });

        });  
     
     
     $("#btAtualizaEstoqProd").on("click", function () {
  
         notaRodape("Atualizando estoque de produtos");
         
       
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
                
                notaRodape(dados);   
      
            //    carregFreteLojas();
            //    efeitoload('out');
                
            });

        });  
     
      
    
     $("#btBuscar").on("click", function () {
         
         efeitoload('in');
         
        let busca  = $("#FiltraProd").val();
        let count  = $('#produtos option:selected').length;
        let nota  = $('#NotasEntrada option:selected').length;
         
               
        if(busca.length  >= 3 && count ==0) {   
             
           $('#produtos option').prop('selected', true);
         }
         
         
         if(nota > 0 && count ==0) {   
              $('#produtos option').prop('selected', true);
         }
         
        carregaAnalisePreco();
            
         efeitoload('out'); 
         
        });

    
   $("#FiltraProd").on("keyup",function(){
       
       localizaProduto();
       
        });  
     
    function localizaProduto(){ 
        
       let busca  = $("#FiltraProd").val();
       let fornec = $("#fornec").val();
       let conta  = $("#conta").val();
      
       if(conta.length  == 0){conta =0;}
       if(fornec.length == 0){fornec=0;}
       
     if( busca.length > 2 ){
      
       FiltraProd(conta,fornec,busca);
         
     } 
        
    if( busca.length < 2 ){
      
    carregaProfFornCodigo(conta, fornec);
         
     }
       
     }
      
    
         
 }); 
    
    
    </script> 
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Preços Lojas</title>
</head>
<body>
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
    <div class="div_titulos"> Análise de preço Multilojas</div>
    <form name="form1" id="form1" class="formPadrao1"  accept-charset="UTF-8" >
     <table border="0" align="center" cellpadding="3" cellspacing="3"  class="" style="">
      <tr>
       <td align="left" valign="middle">Conta</td>
       <td align="left" valign="middle">Lojas</td>
       <td align="left" valign="middle">Fornecedor</td>
       <td align="left" valign="middle"> Notas de compra</td>
       <td align="left" valign="middle">Filtrar produtos
        <input type="text" name="FiltraProd"    id="FiltraProd" class="inputSizeM">
       </td>
      </tr>
      <tr>
       <td height="26" align="left" valign="top">
        <select name="conta[]" size="6" autofocus="autofocus" multiple="MULTIPLE" class="inputSizeP" id="conta">
         <?php foreach($conta as $col ) { ?>
         <option value="<?php echo $col['id'];  ?>"> <?php echo $col['nome_conta_bling'];  ?></option>
         <?php } ?>
        </select>
       </td>
       <td align="left" valign="top">
        <select name="lojas[]" autofocus="autofocus" multiple="MULTIPLE" class="inputSizeP" id="lojas" size="6" >
        </select>
        <br>
        <div class=" divBotaoM" id="btAtualizaLoja">atualizar base lojas</div>
       </td>
       <td align="left" valign="top">
        <select name="fornec[]" size="6" autofocus="autofocus" multiple="MULTIPLE" class="inputSizeXG" id="fornec">
         <option value=" "> </option>
        </select>
        <br>
        <div align="right"  class=" divBotaoM" id="btAtualizaForn">atualizar base fornecedor</div>
       </td>
       <td align="left" valign="top">
        <select name="NotasEntrada"  class="inputSizeP" id="NotasEntrada" size="6" >
         <option value=" "> </option>
        </select>
           <br>

        <div align="right"  class=" divBotaoM" id="btAtualizaNota">atualizar nota</div>
       </td>
       <td align="left" valign="top">
        <select name="produtos[]" size="6" autofocus="autofocus"  multiple class="inputSizeXG" id="produtos">
         <option value=" "> </option>
        </select>
        <br>
        <div align="right"  class=" divBotaoM" id="btAtualizaProd">atualizar cadastro</div>
        <div align="right"  class=" divBotaoM" id="btAtualizaEstoqProd">atualizar estoque</div>
       </td>
      </tr>
      <tr>
       <td colspan="2" align="center" valign="top">Estoque
        <select name="opcEstoque" id="opcEstoque">
         <option value="T">Selecione</option>
         <option value="1">Maior que zero</option>
         <option value="+5">Acima de 5</option>
         <option value="+10">Acima de 10</option>
         <option value="+20">Acima de 20</option>
         <option value="0">Igual a zero</option>
         <option value="-1">Menor que zero</option>
        </select>
       </td>
       <td align="center" valign="top">Exibir histórico de venda
        <select name="histVenda" id="histVenda">
         <option value="N">Não</option>
         <option value="S">Sim</option>
        </select>
       </td>
       <td align="right" valign="top">&nbsp;</td>
       <td align="right" valign="top">
        <input type="button" autofocus="autofocus" class="inputSizeM" id="btBuscar" value="Buscar">
       </td>
      </tr>
     </table>
    </form>
   </div>
   <div id="divCarregado"> </div>
  </div>
 </div>
</div>
<div id="rodape"></div>
<div id="absolut">
 <div class="load"><img src="imgs/loading-gif-transparent.gif"  width="50"></div>
</div>
<div id="notaRodape"> </div>
</body>
</html>