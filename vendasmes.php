<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Vendas Mês</title>
</head>
        
<body>
        
<?php
  require_once('menu.php');
  require_once("config.php");
     
 $Class = new classMasterApi(); 
    
 $Class->loadAccessToken(1);
    
$myToken = $Class->getAccess_Token();

echo "<hr>";

  //FAZENDO A PRIMEIRA REQUISIAÇÃO A API DO BLING
    
    $operApi    = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso    = "pedidos/vendas";  
    $nPagina    = 1; //numero páginas
    $limite     = 100; // linhas por página
    
    //$filtro      = "idProduto=16107333481&";
    //$filtro     .= "idFornecedor=12260862944";
    
    $dataInicial  = "dataInicial=".Date('Y-m-d', strtotime(-intval(Date('d')-1) .'days'));
    $dataFinal    = "dataFinal="  .Date('Y-m-d');
    
  $filtro  = http_build_query(array('idsSituacoes=>31','idsSituacoes=>15'));
    
  $fullDados = array();
    
  $cond = true;
    
  $requisicao = "";
     
  while($cond){
       
          $requisicao = 
          $recurso     ."?".
          "pagina=".$nPagina."&".
          "limite=".$limite ."&".
          $dataInicial ."&".
          $dataFinal   ."&".
          $filtro      ;
       
  $pedidos = $Class->apiGET($requisicao, $operApi, $myToken);
  
  $fullDados[$nPagina] = json_decode($pedidos);
        
  $nPagina++;
      
  if(strlen($pedidos) <= 11) { $cond = false;}
       
}
    
                   
   echo "<hr>";
    $linhas = 1;
    $valorTotalPedidos = 0;
    
    foreach($fullDados as $dd){
     
    foreach($dd as $lins){
        
       foreach($lins as $lin){
                   
     /* echo " | Pedido ".$lin->numero.
             " | Data   ".$lin->data. 
             " | valor  ".$lin->total."<br>";    
     */
        $linhas++;
        $valorTotalPedidos += $lin->total;
       }   

    }
    
}
    
    echo "Total pedidos: ". $linhas;
    echo "<br>";
    echo "Valor Total: R$ ". number_format($valorTotalPedidos, 2, ',', '.');
        

   
?>


</body>
</html>