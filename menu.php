<script src="js/jquery-3.7.1.js"></script>
<script src="js/js_geral.js"></script>

<link rel="stylesheet" href="css/style.css" />
<script>

 $(document).ready(function(){
        
     $("#btRecolher").click(function () {
  
        $("#divMenu").toggleClass('recolhido'); 
        $(".subDivMenu").fadeToggle();
    
      var valorAtual = $("#encolherBtn").val();
      if (valorAtual === ">") {
        $("#encolherBtn").val("<");
      } else {
        $("#encolherBtn").val(">");
      }
    });
        

 $(".ttMenu").on("click", function(){
         var x = $(this).attr("id");
         $("#div"+x).slideToggle();  
          
          });


    
$("#logoff").on("click", function(){
    
    alert("sair");
    
        $.get("login_load.php?sair=s", function(dados){
        $("#absolut_login").html(dados).fadeIn();

        });

     });
    
     });   

</script>
<style>
/* stylesheet */   
.menu {
    width: auto;
    position: relative;
}
.menu div, span, ul, li, a {
    font-size: 16px !important
}
.menu li {
    margin-left: 30px;
}
#btRecolher {
    position: absolute;
    margin: 0px;
    top: 100px;
    right: 10;
    z-index: 2000;
    cursor: pointer;
}
.ttMenu, a {
    display: flex;
    align-items: center;
}
.ttMenu img {
    width: 20px;
    height: 20px;
    margin-right: 10px;
}
</style>

