<?php
require_once("session.php");
require_once( "config.php" );
$Class = new classMasterApi();
$sql = new Sql();
     
     ?>
 <link rel="stylesheet" href="css/style.css" /> 
<link rel="stylesheet" href="js/datatables.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/datatables.js"></script> 
<script src="//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json"></script> 
<script src="js/js_geral.js"></script>



<script>
    
$(document).ready(function () {
    
    
      $(".btnFechar").on("click", function() {
      
    $(this).closest(".divBloco").slideUp(); // Fecha apenas a div pai correspondente
  
  }); 
   
    
    
  var table = new DataTable('#tblDin', {
    language: {
        url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json',
    },
    pageLength: 100,
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
      
       
       
$(".qtdePedido").on("change", function(){
         
  var x = parseInt($(this).attr("title"));
 
    $.post("salvaPedidoCompra.php",   { 
        
        idProd: $("#idProdPedido"+x).val(),
          qtde: $("#qtdeProdPedido"+x).val(),
         conta: $("#contaPedido"+x).val(),
        cnpjFornec: $("#fornecPedido"+x).val()

   },  function(dados){
            
        notaRodape(dados);   
     
        
    var conta =  $("#fornec").val();
    var fornec = $("#conta").val();    
     exibePedido(conta,fornec); // esta função está dentro do arquivo 'js_geral.js'
       
  });

});  
        
        
$("#btlimpar").on("click", function(){
   
        $(".qtdePedido").val("");  
    
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
    
      notaRodape(dados);   
        
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
        
    
});


</script> 

<!--<div class="divBloco scroll height_500">-->


    	<?php

    function listQtde( $conta, $idprod ) {

        $sql = new Sql();

        $sel = "SELECT COALESCE(`qtde`,0) as 'qtde' FROM tb_pedidosCompras_itens WHERE idConta = " . $conta . " AND idProduto = " . $idprod;
        $res = $sql->select( $sel );

        if ( count( $res ) > 0 ) {

            return $res[ 0 ][ 'qtde' ];

        }

    }

    if ( empty( $_POST ) ) {

        echo "Nenhum dado econtrado";

    } else {


        $conta = $_POST[ 'conta' ] ?? "";
        $cnpjFornec = $_POST[ 'fornec' ] ?? "";
        $produtos = $_POST[ 'produtos' ] ?? "";


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

            $produtos = "AND tb_produto_fornecedor.codigo IN ('" . $produtos . "') ";

        }

        $sel = "SELECT
    tb_user_api.id AS conta,
    tb_user_api.nome_conta_bling AS nome_conta,
    tb_contatos.cpf_cnpj AS cnpjFornec,
    tb_produto_fornecedor.produto_id AS ProdutoID,
    tb_produto_fornecedor.codigo AS Cod_Forn,
    tb_produtos_detalhes.codigo AS SKU,
    tb_produtos_detalhes.gtin AS EAN,
    tb_produtos_detalhes.itensPorCaixa AS cx,
    tb_produto_fornecedor.descricao AS Descricao,
    tb_saldo_estoque.saldoFisico AS Estoq,
    tb_produto_fornecedor.precoCusto AS Custo,
TRUNCATE ( tb_saldo_estoque.saldoFisico * tb_produto_fornecedor.precoCusto, 2 ) AS 'Total'
FROM
    tb_tipo_contatos
JOIN tb_contatos ON tb_tipo_contatos.id_tipo_contatos = tb_contatos.id_tipo_contato
JOIN tb_user_api ON tb_user_api.id = tb_tipo_contatos.id_conta_bling
JOIN tb_produto_fornecedor ON tb_produto_fornecedor.fornecedor_id = tb_contatos.id_bling
JOIN tb_produtos_detalhes ON tb_produtos_detalhes.id_bling = tb_produto_fornecedor.produto_id
JOIN tb_saldo_estoque ON tb_saldo_estoque.produto_id = tb_produtos_detalhes.id_bling
JOIN tb_produtos ON tb_produtos.id_bling = tb_produto_fornecedor.produto_id 
WHERE 
tb_contatos.id_conta_bling IN (" . $conta . ")
AND tb_contatos.cpf_cnpj   IN ('" . $cnpjFornec . "') ";
        $sel .= $produtos;
        $sel .= "        
AND tb_produtos.tipo = 'P'
AND tb_produtos.situacao = 'A'
AND tb_produtos.formato = 'S'
GROUP BY
   tb_produto_fornecedor.produto_id,
    tb_user_api.nome_conta_bling
    
ORDER BY
    tb_produto_fornecedor.descricao, tb_user_api.nome_conta_bling ASC;";

    //    echo $sel;

        $res = $sql->select( $sel );


        if ( count( $res ) > 0 ) {

     echo " <div class='divBlocoNV1'>
            <div class='divBlocoNV2'>
            <div class='divBloco'>  
           "; 
         
            echo "<label class='size12 divBotaoM btnFechar' id='lbx' style='float:right'>Fechar</label>";
            echo "<label class='size12 divBotaoM' id='btlimpar' style='float:right'>Limpar</label> ";
            echo "<label class='size12 divBotaoM' id='btlexcluirPedido' style='float:right'>Excluir Pedido</label> ";
            echo "<label class='size12 divBotaoM' id='btExibePedido' style='float:right'>Vizualizar Pedido</label>";
            echo "<br>";
            echo "<br>";

            echo "<table id='tblDin' class='display compact tblStyle1'>";
            
            echo "<thead>";
            
            echo "<tr>";
            echo "<th> </th>";
            echo "<th>Conta</th>";

            echo "<th>Ref.</th>";
            echo "<th>SKU</th>";
            // echo "<th>EAN</th>";
            echo "<th>Descrição</th>";


            echo "<th> " . $Class->mesAbrev( Date( 'm', strtotime( -3 . 'month' ) ) ) . " </th>";
            echo "<th> " . $Class->mesAbrev( Date( 'm', strtotime( -2 . 'month' ) ) ) . " </th>";
            echo "<th> " . $Class->mesAbrev( Date( 'm', strtotime( -1 . 'month' ) ) ) . " </th>";
            echo "<th> " . $Class->mesAbrev( Date( 'm' ) ) . "</th>";
            echo "<th title='Vendas ultimos 30 dias'> 30d </th>";
            echo "<th title='Estoque atual'>Estoq</th>";
            echo "<th>Custo</th>";
            echo "<th>Total</th>";
            echo "<th title='Cobertura, dias de estoque'>Dias</th>";
            echo "<th title='Cobertura, dias de estoque'>CX</th>";
            echo "<th title='Sugestão de compra'>Sugestão</th>";
            echo "<th title='Qtde a comprar'>Pedido</th>";

            echo "</tr>";
            echo "</thead>";
             echo "<tbody>";
            $y = 0;

            foreach ( $res as $col ) {

                $y++;

                echo "<tr class='trchave trClick'>";
                echo "<td style='text-align:center;'> " . $y . " </td>";
                echo "<td style='text-align:center;'> " . $col[ 'nome_conta' ] . " </td>";

             /*   echo "<td style='text-align:center;'><a href='https://www.bling.com.br/produtos.php#edit/" . $col[ 'ProdutoID' ] . "' target='_blank'>" . $col[ 'Cod Forn' ] . "</a></td>";
                echo "<td style='text-align:center;'><a href='https://www.bling.com.br/produtos.php#edit/" . $col[ 'ProdutoID' ] . "' target='_blank'>" . $col[ 'SKU' ] . "</a></td>";
               */
               echo "<td style='text-align:center;'>" . $col[ 'Cod_Forn' ] . "</td>";
                echo "<td style='text-align:center;'>" . $col[ 'SKU' ] . "</td>";
                //   echo "<td style='text-align:center;'>" . $col[ 'EAN' ] . "</td>";


                echo "<td style='text-align:left  ;'>" . $col[ 'Descricao' ] . "</td>";

                $histVenda = $Class->histVendProdMes( $col[ 'conta' ], $col[ 'ProdutoID' ] );

                $m3 = $m2 = $m1 = $m0 = $v30dias = 0;

                foreach ( $histVenda as $row ) {

                    echo "<td style='text-align:center;'>";
                    echo $m3 = $row[ '3' ];
                    echo "</td>";
                    echo "<td style='text-align:center;'>";
                    echo $m2 = $row[ '2' ];
                    echo "</td>";
                    echo "<td style='text-align:center;'>";
                    echo $m1 = $row[ '1' ];
                    echo "</td>";
                    echo "<td style='text-align:center;'>";
                    echo $m0 = $row[ '0' ];
                    echo "</td>";
                    echo "<td style='text-align:center;'>";
                    echo $v30dias = $row[ '30d' ];
                    echo "</td>";

                }
                $estoq = number_format( $col[ 'Estoq' ], 0, ',', '.' );
                
                echo "<td style='text-align:center; font-weight: bold'>{$estoq}</td>";

                echo "<td style='text-align:right ;'>" . number_format( $col[ 'Custo' ], 2, ',', '.' ) . "</td>";
                echo "<td style='text-align:right ;'>" . number_format( $col[ 'Total' ], 2, ',', '.' ) . "</td>";

                echo "<td style='text-align:center;'>";
                if ( $v30dias == 0 ) {
                    echo 0;
                } else {

                    $cobertura = ( int )$estoq / ( ( int )$v30dias / 30 );
                    echo number_format( $cobertura, 0, ',', '.' );
                }
                echo "</td>";

                echo "<td>" . $col[ 'cx' ] . "</td>";
                echo "<td style='text-align:center;'>";

                if ( $v30dias == 0 ) {

                    echo $sugest = max( $m1, $m2, $m3, $m0 );

                } else {


                    $sugest = ( ( int )$v30dias - ( int )$estoq );

                    echo $sugest;

                }
                echo "</td>";


                echo "<td>
     <input type='hidden' id='contaPedido"  . $y . "' title='" . $y . "' value='" . $col[ 'conta' ] . "'  >  
     <input type='hidden' id='fornecPedido" . $y . "' title='" . $y . "' value='" . $col[ 'cnpjFornec' ] . "'>  
     <input type='hidden' id='idProdPedido" . $y . "' title='" . $y . "' value='" . $col[ 'ProdutoID' ] . "'>  
     <input type='number' id='qtdeProdPedido" . $y . "' title='" . $y . "' tabindex='" . $y . "' value='" . listQtde( $col[ 'conta' ], $col[ 'ProdutoID' ] ) . "'  class='qtdePedido inputDestaq' size='3' ></td>";

            echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
   
echo "</div>";
echo "</div>";
echo "<br>";


        } else {

            echo '<script>notaRodape("Nenhum PRODUTO encontrado!"); </script>';
        }

    }
    ?>
<div id="exibePedido" class="oculta exibePedido"> </div>
<div id="notaRodape"> </div>
