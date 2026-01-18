
<?php

require_once( "config.php" );

$Class = new classMasterApi();
$sql   = new Sql();


  if ( isset( $_POST[ "user" ] ) ) {


$lg = $sql->select( "SELECT * FROM tb_user_app WHERE login=:USER AND senha=:SENHA", array( ":USER" => strip_tags( $_POST[ "user" ]), ":SENHA" => strip_tags( $_POST[ "senha" ]) ) );

    if ( count( $lg ) > 0 ) {

      foreach ( $lg as $user ) {
          
        session_start();
        
        $_SESSION['LAST_ACTIVITY'] = time();

        $_SESSION[ "idUsuario" ] = $user[ "id" ];
        $_SESSION[ "NomeUsuario" ] = $user[ "nome" ];

        echo true;
   
      }

    } else {
        
      session_destroy();
     
      echo false;
        
    }
  }


  ?>
  