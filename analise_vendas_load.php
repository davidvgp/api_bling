<?php
require_once( "session.php" );
require_once( "config.php" );


?>

<script src="js/jquery-3.7.1.js"></script> 
<link rel="stylesheet" href="js/datatables.css" />
<script src="js/datatables.js"></script>
<link   rel="stylesheet" href="css/style.css" />
<script src="js/js_geral.js"></script> 
<script src="//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json"></script> 
<script>
    
$(document).ready(function(){

 //   setInterval(function(){ location.reload(); }, 120000);
        
  
let table = new DataTable('#tblDin', {
  //  responsive: true,
    
    language: {
        url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json'
    },
    order: [[0, 'desc']],
    pageLength: 50,
    
    columnDefs: [
      
        {   
            targets: ["_all"],
            className: 'dt-head-center'
       
        },   
        {
            targets: [8],
            className: 'dt-body-left'
        }
    ],
    
    layout: {
        bottomEnd: {
            paging: {
                firstLast: false
            }
        }
    }
});
    
    
           
    
    $(".btConfig").on("click", function(){
        
        efeitoload("in");
        
        var idx = $(this).attr("id");
        var post = $("#form"+idx).serialize();
        
        $('#divCarregado').slideUp(function () {
                    
                $.post("analise_preco_load.php",post,function (dados) {

                    $("#divCarregado").html(dados).slideDown(800);
                
                    efeitoload('out');
               
                });

            });
        
        
    });

});     
     
</script>

<?php



$Class = new classMasterApi();
$sql = new Sql();


$conta   = $_POST[ 'conta' ] ?? "";
$fornec  = $_POST[ 'fornec' ] ?? "";
$prod    = $_POST[ 'produtos' ] ?? "";
$lojas   = $_POST[ 'lojas' ] ?? "";
$dataIni = $_POST[ 'dataIni' ] ?? "";
$dataFin = $_POST[ 'dataFin' ] ?? "";
$statusPedidos = $_POST[ 'statusPedidos' ] ?? array( 9, 15 );


if ( is_array( $conta ) ) {

    $conta = implode( ', ', $conta );

    $conta = " AND tb_vendas.id_conta_bling IN ({$conta}) ";

}

if ( is_array( $fornec ) ) {

    $fornec = implode( "', '", $fornec );

    $fornec = " AND tb_contatos.cpf_cnpj IN('{$fornec}') ";

}


if ( is_array( $lojas ) ) {

    $lojas = implode( "', '", $lojas );


    $lojas = " AND tb_canais_de_venda.tipo IN( '{$lojas}') ";

}

if ( is_array( $statusPedidos ) ) {

    $statusPedidos = implode( ",", $statusPedidos );

    $filtroStatusPedidos = "AND tb_vendas.situacao_id IN ({$statusPedidos}) ";

}

if ( is_array( $prod ) ) {

    $prod = implode( "', '", $prod );

    $prod = " AND tb_produto_fornecedor.codigo IN ('{$prod}') ";

}



$sel1 = "
SELECT
    tb_vendas.id as id,
date_format(tb_vendas.dataVenda, '%d/%m/%y') AS 'Data',
    (
        SELECT COUNT(*) 
        FROM tb_itensPedido 
        WHERE tb_itensPedido.id_pedido_bling = tb_vendas.id_bling
    ) AS itens,
    tb_user_api.id AS idConta,
    tb_user_api.nome_conta_bling AS conta,
    tb_vendas.numero AS Pedido,
    tb_vendas.id_bling as idPedido,
	tb_situacoes_modulos.nome AS Stt,
    tb_situacoes_modulos.cor AS Cor,
	tb_pedidos.numeroLoja as PedidoLoja,
    tb_canais_de_venda.tipo AS LojaTipo,
	tb_canais_de_venda.txFixa1 AS TaxaFixa1,
    tb_canais_de_venda.txFixa2 AS TaxaFixa2,
    tb_itensPedido.frete_prop as Frete,
    tb_itensPedido.codigo AS SKU,
    tb_itensPedido.descricao AS Produto,
 truncate(tb_itensPedido.quantidade,0) AS Qtde,
    tb_itensPedido.valor AS 'Valor',
 COALESCE(tb_itensPedido.custo_na_venda,0) AS Custo,
    tb_itensPedido.desconto AS Desconto,
    tb_vendas.totalProdutos as TotalProdutos,
    tb_vendas.total AS total_NF,
    tb_itensPedido.taxa_prop AS Taxa,
    tb_pedidos.taxas_valorBase as ValorBase,
	tb_aliq_simples.aliq AS Imp,
	tb_contatos.nome as Forn,
    tb_produtos_detalhes.cubagem as cubagen

FROM
    tb_canais_de_venda

JOIN tb_vendas             ON tb_canais_de_venda.id_bling      = tb_vendas.loja_id AND tb_vendas.dataVenda between  '{$dataIni}' AND '{$dataFin}' {$filtroStatusPedidos} 
JOIN tb_situacoes_modulos  ON tb_vendas.situacao_id            = tb_situacoes_modulos.id_bling
JOIN tb_aliq_simples       ON tb_vendas.id_conta_bling         = tb_aliq_simples.id_conta_bling AND DATE_FORMAT( tb_aliq_simples.exercicio, '%Y-%m') = DATE_FORMAT( tb_vendas.dataVenda, '%Y-%m')
JOIN tb_user_api           ON tb_vendas.id_conta_bling         = tb_user_api.id {$conta}
JOIN tb_pedidos            ON tb_vendas.id_bling               = tb_pedidos.id_bling  
JOIN tb_itensPedido        ON tb_pedidos.id_bling              = tb_itensPedido.id_pedido_bling
JOIN tb_produtos_detalhes  on tb_produtos_detalhes.codigo      = tb_itensPedido.codigo
JOIN tb_produto_fornecedor ON tb_itensPedido.produto_id        = tb_produto_fornecedor.produto_id {$prod}
JOIN tb_contatos           ON tb_produto_fornecedor.fornecedor_id = tb_contatos.id_bling {$fornec}
 ";
