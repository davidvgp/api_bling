<?php

require_once('menu.php');
//etapa 2:  recebendo 'uthorization_code'
	
	if(isset($_GET["code"])){

	 $code  = $_GET["code"];
	 $state = $_GET["state"];
      
  //granando o 'uthorization_code' no banco para usar em outras páginas. 
      
  $conn = new PDO("mysql:dbname=david682_mvjob;local=br212.hostgator.com.br:3306", "david682_mvjob", "@M47zed5iir");
      
  $stmt = $conn->prepare("UPDATE tb_token_autorizacao SET code=:cd, state=:stt WHERE id=1");
  
        
  $stmt->bindParam(':cd', $code);
  $stmt->bindParam(':stt', $state);
 
  $stmt->execute();   
  
  

/**** REQUISINTANDO O TOKEM DE ACESSOS*******************************************/
        
	$curl = curl_init();
	
    // Definido a url para requisisção.
    $u = "https://www.bling.com.br/Api/v3/oauth/token";
    
	// parametros 
    $credenc_Client_app = "755963990cb38996319b3960b3ef3f33c410878c:336a32ff33f3859b1fb918480e56b7abc9af8696c479ab4d2e351fc61dab";
	
	$set_header = array ("Content-Type: application/x-www-form-urlencoded",
                         "Accept: 1.0", 
                         "Authorization: Basic ".base64_encode($credenc_Client_app));
    
    $data_code = "grant_type=authorization_code&code=".$code;
    
	/*************************************************************************************************************
	PAGINA DA API BLING
	https://developer.bling.com.br/aplicativos#authorization-code
	**************************************************************************************************************/
	
	//define as opões da requisão
    curl_setopt($curl, CURLOPT_URL           , $u);
	curl_setopt($curl, CURLOPT_POST          , true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS    , $data_code);
    curl_setopt($curl, CURLOPT_HTTPHEADER    , $set_header);

    //envia a requisição e obtém a resposta.
		
	$response = curl_exec($curl);
    
	curl_close($curl);
    
     //transformando o dados do retorno de json para array
   $dadosTokenAcesso = json_decode($response);
          
      /***********PARAMETROS DE RETORNO ************************************************************************
    
            access_token : Token utilizado para requisitar os recursos do usuário.
            expires_in	 : Tempo de expiração do access_token em segundos.
            token_type	 : Tipo do esquema de autenticação (Bearer Authentication).
            scope	     : Lista dos ids dos escopos que o app possui permissão de acesso.
            refresh_token: Token utilizado para requisitar um novo token de acesso, após a expiração do access_token
            
   
            $dadosTokenAcesso->access_token;
            $dadosTokenAcesso->expires_in;
            $dadosTokenAcesso->token_type;
            $dadosTokenAcesso->scope;
            $dadosTokenAcesso->refresh_token;
  ************************************************************************************************************/     

    //SALVANDO O TOKENS NO BANCO DE DADOS.
        
  $stmt2 = $conn->prepare("UPDATE tb_token_acess a SET 
  a.access_token ='$dadosTokenAcesso->access_token',
  a.expires_in   ='$dadosTokenAcesso->expires_in',
  a.token_type   ='$dadosTokenAcesso->token_type',
  a.scope        ='$dadosTokenAcesso->scope',
  a.refresh_token='$dadosTokenAcesso->refresh_token' WHERE a.id=1");
          
  
  $stmt2->execute(); 

     echo "<hr>";    
    if($stmt2){ echo "Dados de acesso adiquiridos."; }
               
//header("location:https://mvjob.com.br/api_bling/pg2.php?code=".$code."&state=".$state."&error=".$error."&error_description=".$error_description); 
     
   echo "<hr>";
        
        
}
        
?>
