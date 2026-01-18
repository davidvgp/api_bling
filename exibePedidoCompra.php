<?php require_once( "session.php" ); ?>
<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script>
<script src="js/js_geral.js"></script>

<script>

    

    function calcDifPreco(x){
    
    let custo      = InNumCalc($('input[id="custo"][tabindex="'+x+'"]').val());
    let precoatual = InNumCalc($('input[id="precoatual"][tabindex="'+x+'"]').val());
    
    let diferenca = (precoatual - custo) / custo *100;
    
  
 diferenca = parseFloat(diferenca).toFixed(2);

        // Adicionar o sinal "+" se o número for positivo
        if (diferenca > 0) {
            diferenca = "+" + diferenca;
        } else if (diferenca < 0) {
            diferenca = "-" + Math.abs(diferenca); // Garante que o número negativo não tenha dois sinais
        }

        $('input[id="diferenca"][tabindex="'+x+'"]').val(diferenca+"%"); 
        
        }

 
    
    
$(document).ready(function() {
    
  /*  
    var table = new DataTable('#tblDin', {
    responsive: true,
    
    language: {
        url: '//cdn.datatables.net/plug-ins/2.2.2/i18n/pt-BR.json'
    },
    order: [[5, 'desc']],
    pageLength: 100,
    columnDefs: [
        {
            targets: [3],
            className: 'text-left'
        },
        {
            targets: [0,1,2,4],
            className: 'text-center'
        },
        {
            targets: [5,6,7,8],
            className: 'text-right'
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
    
  */  
    
    
    
      
       $(".precoatual").on("change", function () {

        let x = parseInt($(this).attr("tabindex"));
        
            calcDifPreco(x);


        });  
    
    
   $('input[type="text"]').keypress(function (e) {
    if (e.which == 13) { // Verifica se a tecla Enter foi pressionada
        e.preventDefault(); // Evita o comportamento padrão

        var currentId = $(this).attr('id'); // Captura o ID do elemento atual
        var currentTabindex = parseInt($(this).attr('tabindex')); // Captura o tabindex atual e o converte para inteiro

        if (!currentId || isNaN(currentTabindex)) {
            notaRodape("ID ou tabindex inválido.");
            return; // Interrompe caso o ID ou tabindex sejam inválidos
        }

        // Calcula o próximo tabindex
        var nextTabindex = currentTabindex + 1;

        // Seleciona o próximo elemento com o mesmo ID, mas tabindex incrementado
        var nextElement = $('input[id="' + currentId + '"][tabindex="' + nextTabindex + '"]');

        if (nextElement.length > 0) {
            nextElement.select(); // Move o foco para o próximo elemento
        } else {
         // volta para o primeiro elemento com tabindex = 1;   
            var nextElement = $('input[id="' + currentId + '"][tabindex="1"]');
            nextElement.select();
           
        }
    }
}); 
  
    
  $(".btnFechar").on("click", function() {
      
    $(this).closest("#corpoPedido").slideUp(); // Fecha apenas a div pai correspondente
  
  }); 
        
    

    $(".precoatual").on("change", function () {

        let x = parseInt($(this).attr("tabindex"));
        
            calcDifPreco(x);


        });   
    
  
    


 $('#imprimir').on('click', function () {
      // Aguarde um pequeno intervalo para renderizar o conteúdo dinâmico, se necessário
      setTimeout(function () {
        var conteudo = $('.areaImpressao').html(); // Certifique-se de capturar o ID correto
        if (conteudo) {
          var janela = window.open('', '_blank', 'width=600,height=800');
          janela.document.write('<html><head><title>Impressão</title><link rel="stylesheet" href="css/style.css" /></head><body>');
          janela.document.write(conteudo);
          janela.document.write('</body></html>');
          janela.document.close();
          janela.print();
        } else {
          alert('Não foi possível capturar o conteúdo da div para impressão.');
        }
      }, 500); // Intervalo de 500ms para garantir que o conteúdo seja carregado
    });

 });    
</script>
<?php
require_once( "config.php" );

$Class = new classMasterApi();
$sql   = new Sql();

$conta      = $_POST[ 'conta' ] ?? "";
$cnpjFornec = $_POST[ 'fornec' ] ?? "";


if ( is_array( $conta ) ) {

    $conta = implode( ',', $conta );

}

if ( is_array( $cnpjFornec ) ) {

    $cnpjFornec = implode( "','", $cnpjFornec );

}

