<?php
require_once( "session.php" );
require_once( "config.php" );
?>

<script src="js/jquery-3.7.1.js"></script> 
<link rel="stylesheet" href="js/datatables.css" />

<script src="js/datatables.js"></script>
<link   rel="stylesheet" href="css/style.css" />
<script src="js/js_geral.js"></script> 

<script>
    
    
$(document).ready(function() {
   

let table = new DataTable('#tblDin', {
    
    responsive: true,
    
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
            targets: [1,2, 3,4,5,8,19,20,21],
            className: 'dt-body-center'
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
    
    
  
    
    $("#btConfig").on("click", function(){
        
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

 $contas = $Class->getContas($_SESSION[ "idUsuario" ] );


  foreach ( $contas as $col ) {

   $idConta[] = $col[ 'id' ];

  }
  if ( is_array( $idConta ) ) {

   $contas = implode( ', ', $idConta );

  }

  listaPedidos( $contas );


  function listaPedidos( $idsContas, $nomeConta = 'Geral' ) {

   $Class = new classMasterApi();
   $sql = new Sql();

   $sel = "SELECT
            tb_vendas.id as id,
	tb_vendas.id_conta_bling as 'idconta',
date_format(tb_vendas.dataVenda, '%d/%m/%y') AS 'Data',

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         (
        SELECT COUNT(*) 
        FROM tb_itensPedido 
        WHERE tb_itensPedido.id_pedido_bling = tb_vendas.id_bling
    ) AS 'itens'
    ,
    c.nome_conta_bling as conta,
    tb_vendas.numero AS Pedido,
    tb_vendas.id_bling as idPedido,
	tb_situacoes_modulos.nome AS Stt,
    tb_situacoes_modulos.cor AS Cor,
	tb_pedidos.numeroLoja as Pedido_Loja,
    tb_canais_de_venda.descricao AS Loja,
	tb_canais_de_venda.tipo AS LojaTipo,
	tb_canais_de_venda.txFixa1 AS TaxaFixa1,
    tb_canais_de_venda.txFixa2 AS TaxaFixa2,
    tb_itensPedido.frete_prop as Frete,
    tb_itensPedido.codigo AS SKU,
    tb_produtos_detalhes.cubagem as cubagen,
    tb_itensPedido.descricao AS Produto,
 truncate(tb_itensPedido.quantidade,0) AS Qtde,
    tb_itensPedido.valor AS Valor,
 COALESCE(tb_itensPedido.custo_na_venda,0) AS Custo,
    tb_itensPedido.desconto AS Desconto,
	tb_vendas.totalProdutos as TotalProdutos,
    tb_vendas.total AS TotalNF,
    tb_itensPedido.taxa_prop AS Taxa,
    tb_pedidos.taxas_valorBase as BaseC,
	tb_aliq_simples.aliq AS Imp

FROM
   tb_itensPedido
 
JOIN tb_pedidos on tb_pedidos.id_bling = tb_itensPedido.id_pedido_bling 

JOIN tb_produtos_detalhes on tb_produtos_detalhes.codigo = tb_itensPedido.codigo

JOIN tb_vendas on tb_vendas.id_bling   = tb_pedidos.id_bling AND tb_vendas.id_conta_bling IN ({$idsContas}) and tb_vendas.dataVenda > DATE_SUB(CURRENT_DATE(), INTERVAL 5 DAY)

JOIN tb_user_api as c on c.id = tb_vendas.id_conta_bling
JOIN tb_situacoes_modulos ON tb_vendas.situacao_id = tb_situacoes_modulos.id_bling

JOIN tb_canais_de_venda ON tb_vendas.loja_id = tb_canais_de_venda.id_bling

JOIN tb_aliq_simples ON tb_vendas.id_conta_bling = tb_aliq_simples.id_conta_bling AND DATE_FORMAT(tb_aliq_simples.exercicio, '%Y-%m') = DATE_FORMAT(tb_pedidos.dataPedido, '%Y-%m')
    group by
    c.id,
    tb_vendas.numero,
    tb_itensPedido.descricao
    order by  tb_vendas.id  desc limit 200;";
   
    //echo $sel;

   $ult_pedidos = $sql->select( $sel );

   ?>
 <div class='divBlocoNV1'>
 <div class='divBlocoNV2'>
 <div class='div_titulos'>Pedidos: <?php echo $nomeConta?></div>
 
  <table id="tblDin" class="display compact tblStyle1">
      
   <thead class="theadsticky">
    <tr>
     <th>ID</th>
     <th>Conta</th>
     <th>Data</th>
     <th>Pedido</th>
     <th>Loja</th>
     <th>Stt</th>
     <th>SKU</th>
     <th>Produto</th>
     <th>Qtde</th>
     <th>Valor</th>
     <th>Custo</th>
     <th>Desc</th>
     <th>Total</th>
     <th>BC</th>
     <th>Taxa</th>
     <th>Frete</th>
     <th>Repas</th>
     <th>Imp</th>
     <th>%</th>
     <th>Lucr</th>
     <th>%</th>
     <th>-</th>
    </tr>
   </thead>
   <tbody>
    <?php

    $num_pedido = 0;
    $item = 0;
    $npedido = "";


    foreach ( $ult_pedidos as $col ) {

     $idconta = $col[ 'idconta' ];

     $slct = "SELECT COUNT(*) as 'itens' FROM `tb_itensPedido` WHERE numero_pedido = '{$col[ 'Pedido' ]}' AND id_conta_bling = '{$idconta}'";

     $res1 = $sql->select( $slct );
     $item = $res1[ 0 ][ 'itens' ];
     $qtde = $col[ 'Qtde' ];
     $itens = $col[ 'itens' ];
     $frete = $col[ 'Frete' ];
     $valor = $col[ 'Valor' ];
     $custo = $col[ 'Custo' ];
     $cubagem = $col[ 'cubagen' ];

     $custoTotal = $qtde * $custo;

     $desc = $col[ 'Desconto' ];
     $totalProd = $col[ 'TotalProdutos' ];
     $total_NF = $col[ 'TotalNF' ];
     $taxaFixa = $col[ 'TaxaFixa1' ];
     $taxaFixa2 = $col[ 'TaxaFixa2' ];
     $taxaMKP = $col[ 'Taxa' ];
     $ValorBase = $col[ 'BaseC' ];
     $aliq = $col[ 'Imp' ];

     if ( $ValorBase == 0 ) {
      $ValorBase = $total_NF;
     }
     $calc = $Class->calcMargemVenda( $idconta, $col[ 'LojaTipo' ], $itens, $qtde, $frete, $valor, $custo, $custoTotal, $desc, $totalProd, $total_NF, $taxaFixa, $taxaMKP, $ValorBase, $aliq, $cubagem );


     echo "<tr class='trchave'>";

     //    if($a < 0.0){ $color ='#d69143'; echo "negativo"; }

     echo "<td>{$col[ 'id' ]}</td>";
     echo "<td>{$col[ 'conta' ]}</td>";
     echo "<td>{$col[ 'Data' ]}</td>";
     echo "<td>{$col[ 'Pedido']}</td>";

     echo "<td><img src='imgs/icon/{$col[ 'LojaTipo' ]}.svg' class='copiarTextoImg' title='Clicar 2x para copiar o nº {$col[ 'Pedido_Loja' ]}' alt={$col[ 'Pedido_Loja' ]} width='20' height='20'></td>";

     echo "<td><span class='iconeBola' title='{$col['Stt']}' style='background-color:{$col['Cor']}'></span></td>";
     echo "<td>{$col[ 'SKU' ]}</td>";
     echo "<td>{$col[ 'Produto' ]}</td>";
     echo "<td>{$col[ 'Qtde' ]}</td>";

     foreach ( $calc as $row => $vlr ) {

      echo "<td>{$vlr}</td>";
      // 12 linhas
     }

     $idx = $col[ 'idconta' ] . $col[ 'idPedido' ] . $col[ 'SKU' ];

     // este trecho é só para gerar dados de post para enviar para página de analise de preço
     echo "<form id='form{$idx}'>";
     echo "<input type='hidden' id='conta{$idx}' name='conta' value='{$col['idconta']}'>";
     echo "<input type='hidden' id='produtos{$idx}' name='produtos' value='{$col[ 'SKU' ]}'>";
     //  echo "<input type='hidden' id='lojas{$idx}' name='lojas'  value='{$col[ 'LojaTipo' ]}'>";
     echo "</form>";
     //**********************************************************************************************************    


     echo "<td><img src='imgs/iconMenu/iconAjustes.png' width='14' title='Revisar o preço'  class='btConfig' id='{$idx}' ></td>";

     echo "</tr>";


    }

    ?>
   </tbody>
<!--   <tfoot>
    <tr>
     <th>ID</th>
     <th>Conta</th>
     <th>Data</th>
     <th>Pedido</th>
     <th>Loja</th>
     <th>Stt</th>
     <th>SKU</th>
     <th>Produto</th>
     <th>Qtde</th>
     <th>Valor</th>
     <th>Custo</th>
     <th>Desc</th>
     <th>Total</th>
     <th>Base C</th>
     <th>Taxa</th>
     <th>Frete</th>
     <th>Repas</th>
     <th>Imp</th>
     <th>%</th>
     <th>Lucr</th>
     <th>%</th>
    </tr>
   </tfoot>-->
  </table>
 </div>
</div>


<?php } // fecha a função ?>
