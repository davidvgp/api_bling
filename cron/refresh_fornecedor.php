<?php

require_once( '../config.php' );


$Class = new classMasterApi();
$Class->loadAccessToken( 1 );
$myToken = $Class->getAccess_Token();
$sql = new Sql();

$operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
$recurso = "contatos";
//$recurso = "contatos"; 

$nPagina = 1; //numero páginas
$limite = 100; // linhas por página

// $filtro  = "idProduto=16107333481&";
// $filtro .= "idFornecedor=12260862944&";
// $filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));
$filtro = "idTipoContato=10981889632&criterio=1";

$fullDados = array();
$cond = true;
$requisicao = "";

while ( $cond ) {

  $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $filtro;

  $dados = $Class->apiGET( $requisicao, $operApi, $myToken );

  $fullDados[ $nPagina ] = json_decode( $dados );

  $nPagina++;

  if ( strlen( $dados ) <= 11 ) {
    $cond = false;
  }

}


$cont_ins = 0;
$cont_upd = 0;


foreach ( $fullDados as $dd ) {

  foreach ( $dd as $col ) {

    foreach ( $col as $lin ) {

      $sel = $sql->select( "SELECT * FROM tb_fornecedores WHERE id_bling = '" . $lin->id . "'" );

      if ( count( $sel ) == 0 ) {
          
      
        $ins = 'INSERT INTO tb_fornecedores (id_bling, nome, codigo, situacao, numeroDocumento, telefone, celular) VALUES (
      "' . $lin->id . '",
      "' . $lin->nome . '",
      "' . $lin->codigo . '",
      "' . $lin->situacao . '",
      "' . $lin->numeroDocumento . '",
      "' . $lin->telefone . '",
      "' . $lin->celular . '")';

        $sql->run( $ins );

        $cont_ins++;

      } else {

     $up = 'UPDATE tb_fornecedores SET 
      
      nome             ="' . $lin->nome . '",
      codigo           ="' . $lin->codigo . '",
      situacao         ="' . $lin->situacao . '",
      numeroDocumento  ="' . $lin->numeroDocumento . '",
      telefone         ="' . $lin->telefone . '",
      celular          ="' . $lin->celular . '"
      WHERE id_bling   ="' . $lin->id.'"';
   
      $sql->run($up);
          
          $cont_upd++;
        }

      }
    }

  }

  echo "<br>";
  echo "Total cadastrado: " . $cont_ins;
  echo "<br>";
  echo "Total atualizado: " . $cont_upd;
  echo "<br>";
  ?>