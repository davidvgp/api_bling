<?php


//etapa 2:  recebendo 'uthorization_code'
	
	if(isset($_GET["code"])){

    require_once("config.php");
     require_once( "class/classMasterApi.php" );
	 
    //Recebendo os code de autorização para requisar os token de acesso    
    $code  = $_GET["code"];
	$state = $_GET["state"];
	$conta =  $_GET["conta"];
        
          
    $cma = new classMasterApi();
        
    // Atualizando no banco o código de autorização.
        
     $cma->upCodeAutorizacao($code, $state);
            
    //**** REQUISINTANDO O TOKEM DE ACESSOS*******************************************

    $curl = curl_init();

    // Definido a url para requisisção.
    $u = "https://www.bling.com.br/Api/v3/oauth/token";
        
        
    $sql = new Sql();
        
    $sell = $sql->select("SELECT id FROM tb_token_autorizacao WHERE state =:st", array( ":st"=>$state));        
        
    foreach($sell as $id_at){
        
     $cma->set_Id($id_at["id"]);
        
    }    

    $cma->get_ClienteId_Secret( $cma->get_Id() );

    // parametros 
    $Client_Id = $cma->getClient_Id();
    $Client_Secre = $cma->getClient_Secret();
    $credenc_Client_app = $Client_Id . ":" . $Client_Secre;
        
	$set_header = array ("Content-Type: application/x-www-form-urlencoded",
                         "Accept: 1.0", 
                         "Authorization: Basic ".base64_encode($credenc_Client_app));
    
    $data_code = "grant_type=authorization_code&code=".$code;
    
	//define as opões da requisão
    curl_setopt($curl, CURLOPT_URL           , $u);
	curl_setopt($curl, CURLOPT_POST          , true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS    , $data_code);
    curl_setopt($curl, CURLOPT_HTTPHEADER    , $set_header);

    //envia a requisição e obtém a resposta com os tokens de acesso a api
		
	$response = curl_exec($curl);
    
	curl_close($curl);
    
     //transformando o dados do retorno de json para array
   $dados = json_decode($response);
  
 if ( isset( $dados->error ) ) {
      
     print_r($dados);
                  
 }else{  

  $stmt = $cma->upAccessToken(  $dados->access_token, 
                                $dados->expires_in, 
                                $dados->token_type, 
                                $dados->scope,
                                $dados->refresh_token,
                                $cma->get_Id());
 }
        
  
  if($stmt){  $msg = "Tokens liberados!<br>";   } else {$msg = "Erro ao requisatar token";}

               
header("location:https://mvjob.com.br/api_bling/index.php?msg=".$msg); 
     
require_once('menu.php');
        
        
}
        
?>
