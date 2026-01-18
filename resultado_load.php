<?php
session_start();
require_once("session.php"); 

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script>
<script>

$(document).ready(function () {
    
  $(".btnFechar").on("click", function() {
    
      
    $(this).closest(".divBloco").slideUp(); // Fecha apenas a div pai correspondente
  
  });     
    
  });     
    
    
</script>

<?php


$conta     = $_POST[ 'conta' ] ?? "";
$exercicio = $_POST[ 'exercicio' ] ?? "";


if ( is_array( $conta ) ) {

    $conta = implode( ',', $conta );

}

/*
 $selRealizado = "SELECT
tb_vendas.id_bling,
tb_pedidos.dataPedido,
tb_itensPedido.qtde_itens,
tb_aliq_simples.aliq as 'imp',  
SUM( tb_itensPedido.custo_na_venda) as 'custo',
TRUNCATE(COALESCE(tb_vendas.total), 2) AS 'totalVenda',
TRUNCATE (COALESCE(AVG( tb_pedidos.taxas_taxaComissao / tb_pedidos.totalProdutos * 100), 0 ), 2 ) AS 'taxaLoja',
    TRUNCATE(COALESCE(AVG(IF(tb_pedidos.totalProdutos - tb_pedidos.taxas_valorBase < 0, 0, tb_pedidos.totalProdutos - tb_pedidos.taxas_valorBase)), 0), 2) AS 'Frete',
TRUNCATE ( SUM( tb_itensPedido.custo_na_venda * tb_itensPedido.quantidade ) /(SUM(tb_vendas.total)) * 100, 2) AS 'CMV'
FROM
    tb_pedidos
JOIN tb_itensPedido ON tb_itensPedido.id_pedido_bling = tb_pedidos.id_bling
JOIN tb_vendas ON tb_vendas.id_bling = tb_pedidos.id_bling AND DATE_FORMAT(tb_pedidos.dataPedido, '%Y-%m') = '" . $exercicio . "' 
JOIN tb_aliq_simples   ON tb_vendas.id_conta_bling =  tb_aliq_simples.id_conta_bling and DATE_FORMAT(tb_aliq_simples.exercicio, '%Y-%m') = '" . $exercicio . "' 
JOIN tb_produtos ON tb_itensPedido.produto_id = tb_produtos.id_bling
WHERE
  tb_pedidos.id_conta_bling IN (" . $conta . ") 
AND tb_vendas.situacao_id in (9,15)  
GROUP by  tb_vendas.numero  ";
*/
$selRealizado = "SELECT
    v.id_bling,
    p.dataPedido,
    sum(ip.qtde_itens) as qtde_itens,
    tb_aliq_simples.aliq AS 'imp',
    SUM(ip.custo_na_venda) AS 'custo',
TRUNCATE
    (COALESCE(v.total), 2) AS 'totalVenda',
TRUNCATE ( COALESCE( AVG( p.taxas_taxaComissao / p.totalProdutos * 100 ),0 ), 2 ) AS 'taxaLoja',
TRUNCATE ( COALESCE( AVG(IF( p.totalProdutos - p.taxas_valorBase < 0, 0, p.totalProdutos - p.taxas_valorBase ) ), 0 ),2) AS 'Frete',
TRUNCATE ( ip.custo_item_total * ip.qtde_itens / v.total * 100, 2 ) AS 'CMV'
FROM
    tb_pedidos p
JOIN tb_itensPedido ip ON ip.id_pedido_bling = p.id_bling
JOIN tb_vendas v ON v.id_bling = p.id_bling AND DATE_FORMAT(p.dataPedido, '%Y-%m') = '" . $exercicio . "' 
JOIN tb_aliq_simples ON v.id_conta_bling = tb_aliq_simples.id_conta_bling AND DATE_FORMAT( tb_aliq_simples.exercicio,  '%Y-%m' ) = '" . $exercicio . "'
JOIN tb_produtos  pr ON ip.produto_id = pr.id_bling
WHERE
    p.id_conta_bling IN(" . $conta . ")  AND v.situacao_id IN(9, 15)
GROUP BY
    v.numero 
ORDER BY `qtde_itens` DESC  ";

 //echo $selRealizado;


$selRealizado = $sql->select( $selRealizado );


    $totalVenda = 0;
    
    $CMV = 0;  // Custo Mercadoria Vendida
    $taxaLoja = 0;
    $IMP = 0;
    $frete = 0;
    

