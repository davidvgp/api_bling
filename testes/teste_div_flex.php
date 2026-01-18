<!doctype html>
<script src="../js/jquery-3.7.1.js"></script> 

<style>
    /* Estilos gerais */
    
    
:root {
    --cor-base0:#01AEc7; /*escura*/ /* var(--cor-base0) #01AEc7 */
    --cor-base1:#E7E8EA; /*escura*/ /* var(--cor-base1) #E7E8EA */
    --cor-base2:#F7F7F8; /*media */ /* var(--cor-base2) #F7F7F8 */
    --cor-base3:#FFFFFF; /*clara */ /* var(--cor-base3) #ffffff */
    --cor-base4:#f7f7f7;
    
    --cor-texto:#000000; /* var(--cor-texto) #ffffff */
    --cor-texto1:#3b3b3b; /* var(--cor-texto1) #ffffff */
    --cor-texto2:#ffffff; /* var(--cor-texto2) #ffffff */
    --cor-texto3:#F7F7F7; /* var(--cor-texto3) #ffffff */
    
    --cor-bt1:#01AEc7; /* var(--cor-bt1) #01AEc7 */
    /*--cor-bt1:#4a90e2;*/
    --cor-bt1-hover:#FFFFFF; /* var(--cor-bt1-hover) #ffffff */
    /*--cor-bt1-hover:#60a9ff;*/
    
    --cor-bt2:#ACACAC;    /* var(--cor-bt2) #ACACAC */
    --cor-bt2-hover:#dedede; /* var(--cor-texto3) #ffffff */
    
    --cor-bt3:#4a90e2; /* var(--cor-bt3) #4a90e2 */
    --cor-alerta:#FF5252; /* var(--cor-alerta) #FF5252 */
    --cor-borda1:#ACACAC; /* var(--cor-borda1) #ACACAC */
    
}
    
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    display: flex;
    flex-direction: column;
}

#header {
    height: 50px;
    background-color: var(--cor-base3);
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 10;
}

/* Layout principal (menu + conteúdo) */
#main {
    display: flex;
    margin-top: 50px; /* Espaço para o header fixo */
    flex: 1;
}

#divMenu {
    width: 200px; /* Largura inicial do menu */
    transition: width 0.3s; /* Suaviza o recolhimento/expansão */
    background-color: var(--cor-base1);
    overflow-y: auto;
}

#divMenu.recolhido {
    width: 50px; /* Largura quando recolhido */
}

#divContainer {
    flex: 1;
    padding: 10px;
    transition: margin-left 0.3s; /* Ajuste suave para o conteúdo */
}
 /* Rodapé */
#rodape {
    height: 50px;
    background-color: var(--cor-base1);
    text-align: center;
    padding: 10px;
    box-sizing: border-box;
}
    .container {
    display: flex; /* Define o contêiner como flex */
    flex-direction: row; /* Define a direção dos itens (row, row-reverse, column, column-reverse) */
    justify-content: space-between; /* Espaçamento horizontal entre os itens */
    align-items: center; /* Alinhamento vertical dos itens */
    width: 100%;
    height: 200px;
    background-color: #f1f1f1;
}

.item {
    flex: 1; /* Faz com que todos os itens tenham tamanhos iguais */
    margin: 5px;
    background-color: #ccc;
    text-align: center;
    padding: 10px;
    border: 1px solid #000;
}
   
</style>

<script>
$(document).ready(function () {
    $('#divMenu').on('click', function () {
        $(this).toggleClass('recolhido'); // Alterna a classe "recolhido" no menu
    });
});
</script>
<html>
<head>
<meta charset="utf-8">
<div id="header">Header fixo</div>
<div id="main">
    <div id="divMenu" class="menu">Menu lateral</div>
  <div class="container">
    <div class="item">Sub Div 1</div>
    <div class="item">Sub Div 2</div>
    <div class="item">Sub Div 3</div>
</div>

</div>
<div id="rodape">Todos os direitos reservados.</div>

</html>