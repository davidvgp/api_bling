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


$sel_agrupador = $sql->select( "SELECT * FROM `tb_canais_de_venda_agrupador` WHERE 1" );


echo "<br>";
 $cont_ins = 0;

 $cont_upd = 0;

$contaSegundo = 0;

foreach ( $sel_agrupador as $agrupador ) {


  $requisicao = $recurso . "?" . $filtro . $agrupador[ 'agrupador' ];

    $dados = $Class->apiGET( $requisicao, $operApi, $myToken );

    $dados = json_decode( $dados );
 
  // print_r($dados);
  // echo "<br>";
    
    foreach ( $dados as $col ) {

      foreach ( $col as $lin ) {
          
        $cad_cdvt = "call p_cad_canal_venda_tipos (
        :PID_CONTA_BLING,
        :PNOME,
        :PTIPO,
        :PAGRUPADOR)" ;  
          
        $dados_cdvt = array (
            "PID_CONTA_BLING" => 0,
            "PNOME" =>  $lin->nome,
            "PTIPO" =>  $lin->tipo,
            "PAGRUPADOR" => $lin->agrupador
        );
        
            $sql->run($cad_cdvt, $dados_cdvt);
       

      }
        
    }
           $contaSegundo++;
        if ($contaSegundo == 3){ sleep(1); $contaSegundo = 0; }  
    
  }

echo "Atualizanbo base Tipos Canais de Venda";
echo "<br>";
echo "Total cadastrado: " . $cont_ins;
echo "<br>";
echo "Total atualizado: " . $cont_upd;
echo "<br>";

?>