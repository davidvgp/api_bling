<?php

require_once( 'config.php' );


$Class = new classMasterApi();
$Class->loadAccessToken( 1 );
$myToken = $Class->getAccess_Token();
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


echo "<br>";
 $cont_ins = 0;
 $cont_upd = 0;
foreach ( $res_agrupador as $agrupador ) {


  $requisicao = $recurso . "?" . $filtro . $agrupador[ 'agrupador' ];

    $dados = $Class->apiGET( $requisicao, $operApi, $myToken );

    $dados = json_decode( $dados );
 
   

    foreach ( $dados as $col ) {

      foreach ( $col as $lin ) {
          
       $sel = "SELECT * FROM tb_canais_de_venda_tipos WHERE nome = '" . $lin->nome . "' AND tipo ='" . $lin->tipo . "'";
    
    $sel = $sql->select( $sel );

        if ( count( $sel ) == 0 ) {

    $ins = 'INSERT INTO tb_canais_de_venda_tipos ( `nome`, `tipo`, `agrupador`) VALUES ("'. $lin->nome . '","' . $lin->tipo . '",' . $lin->agrupador.')';
            
          $sql->run($ins);

          $cont_ins++;

        } else {

    $up = 'UPDATE tb_canais_de_venda_tipos SET nome= "'.$lin->nome.'", tipo= "'. $lin->tipo .'" WHERE agrupador ='.$agrupador[ "agrupador"].' AND nome= "'.$lin->nome.'"';    
            
          $sql->run( $up);
          $cont_upd++;
        }


      }
    }
  }
echo "Atualizanbo base Tipos Canais de Venda";
echo "<br>";
echo "Total cadastrado: " . $cont_ins;
echo "<br>";
echo "Total atualizado: " . $cont_upd;
echo "<br>";

?>