// caso não hava venda no mês pesquisado, busca as vendas do mês anterior para se obter uma prévia dos objetivos do mês.

if ( count( $selRealizado ) == 0 ) {
    
    
    
$exercicio2 = Date( 'Y-m', strtotime( -1 . 'month', strtotime($exercicio) ));
    
 $selRealizado = "SELECT
    v.id_bling,
    p.dataPedido,
    sum(ip.qtde_itens) as qtde_itens,
    tb_aliq_simples.aliq AS 'imp',
    SUM(ip.custo_na_venda) AS 'custo',
TRUNCATE
    (COALESCE(v.total), 2) AS 'totalVenda',
TRUNCATE ( COALESCE( AVG( p.taxas_taxaComissao / p.totalProdutos * 100 ),0 ), 2 ) AS 'taxaLoja',
TRUNCATE ( COALESCE( AVG(IF( p.totalProdutos - p.taxas_valorBase < 0, 0, p.totalProdutos - p.taxas_valorBase ) ), 0 ),2) AS 'Frete',
TRUNCATE ( ip.custo_item_total * ip.qtde_itens / v.total * 100, 2 ) AS 'CMV'
FROM
    tb_pedidos p
JOIN tb_itensPedido ip ON ip.id_pedido_bling = p.id_bling
JOIN tb_vendas v ON v.id_bling = p.id_bling AND DATE_FORMAT(p.dataPedido, '%Y-%m') = '" . $exercicio . "' 
JOIN tb_aliq_simples ON v.id_conta_bling = tb_aliq_simples.id_conta_bling AND DATE_FORMAT(
        tb_aliq_simples.exercicio, DATE_FORMAT( tb_aliq_simples.exercicio,  '%Y-%m' ) = '" . $exercicio . "'
JOIN tb_produtos  pr ON ip.produto_id = pr.id_bling
WHERE
    p.id_conta_bling IN(" . $conta . ")  AND v.situacao_id IN(9, 15)
GROUP BY
    v.numero 
ORDER BY `qtde_itens` DESC  "; 
        
 //   echo $selRealizado."<br>";
    
    $selRealizado = $sql->select( $selRealizado );
    
    

}


    for ( $i = 0; $i < count( $selRealizado ); $i++ ) {


        $totalVenda += $selRealizado[ $i ][ 'totalVenda' ];

    }

    for ( $i = 0; $i < count( $selRealizado ); $i++ ) {

        $taxaLoja += $selRealizado[ $i ][ 'totalVenda' ] / $totalVenda * $selRealizado[ $i ][ 'taxaLoja' ];
        
        $CMV += $selRealizado[ $i ][ 'totalVenda' ] / $totalVenda * $selRealizado[ $i ][ 'CMV' ];
        $IMP += $selRealizado[ $i ][ 'totalVenda' ] / $totalVenda * $selRealizado[ $i ][ 'imp' ];
        $frete  +=   $selRealizado[ $i ][ 'Frete' ] ;

    }

if ( count( $selRealizado ) == 0 ) {
    
    $totalVenda = 0;
}

    $selDespesasValor = "SELECT
	tb_despesas_categoria.categoria as 'categoriaDespesa',
    tb_despesas_categoria.descricao as 'descricao',
    COALESCE( SUM(valor),0) AS 'valorDespesas'
FROM
    `tb_despesas`
JOIN   tb_despesas_categoria ON tb_despesas.id_categoria = tb_despesas_categoria.id
WHERE
    DATE_FORMAT(dataIni, '%Y-%m') <= '" . $exercicio . "'  AND DATE_FORMAT(dataFin, '%Y-%m') >= '" . $exercicio . "' 
    AND id_conta IN (" . $conta . ")  
    AND tb_despesas_categoria.classificado = 'valor'
    GROUP BY tb_despesas_categoria.categoria ";


// echo $selDespesasValor;

    $resDespesasValor = $sql->select( $selDespesasValor );

    $totalValorDespesas = 0;

    foreach ( $resDespesasValor as $col ) {

        $totalValorDespesas += $col[ 'valorDespesas' ];
    }

    $FRETE = $frete / $totalVenda;
    
    $MC = 100 - ( $CMV + $taxaLoja + $IMP + $FRETE);

    $PE = ( $totalValorDespesas / ( $MC / 100 ) );

    $LL = ( $totalVenda * $MC / 100 ) - $totalValorDespesas;


    ?>

<div id="" class="divBloco"> 
    
          <label class='size12 divBotaoM btnFechar' id='lbx' style='float:right'>Fechar</label>
    <br>
    <br>
    <br>
    <div class="div_titulos size18">Resultado no exercício <?php echo  date('m-Y',strtotime($exercicio) );?> </div>
    <hr>
    <table align="center" width="400"  cellpadding="5" cellspacing="5"  >
        <tr class="trchave">
            <th colspan="3">
                <?php

                foreach ( $_POST[ 'conta' ] as $col => $a ) {

                    echo $Class->getConta( $a ) . "<br> ";

                }


                ?>
                &nbsp;</th>
        </tr>
        <tr class="trchave">
            <th>Despesas</th>
            <th align="center"><strong>%</strong></th>
            <th align="center">Valor</th>
        </tr>
        <?php

        foreach ( $resDespesasValor as $col ) {

            echo " <tr>";
            echo "<td align='left' title='" . $col[ 'descricao' ] . "'>" . $col[ 'categoriaDespesa' ] . "</td>";
            echo "<td align='right'>" . $desp = number_format( $col[ 'valorDespesas' ] / $totalVenda * 100, 2, ',', '.' ) . "</td>";
            echo "<td align='right'>" . number_format( $col[ 'valorDespesas' ], 2, ',', '.' ) . "</td>";
            echo "</tr>";

        }
        ?>
        <tr class="trchave"  style="border-top: 1px solid #333;">
            <th>
            </th>
            <th align="right">Total </th>
            <th align="right"><?php echo  number_format($totalValorDespesas,2,',','.') ;?></th>
        </tr>
        <tr>
            <td align="left">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
        </tr>
        <tr>
            <th align="center">Variáveis</th>
            <th align="center" class="trchave">%</th>
            <th align="center" class="trchave">Valor</th>
        </tr>
        <tr>
            <td align="left">Custo Mercadoria Vendida</td>
            <td align="right"><?php echo  number_format($CMV ,2,',','.') ;?></td>
            <td align="right"><?php echo number_format(($CMV * $totalVenda/100),2,',','.') ;?></td>
        </tr>
        <tr>
            <td align="left">Taxas Marketplace</td>
            <td align="right"><?php echo number_format($taxaLoja,2,',','.') ;?></td>
            <td align="right"><?php echo number_format(($taxaLoja * $totalVenda/100),2,',','.') ;?></td>
        </tr>
        <tr>
            <td align="left">Frete </td>
            <td align="right"><?php echo number_format(($frete / $totalVenda*100),2,',','.') ;?></td>
            <td align="right"><?php echo number_format($frete,2,',','.') ;?></td>
        </tr>
        <tr>
            <td align="left">Aliquota imposto</td>
            <td align="right"><?php echo number_format($IMP,2,',','.') ;?></td>
            <td align="right"><?php echo number_format(($IMP * $totalVenda/100),2,',','.') ;?></td>
        </tr>
        <tr>
            <td align="left">Margem de Contribuição</td>
            <td align="right"><?php echo number_format($MC,2,',','.') ;?></td>
            <td align="right"><?php echo number_format(($MC * $totalVenda/100),2,',','.') ;?></td>
        </tr>
        <tr>
            <td align="left">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
        </tr>
        <tr>
            <th align="center">Ponto de Equilíbrio </th>
            <th align="right">&nbsp;</th>
            <th align="right"><?php echo number_format($PE,2,',','.') ;?></th>
        </tr>
        <tr>
            <td align="left"></td>
            <td align="right"></td>
            <td align="right"></td>
        </tr>
        <tr>
            <td align="left"></td>
            <td align="right"></td>
            <td align="right"></td>
        </tr>
        <tr>
            <th align="center">Resultado</th>
            <th align="center" class="trchave">%</th>
            <th align="center" class="trchave">Valor</th>
        </tr>
        <tr>
            <td align="left">Realizado</td>
            <th align="right">&nbsp;</th>
            <th align="right"><?php echo number_format($totalVenda,2,',','.') ;?></th>
        </tr>
        <tr>
            <td align="left">Lucro Líquido</td>
            <th align="right"><?php echo number_format(($LL / $totalVenda*100),2,',','.') ;?></th>
            <th align="right"><?php echo number_format($LL,2,',','.') ;?></th>
        </tr>
        <tr>
            <td align="left">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>


