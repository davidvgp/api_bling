<?php


class masterApi{
   
    public $conn;
        
    public function connBanco(){
        
       $conn = new PDO("mysql:dbname=david682_mvjob;local=br212.hostgator.com.br:3306", "david682_mvjob", "@M47zed5iir"); 
        
        return $conn;
    }
    
   
    
 public function selDados($selDados){   
     
        $conn =  $this->connBanco();

        $stmt = $conn->prepare($selDados);

        $stmt->execute();      

        $res  = $stmt->fetchAll(PDO::FETCH_ASSOC); // VERIFICAR OUTROS PARAMETROS PARA CONSULTA NO PHP MANUAL


        foreach($res as $row => $dados){

            return $dados;


        }

 } // fim metodo/função
    
    
    
public function insDados($cadsql){
    
     $conn =  $this->connBanco();

     $ins = $conn->prepare($cadsql);

     return $ins->execute();



    }
    
    
    
}//fim class
?>
