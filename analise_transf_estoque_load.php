<?php

require_once( "session.php" );
require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();
?>
<script src="js/jquery-3.7.1.js"></script> 
<link rel="stylesheet" href="js/datatables.css" />
<script src="js/datatables.js"></script>
<link   rel="stylesheet" href="css/style.css" />
<script src="js/js_geral.js"></script> 
<script src="//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json"></script> 
<script>
    
$(document).ready(function () {
    
 var table = new DataTable('#tblDin', {
    responsive: true,
    
    language: {
        url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json'
    },
    order: [[4, 'asc']],
    pageLength: 100,
    
     columnDefs: [
        {
            targets: [4],
            className: 'text-left'
        },
        {
            targets: [0,1,2,3,5,6,7,8,9],
            className: 'text-center'
        },
        {
            targets: [],
         //   className: 'text-right'
        },
          {
            targets: [0,18], // Índice da coluna que você deseja desativar a ordenação (baseado no zero)
            orderable: false // Desativa a ordenação para essa coluna
        }
    ],
     
    layout: {
        bottomEnd: {
            paging: {
                firstLast: false
            }
        }
    },
     
       rowCallback: function(row, data, index) {
            // Verifique se a linha precisa ser fixa
            if ($(row).hasClass('tr-fixa')) {
                $(row).attr('style', 'position: sticky; top: 0; background: #f1f1f1;');
            }
        },
     
        initComplete: function(settings, json) {
            // Certifique-se de que a linha acima do <thead> tenha uma classe específica
            $('#tblDin tr:first-child').addClass('tr-fixa');
        }

});
    
  
        
       
  $(".trClick").click(function(){
                         
    $(this).css("background-color","hsla(34,100%,50%,0.29)");               
                              
}); 

      $("#notaRodape").on("dblclick", function(){
     
        $("#notaRodape").fadeOut();
  
  });  
    
    $(".trClick").dblclick(function(){
                         
    $(this).css("background-color","");               
                              
});    
      
   
                
$("#btlimpar").on("click", function(){
   
     
    $("#form2")[0].reset();
    $('.IconEdit').fadeOut();
    
    $("#form2").nextAll("tr").css("background-color", "var(--cor-base2)");

     
});
        
$("#btExibePedido").on("click", function(){    
    
  
    $("#divContainer").animate({  scrollTop: $("#divContainer")[0].scrollHeight }, 1000); // 1000 milissegundos = 1 segundo
  
    efeitoload("in");
  
    var conta =  $("#fornec").val();
    var fornec = $("#conta").val();    
     exibePedido(conta,fornec); // esta função está dentro do arquivo 'js_geral.js'
    
});
    
$("#btlexcluirPedido").on("click", function(){
          
   if(confirm("Deseja excluir?")){ 
    $.post("carrega_dinamicos_html.php", 
           {
             func: 'excluiPedidoCompra',
            conta:  $("#conta").val(),
            fornec: $("#fornec").val()
    
          }, function(dados){
    
    $("#notaRodape").html(dados).fadeIn();
        
        $("#exibePedido").slideUp(); 
        
    }); 
   }
                              
                              
});
   
$(".qtdePedido").on("keyup", function (e) {
      
   var id = parseInt($(this).attr("title")); 
         
     if (e.keyCode == '13') {
         
         var x = (id + 1);
         
       $("#qtdeProdPedido"+x).focus();
         
      }
  });  
    
 $("#lbx").on("click", function(){
     
     
  $("#exibePedido").slideUp();
     
     
 }); 
        
    
$('input[type="text"]').keypress(function (e) {
    if (e.which == 13) { // Verifica se a tecla Enter foi pressionada
        e.preventDefault(); // Evita o comportamento padrão
        var currentTabIndex = parseInt($(this).attr('tabindex'), 10); // Captura o tabindex atual
        var $next = $('input[tabindex="' + (currentTabIndex + 1) + '"]'); // Seleciona o próximo input com o tabindex seguinte
        if ($next.length) {
            $next.focus(); // Move o foco para o próximo campo
        }
    }
});


    
});


</script> 

<!--<div class="divBloco scroll height_500">-->

