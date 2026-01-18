<?php
session_start();
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

//  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( 'config.php' );

$Class = new classMasterApi();
$sql = new Sql();


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Atualiza produtos fornecedores</title>
</head>
<body>
<div class="header">
  <?php require_once("requires/header.php");  ?>
</div>
<div id="divMenu" class="col-2 menu">
  <?php require_once("requires/painel_api.php");   ?>
</div>
<div id="divContainer" class="col-10">
  <div id="" class="divBlocoGrande">
      
<?php


$Class = new classMasterApi();
$sql = new Sql();

//$ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_app
      
$ids_user_api = $Class->loadUserApi( 1 ); // carrega o id_user_api, pelo id_user_app


foreach ( $ids_user_api as $id_user ) {

  $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

$ttl_Listados = 1;
    
  foreach ( $token as $ids ) {

    $accessToken = $ids[ 'access_token' ];

    $id_forn = "CALL p_sel_id_contato (:FORN, :ID_USER)";
    $param = array(
      ":FORN" => '%forn%',
      ":ID_USER" => $id_user[ 'id' ]
   
    );


    $resp = $sql->select( $id_forn, $param );

    $requisicao = "";

    foreach ( $resp as $idForn ) {

      $fullDados = array();
      $cond = true;
  
      $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
      $recurso = "produtos/fornecedores";
      $nPagina = 1; //numero páginas
      $limite = 100; // linhas por página

      // $filtro      = "idProduto=16107333481&";
      $filtro = "idFornecedor=" . $idForn[ 'id_bling' ];
      //$filtro = "idFornecedor=16615253563";

      //$filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));

        $sleep = 1;
      while ( $cond ) {

        $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

        $dados =  json_decode($Class->apiGET( $requisicao, $operApi, $accessToken ));
          
          usleep(30000);
          
         if ( empty( $dados->data )) {

          $cond = false;
             
         } else{

              $fullDados[ $nPagina ] = $dados;
              $nPagina++;
         }
        
 
      foreach ( $fullDados as $dd ) {

        foreach ( $dd as $lins ) {

          foreach ( $lins as $lin ) {

            if (!empty( $lin->id ) ) {
                
              $instr = "CALL p_cad_prod_forn (
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
                ":ID_CONTA_BLING" => $id_user[ 'id' ],
                ":ID_BLING" => $lin->id,
                ":DESCRICAO" => $lin->descricao,
                ":CODIGO" => $lin->codigo,
                ":PRECOCUSTO" => $lin->precoCusto,
                ":PRECOCOMPRA" => $lin->precoCompra,
                ":PADRAO" => $lin->padrao,
                ":PRODUTO_ID" => $lin->produto->id,
                ":FORNECEDOR_ID" => $lin->fornecedor->id

              );

              $sql->run( $instr, $dados );
              $ttl_Listados++;
                
            }
          }
        }
      }
    }

    echo "Baste Produtos Fornecedor";
    echo "<Br>";
    echo "<Br>";
    echo "Conta " . $id_user[ 'id' ];

    echo "<Br>";
    echo "Total dados castrado/atualizados " . $ttl_Listados;
    echo "<Br>";

  }
}

?>
  </div>
</div>
</body>
</html>