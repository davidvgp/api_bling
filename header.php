<?php  

require_once( "session.php" );
require_once( "config.php" );


?>

<link rel="stylesheet" href="css/style.css" />  

<style>
    #divMainHeader {
        display: flex;
    }
    #hd1, #hd2, #logoApp, #hd4, #hdUser {
        flex: 1;
        display: flex; /* Define o contêiner flex para cada div */
        justify-content: center; /* Alinha horizontalmente */
        align-items: center; /* Alinha verticalmente */
    }
    .hdNomeUser {
        display: flex; /* Torna o contêiner flex */
        align-items: center; /* Alinha imagem e texto verticalmente */
        font-size: 18px;
        color: var( --cor-bt1);
    }
    #hdUser img {
        margin-right: 5px; /* Espaçamento entre imagem e texto */
        width: 30px;
        height: 30px;
        
    }
</style>

<div id="divMainHeader">
    <div id="logoApp">
        <img src="imgs/logo_app.png" width="40">
    </div>
    <div id="hd2"></div>
    <div id="hd3"></div>
    <div id="hd4"></div>
    <div id="hdUser">
        <div class="hdNomeUser">
         <img src="imgs/iconMenu/iconUser-cor2.png" />
         <?php echo $_SESSION["NomeUsuario"] ?>
        </div>
       
    </div>
</div>


