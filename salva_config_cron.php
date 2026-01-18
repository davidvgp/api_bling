<?php
session_start();


if ( empty( $_SESSION[ "idUsuario" ] ) ) {

  header( "location:https://mvjob.com.br/api_bling/login_app.php" );


}

require_once( 'config.php' );

$Class = new classMasterApi();

$sql   = new Sql();

$salva1 = $sql->run( "UPDATE `tb_cron_historico` SET `venda`= '" . $_POST[ 'chv' ] . "' WHERE id_conta = " . $_POST[ 'conta' ] );

$salva1 = $sql->run( "UPDATE `tb_cron_refresh`  SET  `venda`= '" . $_POST[ 'crv' ] . "' WHERE id_conta = " . $_POST[ 'conta' ] );

// header( "location:https://mvjob.com.br/api_bling/index_cron.php" );


    /*
echo "chp" . $_POST[ 'chp' ];
echo "<Br>";
echo "chv" . $_POST[ 'chv' ];
echo "<Br>";
echo "crp" . $_POST[ 'crp' ];
echo "<Br>";
echo "crv" . $_POST[ 'crv' ];
echo "<Br>";
echo "conta" . $_POST[ 'conta' ];
*/
    
echo "<Br>";    
echo "<Br>";    
    
    $contas = $Class->getNomeConta($_POST['conta']); 
    
echo "Configurações salvas." ;  
    
echo "<Br>";
echo "<Br>";
echo "Conta " . $contas[0]['nome_conta_bling'];




?>
