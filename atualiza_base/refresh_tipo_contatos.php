<?php
session_start();
require_once("../session.php"); 

require_once( '../config.php' );

$Class = new classMasterApi();
$sql   = new Sql();
$conta = $Class->getContas( $_SESSION[ "idUsuario" ] );

$IdContaToken = $Class->getIdContaToken();


?>

<div id="" class="divMiniBloco width_50p">    
<div class="div_titulos size16">Tipos de Contatos</div> 
<hr> 


<?php


foreach($IdContaToken as $idConta => $token){
    
    $cont_dadao     = 1;

    //FAZENDO REQUISIAÇÃO API DO BLING


    $fullDados = array();
    $operApi   = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso   = "contatos/tipos?";
    $nPagina   = 1; //numero páginas
    //  $limite = 100; // linhas por página       


    $requisicao = $recurso;

    $returnApi = json_decode( $Class->apiGET( $requisicao, $operApi, $token ) );

   //   print_r($returnApi);
      
    $values = array();
    $cont_ins = 0;  

    foreach ( $returnApi as $col ) {

      foreach ( $col as $lin ) {
          
          if( $lin->descricao == "Cliente" ) { $stt = "I";}else{$stt = "A";}
          
       $values = array( 
            ":ID_TIPO_CONTATOS" => $lin->id,
            ":DESCRICAO"        => $lin->descricao,
            ":STATUS_APP"       => $stt,
            ":ID_CONTA_BLING"   => $idConta 
        );


        $cadsql = "CALL p_tipo_contatos (:ID_TIPO_CONTATOS,:DESCRICAO,:STATUS_APP,:ID_CONTA_BLING)";


         $sql->run( $cadsql, $values ) ;

        $cont_ins++;
          
      } 
     
      echo "<br>";
      echo "Conta " . $Class-> getConta($idConta);
      echo "<Br>";
      echo "<br>";
      echo "Total dados cadastrados/atualizados: ".$cont_ins;
    }
      
      echo "<hr>";
  }

?>
</div>
</div>