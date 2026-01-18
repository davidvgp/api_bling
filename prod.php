<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Teste Get Api Produtos</title>
</head>
        
<body>
    
    
<?php
    require_once('menu.php');
    require_once('config.php');
    
    
$Class = new classMasterApi(); 
$Class->loadAccessToken(1);
$myToken = $Class->getAcessToken();

    
/**************************************************************
    [id] => 1
    [access_token] => 21e6d9f086a848cd13f4e4c6b13860d49dd54b85
    [expires_in] => 21600
    [token_type] => Bearer
    [scope] => 98309 98313 98314 107041 575904 5990556 106168710 199272829 318257550 318257570 318257576 318257583 333936575 363921599 1780272711 1869535257
    [refresh_token] => 85531fb3bcfac3e4bf7b17fd4c18eae1da65faa5
    [timestamp] => 2024-06-29 21:15:09.196217

**************************************************************/
    
    
echo "<hr>";

  //FAZENDO A PRIMEIRA REQUISIAÇÃO A API DO BLING
    
    $operApi    = "GET"; //tipo de operação GET, POST, DELETE, PATCH ou PUT
    $recurso    = "produtos/fornecedores";  
    $nPagina    = 1; //numero páginas
    $limite     = 100; // linhas por página
    
    // $filtro      = "idProduto=16107333481&";
    $filtro     = "idFornecedor=12260862944";
  //$filtro = http_build_query(array('idProduto'=>'16088957453','idFornecedor'=>'12260862944'));
   
  $fullDados  = array();
  $cond       = true;
  $requisicao = "";
    
    while($cond){
       
      $requisicao = $recurso."?pagina=".$nPagina."&limite=".$limite ."&".$filtro;
       
      $dados = $Class->apiGET($requisicao, $operApi, $myToken);
     
       $fullDados[$nPagina] = json_decode($dados);
        
       $nPagina++;
      
        if(strlen($dados) <= 11) { $cond = false;}
       
        }
       
        
    echo "<hr>";
    
   
    $linhas = 1;
    $valorTotalPedidos = 0;
    
    echo "<table border='0'>";
    echo " <tr>
      <td>id Bling</td>
      <td>Produto</td>
      <td>Ref.</td>
      <td>Ref.</td>
      <td>Preço Venda</td>
      <td>Custo</td>
    </tr> 
    ";
    foreach($fullDados as $dd){
        
     foreach($dd as $lins){    
        foreach ($lins as $obj){ 
        echo "<tr>";  
     //   echo "<td>";
    //    print_r($obj);
    //    echo "</td>";
    
        echo "<td>".$obj->id.         "</td>";
        echo "<td>".$obj->descricao.  "</td>";
        echo "<td>".$obj->codigo.     "</td>";
        echo "<td>".$obj->precoCusto. "</td>";
        echo "<td>".$obj->precoCompra."</td>";
        echo "<td>".$obj->fornecedor->id."</td>";
    
        //echo $obj.": ". $lin."<br>";  
       echo "</tr>";
        }
               
    }
}
    echo "</table>";
?>


</body>
</html>