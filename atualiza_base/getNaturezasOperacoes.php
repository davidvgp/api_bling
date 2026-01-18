<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Naturezas de Operaçãoes</title>
</head>

<body>
<?php
    
session_start();
    
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

require_once( "../config.php" );

    
$sql = new Sql();

$Class = new classMasterApi();

$idsContas = $Class->getContas( $_SESSION[ "idUsuario" ] ); // carrega o id_user_api, pelo id_user_app


foreach ( $idsContas as $conta ) {
  
    
 $cont_dados = 0;

$token = $Class->carregaAccessToken( $conta['id'] ); // carrega os tokens pelo id_user_api

       

//FAZENDO REQUISIAÇÃO API DO BLING

// $recurso = "situacoes/modulos/98310";  
$recurso = "naturezas-operacoes?pagina=1&limite=100&situacao=1";
$operApi = "GET";
$requisicao = $recurso;

$resp = $Class->apiGET( $requisicao, $operApi, $token );

$dados = json_decode( $resp );

      print_r( $dados );
      echo "<hr>";
    
if ( isset( $dados->error ) ) {

    echo "erro na requisção " . $requisicao . $operApi . $token;

     echo "data<Br>";
     print_r( $dados );
     echo "<hr>";


} else {


    foreach ( $dados as $col ) {

        foreach ( $col as $lin ) {


            $call = "call p_cad_naturezas_operacoes  (  :ID_CONTA,
                                                        :ID_BLING,
                                                        :SITUACAO,
                                                        :PADRAO,
                                                        :DESCRICAO


    )";
            $paran = array(
                            ":ID_CONTA" => $lin->$conta['id'],
                            ":ID_BLING" => $lin->id,
                            ":SITUACAO" => $lin->situacao,
                            ":PADRAO" => $lin->padrao,
                            ":DESCRICAO" => $lin->descricao


            );


            $sql->run( $call, $param );


        }

    }
    echo $msg1 = "Base Naturezas Operações cadastrada/atualizada";
    echo "<br>";
    echo "<hr>";
} // fim if($dados-error)

}

?>
</body>
</html>