foreach ( $_POST[ 'conta' ] as $col => $li ) {


$sel = "SELECT
    tb_pedidosCompras_itens.num_pedido AS 'numPedido',
    tb_user_api.cnpj AS 'cnpj',
    tb_produtos.codigo AS 'SKU',
    tb_produto_fornecedor.codigo AS 'Ref',
    tb_produto_fornecedor.descricao AS 'Produto',
    tb_pedidosCompras_itens.qtde AS 'Qtde',
    tb_pedidosCompras_itens.custo AS 'Custo',
TRUNCATE
    (tb_pedidosCompras_itens.custo * tb_pedidosCompras_itens.qtde, 2 ) AS 'Total', tb_pedidosCompras_itens.dataPedido AS 'Data'
FROM
    tb_pedidosCompras_itens
JOIN tb_produtos           ON tb_produtos.id_bling             = tb_pedidosCompras_itens.idProduto
JOIN tb_produto_fornecedor ON tb_produto_fornecedor.produto_id = tb_pedidosCompras_itens.idProduto
JOIN tb_user_api           ON tb_user_api.id                   = tb_pedidosCompras_itens.idConta
JOIN tb_contatos           ON tb_contatos.id_bling             = tb_produto_fornecedor.fornecedor_id
WHERE
   tb_pedidosCompras_itens.idConta in(" . $li . ") AND
   tb_contatos.cpf_cnpj in ( '" . $cnpjFornec . "')
GROUP BY tb_user_api.cnpj, tb_produtos.codigo
order by tb_produto_fornecedor.descricao  asc ";

 //  echo $sel;

$res = $sql->select( $sel );

$y = 1;
$totalPedido = 0;
if ( count( $res ) > 0 ) {
    
   echo "<div class='divBlocoNV2'>
   <div id='corpoPedido' class='corpoPedido'> 
   <label class='size12 divBotaoM btnFechar' id='lbx' style='float:right'>Fechar</label>
   <label class='size12 divBotaoM' id='imprimir' style='float:right'>Imprimir</label>
  <div class='areaImpressao'>
  <br>
  <br>
  <div class='div_titulos'> Intenção de Compra </div>";
   
    echo "<div class='subTitulos'>";
    echo "Emissão " . Date( 'd/m/Y', strtotime( $res[ 0 ][ 'Data' ] ) );
    echo " | ";
    echo " CNPJ: " . $res[ 0 ][ 'cnpj' ];
    echo "</center>";
    echo "</div><br>";

    ?>
    
    
<table id="tblDin" class='tblFormPreco display'>
    <thead>
    <tr>
        <th>item</th>
        <th>SKU</th>
        <th>Ref</th>
        <th>Produto</th>
        <th>Qtde(Un)</th>
        <th>Ult.Custo</th>
        <th>Total</th>
        <th>Preço Atual</th>
        <th>Diferença</th>
    </tr>
    </thead>
    <tbody>
    
    
    <?php
    $i=0;
    foreach ( $res as $k ) {
        
        $i++;
        echo "<tr class='trClick' >";
        echo "<td align='center'>" . $y++ . "</td>";
        echo "<td align='center'>" . $k[ 'SKU' ] . "</td>";
        echo "<td align='center'>" . $k[ 'Ref' ] . "</td>";
        echo "<td>" . $k[ 'Produto' ] . "</td>";
        echo "<td align='center'><strong>" . $k[ 'Qtde' ] . "</strong></td>";
        echo "<td ><input type='text' id='custo'  tabindex='{$i}'  value='" . number_format( $k[ 'Custo' ], 2, ',', '.' ) . "'  class='custo' size='4'></td>";
        echo "<td align='right'>" . number_format( $k[ 'Total' ], 2, ',', '.' ) . "</td>";
        echo "<td align='center'><input type='text' id='precoatual'   tabindex='{$i}'  value=''  class='precoatual' size='4'></td>";
        echo "<td align='center'><input type='text' id='diferenca'    tabindex='{$i}'  value=''  class='diferenca ' size='4'></td>";
        echo "</tr>";

        $totalPedido += $k[ 'Total' ];
    }

    ?>
    </tbody>
 <tfoot>
        <tr style="border-top: ridge 1px var(--cor-borda1)">
            <td colspan="6" align="center"><strong>Total</strong></td>
            <td align="right"><strong><?php echo number_format($totalPedido,2, ',', '.' );?></strong> </td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
    
</table>
        
<?php

} else {
   echo '<script>notaRodape("Nenhum PEDIDO encontrado!"); </script>';

}

echo "
</div>
</div>
</div>
</div>";

}

?>
