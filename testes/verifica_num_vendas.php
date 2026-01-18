<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sem título</title>
</head>

<body>
<?php
require_once( "../config.php" );

$Class = new classMasterApi();
$sql = new Sql();

$menorPeido  = $sql->select("SELECT id_bling, MIN(numero) as numero FROM tb_vendas WHERE id_conta_bling = 1 LIMIT 1");
$UltimoPeido = $sql->select("SELECT id_bling, MAX(numero) as numero FROM tb_vendas WHERE id_conta_bling = 1 LIMIT 1");

//$menorPeido  = $sql->select("SELECT id_bling, MIN(numero) as numero FROM tb_pedidos WHERE id_conta_bling = 1 LIMIT 1");
//$UltimoPeido = $sql->select("SELECT id_bling, MAX(numero) as numero FROM tb_pedidos WHERE id_conta_bling = 1 LIMIT 1");


    echo "Menor na base: ". $menor = $menorPeido[0]['numero'];
    
    echo "<br>";
    
    echo "Ultimo na base: ". $ultimo = $UltimoPeido[0]['numero'];
    
    echo "<br>";
    
    
   function pegaIdPeido($numero){
        
       $sql = new Sql();
       
        $idPedido = $sql->select("SELECT id_bling as 'numero' FROM tb_vendas WHERE id_conta_bling = 1 and numero = ".$numero);
        
       if(count($idPedido)> 0){
       
        return $idPedido[0]['numero'];
       }else{
           
           return 0;
       }
       
    }
    
    
    
for ( $i = $menor; $i < $ultimo; $i++ ) {
    
$verif_cron = $sql->select( "SELECT numero FROM tb_vendas WHERE id_conta_bling  = 1 AND numero = ".$i);
    
//$verif_cron = $sql->select( "SELECT numero FROM tb_pedidos WHERE id_conta_bling = 1 AND numero = ".$i);

    if(count($verif_cron) > 0) {
          
  }else{
        
    echo "Pedido não encontrado ".$i." : " .pegaIdPeido($i)."<BR>";   
        
    }
    
   
    }
    
  ?>
</body>
</html>