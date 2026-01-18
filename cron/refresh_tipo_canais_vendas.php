<div class="divMiniBloco widht30">

<?php

require_once( '../config.php' );


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


$res_agrupador = $sql->select( "SELECT agrupador, nome FROM tb_canais_de_venda_agrupador" );


echo "<br>";
 $cont_ins = 0;
 $cont_upd = 0;
foreach ( $res_agrupador as $agrupador ) {


  $requisicao = $recurso . "?" . $filtro . $agrupador[ 'agrupador' ];

    $dados = $Class->apiGET( $requisicao, $operApi, $myToken );

    $dados = json_decode( $dados );
 
   

    foreach ( $dados as $col ) {

      foreach ( $col as $lin ) {
          
       $call = "call p_cad_canal_venda_tipos (
       :NOME,
       :TIPO,
       :AGRUPADOR )
       ";
        $param = array(":NOME" => $lin->nome,
                       ":TIPO" => $lin->tipo,
                       ":AGRUPADOR" => $lin->agrupador);  
          
    $sql->run($call, $param);
          
          $cont_ins++;
          
      }
    }
  }
echo "Atualizanbo base Tipos Canais de Venda";
echo "<br>";
echo "Total cadastrado/atualizados: " . $cont_ins;
echo "<br>";
echo "<br>";

?>
</div>