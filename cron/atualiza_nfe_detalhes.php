<?php
session_start();
require_once("../session.php"); 

require_once( '../config.php' );

$Class = new classMasterApi();
$sql   = new Sql();
$conta = $Class->getContas(1);

$IdContaToken = $Class->getIdContaToken();

?>

  <div id="" class="divBloco">
      <div class="div_titulos">NFs detalhes</div>      
    <?php

foreach($IdContaToken as $idConta => $token){
    
 echo $Class->getDetalhesNfes( $idConta, $token);
  
    }

    ?>
  </div>
</div>
