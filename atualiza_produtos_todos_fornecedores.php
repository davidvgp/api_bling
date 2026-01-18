<?php
require_once( "session.php" );
require_once( "config.php" );

$Class = new classMasterApi();

$dados = $Class->getIdContaToken();

$idConta = $_POST[ 'conta' ];
$token = $dados[$idConta];

$resp = $sql->select( "CALL p_sel_id_contato (:FORN, :ID_USER)", array( ":FORN" => '%forn%', ":ID_USER" => $idConta ));

$requisicao = "";
$count = 0;

foreach ( $resp as $idForn ) {
    
$fullDados = array();
  $cond = true;

  $operApi = "GET";
  $recurso = "produtos/fornecedores";
  $nPagina = 1;
  $limite = 100; // linhas por página

  // $filtro      = "idProduto=16107333481&";
  $filtro = "idFornecedor=" . $idForn[ 'id_bling' ];
  //$filtro = "idFornecedor=16615253563";

  //$filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));


  while ( $cond ) {

    $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

    usleep( 400000 );
    $dados = json_decode( $Class->apiGET( $requisicao, $operApi, $token ) );


    if ( !empty( $dados->error ) ) {

      echo "<br>";
      print_r( $dados );
      $cond = false;

    } else {

      if ( empty( $dados->data ) ) {

        $cond = false;

      } else {

        $fullDados[ $nPagina ] = $dados;
        $nPagina++;
      }
    }
  }


  foreach ( $fullDados as $dd ) {

    foreach ( $dd as $lins ) {

      foreach ( $lins as $lin ) {

        if ( !empty( $lin->id ) ) {

          $call = "CALL p_cad_prod_forn (
                                            :ID_CONTA_BLING,
                                            :ID_BLING,
                                            :DESCRICAO,
                                            :CODIGO,
                                            :PRECOCUSTO,
                                            :PRECOCOMPRA,
                                            :PADRAO,
                                            :PRODUTO_ID,
                                            :FORNECEDOR_ID
                                        )";

          $dados = array(
            ":ID_CONTA_BLING" => $idConta,
            ":ID_BLING" => $lin->id,
            ":DESCRICAO" => $lin->descricao,
            ":CODIGO" => $lin->codigo,
            ":PRECOCUSTO" => $lin->precoCusto,
            ":PRECOCOMPRA" => $lin->precoCompra,
            ":PADRAO" => $lin->padrao,
            ":PRODUTO_ID" => $lin->produto->id,
            ":FORNECEDOR_ID" => $lin->fornecedor->id

          );

          $res = $sql->select( $call, $dados );

            $count++;
        }
      }
    }
  }
}

echo "Baste Produtos Fornecedor";
echo "<Br>";
echo "<Br>";
echo "Conta " . $idConta;
echo "<Br>";
echo "<Br>";
echo "Produtos atualizados/cadastrados:: " . $count;
echo "<Br>";




?>
