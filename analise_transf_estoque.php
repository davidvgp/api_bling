<?php
require_once( "session.php" );
require_once( "config.php" );


$Class = new classMasterApi();
$sql = new Sql();

$conta = $Class->getContas( $_SESSION[ "idUsuario" ] );


?>
<script>

$(document).ready(function(){

    chkBoxListaFornecedor();

function chkBoxListaFornecedor(){
    
    let conta;
    
    if ($("#checkbox1").is(':checked')) {
    if($("#conta").val().length == 0){
             conta = 0;
    }else{
            conta = $("#conta").val();
    }
        carrFornEmComum(conta);
        
    } else {
        
       carregaFornecedores(0);
    }
    
}
    
        
$("#checkbox1").on("change",function(){   
 
    chkBoxListaFornecedor();
    
 });

$("#btBuscar_ajuste_estoque").on("click", function () {

efeitoload("in");

    let filtroProd = $("#FiltraProd").val().length;
     
  if (filtroProd > 2) {
    $('#produtos').find('option').prop('selected', true);
  } 


    
let post = $("#form1").serialize();

$.post("analise_transf_estoque_load.php", post, function (dados) {

$("#divCarregado").html(dados).slideDown();

efeitoload('out'); 

});   

});



$("#conta").on("change", function () {

let conta  = $("#conta").val();
let fornec = $("#fornec").val();
let busca  = $("#FiltraProd").val();  

efeitoload('in');

 carregaProfFornCodigo(conta, fornec);
    

});   



$("#fornec").on("change", function () {

efeitoload('in');

let conta  = $("#conta").val();

if(conta.length == 0)  { 
$('#conta').find('option').prop('selected', true);  
let conta  = $("#conta").val(); 
}

let fornec = $("#fornec").val();
let busca  = $("#FiltraProd").val();

carregaProfFornCodigo(conta, fornec);

FiltraProd(conta,fornec,busca);

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
      

$("#btAtualizaForn").on("click", function () {

let conta  = $("#conta").val();
let fornec = $("#fornec").val();
let prod   = $("#produtos").val();

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

let post = $("#form1").serialize();

open_analise_sugestao_compra(post); 

}

); 

}
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
     
      

});    

</script>
<!doctype html>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>
<title>Transf. Estoques</title>
</head>
<body>
<div id="header">
 <?php require_once("header.php");  ?>
</div>
<div id="main">
<div id="divMenu">
<?php require_once("menu.php");  ?>
</div>
<div id="divContainer" class="col-10">
 <div class='divBlocoGrande'>
  <div class="divBloco">
   <form name="form1" id="form1" class="formPadrao1"  accept-charset="UTF-8" >
    <div class="div_titulos size20">Transferência entre estoque</div>
    <table align="center" cellpadding="5">
     <tr>
      <td align="left" valign="bottom">Contas</td>
      <td align="left" valign="bottom">
       <input name="checkbox" type="checkbox" id="checkbox1" title="Fornecedores em comum" checked="checked">
       Fornecedores em comum</td>
      <td align="left" valign="bottom">Filtrar produtos
       <input type="text" name="FiltraProd" id="FiltraProd" value="" class="inputSizeG">
      </td>
     </tr>
     <tr>
      <td align="left" valign="top">
       <select name="conta[]" size="7" multiple="MULTIPLE" class="inputSizeM" id="conta" >
        <?php foreach($conta as $col ) { ?>
        <option value="<?php echo $col['id']  ?>"> <?php echo $col['conta']  ?></option>
        <?php } ?>
       </select>
      </td>
      <td align="left" valign="top">
       <select name="fornec[]" size="7" multiple="MULTIPLE" class="inputSizeXG" id="fornec">
       </select>
       <br>
       <label class="divBotaoM" id="btAtualizaForn">atualizar base</label>
      </td>
      <td align="left" valign="top">
       <select name="produtos[]" size="7" multiple="MULTIPLE" class="inputSizeXXG" id="produtos">
       </select>
       <br>
       <div class="divBotaoM" id="btAtualizaProd">atualizar cadastro</div>
       <div class="divBotaoM" id="btAtualizaEstoqProd">atualizar estoque</div>
      </td>
     </tr>
     <tr>
      <td>&nbsp;</td>
      <td align="left">Exibir histórico de venda
       <select name="histVenda" id="histVenda">
        <option value="N">Não</option>
        <option value="S">Sim</option>
       </select>
      </td>
      <td align="right"><span style="text-align: right;">
       <input type="button" class="inputSizeM" id="btBuscar_ajuste_estoque" title="Para ajuste de estoque." value="Listar produtos" name="btListaPedidos">
       </span></td>
     </tr>
    </table>
   </form>
  </div>
  <div id="divCarregado"> </div>
  <div id="exibePedido" class="oculta size12 width_70p"> </div>
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