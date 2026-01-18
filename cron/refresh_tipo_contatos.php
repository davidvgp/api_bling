<?php

require_once( '../config.php' );


$Class = new classMasterApi();
$Class->loadAccessToken( 1 );
$myToken = $Class->getAccess_Token();
$sql = new Sql();

$operApi    = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
$recurso = "contatos/tipos";
//$recurso = "contatos"; 

$nPagina = 1; //numero páginas
$limite =  100; // linhas por página

// $filtro  = "idProduto=16107333481&";
// $filtro .= "idFornecedor=12260862944&";
// $filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));
$filtro = "";

$fullDados = array();
$cond = true;
$requisicao = "";

 
   $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;
  
    $dados = $Class->apiGET( $requisicao, $operApi, $myToken );
    
    $dados = json_decode($dados);   
   
$cont_ins = 0;

foreach ( $dados as $col ) {

  foreach ( $col as $lin ) {

    $value = array(
    ":ID_TIPO_CONTATOS" => $lin->id,
    ":DESCRICAO_TIPO_CONTATOS" => $lin->descricao,
    ":STATUS_APP" => $lin->status_app,
    ":ID_CONTA_BLING" => $lin->id_conta_bling
    );

      $ins = "CALL p_tipo_contatos (
        :ID_TIPO_CONTATOS,
        :DESCRICAO_TIPO_CONTATOS,
        :STATUS_APP,
        :ID_CONTA_BLING

      
      )";
      
      $sql->run($ins, $value);
   $cont_ins++ ;
  }
}

echo "<br>";
echo "Basete tipo de contatos atualziada";
echo "<br>";
echo "<br>";
echo "Total dados cadastrados/atualizados: ".$cont_ins;

echo "<br>";

?>