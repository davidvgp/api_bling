<?php
require_once( "session.php" );
require_once( "config.php" );

    $ids_user = $_SESSION[ "idUsuario" ];
    $Class = new classMasterApi();
    $ids_user = $Class->loadUserApi( $ids_user );
?>


<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/style_menu.css" />

<!doctype html>
<html>
<head>
<title>Flash de Vendas</title>
</head>

<body>
<div id="header">
    <?php require_once("header.php");  ?>
</div>
<div id="main">
<div id="divMenu" class=""><?php require_once( "menu.php" ); ?></div>

<div id="divContainer" >
  <div class="divBlocoGrande">   


<div class="divBloco width_40p">
<?php
    echo "<div class='div_titulos size20'>Atualizar token de acesso</div><hr>";

    echo "<table width='100%' align='center'>";

    foreach ( $ids_user as $ids ) {


        echo "<tr>";

        echo "<th>" . $ids[ 'nome_conta_bling' ] . "</th> <th> <a href=" . $ids[ 'link_convite' ] . " target='_blank' class='btLink width_50p'>atualizar</a><th></tr>";

        echo "</tr>";

    }
    echo "</table>";
    ?>
</div>

        
</div>   
</div>   
</div>   
    
<div id="rodape">41</div>
<div id="absolut">
    <div class="load"><img src="imgs/loading-gif-transparent.gif"  width="50"></div>
</div>
</div>

<div id="notaRodape"> </div>
    
</body>
</html>







