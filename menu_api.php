

<link rel="stylesheet" href="css/style.css" />
<script src="js/jquery-3.7.1.js"></script> 
<script src="js/js_geral.js"></script> 

<script>
    
function recolheMenu() {

        $("#encolherBtn").fadeOut();

        $("#divMenu").animate({
            width: "3%"
        }, 500);
            
    //    $("#btencolhe_expande").animate({
    //        marginLeft:"0" }, 500);
            
            
        $("#divContainer").animate({
            width: "96.90%"
        }, 500);
        $("#subDivMenu").fadeOut();
        $("#expandirBtn").fadeIn(); 
        
    }

    function expandeMenu() {
        
   //     $("#btencolhe_expande").animate({
    //        marginLeft:"280px" }, 500);
        

        $("#divContainer").animate({
            width: "83.32%"
        }, 500).delay(100);

        $("#divMenu").animate({
            width: "16.66%"
        }, 500).delay(100);

        $("#subDivMenu").fadeIn();
       
        $("#expandirBtn").fadeOut();
        $("#encolherBtn").fadeIn();
    }

    
    $(document).ready(function(){

    $("#encolherBtn").click(function () {
            recolheMenu();
        });
    $("#expandirBtn").click(function () {
            expandeMenu();
        });

    $(".ttMenu").on("click", function(){
         var x = $(this).attr("id");
         $("#div"+x).slideToggle();  
          }); 

    $("#logoff").on("click", function(){
        $.get("login_load.php?sair=s", function(dados){
        $("#absolut_login").html(dados).fadeIn();

        });

     });   

 });

</script>

    <div id="subDivMenu">
    <br><br><br>
    <div class="menu">
    <ul>
      <li><a href="home.php" title="" target="">INÍCIO</a></li>
      <li><a href="http://162.240.162.180:2082/cpsess0530780649/3rdparty/phpMyAdmin/index.php" title="phpMyAdmin" target="_blank">Banco de Dados</a></li>
      <li><a href="http://162.240.162.180:2082/cpsess0739764407/frontend/jupiter/index.html" title="Cpanel" target="_blank">CPANEL</a></li>
      <div id="api" class="ttMenu">CONFIGURAÇÕES</div>
      <div id="divapi" class="subMenu2">
        <li id="menupainel1" class="btMenuPainel" title="atualiza_token_access.php">&#10157; Atualiza tokens de acessso</li>
        <li> <a href="lojas_configuracoes.php" title="configuracoes lojas">&#10157; Lojas virtuais</a></li>
   
        <li> <a href="index_cron.php">&#10157; Inportações automáticas</a></li>
      </div>
      <div id="cvl" class="ttMenu">IMPORTAÇÕES</div>
      <div id="divcvl" class="subMenu2">
        <li id="menupainel1" class="btMenuPainel" title="refresh_canais_vendas.php">&#10157; Atualiza Canais de vendas</li>
        <li id="menupainel1" class="btMenuPainel" title="refresh_canais_vendas_tipo.php">&#10157; Atualiza Canais de vendas tipos</li>
        <li id="menupainel1" class="btMenuPainel" title="atualiza_categorias_lojas.php">&#10157; Atualiza Categorias jojas</li>
        <li id="menupainel1" class="btMenuPainel" title="atualiza_depositos.php">&#10157; Atualiza Depositos</li>
        <li><a href="atualiza_pedido_itens.php">&#10157; Atualiza Pedidos detalhes</a></li>
        <li id="menupainel1" class="btMenuPainel" title="atualiza_base/getSituacoes.php">&#10157; Atualiza Situações de pedidos</li>
        <li id="menupainel1" class="btMenuPainel" title="atualiza_base/getNaturezasOperacoes.php">&#10157; Atualiza Natureza Operações</li>
      </div>
      <div id="prod" class="ttMenu">PRODUTOS</div>
      <div id="divprod" class="subMenu2">
        <li id="menupainel1" class="btMenuPainel" title="atualiza_produtos.php">&#10157; Atualiza Produtos</li>
        <li id="menupainel1" class="btMenuPainel" title="atualiza_produtos_detalhes.php">&#10157; Atualiza Produtos detalhes</li>
        <li id="menupainel1" class="btMenuPainel" title="produto_estrutura.php">&#10157; Atualiza Produtos estrutura</li>
          <li><a href="atualiza_saldo_estoque_produtos.php">&#10157; Atualiza Saldo de estoque</a></li>
          <li><a href="atualiza_produtos_fornecedor.php">&#10157; Atualiza Produto fornecedor</a></li>
        <li id="menupainel1" class="btMenuPainel" title="carrega_produtos_lojas.php">&#10157; Atualiza Produto anúncios</li>
     
      </div>
      <div id="forn" class="ttMenu">FORNECEDORES</div>
      <div id="divforn" class="subMenu2">
          <li> <a href="atualiza_base/refresh_contatos.php">&#10157; Atualiza Fornecedores</a></li>
          <li> <a href="atualiza_base/refresh_tipo_contatos.php">&#10157; Atualiza Tipos de Contatos</a></li>
       
      </div>
      <li><a title="Fazer Logoff" id="logoff">Sair</a></li>
    </ul>
  </div>
</div>
    <div id='btRecolher'>
    <input class='bt_recolhe_menu' type="button"  id="encolherBtn"  value="<">
    <input class='bt_recolhe_menu' type="button"  id="expandirBtn"  value=">" style="display:none;">
</div>