$sel1 .= $lojas;
$sel1 .= " group by tb_itensPedido.descricao, tb_itensPedido.numero_pedido ";
$sel1 .= " order by tb_vendas.id desc";

//echo $sel1."<hr>";

$res = $sql->select( $sel1 );

if ( count( $res ) > 0 ) {

    echo "<div class='divBlocoNV1'>";
    echo "<div class='divBlocoNV2'>";
    echo "<table id='tblDin' class='display compact tblStyle1'>";
    
    echo "<thead>";
   
    echo "<tr style='font-size:10px'>";
    echo "<th>ID</th>";
    echo "<th>Data</th>";
    echo "<th>Conta</th>";
    echo "<th>Ped</th>";
    echo "<th>Loja</th>";
    echo "<th>Stt</th>";
    echo "<th>PedidoLoja</th>";


    echo "<th>SKU</th>";
    echo "<th>Produto</th>";
    echo "<th>Qtde</th>";
    echo "<th>Valor</th>";
    echo "<th>Custo</th>";
    echo "<th>Desc</th>";
    echo "<th>Total</th>";
    
    echo "<th title='Base Calculo'>BC</th>";
    echo "<th>Taxa</th>";
    echo "<th>Frete</th>";
    echo "<th>Repas</th>";
    echo "<th>Imp</th>";
    echo "<th>%</th>";
    echo "<th>Lucr</th>";
    echo "<th>%</th>";
    echo "<th>*</th>"; // 23 colulas
    
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    $num_pedido = 0;

    foreach ( $res as $col ) {

        $idConta = $col[ 'idConta' ];
        $qtde = $col[ 'Qtde' ];
        $itens = $col[ 'itens' ];
        $frete = $col[ 'Frete' ];
        $valor = $col[ 'Valor' ];
        $custo = $col[ 'Custo' ];
        $cubagem = $col[ 'cubagen' ];
        $custoTotal = $qtde * $custo;
        $desc = $col[ 'Desconto' ];
        $TotalProdutos = $col[ 'TotalProdutos' ];
        $total_NF = $col[ 'total_NF' ]; // total da NF
        $taxaFixa = $col[ 'TaxaFixa1' ];
        $taxaMKP = $col[ 'Taxa' ];
        $ValorBase = $col[ 'ValorBase' ];
        $aliq = $col[ 'Imp' ];

        if ( $ValorBase == 0 ) {
            $ValorBase = $total_NF;
        }

      
        echo "<tr class='trchave'>";

        //    if($a < 0.0){ $color ='#d69143'; echo "negativo"; }

        echo "<td>{$col[ 'id' ]}</td>";
        echo "<td>{$col[ 'Data' ]}</td>";
        echo "<td>{$col[ 'conta' ]}</td>";
        echo "<td>{$col[ 'Pedido' ]}</td>";
        
    echo "<td><img src='imgs/icon/{$col[ 'LojaTipo' ]}.svg' class='copiarTextoImg' title='Clicar 2x para copiar o nº {$col[ 'PedidoLoja' ]}' alt={$col[ 'PedidoLoja' ]} width='20' height='20'></td>";
        
          
        echo "<td><span class='iconeBola' title='{$col['Stt']}' style='background-color:{$col['Cor']}'></span></td>";
                
        echo "<td>{$col[ 'PedidoLoja' ]}</td>";


        echo "<td>{$col[ 'SKU' ]}</td>";
        echo "<td>{$col[ 'Produto' ]}</td>";
        echo "<td>{$col[ 'Qtde' ]}</td>";

  
     $calc = $Class->calcMargemVenda($col[ 'idConta' ] , $col[ 'LojaTipo' ], $itens, $qtde, $frete, $valor, $custo, $custoTotal, $desc, $TotalProdutos, $total_NF, $taxaFixa, $taxaMKP, $ValorBase, $aliq, $cubagem );

      
        foreach ( $calc as $row => $vlr ) {

            echo "<td>{$vlr}</td>";
            // 12 linhas
        }
        
           $idx = $col['conta']. $col[ 'idPedido' ].$col[ 'SKU' ];
                
                // este trecho é só para gerar dados de post para enviar para página de analise de preço
                echo "<form id='form{$idx}'>";
                echo "<input type='hidden' id='conta{$idx}'      name='conta' value='{$col['conta']}'>";
                echo "<input type='hidden' id='produtos{$idx}' name='produtos' value='{$col['SKU' ]}'>";
                echo "<input type='hidden' id='lojas{$idx}'      name='lojas'  value='{$col['LojaTipo']}'>";
                echo "</form>";
                //**********************************************************************************************************   
    
    
    echo "<td><img src='imgs/btConfig.png' width='14' title='Revisar o preço' class='btConfig' id='{$idx}' ></td>";
    echo "</tr>";

    }

   echo "</tbody>";
   echo "</table>";
   echo "</div>";
   echo "</div>";
   echo "</div>";


} else {
    echo "<script>";
    echo 'notaRodape("Nenhuma informação para os filtros selecionados!")';
    echo "</script>";

}
?>