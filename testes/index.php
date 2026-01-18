<!doctype html>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Testes</title>
</head>

<body>
<?php
require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$menu = array(

  array( "url" => "index.php",
    "target" => "_parent",
    "link" => "Início" ),

  array( "url" => "ultimos_pedidos.php",
    "target" => "_parent",
    "link" => "Vendas recentes" ),

  array( "url" => "rank_produtos.php",
    "target" => "_parent",
    "link" => "Produtos mais vendidos" ),

  array( "url" => "rank_fornecedores.php",
    "target" => "_parent",
    "link" => "Vendas por fornecedores" ),

  array( "url" => "rank_lojas.php",
    "target" => "_parent",
    "link" => "Vendas por lojas" ),

  array( "url" => "custo_prod_forn.php",
    "target" => "_parent",
    "link" => "Fornecedor produtos" ),

  array( "url" => "prod_estoq_custo.php",
    "target" => "_parent",
    "link" => "Consulta Produtos" ),

  array( "url" => "analise_sugestao_compra.php",
    "target" => "_parent",
    "link" => "Sugestão de compras" ),
    
    array( "url" => "index_painel.php",
    "target" => "_blank",
    "link" => "Painiel API" ),


);
    
    
  echo "<ul>";
foreach ( $menu as $link ) {

    echo '<li><a href="https://mvjob.com.br/api_bling/'.$link["url"].'" target="'.$link["target"].'">'.$link["link"].'</a></li>';
 }  
 echo "</ul>";

    
    
  echo"<hr>";  
  echo"<hr>"; 
    
  $sel = $sql->select("SELECT id_bling as id, descricao as nome FROM tb_canais_de_venda WHERE id_conta_bling = 1 ORDER BY nome ASC" );

    print_r( $sel);
    
 echo"<hr>"; 
    
    foreach ( $sel as $dados ) {

   

        echo "option value='" . $dados['id'] . "'" . $dados['nome'] . "";
        
    }
    
    
 echo"<hr>";  
  
    
    
    
$itens =array(array("item"=>1, "qtde"=>2, "valor"=>25),array("item"=>1, "qtde"=>1, "valor"=>250));
    
    echo "Frete: ". $frete = 34;
    echo "<br>"; 
    echo "Taxa: ". $taxa = 23;
    echo "<br>"; 
    echo "Aliq Imposto: ". 9.5;
    echo "<br>"; 
    
    $tt = 0;
    $mdp = 0;
   echo count($itens);
    
    echo "<br>";  
    echo "<hr>";  
    
    foreach($itens as $v =>$d){
           
        $tt += $d['
    valor '];
    
    }
    
      echo $tt;
        
        echo "<br>";
    
    foreach($itens as $v =>$d){
        echo $d['
    item '];
        echo "<br>";  
        echo $d['
    qtde '];
        echo "<br>";  
        echo $d['
    valor '];
        echo "<br>";
        
        echo "mdp: ". $mdp = $d['
    valor '] /  $tt;    
        echo "<br>";
        echo "part frete: ". $mdp * $frete;
        echo "<br>";
        echo "part taxa: ". $mdp * $taxa;
        
    }
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "à 3 dias atraz: ".    $dataMesAtual =  Date('
    d - m - Y ', strtotime( ' - 3 day '));
    echo "<br>";
    echo "à 30 dias atraz: ".   $dataMesAtual =  Date('
    d - m - Y ', strtotime( ' - 30 days '));
    echo "<br>";
      echo "à 3 meses atraz: ". $dataMesAtual =  Date('
    1 - m - Y ', strtotime( -1 .'
    month '));
      echo "<br>";
      echo "Ultimo dia utlimo meses: ". $dataMesAtual =  Date('
    t - m - Y ', strtotime( -1 .'
    month '));
    echo "<br>";
      echo "à 2 anos atraz: ".  $dataMesAtual =  Date('
    1 - 1 - Y ', strtotime( -2  . '
    year '));
    echo "<br>";
    
    echo "mes " . Date("m", strtotime($dataMesAtual.''));
     echo "<br>";
     echo "<br>";
     echo "<br>";
    
    
    
    
    
     echo "<br>";
     echo "<br>";
    
    
$data = array(
     array('
    codigos '  => '
    111111 '),
     array('
    codigos '  => '
    222222 '),
     array('
    codigos '  => '
    322222 '),
     array('
    codigos '  => '
    444444 '));

echo http_build_query($data,"","");
    
    
    
    
    ?>
</body>
</html>