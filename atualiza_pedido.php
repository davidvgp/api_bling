<?php session_start(); 
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}


require_once( 'menu.php' );
require_once( 'config.php' );


$Class = new classMasterApi();
$sql = new Sql();

$ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_app


$pedidosListados = 0;
$pedidosAtualizados = 0;


if ( isset( $_POST[ 'dataInicial' ] ) ) {


  foreach ( $ids_user_api as $id_user ) {

    $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api


    foreach ( $token as $ids ) {

      $accessToken = $ids[ 'access_token' ];


      $sqlNumPedido = "SELECT id_bling, data FROM tb_pedidos_venda WHERE id_conta_bling = " . $ids[ 'id' ] . " AND data >= '" . Date( $_POST[ 'dataInicial' ] ) . "' AND data <= '" . Date( $_POST[ 'dataFinal' ] ) . "'";

      $resNumPeiddo = $sql->select( $sqlNumPedido );

      foreach ( $resNumPeiddo as $idPedido ) {

        $operadorApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
        $recurso = "pedidos/vendas/" . $idPedido[ "id_bling" ];

        $pedido = $Class->apiGET( $recurso, $operadorApi, $accessToken );

        $pedido = json_decode( $pedido );

        foreach ( $pedido as $lin ) {

          $sqlverifica = "SELECT id_bling FROM tb_pedidos WHERE id_bling=" . $lin->id;

          $verifica = $sql->select( $sqlverifica );

          $values = "";
          $update = "";


          if ( count( $verifica ) == 0 ) {


            $values =
       "'" . $lin->id . "',
        '" . $lin->totalProdutos . "',
        '" . $lin->outrasDespesas . "',
        '" . $lin->desconto->valor . "',
        '" . $lin->categoria->id . "',
        '" . $lin->notaFiscal->id . "',
        '" . $lin->vendedor->id . "',
        '" . $lin->intermediador->cnpj . "',
        '" . $lin->intermediador->nomeUsuario . "',
        '" . $lin->taxas->taxaComissao . "',
        '" . $lin->taxas->custoFrete . "',
        '" . $lin->taxas->valorBase . "'";


            $ins = 'INSERT INTO  tb_pedidos ( id_bling ,  totalProdutos , outrasDespesas ,  desconto_valor ,  categoria_id ,  notaFiscal_id , vendedor_id ,  intermediador_cnpj ,  intermediador_nomeUsuario ,  taxas_taxaComissao ,  taxas_custoFrete ,  taxas_valorBase ) VALUES(' . $values . ')';


            $sql->run( $ins );

            foreach ( $lin->itens as $item ) {

              $itens = "'" . $lin->numero . "',
            '" . $item->id . "',
            '" . $item->codigo . "',
            '" . $item->unidade . "',
            '" . $item->quantidade . "',
            '" . $item->desconto . "',
            '" . $item->valor . "',
            '" . $item->descricao . "',
            '" . $item->produto->id . "'";


              $insItens = 'INSERT INTO tb_itensPedido( numero_pedido, id_bling, codigo, unidade, quantidade, desconto, valor, descricao, produto_id) VALUES (' . $itens . ')';

              $sql->run( $insItens );

            }
            $pedidosListados++;

          } else {

            $update = "UPDATE tb_pedidos SET 
 
 totalProdutos  = '" . $lin->totalProdutos . "',
 outrasDespesas = '" . $lin->outrasDespesas . "',
 desconto_valor = '" . $lin->desconto->valor . "',
 categoria_id   = '" . $lin->categoria->id . "',
 notaFiscal_id  = '" . $lin->notaFiscal->id . "',
 vendedor_id    = '" . $lin->vendedor->id . "',
 intermediador_cnpj        = '" . $lin->intermediador->cnpj . "',
 intermediador_nomeUsuario = '" . $lin->intermediador->nomeUsuario . "',
 taxas_taxaComissao        = '" . $lin->taxas->taxaComissao . "',
 taxas_custoFrete          = '" . $lin->taxas->custoFrete . "',
 taxas_valorBase           = '" . $lin->taxas->valorBase . "' WHERE id_bling = '" . $lin->id . "';";

            $sql->run( $update );
            $pedidosAtualizados++;
          }

        }

      }

    }
      
  }

}
echo "Pedidos listados " . $pedidosListados;
echo "<br>";
echo "Pedidos atualizadoss " . $pedidosAtualizados;


/*
//$sql->run("INSERT INTO tb_Cron (script) VALUES ('refresh_pedidos')");
$timestamp = Date( 'Y-m-d H:i:s' );
$up = "UPDATE tb_Cron SET script = 'refresh_pedidos', time_refresh = '" . $timestamp . "' WHERE id_Cron = 1";
$sql->run( $up );
*/
?>
<h3>Atualizar base de pedidos de vendas</h3>
<form id="form_loing" action="atualiza_pedido.php" method="POST" >
  <table width="300" border="0">
    <tbody>
      <tr>
        <td>Data inicial</td>
        <td><input type="date" name="dataInicial"  value="<?php echo Date('Y-m-d');?>"></td>
      </tr>
      <tr>
        <td>Data Final</td>
        <td><input type="date" name="dataFinal"    value="<?php echo Date('Y-m-d');?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Atualizar"></td>
      </tr>
    </tbody>
  </table>
</form>
