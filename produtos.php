<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Produtos</title>
</head>
<body>
    
    
<?php
    require_once('menu.php');
    require_once('config.php');
    
    
$Class = new classMasterApi(); 
$Class->loadAccessToken(1);
$myToken = $Class->getAccess_Token();


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
    
   // while($cond){
       
      $requisicao = $recurso."?pagina=".$nPagina."&limite=".$limite ."&".$filtro;
       
      $dados = $Class->apiGET($requisicao, $operApi, $myToken);
     
    print_r($dados);
    
       $fullDados[$nPagina] = json_decode($dados);
        
       $nPagina++;
      
        if(strlen($dados) <= 11) { $cond = false;}
       
    //    }
       
        
    echo "<hr>";
    
   
    $linhas = 1;
    $valorTotalPedidos = 0;
    
    echo "<table border='0'>";
    echo " <tr>
      <td>id Bling</td>
      <td>Produto</td>
      <td>Ref.</td>
      <td>Preço Venda</td>
      <td>Custo</td>
      <td>ID Fornecedor</td>
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
        echo "<td>".number_format($obj->precoCusto,  2, ',', '.'). "</td>";
        echo "<td>".number_format($obj->precoCompra, 2, ',', '.'). "</td>";
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