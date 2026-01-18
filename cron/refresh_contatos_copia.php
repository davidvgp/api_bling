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
$cont_upd = 0;

foreach ( $dados as $col ) {

  foreach ( $col as $lin ) {

    $values = "('$lin->id','" . $lin->descricao . "')";

    $sel = $sql->select( "SELECT * FROM tb_tipo_contatos WHERE id_tipo_contatos = " . $lin->id);

    if ( count( $sel ) == 0 ) {

      $sql->run( "INSERT INTO tb_tipo_contatos (id_tipo_contatos, descricao_tipo_contatos) VALUES
      (".$lin->id.",'".$lin->descricao."')");

        $cont_ins++;
        
    } else {

      $sql->run( "UPDATE tb_tipo_contatos SET 
      id_tipo_contatos=" . $lin->id . ", 
      descricao_tipo_contatos='" . $lin->descricao . "'
      WHERE id_tipo_contatos=" . $lin->id);
        $cont_upd++;
    }


  }
}

echo "<br>";
echo "Total cadastrado: ".$cont_ins;
echo "<br>";
echo "Total atualizado: ".$cont_upd;
echo "<br>";

?>