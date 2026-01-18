<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

?>
<div id="" class="divMiniBloco width_50p">    
<div class="div_titulos size16"> Podutos</div>    <hr> 
<?php

require_once( 'config.php' );

$Class = new classMasterApi();
$sql = new Sql();

$ids_user_api = $Class->loadUserApi( 1 ); // carrega o id_user_api, pelo id_user_app

$ttl_Listados = 1;


foreach ( $ids_user_api as $id_user ) {

  $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api


  foreach ( $token as $ids ) {

    $accessToken = $ids[ 'access_token' ];
    $idConta = $ids[ 'id' ];


    $requisicao = "";

    $fullDados = array();
    $cond = true;

    $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso = "produtos/lojas";
    $nPagina = 0; //numero páginas
    $limite = 100; // linhas por página

    // $filtro = "idProduto=16107333481&";
    // $filtro = "idFornecedor=" . $idForn[ 'id_bling' ];
    // $filtro = "idFornecedor=16615253563";
    //$filtro  = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));

    while ( $cond ) {

      //$requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

      $requisicao = $recurso . "?pagina=" . $nPagina++ . "&limite=" . $limite;

      $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $accessToken ) );

      usleep(330000);


      if ( isset( $dados->error ) ) {

        print_r( $dados );
        echo "<hr>";

        $cond = false;

      } else {


        foreach ( $dados as $lins ) {

          foreach ( $lins as $lin ) {

            $instr = "CALL p_cad_prod_lojas (
                                                :ID_CONTA,
                                                :ID_BLING,
                                                :CODIGO,
                                                :PRECO,
                                                :PRECOPROMOCIONAL,
                                                :PRODUTO_ID,
                                                :LOJA_ID,
                                                :FORNECEDORLOJA_ID,
                                                :MARCALOJA_ID

                                                )";

            $dadosProdutos = array(
              ":ID_CONTA" => $idConta,
              ":ID_BLING" => $lin->id,
              ":CODIGO" => $lin->codigo,
              ":PRECO" => $lin->preco,
              ":PRECOPROMOCIONAL" => $lin->precoPromocional,
              ":PRODUTO_ID" => $lin->produto->id,
              ":LOJA_ID" => $lin->loja->id,
              ":FORNECEDORLOJA_ID" => $lin->fornecedorLoja->id,
              ":MARCALOJA_ID" => $lin->marcaLoja->id

            );


            $sql->run( $instr, $dadosProdutos );

            $ttl_Listados++;

            foreach ( $lin->categoriasProdutos as $li2 ) {

              $inse2 = "call p_cad_catego_prod_lojas (
              :ID_CONTA,
              :ID_PRODUTO_LOJA,
              :CATEGORIASPRODUTOS_ID )";


              $val = array(
                ":ID_CONTA" => $idConta,
                ":ID_PRODUTO_LOJA" => $lin->produto->id,
                ":CATEGORIASPRODUTOS_ID" => $li2->id
              );

              $sql->run( $inse2, $val );
            }
          }
        }
      }
    }

    echo "Base Produtos Lojas";
    echo "<Br>";
    echo "Conta " . $idConta;
    echo "<Br>";
    echo "Total dados castrado/atualizados " . $ttl_Listados;
    echo "<hr>";

  }
}

?>
