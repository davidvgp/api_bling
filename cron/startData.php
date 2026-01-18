<?php

require_once( '../config.php' );

$Class = new classMasterApi();
$sql      = new Sql();

$inicio_data = $sql->select("SELECT MIN(dataVenda) as inicio_data FROM tb_vendas LIMIT 1");


echo $dataBusca = $inicio_data[0]["inicio_data"];

echo "<br>";

echo $dataLimite = Date('Y-01-01', strtotime(-intval(2) .'year'));
echo "<br>";

echo $dataBusca2  = Date('Y-m-d', strtotime(-5 . 'days', strtotime($dataBusca)));
echo "<hr>";

echo $dataBusca  = Date('Y-m-d', strtotime($dataBusca));
echo "<hr>";


$check = true;
$i = 1;


/*
while($check){

  echo "<br>";
  echo $dataBusca2 =  Date('Y-m-d', strtotime(-$i.'days', strtotime($dataBusca))); 
  
    $i++;
    
    if($dataLimite == $dataBusca2) {$check = false;}
    
}
*/

?>