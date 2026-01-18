<div class="divMiniBloco width_50p">   


<?php
session_start();
//***************************************************************************************************
//********************** arquivo de refresh CANAIS DE VENDA******************************************
require_once( "session.php" );
require_once( 'config.php' );
require_once( "class/classMasterApi.php" );

$sql = new Sql();

$Class = new classMasterApi();


 $dados = $Class->getIdContaToken();
      
//      print_r($dados);
      
foreach($dados as $idConta => $token){

  $cont_dados = 0;


  $limite = 100; // linhas por página

    $operApi = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso = "naturezas-operacoes";
    $situacao = 1; // 0 inativo; 1 ativo

    $fullDados = array();
    $nomeGrupo = array();

    $requisicao = "";


    echo "<br>";

        
      $nPagina = 1; //numero páginas
      $cond = true;

     while ( $cond ) {

    $requisicao = $recurso . "?pagina=" . $nPagina . "&limite=" . $limite . "&" . $situacao;

        usleep( 333334 );

        $dados = $Class->apiGET( $requisicao, $operApi, $token );

        $dados = json_decode( $dados );


        if ( !empty( $dados->data ) ) {
        //  echo "data<Br>";
        //  print_r( $dados );
        //  echo "<hr>";

          $fullDados[ $nPagina ] = $dados;
          $nPagina++;

        } else {

          if ( !empty( $dados->error ) ) {

            echo "Error<Br>";
            print_r( $dados );
            echo "<hr>";

            $cond = false;
          }
            
          $cond = false;
        }
      }
   
    $call = "call p_cad_naturezas_operacoes  ( :ID_CONTA,:ID_BLING, :SITUACAO, :PADRAO, :DESCRICAO )";
      
    foreach ( $fullDados as $dd ) {
      foreach ( $dd as $col ) {
        foreach ( $col as $lin ) {
 
          $value = array(   ":ID_CONTA"  => $idConta,
                            ":ID_BLING"  => $lin->id,
                            ":SITUACAO"  => $lin->situacao,
                            ":PADRAO"    => $lin->padrao,
                            ":DESCRICAO" => $lin->descricao
                        );

          $sql->run( $call ,$value );
          $cont_dados++;

        }
      }
    }
    echo "<br>";
    echo "Conta " . $Class->getConta(  $idConta );
    echo "<br>";
    echo "Natureza de Operação cadastrado/atualziados: " . $cont_dados;
    echo "<br>";
    echo "<hr>";


  }

?>
</div>