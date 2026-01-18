<?php
session_start();

//***************************************************************************************************
//********************** arquivo de refresh pedido de venda ultimo ano.******************************

require_once( '../menu.php' );
require_once( '../config.php' );


$Class = new classMasterApi();
$sql = new Sql();

$ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_app


if ( isset( $_POST[ 'dataInicial' ] ) ) {


  $id_conta_bling = "";

  foreach ( $ids_user_api as $id_user ) { // Laço 1 : RETPETE O CÓDIGO PARA TODAS DAS CONTAS Bling


    $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api


    foreach ( $token as $ids ) { //  Laço 2 : EXECUTA A CHAMADA A API

      $id_conta_bling = $ids[ 'id' ];
      $accessToken = $ids[ 'access_token' ];


      $dataInicial = "dataInicial=" . Date( $_POST[ 'dataInicial' ] ); // Date( '2024-1-1' );
      $dataFinal = "dataFinal=" . Date( $_POST[ 'dataFinal' ] ); //  Date( 'Y-m-d' );

      $filtro = "";
      $cond = true;
      $requisicao = "";
      $fullDados = array();

      $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
      $recurso = "pedidos/vendas";
      $nPagina = 1; //numero páginas
      $limite = 100; // linhas por página     


      while ( $cond ) { //  LAÇO 3 : PERCORRE TODAS AS LINHAS DENTRO DO INTERVÁLO SOLCITADO NA BUSCA


        $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $dataInicial . "&" . $dataFinal;

        $pedidos = json_decode( $Class->apiGET( $requisicao, $operApi, $accessToken ) );

        if ( empty( $pedidos->data ) ) { // verifica retorno vazio, encerra o LAÇO 3

          $cond = false;
        } else {

          $fullDados[ $nPagina ] = $pedidos; // ARMAZENA TODOS OS DADOS RETORNANA EM UM ARRAY, FORMANDO UM ARRAY DE 3 NÍVEIS

          $nPagina++;

        }

      } // FIM LAÇO 3


      $values1 = array();
      $pedAtualizado[ $id_conta_bling ] = 0;
      $pedCarregado[ $id_conta_bling ] = 0;
      $valorTotalPedidos[ $id_conta_bling ] = 0;
      $valorPedidos = 0;
      $i = 0;

      foreach ( $fullDados as $dd ) { // LAÇO 4 : PERCORRE O ARRAY PREENCHIDO NO LAÇO 3


        foreach ( $dd as $lins ) { // LAÇO 5 : PERCORRE O 2º NÍVEL DO ARRAY REENCHIDO NO LAÇO 3
         $i = 0;
        foreach ( $lins as $lin ) { // LAÇO 6 : PERCORRE O 3º NÍVEL DO ARRAY PREENCHIDO NO LAÇO 3


          $valorPedidos += $lin->total;


        //  $values1[$i++] = array(
          $values1 = array(
              
              ":ID_CONTA_BLING" => ( int )$id_conta_bling,
              ":ID_BLING" => ( int )$lin->id,
              ":NUMERO" => $lin->numero,
              ":NUMEROLOJA" => $lin->numeroLoja,
              ":DATA" => $lin->data,
              ":DATASAIDA" => $lin->dataSaida,
              ":DATAPREVISTA" => $lin->dataPrevista,
              ":TOTALPRODUTOS" => $lin->totalProdutos,
              ":TOTAL" => $lin->total,
              ":CONTATO_ID" => $lin->contato->id,
              ":CONTATO_NOME" => addslashes( $lin->contato->nome ),
              ":CONTATO_TIPOPESSOA" => $lin->contato->tipoPessoa,
              ":CONTATO_NUMERODOCUMENTO" => $lin->contato->numeroDocumento,
              ":SITUACAO_ID" => $lin->situacao->id,
              ":SITUACAO_VALOR" => $lin->situacao->valor,
              ":LOJA_ID" => $lin->loja->id );


            $pedCarregado[ $id_conta_bling ] = ++$i;
            $valorTotalPedidos[ $id_conta_bling ] = $valorPedidos;
 //         }

   //       if ( count( $values1 ) > 0 ) {


            $inse = "CALL pedidos (
                                    :ID_CONTA_BLING,
                                    :ID_BLING,
                                    :NUMERO,
                                    :NUMEROLOJA,
                                    :DATA,
                                    :DATASAIDA,
                                    :DATAPREVISTA,
                                    :TOTALPRODUTOS,
                                    :TOTAL,
                                    :CONTATO_ID,
                                    :CONTATO_NOME,
                                    :CONTATO_TIPOPESSOA,
                                    :CONTATO_NUMERODOCUMENTO,
                                    :SITUACAO_ID,
                                    :SITUACAO_VALOR,
                                    :LOJA_ID)";


            $sql->run( $inse, $values1 );


          } //  FIM LAÇO 6 : foreach ( $lins as $lin ) {

        } //  FIM LAÇO 5 : foreach ( $dd as $lins ) 

      } //  FIM LAÇO 4 : foreach ( $fullDados as $dd ) 
        
    } // FIM LAÇO 1 : foreach ( $ids_user_api as $id_user ) 


    echo "<hr>";
    echo "<br>";
    echo "Pedidos Carregados/Atualizados<br>";
    echo "<br>";
    echo "Conta " . $id_conta_bling . " : " . $pedCarregado[ $id_conta_bling ];
    echo "<br>";
    echo "Valor Total: R$ " . number_format( $valorTotalPedidos[ $id_conta_bling ], 2, ',', '.' );
    echo "<br>";
    echo "<br>";

    if ( isset( $_POST[ 'dataInicial' ] ) ) {

      echo "Período: " . $_POST[ 'dataInicial' ] . " a " . $_POST[ 'dataFinal' ];

    }
    echo "<hr>";


  } // FIM LAÇO 7 : foreach ( $token as $ids ) 


} // FIM empyt dataInicial


?>
<h3>Atualizar base de pedidos de vendas</h3>
<form id="form_loing" action="../atualiza_base_vendas.php" method="POST">
  <table width="300" border="0">
    <tbody>
      <tr>
        <td>Data inicial</td>
        <td><input type="date" name="dataInicial" value="<?php echo Date('Y-m-01');?>"></td>
      </tr>
      <tr>
        <td>Data Final</td>
        <td><input type="date" name="dataFinal" value="<?php echo Date('Y-m-d');?>"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Atualizar"></td>
      </tr>
    </tbody>
  </table>
</form>
