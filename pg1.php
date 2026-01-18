<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Pagina Requisição</title>

</head>

<body>
	
	<a href="https://www.bling.com.br/Api/v3/oauth/authorize?response_type=code&client_id=755963990cb38996319b3960b3ef3f33c410878c&state=eddb98c32278bab93b4910bc80e6498e">Requisitar</a>	
<?php
	
	echo "<hr>";
    echo "Error:     ". $_GET["error"];    
    echo "<br>";
    echo "Descrição: ". $_GET["error_description"];
    
	// etapa 1:  solicitando autorização ao servidor
	// uma vez autorizado, volta para URL de direcionamento inserida do cadastro do aplicativo. 
	$url = 'https://www.bling.com.br/Api/v3/oauth/authorize?response_type=code&client_id=755963990cb38996319b3960b3ef3f33c410878c&state=eddb98c32278bab93b4910bc80e6498e';
	
	// etapa 2:  está na págia index.php
	
	// etapa 3:  //recebendo os parametros enviado pela URL de redirecionamento ao realizar a validação de autorização.
		
		
	// etapa 4:  requisitar o 'access_token', utilizando o 'authorization_code', recebido no passo anterior.
	
		
	// Inicia a sessão cURL
	
	$curl = curl_init();
	
    // Definido a url para requisisção.
    $u = "https://www.bling.com.br/Api/v3/oauth/token";
    

	// parametros 
    $credenc_Client_app = "755963990cb38996319b3960b3ef3f33c410878c:336a32ff33f3859b1fb918480e56b7abc9af8696c479ab4d2e351fc61dab";
	
	$set_header = array ("Content-Type: application/x-www-form-urlencoded",
                         "Accept: 1.0", 
                         "Authorization: Basic ".base64_encode($credenc_Client_app));
    
    
   //  $set_header = "Content-Type: application/x-www-form-urlencoded, Accept: 1.0, Authorization: Basic ".$credenc_Client_app;
	
    $data_code = "grant_type=authorization_code&code=".$_GET["code"];
    
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
    
    echo "<hr>";

    //transformando o dados do retorno de json para array
   $dadosTokenAcesso = json_decode($response);
    
    /***********PARAMETROS DE RETORNO ************************************************************************
    
            access_token : Token utilizado para requisitar os recursos do usuário.
            expires_in	 : Tempo de expiração do access_token em segundos.
            token_type	 : Tipo do esquema de autenticação (Bearer Authentication).
            scope	     : Lista dos ids dos escopos que o app possui permissão de acesso.
            refresh_token: Token utilizado para requisitar um novo token de acesso, após a expiração do access_token
            
    ***********************************************************************************************************/
    
    /*
	$dadosTokenAcesso->access_token;
	$dadosTokenAcesso->expires_in;
    $dadosTokenAcesso->token_type;
	$dadosTokenAcesso->scope;
	$dadosTokenAcesso->refresh_token;
  */  
    
    
    
    
    
    
    //FAZENDO A PRIMEIRA REQUISIAÇÃO A API DO BLING
    
    $url_api_blig = "https://www.bling.com.br/Api/v3/";
    
    $recurso = "produtos/";  
   //$recurso = "contatos"; 
   
   $filtro = "16088957453";
   // $filtro = http_build_query(array('idProduto'=>'16088957453'));
    
    
    $totalPagina = "?pagina=1";
    $totalLimite = "&limite=2";
    
    $url_full = $url_api_blig.$recurso.$filtro.$totalPagina.$totalLimite;
    
    echo $url_full;
    
    $curl2 = curl_init();
    
    $set_header2 = array ("Accept: 1.0", "Authorization: Bearer ".$dadosTokenAcesso->access_token);
    
    
    curl_setopt($curl2, CURLOPT_URL           , $url_full);
	curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl2, CURLOPT_HTTPHEADER    , $set_header2);
	
    
    $resp = curl_exec($curl2);
    
	curl_close($curl2);
    
    echo "<hr>";

    //transformando o dados do retorno de json para array
   $dadosEstoque = json_decode( $resp);
    
    print_r( $dadosEstoque);
    
    
    
    
    
    
    
    
    
    
    ?>
	
	
<br>
<br>

	
	
</body>
</html>