<?php session_start(); 

session_start();

require_once( "config.php" );


$Class = new classMasterApi();
$sql = new Sql();

$operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
$recurso = "canais-venda/tipos";
//$recurso = "contatos"; 

$nPagina = ""; // 1; //numero páginas
$limite = ""; // 100; linhas por página

$filtro = "tipos=";
// $filtro .= "idFornecedor=12260862944&";
// $filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));

$fullDados = array();
$cond = true;
$requisicao = "";


$res_agrupador = $sql->select( "SELECT agrupador, nome FROM `tb_canais_de_venda_agrupador`" );


$ids_user_api = $Class->loadUserApi( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app

foreach ( $ids_user_api as $id_user ) { // Laço 1 : RETPETE O CÓDIGO PARA TODAS DAS CONTAS Bling

  echo "<div class='divBlocoGrande'>";

  $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

    
  foreach ( $token as $ids ) { //  Laço 2 : EXECUTA A CHAMADA A API

    $accessToken = $ids[ 'access_token' ];   

    echo "<br>";
    $cont_ins = 0;
    $cont_upd = 0;
    foreach ( $res_agrupador as $agrupador ) {


      $requisicao = $recurso . "?" . $filtro . $agrupador[ 'agrupador' ];

      $dados = $Class->apiGET( $requisicao, $operApi, $accessToken );

  //   print_r($dados);
        
        $dados = json_decode( $dados );


      foreach ( $dados as $col ) {

        foreach ( $col as $lin ) {

          $inse = "call p_cad_canal_venda_tipos (
       :ID_CONTA_BLING,
       :NOME,
       :TIPO,
       :AGRUPADOR )
       ";
          $param = array(
            ":ID_CONTA_BLING" => $ids[ 'id' ],
            ":NOME" => $lin->nome,
            ":TIPO" => $lin->tipo,
            ":AGRUPADOR" => $lin->agrupador );

          $sql->select( $inse, $param );

          $cont_ins++;

        }
      }
    }

echo "Atualizanbo base Tipos Canais de Venda";
echo "<br>";
echo "Conta: ".$id_user[ 'nome_conta_bling' ];      
echo "<br>";
echo "<br>";
echo "Total cadastrado/atualizados: " . $cont_ins;
echo "<br>";
echo "<br>";
  }
}
?>