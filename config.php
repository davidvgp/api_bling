<?php

spl_autoload_register(function($class_name){
    
    $filename = "../class".DIRECTORY_SEPARATOR.$class_name.".php";
    
    if(file_exists(($filename))){
        
        require_once($filename);
        
    }
        
     $filename2 = "class".DIRECTORY_SEPARATOR.$class_name.".php";
    
    if(file_exists(($filename2))){
        
        require_once($filename2);
        
    }   
        
});

?>