<?php

require_once( "../config.php" );


$Class = new classMasterApi();


$sql = new Sql();

$contas = $Class->getContas(1);  // 1 nesse caso é o id_user_app



    foreach ( $contas as $conta ) { // PERCORRE OS DADOS DAS CONTAS API /  TB_USER_API

        echo "<hr>";
        echo "id conta " . $conta[ "id" ];
        echo "<Br>";
        echo "id CNPJ " . $conta[ "cnpj" ];
        echo "<br>";

        $dados = $Class->loadRefreshToken( $conta[ "id" ] ); // carrega os refresh tokem pelo $idconta


       $credenciais = $Class->getCliente_Id() . ":" . $Class->getCliente_Secret();

        $set_header = array( "Content-Type: application/x-www-form-urlencoded", "Accept: 1.0", "Authorization: Basic " . base64_encode( $credenciais ) );

   echo     $data_code = "grant_type=refresh_token&refresh_token=" . $Class->getRefresh_token();


        $curl = curl_init();
        $u = "https://www.bling.com.br/Api/v3/oauth/token";

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


        if ( isset( $dados->error ) ) {

 echo "<br>";
            print_r( $dados );
           

        } else {

            $stmt = $Class->upAccessToken(
                $conta[ "id" ],
                $dados->access_token,
                $dados->expires_in,
                $dados->token_type,
                $dados->scope,
                $dados->refresh_token
               
            );


            if ( $stmt ) {
                echo $msg = "<br>Tokens atualizado!<br>";
            } else {
                echo $msg = "<br>Erro ao requisatar token";
            }
            echo "<hr>";
        }
    }
    
    //header("location:https://mvjob.com.br/api_bling/index.php?msg=".$msg); 


?>