<div class="menu">
 <div id='btRecolher'>
  <input class='bt_recolhe_menu' type="button"  id="encolherBtn"  value="<">
  <input class='bt_recolhe_menu' type="button"  id="expandirBtn"  value=">" style="display:none;">
 </div>
 <div class="subDivMenu">
  <ul>
   <div class="ttMenu"><a href="home.php"><img src="imgs/iconMenu/iconHome.png"  alt=""/>Início</a></div>
   <div id="M1" class="ttMenu"><img src="imgs/iconMenu/iconVendas.png"  alt=""/>Vendas</div>
   <div id="divM1" class="subMenu2">
    <li><a href="ultimas_vendas.php"       target="_blank" title="Ultimas vendas">Ultimas vendas</a></li>
    <li><a href="analise_vendas.php"       target="_blank" title="Ultimas vendas">Relatórios</a></li>
    <li><a href="flash_vendas_lojas.php"   target="_blank" title="Rank de lojas">Rank Lojas</a></li>
    <li><a href="rank_fornecedores.php"    target="_blank" title="Rank de fornecedor">Rank Fornecedores</a></li>
    <li><a href="rank_produtos.php"        target="_blank" title="Rank de produtos">Rank Produtos</a></li>
    <li><a href="atualiza_base_vendas.php" target="_blank" title="Rank de produtos">Atualizar Base</a></li>
    <li><a href="atualiza_pedido_itens.php" title="Atualizar Base de Pedidos">Atualiza Pedidos</a></li>
   </div>
   <div id="MenuProd" class="ttMenu"><img src="imgs/iconMenu/iconProdutos.png"  alt=""/>Produtos</div>
   <div id="divMenuProd" class="subMenu2">
    <li><a href="analise_transf_estoque.php"  target="_blank" title="Consulta Estoque Custo">Transferencia de Estoque</a></li>
    <li><a href="analise_sugestao_compra.php" target="_blank" title="Analise sugestão de compras">Compras</a></li>
    <li><a href="atualiza_produtos.php"       target="_blank" title="Atualiza Produtos Geral">Atualiza Produtos</a></li>
    <li><a href="atualiza_produtos_novos.php" target="_blank" title="Atualiza Novos Produtos">Produtos Novos</a></li>
    <li><a href="atualiza_saldo_estoque_produtos.php" target="_blank" title="Atualiza Saldo estoque Geral">Saldo estoque Geral</a></li>
    <li><a href="atualiza_produtos_inativos.php?filtro=3" target="_blank" title="Atualiza base Inativos">Produtos Inativos</a></li>
    <li><a href="atualiza_produtos_inativos.php?filtro=4" target="_blank" title="Atualiza base Excluídos">Produtos Excluidos</a></li>
    <li><a href="atualiza_produtos_detalhes.php" target="_blank" title="Atualiza Base Produtos Detalhes">Produtos Detalhes</a></li>
    <li><a href="ajuste_estoque.php" target="_blank" title="Consulta Estoque Custo">Ajuste de Estoque</a></li>
    <li><a href="cron/atualiza_nfe_detalhes.php" target="_blank" title="Consulta Estoque Custo">Atualiza Ult. NF compra</a></li>
   </div>
   <div id="Comercial" class="ttMenu"><img src="imgs/iconMenu/iconComercial.png"  alt=""/>Comercial</div>
   <div id="divComercial" class="subMenu2">
    <li><a href="analise_sugestao_compra.php" target="_blank" title="Analise sugestão de compras">Compras</a></li>
    <li><a href="analise_preco.php"           target="_blank" title="Precificação por lojas">Preços</a></li>
    <li><a href="despesas.php"                target="_blank" title="">Despesas</a></li>
    <li><a href="impostos.php"                target="_blank" title="">Impostos </a></li>
    <li><a href="resultado.php"               target="_blank" title="Resultado">Resultado</a></li>
   </div>
   <div id="cvl" class="ttMenu"><img src="imgs/iconMenu/iconRefresh2.png"  alt="" />Importações</div>
   <div id="divcvl" class="subMenu2">
    <li><a href="atualiza_pedido_itens.php">Pedidos detalhes</a></li>
    <li><a href="atualiza_token_access.php">Tokens de acessso</a></li>
    <li id="menupainel1" class="btMenuPainel" title="refresh_canais_vendas.php">Canais de vendas</li>
    <li id="menupainel1" class="btMenuPainel" title="refresh_canais_vendas_tipo.php">Canais de vendas tipos</li>
    <li id="menupainel1" class="btMenuPainel" title="atualiza_categorias_lojas.php">Categorias jojas</li>
    <li id="menupainel1" class="btMenuPainel" title="atualiza_depositos.php">Depositos</li>
    <li><a href="atualiza_pedido_itens.php">Pedidos detalhes</a></li>
    <li id="menupainel1" class="btMenuPainel" title="atualiza_base/getSituacoes.php">Situações de pedidos</li>
    <li id="menupainel1" class="btMenuPainel" title="atualiza_base/getNaturezasOperacoes.php">Natureza Operações</li>
    <li> <a href="atualiza_base/refresh_contatos.php">Fornecedores</a></li>
    <li> <a href="atualiza_base/refresh_tipo_contatos.php">Tipos de Contatos</a></li>
    <li id="menupainel1" class="btMenuPainel" title="atualiza_produtos.php">Produtos</li>
    <li id="menupainel1" class="btMenuPainel" title="atualiza_produtos_detalhes.php">Produtos detalhes</li>
    <li id="menupainel1" class="btMenuPainel" title="produto_estrutura.php">Produtos estrutura</li>
    <li><a href="atualiza_saldo_estoque_produtos.php">Saldo de estoque</a></li>
    <li><a href="atualiza_produtos_fornecedor.php">Produto fornecedor</a></li>
    <li id="menupainel1" class="btMenuPainel" title="carrega_produtos_lojas.php">Produto anúncios</li>
   </div>
   <div id="api" class="ttMenu"><img src="imgs/iconMenu/iconConfig.png"  alt=""/>Configurações</div>
   <div id="divapi" class="subMenu2">
    <li><a href="http://162.240.162.180:2082/cpsess0530780649/3rdparty/phpMyAdmin/index.php" title="phpMyAdmin" target="_blank">Banco de Dados</a></li>
    <li><a href="http://162.240.162.180:2082/cpsess0739764407/frontend/jupiter/index.html" title="Cpanel" target="_blank">CPANEL</a></li>
    <li><a href="lojas_configuracoes.php" title="configuracoes lojas"> Lojas virtuais</a></li>
    <li><a href="index_cron.php"> Inportações automáticas</a></li>
   </div>
   <div class="ttMenu"><a  title="Fazer Logoff" id="logoff"> <img src="imgs/iconMenu/iconSair.png"/> Sair </a></div>
  </ul>
 </div>
</div>
<div id="absolut_login"> </div>
