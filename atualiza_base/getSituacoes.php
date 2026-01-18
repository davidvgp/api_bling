<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Situações / Modulos </title>
    
</head>

<body>
<?php


require_once( "../config.php" );
    
    
$sql = new Sql();    

//instanciando a class de conexão com o BD o select no banco para pegar os token

$cma = new classMasterApi();

$cma->loadAccessToken( 1 );

 $myToken = $cma->getAccess_Token();


//FAZENDO REQUISIAÇÃO API DO BLING

// $recurso = "situacoes/modulos/98310";  
$recurso    = "situacoes/modulos";
$operApi    = "GET";
$requisicao = $recurso;

$resp = $cma->apiGET( $requisicao, $operApi, $myToken );

  $dados = json_decode( $resp );

if ( isset( $dados->error ) ) {
    
  echo "erro na requisção ". $requisicao . $operApi.  $myToken ;
    

} else {


  foreach ( $dados as $col ) {

    foreach ( $col as $lin ) {

        
    $cad_modulos = "call p_cad_modulos  (
    :ID_BLING,
    :NOME,
    :DESCRICAO

    )";  
    $val_modulos = array(
        ":ID_BLING" => $lin->id,
        ":NOME" => $lin->nome,
        ":DESCRICAO" => $lin->descricao

        );
        
        
     $sql->run($cad_modulos, $val_modulos);   
        
 
        
    }

  }
 echo  $msg1 = "Base modulos cadastrada/atualizada";
    echo "<br>";
    echo "<hr>";
} // fim if($dados-error)



$modulos = $sql->select( "SELECT * FROM tb_modulos" );


foreach ( $modulos as $row ) {

  $id_modulo = $row[ "id_bling" ];

  $recurso = "situacoes/modulos/" . $id_modulo;
  $operApi = "GET";
  $requisicao = $recurso;

  $resp = $cma->apiGET( $requisicao, $operApi, $myToken );

  $dados = json_decode( $resp );

  $values = "";

  if ( isset( $dados->error ) ) {
    
      print_r( $dados );
      
  } else {

    foreach ( $dados as $col ) {

      foreach ( $col as $lin2 ) {
          
          
          $cad_situacao_modulos = "call p_cad_situacao_modulos (
            :ID_BLING,
            :NOME,
            :IDHERDADO,
            :COR,
            :ID_MODULO

          )";
          
          
          $val_situacao_modulos = array(
                ":ID_BLING"  => $lin2->id,
                ":NOME"      => $lin2->nome,
                ":IDHERDADO" => $lin2->idHerdado,
                ":COR"       => $lin2->cor,
                ":ID_MODULO" => $id_modulo
            );
          
          $sql->run($cad_situacao_modulos, $val_situacao_modulos);  
    
      }


    }

    echo  $msg2 = "Base de tipos situacoes modulos cadastrada/atualizada";
    echo "<br>"; 
   
  }
     
  }



?>
</body>
</html>