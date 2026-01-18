<script src="js/jquery-3.7.1.js"></script>
<script src="js/js_forma_precos.js"></script>
<script src="js/salva_preco.js"></script>
<script src="js/aplica_style.js"></script>
<link rel="stylesheet" href="css/style.css" />


<?php

require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


if ( empty( $_POST ) ) {

  echo "Nenhum dado econtrado";

} else {

  foreach ( $_POST as $k => $v ) {

    //       echo $k . ": " . $v . "<br>";

  }



  $conta = ( int )trim( $_POST[ 'conta' ] );

  $fornec = trim( $_POST[ 'fornec' ] );



  $checkBox = "";

  if ( isset( $_POST[ 'histVenda' ] ) ) {

    $checkBox = $_POST[ 'histVenda' ];

  }

  $produtoSelecionados = $_POST[ 'produtos' ];

  // print_r( $produtoSelecionados);                           

  $busca = array();

  foreach ( $produtoSelecionados as $k => $prod ) {

    $res = $sql->select( "CALL p_analise_preco_produto_lojas (:CONTA,:FORNEC,:PRODUTOS)", array( ":CONTA" => $conta, ":FORNEC" => $fornec, ":PRODUTOS" => $prod ) );

    if ( count( $res ) > 0 ) {

      $busca[] = $res;
    }

  }

  // print_r($busca);

  //   echo "<hr>";
  if ( count( $busca ) > 0 ) {


    ?>
<div id="absolut">
  <div class="load"><img src="imgs/loading-gif-transparent.gif" width="50"></div>
</div>
<div class="divBloco shadow">
  <input type="hidden" id="id_conta" value="<?php echo $conta;  ?>">
  <input type="hidden" id="id_forn"  value="<?php echo $fornec;  ?>">
  <div class="div_titulos size20"><?php echo $Class->getConta($conta); ?> | PRODUTOS LOJAS</div>
  Aliquota de Imposto:
  <input title="text" id="alqImposto" style="text-align: center"  value="<?php echo number_format($busca[0][0]['AliqImposto'],2); ?>" size="2">
  %
  <table class="tblFormPreco size12">
    <tbody>
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <?php  if($checkBox == "S"){   ?>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <?php }  ?>
        <td></td>
        <td><input type="text" size="2" style='text-align:center;' value="16.0" class="" id="addComissao">
          <br>
          <input type="button" id="btAplicaComissao" value="&darr;"></td>
        <td><input type="text" size="2" style='text-align:center;' value="6.00" class="" id="addTxFixa">
          <br>
          <input type="button" id="btAplicaTxFixa"   value="&darr;"></td>
        <td><input type="text" size="2" style='text-align:center;' value="1,80" class="" id="addMrkUp">
          <br>
          <input type="button" id="btAplicaMrkup"    value="&darr;"></td>
        <td></td>
        <!--   <td><input type="text" size="2" style='text-align:center;' value="5.0%" class="" id="addDesc"> <br><input type="button" id="btDesc"    value="&darr;"></td>-->
        <td><input type="text" size="2" style='text-align:center;' value="10.0" class="" id="addMarg">
          <br>
          <input type="button" id="btAplicaMarg"    value="&darr;"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr> 
        <!--<th>Fornecedor</th> --> 
        <!--th>IdLoja</th>  --> 
        <th></th>
        <th>Loja</th> 
        <!--<th>IdProduto</th> -->
        
        <th>Anuncio</th>
        <th>SKU</th>
        <th>Descrição</th>
        <!--<th>Deposito</th> -->
        
        <?php  if($checkBox == "S"){   ?>
        <th style='text-align:center;'><?php echo Date( 'm', strtotime( -3 . 'month' ) ) ?></th>
        <th style='text-align:center;'><?php echo Date( 'm', strtotime( -2 . 'month' ) ) ?></th>
        <th style='text-align:center;'><?php echo Date( 'm', strtotime( -1 . 'month' ) ) ?></th>
        <th style='text-align:center;'><?php echo Date( 'm' ) ?></th>
        <?php }  ?>
        <th>Estoq</th>
        <th>Custo</th>
        <th>Preço</th>
        <th>Promo</th>
        <!--  <th>M²</th>-->
        <th>Frete</th>
        <th>Taxa %</th>
        <th>Tx Fixa</th>
        <th>MrkUp</th>
        <th>Novo Preço</th>
        <!--   <th>c/ Desc</th>-->
        <th>%Mrg</th>
        <th>LL</th>
        <th>Repasse</th>
        <th colspan="2"><span id="bt_upTodosPrecos">Atualizar</span></th>
      </tr>
      <?php

      $i = 0;
      $mkpAtual = 0;

      foreach ( $busca as $l1 ) {
          
        foreach ( $l1 as $res ) {
         $i++;
         
         echo "<tr class='trchave'>";
          //echo "<td>".$res['Fornecedor']."</td>";
          //echo "<td>".$res['IdLoja']."</td>";
         
          //echo "<td>".$res['IdProd']."</td>";
          echo "<td>";
          echo "<input type='hidden' id='IdLoja" . $i . "' value='".$res[ 'IdLoja' ]."'>";
          echo "<input type='hidden' id='IdProd" . $i . "' value='".$res[ 'IdProd' ]."'>";
          echo "<input type='hidden' id='IdBling" .$i . "' value='".$res[ 'IdBling' ]."'>";
          echo "<input type='button' id='btExcluiAnuncio" . $i . "' title='" . $i . "' value='x' class='btXDelete'>";
          echo "</td>";
           echo "<td>" . $res[ 'Loja' ] . "</td>";  
          echo "<td>";
          echo "<input type='hidden' id='IdBling" . $i . "' value='".$res[ 'IdBling' ]."'>";
          echo $res[ 'IdAnuncio' ];
          echo "</td>";
          echo "<td>" . $res[ 'SKU' ] . "</td>";
          echo "<td style='text-align:left;'>" . $res[ 'Produto' ] . "</td>";

          //echo "<td>".$res['Deposito']."</td>";

          

          $IdBling = ( int )$res[ 'IdBling' ];
          $lojas = $res[ 'IdLoja' ];

          if ( $res[ 'freteLoja' ] > 0 ) {
            ( float )$frete = $res[ 'freteLoja' ];
          } else {
            ( float )$frete = $res[ 'Frete' ];
          }


          $txFixa = ( float )$res[ 'txFixa' ];
          $custo = ( float )$res[ 'Custo' ];
          $Preco = ( float )$res[ 'Preço' ];
            
                        
          ( float )$vPedMin = $res[ 'vPedMin'];                 
          if( $res[ 'vPedMin'] == "0" ){ $vPedMin = 100000;}  
            
          $novoPreco = ( float )$res[ 'novoPreco' ];
          $Categoria_Produto = $res[ 'Categoria_Produto' ];
          $imposto = ( float )$res[ 'AliqImposto' ];
          $Prom = number_format( ( float )$res[ 'Promocional' ], 2, ',', '' );
          $taxaClassico = 0;
          $taxaPremium = 0;
          $xfrete = 0;
          $margem = 0;
          $xtxFixa = 0;
          $lucro = 0;

          if ( $custo < 1 and $Preco > 0 ) {
            $custo = $Preco / 1.8;
          }

          if ( $Preco < 1 and $custo > 0 ) {
            $Preco = $custo * 1.8;
          }

          if ( $Preco < $vPedMin ) {

            $xfrete = 0;
            $xtxFixa = $txFixa;

          } else {

            $xfrete = $frete;
            $xtxFixa = 0;
          }


          $getTaxaLoja = $Class->taxaVendaLojas( $conta, $lojas, $Categoria_Produto );
          $taxaClassico = number_format((float)$getTaxaLoja[ 0 ][ 'cass' ],2, ',', '' );
          $taxaPremium  = number_format((float)$getTaxaLoja[ 0 ][ 'prem' ],2, ',', '' );


          $imposto = ( $Preco * $imposto / 100 );
          $vcomissao = $xtxFixa + ( $Preco * $getTaxaLoja[ 0 ][ 'cass' ] / 100 );
          $lucro = ( $Preco - $custo - $xfrete - $vcomissao - $imposto );
          $margem = ( $lucro / $Preco ) * 100;
          $mkpAtual = ( $Preco / $custo );
          $promocao = $Prom;

          $mkpAtual = number_format( $mkpAtual, 3, ',', '' );
          $custo = number_format( $custo, 2, ',', '' );
          $Preco = number_format( $Preco, 2, ',', '' );
          $novoPreco = number_format( $novoPreco, 3, ',', '' );
          $margem = number_format( $margem, 3, ',', '' );
          $lucro = number_format( $lucro, 2, ',', '' );
          $txFixa = number_format( $txFixa, 2, ',', '' );
          $frete = number_format( $frete, 2, ',', '' );    

          if ( $checkBox == "S" ) {
            echo "<td style='text-align:center;'>";
            echo $Class->qtdeVendMesProdutoLojas( $conta, $lojas, $res[ 'IdProd' ], 3 );
            echo "</td>";

            echo "<td style='text-align:center;'>";
            echo $Class->qtdeVendMesProdutoLojas( $conta, $lojas, $res[ 'IdProd' ], 2 );
            echo "</td>";

            echo "<td style='text-align:center;'>";
            echo $Class->qtdeVendMesProdutoLojas( $conta, $lojas, $res[ 'IdProd' ], 1 );
            echo "</td>";

            echo "<td style='text-align:center;'>";
            echo $Class->qtdeVendMesProdutoLojas( $conta, $lojas, $res[ 'IdProd' ], 0 );
            echo "</td>";

          }


          echo "<td><strong>" . $res[ 'Estoque' ] . "</strong></td>";
          echo "<td><input type='text' id='custo" . $i . "' title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='" . $custo . "'  class='qtdePedido   ' size='4'></td>";
          echo "<td style='text-align:right;'><strong>" . $Preco . "</strong></td>";
          echo "<td style='text-align:right;'><strong>" . $Prom . "</strong></td>";

          //echo "<td style='text-align:right;'>" . number_format( $res[ 'Cubage' ], 2, ',', '.' ) . "</td>";

          echo "<td><input type='text' id='frete" . $i . "'    title='" . $i . "' tabindex='" . $i . "' style='text-align:right;'  value='" . $frete . "'class='qtdePedido  cFrete ' size='2'></td>";
          echo "<td>";
          echo "<input type='hidden' id='categ_prod" . $i . "' title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='" . $Categoria_Produto . "'  class='qtdePedido  cComissao ' size='2'>";
          echo "<input type='text' id='comissao" . $i . "' title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='" . $taxaClassico . "'  class='qtdePedido  cComissao' size='2'>";
          echo "</td>";
          echo "<td>";
         echo "<input type='hidden' id='vPedMin" . $i . "'   title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='" . $vPedMin . "'  class='qtdePedido  cvPedMin' size='2'>"; 
         echo "<input type='text' id='txFixa" . $i . "'   title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='" . $txFixa . "'  class='qtdePedido  ctxFixa ' size='2'>"; 
            
        echo "</td>";
          echo "<td><input type='text' id='MrkUp" . $i . "'      title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='" . $mkpAtual . "'  class='qtdePedido  cMkp' size='2'></td>";
          echo "<td><input type='text' id='preco" . $i . "'    title='" . $i . "' tabindex='" . $i . "'  value='" . $novoPreco . "' class='qtdePedido cPreco inputDestaq' size='4'></td>";

          //   echo "<td><input type='text' id='promocao" . $i . "'    title='" . $i . "' tabindex='" . $i . "' style='text-align:right; ' value='".$promocao."' class='qtdePedido ' size='4'></td>";
          echo "<td><input type='text' id='marg" . $i . "'    title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='" . $margem . "' class='qtdePedido cMarg' size='2'>
      <label id='calMrg'><label>
      </td>";
          echo "<td><input type='text' id='lucro" . $i . "'    title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='" . $lucro . "' class='qtdePedido ' size='3'></td>";
          echo "<td><input type='text' id='repasse" . $i . "'    title='" . $i . "' tabindex='" . $i . "' style='text-align:right;' value='' class='qtdePedido ' size='3'></td>";
          echo "<td align='center'>";
          echo "<input type='hidden' id='prodIdBling" . $i . "' value='" . $IdBling . "'>";
          echo "<img width='18px' src='imgs/bt_sincroniza.png' title='" . $i . "' id='btEnviaPreco" . $i . "' class='btupdtpreco'>";
          echo "<td align='center'><span id='confirm" .$i. "'></span></td>";
          echo "</td>";
          echo "</tr>";
        }
      }
      ?>
    </tbody>
  </table>
  <label id='verificaCal'></label>
</div>
<?php

} else {
  echo '<div class="divMiniBloco">';
  echo "<br><strong>Nenhum dado encontrado</strong>.<Br><Br> Verifique se possui anuncios para esta loja no Bling. <br><br>";
  echo '</div>';

}

}
?>

  <div id="notaRodape"><label id="fecharRodaPe">Fechar</label></div>