<div class="divBloco2">
 <?php


 if ( empty( $_POST ) ) {

  echo "Nenhum dado econtrado";

 } else {


  $conta = $_POST[ 'conta' ] ?? "";
  $cnpjFornec = $_POST[ 'fornec' ] ?? "";
  $produtos = $_POST[ 'produtos' ] ?? "";
  $opcEstoque = $_POST[ 'opcEstoque' ] ?? "";
  $FiltroEstoque = $_POST[ 'opcEstoque' ] ?? "";
  $filtroqtde = $_POST[ 'filtroqtde' ] ?? "";


  if ( is_array( $conta ) ) {

   $conta = implode( ',', $conta );

  }

  if ( is_array( $cnpjFornec ) ) {

   $cnpjFornec = implode( "','", $cnpjFornec );

  }

  if ( is_array( $cnpjFornec ) ) {

   $cnpjFornec = implode( "','", $cnpjFornec );

  }

  if ( is_array( $produtos ) ) {

   $produtos = implode( "','", $produtos );

   $produtos = " AND f.codigo IN ('" . $produtos . "') ";

  }

  switch ( $opcEstoque ) {
   case "0":
    $opcEstoque = " AND e.saldoFisico = 0.0 ";
    break;
   case "1":
    $opcEstoque = " AND e.saldoFisico > 0.0 ";
    break;
   case "-1":
    $opcEstoque = " AND e.saldoFisico < 0.0 ";
    break;
   case "T":
    $opcEstoque = " AND e.saldoFisico > -1000 ";
    break;
   default:
    $opcEstoque = "";
  }

 
     
  $sel = "SELECT 
    c.id_conta_bling AS conta,
    ctt.cpf_cnpj as cnpj,
    e.produto_id as idprod,
    f.codigo AS codigo,
  	p.codigo as sku,
    p.gtin as ean,
    f.descricao AS descricao,
    f.precoCusto as custo,
        e.saldoFisico AS estoque,
    e.depositos_id as idDeposito
       
FROM 
    tb_produto_fornecedor f
JOIN tb_contatos ctt ON f.fornecedor_id = ctt.id_bling ".$produtos."
JOIN tb_produtos_detalhes p ON f.produto_id = p.id_bling_produto AND p.formato = 'S'
JOIN 
    tb_saldo_estoque e 
    ON f.produto_id = e.produto_id AND f.id_conta_bling = e.id_conta_bling ".$opcEstoque."
JOIN 
    tb_contatos c 
    ON f.fornecedor_id = c.id_bling AND c.cpf_cnpj IN ('{$cnpjFornec}')
WHERE  
f.codigo IN (
        SELECT codigo
        FROM tb_produto_fornecedor
        GROUP BY codigo
        HAVING COUNT(*) > 1)

GROUP BY 
    conta, codigo
ORDER BY 
    f.descricao";

//  echo $sel;

  $res = $sql->select( $sel );


  if ( count( $res ) > 0 ) {

   echo "<label class='size12 divBotaoM' id='btlimpar' style='float:right'>Limpar</label> ";
   echo "<br>";

   echo "<form id='form2'>";
      
   echo "<table id='tblDin' class='display compact tblStyle1'>";

/*
   echo "<tr class='tr-fixa no-sort'>";
   echo "<td colspan='6' style='text-align:center; background-color:hsla(0, 0%, 0%, 0.15);'></td>";
   echo "<td colspan='4' style='text-align:center; background-color:#dedede;'>Ultima Compra</td>";
   echo "<td colspan='5' style='text-align:center; background-color:hsla(0, 0%, 0%, 0.15);'>Histório Venda</td>";
   echo "<td colspan='3' style='text-align:center; background-color:#dedede;'>Estoque</td>";
   echo "<td colspan='2' style='text-align:center; background-color:hsla(0, 0%, 0%, 0.15);'>Comprar</td>";
*/
   echo "</tr>";

   echo "<thead>";

   echo "<tr class='theadsticky'>";
   echo "<th>&#8693;</th>";
   echo "<th>Conta</th>";
   echo "<th>IdProd</th>";
   echo "<th>codigo</th>";
   echo "<th>SKU</th>";
   echo "<th>Descrição</th>";
   echo "<th title='Ultima compra'>Compra</th>";
   echo "<th title='Ultima compra'>Data</th>";
   echo "<th title='Dias corridos'>Dias</th>";
   echo "<th title='% estoque pago'>Pago</th>";
   echo "<th>Custo</th>";

   echo "<th> " . $Class->mesAbrev( Date( 'm', strtotime( -3 . 'month' ) ) ) . " </th>";
   echo "<th> " . $Class->mesAbrev( Date( 'm', strtotime( -2 . 'month' ) ) ) . " </th>";
   echo "<th> " . $Class->mesAbrev( Date( 'm', strtotime( -1 . 'month' ) ) ) . " </th>";
   echo "<th> " . $Class->mesAbrev( Date( 'm' ) ) . "</th>";
   
   echo "<th title='Vendas ultimos 30 dias'> 30d </th>";
   
   echo "<th title='Estoque atual'>Saldo</th>";
  
  // echo "<th title='Cobertura, dias de estoque'>Cober</th>";

   echo "<th title='Qtde a comprar'>Ajustar</th>";
   echo "<th title='Atualizar alterações'><img src='imgs/bt-update-pr.png' id='btRegistar' width='24px' margin='0'></th>";

   echo "</tr>";
      
   echo "</thead>";

   echo "<tbody>";
      $y = 0;
            foreach ( $res as $col ) {
    $y++;
  
                
    echo "<tr >";
    echo "<td><input type='checkbox' id='checkbox1' class='checkbox1' tabindex='{$y}' title='{$col['codigo']}' value='' name='checkbox1[]'></td>";
    echo "<td>{$col[ 'conta' ]}</td>";
    echo "<td>{$col[ 'idprod' ]}</td>";
    echo "<td>{$col[ 'codigo' ]}</td>";
    echo "<td>{$col[ 'sku' ]}</td>";
    echo "<td>{$col[ 'descricao' ]}</td>";

    /****  exibe informações de compra, data, qtde e % de estoque pago *****************************/
    $data = $Class->getEstoqDataCompra( $col[ 'conta' ], $col[ 'ean' ], $col[ 'sku' ] );
    $dtCompra =  Date( "d/m/y", strtotime( $data[ 0 ][ 'dtEmissao' ] ) );
    $qtCompra = $data[ 0 ][ 'compra' ]; // qtde da ultima compra     
    echo "<td style='text-align:center;font-weight: bold;' title='{$dtCompra}'>{$qtCompra}</td>";

    echo "<td style='text-align:center;font-weight: bold;'>";
    echo Date( "d/m/y", strtotime( $data[ 0 ][ 'dtEmissao' ] ) ); // data da ultima compra
    echo "</td>";

    echo "<td style='text-align:center;font-weight: bold;'>";
    echo $data[ 0 ][ 'diasCorrido' ]; // dias corrido da ultima compra
    echo "</td>";

    echo "<td style='text-align:center;font-weight: bold;'>"; // exibe o % do estoque que está pago
    echo number_format( $data[ 0 ][ 'parcPgs' ] / $data[ 0 ][ 'parcelas' ] * 100, 0 ) . "%";
    echo "</td>";
    echo "<td style='text-align:right ;'>" . number_format( $col[ 'custo' ], 2, ',', '' ) . "</td>";
                
    /******************       exibe o histório de venda           **********************************/
    $histVenda = $Class->histVendProdMes( $col[ 'conta' ], $col[ 'idprod' ] );
    $m3 = $m2 = $m1 = $m0 = $v30dias = 0;
   

    foreach ( $histVenda as $row ) {

     echo "<td >";
     echo $m3 = $row[ '3' ];
     echo "</td>";
     echo "<td >";
     echo $m2 = $row[ '2' ];
     echo "</td>";
     echo "<td >";
     echo $m1 = $row[ '1' ];
     echo "</td>";
     echo "<td >";
     echo $m0 = $row[ '0' ];
     echo "</td>";
     echo "<td >";
     echo $v30dias = $row[ '30d' ];
     echo "</td>";

    }
    /******************  FIM  exibe o histório de venda           **********************************/
/*
    echo "<td >";
    if ( $v30dias == 0 ) {
     echo 0;
    } else {

     $cobertura = $estoq / ( $v30dias / 30 );
    
        echo number_format( $cobertura, 0, ',', '' );
   
    }
  
    echo "</td>";
*/
    echo "<td style='text-align:center; font-weight: bold;'>";
          echo $estoq = number_format( $col[ 'estoque' ], 0, ',', '.' );

    echo "</td>";
    
    $conta = $col[ 'conta' ];
     
    echo "<td>
    <input type='number' style='text-align:center; width:60px'  id='qtdeAjuste' title='{$col['codigo']}' tabindex='{$y}' value='{$estoq}' class='qtdeAjuste ' size='2' > 
    
    
    <input type='hidden' id='estoqAtual' tabindex='{$y}' title='".$col[ 'codigo' ]."'  value='{$estoq}'  >  
    <input type='hidden' id='idConta' tabindex='{$y}' title='".$col[ 'codigo' ]."' value='" . $col[ 'conta' ] . "'  >  
    <input type='hidden' id='idProd' tabindex='{$y}' title='".$col[ 'codigo' ]."' value='" . $col[ 'idprod' ] . "'>
    <input type='hidden' id='Custo' tabindex='{$y}' title='".$col[ 'codigo' ]."' value='" . $col[ 'custo' ] . "'>
    <input type='hidden' id='idDep' tabindex='{$y}' title='".$col[ 'codigo' ]."' value='" . $col[ 'idDeposito' ] . "'>
    <input type='hidden' id='operacao' tabindex='{$y}' title='".$col[ 'codigo' ]."' value=''>
       </td>";

   
    echo "<td><div class='IconEdit oculta' tabindex='{$y}' title='".$col[ 'codigo' ]."' ><img src='imgs/bt-update-pr.png' width='20px' margin='0'></div></td>";  
          
    echo "</tr>";       
   }
   echo "</tbody>";
   echo "</table>";
   echo "</form>";
      
   echo "<div id='trancking' style='text-align:center'></div>";

   
      
  } else {

     echo "<script> notaRodape('Nenhum PRODUTO encontrado!')</script>";
  }

 }
 ?>
  <script>
