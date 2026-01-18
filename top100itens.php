<?php session_start(); 
if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );

}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css" />
  
<title>Top 100 Produtos</title>
<style></style>
</head>

<body>
   
    
<?php



require_once( "config.php" );

require_once( "menu.php" );

if ( isset( $_GET[ "msg" ] ) ) {

  echo $_GET[ "msg" ];

}


$Class = new classMasterApi();
$sql = new Sql();

    

 $dataMesAtual =    Date('Y-m-1');
 $dataAnoAtual =    Date('Y-1-1');   
 $rankTop      = 10;  


/***************TOP 100 MAIS QUANTIDADE *******************************************************************************************************/   
    
$ids_user_api = $Class->loadUserApi( $_SESSION[ "id_user_app" ] ); // carrega o id_user_api, pelo id_user_app


foreach ( $ids_user_api as $id_user ) { // Laço 1 : RETPETE O CÓDIGO PARA TODAS DAS CONTAS Bling


  $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

echo "<div class='divBloco'>";
    
  foreach ( $token as $ids ) { //  Laço 2 : EXECUTA A CHAMADA A API


  
    $ult_pedidos = $sql->select( "CALL p_rank_prod_qtde (:ID_CONTA_BLING,:DATA,:RANK)", 
                                array( ":ID_CONTA_BLING" => $ids[ 'id' ],
                                       ":DATA"           =>$dataMesAtual,
                                        ":RANK"          =>  $rankTop ));


echo "<span>Top ". $rankTop." itens em volume </span>";
echo "<span style='float:right'>Conta: ". $id_user[ 'nome_conta_bling' ]."</span>" ;
echo "<hr>";
  echo "<table id='tab_dash_vendas'>";

  foreach ( $ult_pedidos as $col) {
      
        echo "<tr>";
   
    foreach($col as $li => $a){
          
         echo "<th style='font-size:12px; text-align:center;'>" .  $li. "</th>";

      }
      
    echo "</tr>";
      break;
}
      
  foreach ( $ult_pedidos as $col) {     
    echo "<tr>";
   
    foreach($col as $li => $a){
          
      if(is_float($a)|| is_double($a) ){
       
      echo "<td style='font-size:12px; text-align:right;'>" .  $a. "</td>";
          
      }else{
       echo "<td style='font-size:12px; text-align:left;'>" .  $a. "</td>";
      
      }
  }
   

    echo "</tr>";

  }

  echo "</table>";
  
  
  }
    
  
echo "</div>";      
  }
    
/***************TOP 100 MAIS VALOR *********************************************************************************************************/    

foreach ( $ids_user_api as $id_user ) { // Laço 1 : RETPETE O CÓDIGO PARA TODAS DAS CONTAS Bling


  $token = $Class->carregaAccessToken( $id_user[ 'id' ] ); // carrega os tokens pelo id_user_api

echo "<div class='divBloco'>";
    
  foreach ( $token as $ids ) { //  Laço 2 : EXECUTA A CHAMADA A API


    $ult_pedidos = $sql->select( "CALL p_rank_prod_valor (:ID_CONTA_BLING,:DATA,:RANK)", 
                                array( ":ID_CONTA_BLING" => $ids[ 'id' ],
                                       ":DATA"           =>$dataMesAtual,
                                        ":RANK"          =>  $rankTop ));

echo "<span>Top ". $rankTop." itens em valor </span>";
echo "<span style='float:right'>Conta: ". $id_user[ 'nome_conta_bling' ]."</span>" ;
echo "<hr>";
  echo "<table id='tab_dash_vendas'>";

  foreach ( $ult_pedidos as $col) {
      
        echo "<tr>";
   
    foreach($col as $li => $a){
          
         echo "<th style='font-size:12px; text-align:center;'>" .  $li. "</th>";

      }
      
    echo "</tr>";
      break;
}
      
  foreach ( $ult_pedidos as $col) {     
    echo "<tr>";
   
    foreach($col as $li => $a){
          
      if(is_float($a)|| is_double($a) ){
       
      echo "<td style='font-size:12px; text-align:right;'>" .  $a. "</td>";
          
      }else{
       echo "<td style='font-size:12px; text-align:left;'>" .  $a. "</td>";
      
      }
  }
   

    echo "</tr>";

  }

  echo "</table>";
  
  
  }
    
  
echo "</div>";      
  }
  
  ?>

    

</body>
</html>