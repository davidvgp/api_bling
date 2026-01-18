<?php 

if( $_GET["code"] == ""){

header("location:https://www.bling.com.br/Api/v3/oauth/authorize?response_type=code&client_id=755963990cb38996319b3960b3ef3f33c410878c&state=eddb98c32278bab93b4910bc80e6498e");

}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Pagina Requisição</title>

</head>

<body>
	

<?php
    
    	
	echo "code: ".$_GET["code"];	
	echo "<br>";
	echo "state: ".$_GET["state"];
	
    //class obterCodeAcess{
        
        
         $urlHost = "https://www.bling.com.br/Api/v3/oauth/authorize?response_type=code&client_id=755963990cb38996319b3960b3ef3f33c410878c&state=eddb98c32278bab93b4910bc80e6498e";
         $parametro_type;
         $parametro_code;
    
      //public function obterCodes() {
                
        $curl_obterCod = curl_init();
          
        curl_setopt($curl_obterCod, CURLOPT_URL           , $urlHost);
	    curl_setopt($curl_obterCod, CURLOPT_RETURNTRANSFER, true);
       
          $resp_obterCod = curl_exec($curl_obterCod);
    
	       curl_close($curl_obtCod);
                      
        //return $resp_obtCod;              
                      
        print_r ($resp_obterCod);  
    //}
    
    
        
        
        
    //}
    
         
        
        
    ?>
	

    

	
<br>
<br>

	
	
</body>
</html>