$(document).ready(function() {
    
    let qtdeChange = $(`input#qtdeAjuste`);
        
    qtdeChange.on("change", function() {
        
         let valor    = $(this).val(); // Exibe o valor do input após a alteração
         let title    = $(this).attr("title");
         let tabindex = $(this).attr("tabindex");

       
       let chkb = $(`.checkbox1[title='${title}']`);
        
     
        // Seleciona o checkbox com base no title
        chkb.prop("checked", true);

       });

$("#btRegistar").on("click", function() {
    let checkedInputs = $(".checkbox1:checked"); // Seleciona os inputs checados
    let tabIndexes = []; // Array para armazenar tabindex
    let codProd = ""; // Variável para armazenar o valor do title

    // Percorre os elementos checados e armazena os tabindex
    checkedInputs.each(function() {
        tabIndexes.push($(this).attr("tabindex")); // Adiciona o tabindex ao array
    });

    // Agora percorre os valores dos tabindex usando um laço for
    for (let i = 0; i < checkedInputs.length; i++) {
        let currentInput = checkedInputs[i]; // Captura o elemento do array
        let tabindex = $(currentInput).attr("tabindex"); // Captura o tabindex
        codProd = $(currentInput).attr("title"); // Captura o title

    //    alert(`Tabindex ${i}: ${tabindex}\nTitle: ${codProd}`); // Exibe o tabindex e title
        SalvaRegistroEstoque(codProd, tabindex); // Chama a função com os valores
        
        // Desmarca o checkbox
        $(currentInput).prop("checked", false);
    }
});
            
    
    
    
    
    
    
$(".IconEdit").on("click", function() {
    
    let codProd = $(this).attr("title"); // Obtém o título da div clicada
    let tabindex = $(this).attr("tabindex"); // Obtém o tabindex da div clicada
   SalvaRegistroEstoque(codProd, tabindex);

});
    
function SalvaRegistroEstoque(codProd, tabindex)  { 

    // Captura todos os tabindex de elementos com id='idProd' e o title correspondente
    let tabIndexes = $(`input#idProd[title='${codProd}']`).map(function() {
        return $(this).attr("tabindex"); // Captura o tabindex de cada elemento
    }).get(); // Transforma em array
    
 // Captura os valores iDConta para informar a origem do lançamento de estoque, ex: de conta1 para conta2
let contas = $(`input#idConta[title='${codProd}']`).map(function() {
    return $(this).val(); // Captura o valor de cada elemento
}).get(); // Transforma em array


// Itera sobre cada tabindex encontrado
    for (let i = 0; i < tabIndexes.length; i++) {
        let currentTabIndex = tabIndexes[i]; // Valor real de tabindex na iteração

        // Busca o input com os atributos correspondentes
        let idConta    = $(`input#idConta[title='${codProd}'][tabindex='${currentTabIndex}']`).val();
        let idProd     = $(`input#idProd[title='${codProd}'][tabindex='${currentTabIndex}']`).val();
        let Custo      = $(`input#Custo[title='${codProd}'][tabindex='${currentTabIndex}']`).val();
        let idDep      = $(`input#idDep[title='${codProd}'][tabindex='${currentTabIndex}']`).val();
        let qtdeChange = $(`input#qtdeAjuste[title='${codProd}'][tabindex='${currentTabIndex}']`);
        let qtdeAjuste = qtdeChange.val();
        let estoqAtual = $(`input#estoqAtual[title='${codProd}'][tabindex='${currentTabIndex}']`).val();
        let operador   ="";
        let contaDePara ="";
        let DePara = "";
        
            
        // Lógica para obter o id da outra conta, para salvar o nome da conta na observação da transferencia.
        
        if (idConta == contas[0]) {
            contaDePara = contas[1]; // x recebe id da conta[2]
        } else if (idConta == contas[1]) {
            contaDePara = contas[0]; // x recebe id da conta[1]
        }
          
        // Converte os valores para números inteiros corretamente
        qtdeAjuste = parseInt(qtdeAjuste, 10);
        estoqAtual = parseInt(estoqAtual, 10);
        
        if(Math.abs(qtdeAjuste - estoqAtual) != 0) {
         
        if (qtdeAjuste > estoqAtual) {
            
         qtdeAjuste =  Math.abs(qtdeAjuste - estoqAtual); // Retorna a diferença absoluta(qtdeAjuste - estoqAtual )
         operador = "E"; // Entrada
         DePara = "de";
         } else {
         qtdeAjuste =  Math.abs(qtdeAjuste - estoqAtual);
         operador = "S"; // Saída
         DePara = "para";    
        }
        
     //   notaRodape("conta "+idConta +"Prod "+ idProd +" Dep "+ idDep +"Qtde "+ qtdeAjuste+"<br>");
        
    
      // Realiza a chamada AJAX somente se os valores não forem indefinidos
        if (idConta && idProd && idDep && qtdeAjuste) {
            $.post("Put_PrecoProdutoLoja.php", {
                func: 'registroEsotque',
                idProd: idProd,
                codProd: codProd,
                qtdeAjuste: qtdeAjuste,
                Custo: Custo,
                idDep: idDep,
                operador: operador,
                idConta: idConta,
                contaDePara: contaDePara,
                DePara:DePara
            }, function (dados) {
                
            $(`div.IconEdit[tabindex='${currentTabIndex}']`).html(dados);
                
           //    notaRodape(dados);
            });
        } else {
            notaRodape(`Erro: Valores indefinidos para tabindex=${currentTabIndex}. Verifique os atributos.`);
        }
            
        }else{
          notaRodape("Nenhuma alteraçã para o código:"+codProd);   
        }
        
    }
}    

    let valoresArmazenados = {}; // Objeto para armazenar a soma do menor e maior valor por título.

    $(".qtdeAjuste").on("input", function(event) {
        let tituloAtual = $(this).attr("title"); // Captura o título do input
        let valorDigitado = parseFloat($(this).val()) || 0; // Captura o valor digitado no input atual

        let inputsMesmoTitulo = $(`input.qtdeAjuste[title='${tituloAtual}']`); // Obtém os inputs com o mesmo título
        let valores = inputsMesmoTitulo.map(function() {
            return parseFloat($(this).val()) || 0; // Coleta os valores numéricos ou 0
        }).get();

        let menorValor = Math.min(...valores); // Calcula o menor valor
        let maiorValor = Math.max(...valores); // Calcula o maior valor

        if (!valoresArmazenados[tituloAtual]) {
            valoresArmazenados[tituloAtual] = menorValor + maiorValor; // Armazena a soma
        }

        let somaArmazenada = valoresArmazenados[tituloAtual];

        inputsMesmoTitulo.each(function() {
            if (this !== event.target) { // Garante que não estamos alterando o input atual
                $(this).val(somaArmazenada - valorDigitado); // Calcula e define o valor no outro input
            }
        });


    //  let linha = $(`input[title='${tituloAtual}']`).closest("tr");
    //      linha.css("background-color", "var(--cor-bt2)"); // Muda a cor diretamente
            
        $(`.IconEdit[title='${tituloAtual}']`).fadeIn(); // Exibe a div com a classe IconEdit
    });

    $(".qtdeAjuste").each(function() {
        let tituloAtual = $(this).attr("title");
        let inputsMesmoTitulo = $(`input.qtdeAjuste[title='${tituloAtual}']`);

        let valores = inputsMesmoTitulo.map(function() {
            return parseFloat($(this).val()) || 0;
        }).get();

        let menorValor = Math.min(...valores);
        let maiorValor = Math.max(...valores);

        valoresArmazenados[tituloAtual] = menorValor + maiorValor;
    });

});
</script>    
</div>
<br>
<br>
<br>
<br>
<br>

<div id="exibePedido" class="oculta size12 width_70p"> </div>
<div id="notaRodape"> </div>
