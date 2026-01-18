<?php
session_start();
//etapa 2:  recebendo 'uthorization_code'

if ( isset( $_GET[ "code" ] ) ) {
  require_once( "config.php" );
  require_once( "class/classMasterApi.php" );

  //Recebendo os code de autorização para requisar os token de acesso    
  $code = $_GET[ "code" ];
  $state = $_GET[ "state" ];
  $idconta = $_GET[ "idconta" ];
  $msg = "";


  $Class = new classMasterApi();
  $sql = new Sql();     

  if ( isset( $_SESSION[ "id_user_app" ] ) ) {

    $ids_user = $_SESSION[ "id_user_app" ];
  
  }
    
   // Atualizando no banco com o código de autorização.

      $Class->SalvaCodeAutorizacao($idconta, $code, $state);


      //**** REQUISINTANDO O TOKEM DE ACESSOS*******************************************

      $curl = curl_init();

      // Definido a url para requisisção.
      $u = "https://www.bling.com.br/Api/v3/oauth/token";

      $Class->get_ClienteId_Secret( $idconta );

      $sel = "SELECT * FROM tb_user_api WHERE id = ".$idconta;
    
  $res = $sql->select($sel);
    
    foreach($res as $col){       
    
    
      $credenciais =  $col['cliente_id'] . ":" . $col['cliente_secret'];

      $set_header = array( "Content-Type: application/x-www-form-urlencoded","Accept: 1.0", "Authorization: Basic " . base64_encode( $credenciais ) );

      $data_code = "grant_type=authorization_code&code=".$code;


      curl_setopt( $curl, CURLOPT_URL, $u );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data_code );
      curl_setopt( $curl, CURLOPT_HTTPHEADER, $set_header );

      //envia a requisição e obtém a resposta com os tokens de acesso a api

      $response = curl_exec( $curl );

      curl_close( $curl );

      //transformando o dados do retorno de json para array

      $dados = json_decode( $response );


      //SALVANDO O TOKENS NO BANCO DE DADOS.
      if ( isset( $dados->error ) ) {

       print_r( $dados );
          
        

      } else {

      //  print_r( $dados );

          $stmt = $Class->upAccessToken( $idconta,
                                      $dados->access_token,
                                      $dados->expires_in,
                                      $dados->token_type,
                                      $dados->scope,
                                      $dados->refresh_token
                                     
                                    );


        if ( $stmt ) {
            
          echo $msg = "<br>Tokens liberados!<br>";
        
        } else {
            
          echo $msg = "<br>Erro ao requisatar token";
        }

      }
    }
 

}
?>
