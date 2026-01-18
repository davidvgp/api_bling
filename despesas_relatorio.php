<?php
session_start();
require_once("conteudo_header.php"); 
require_once( "config.php" );

$Class = new classMasterApi();
$sql = new Sql();


$sel_conta = $sql->select( "SELECT id, nome_conta_bling as conta FROM tb_user_api" );
$categoriaDespesa = $sql->select( "SELECT * FROM tb_despesas_categoria where 1" );
$sel_origem = $sql->select( "SELECT DISTINCT(origem) FROM `tb_despesas` WHERE  1" );
$sel_forma_pgto = $sql->select( "SELECT DISTINCT(forma_pgto) FROM `tb_despesas` WHERE  1" );
$sel_recorrencia = $sql->select( "SELECT DISTINCT(recorrencia) FROM `tb_despesas` WHERE  1" );


?>


    <script>
 $(document).ready(function () {
    
$("#btlancarDespesa").on("click", function () {
       
        efeitoload('in');
        $("#divDespesas").slideUp();
    
        paginaDespeas("cadastra");
   
    $("#divCarregado").slideUP();
});

 $('#btBuscaRelatorioDesepesas').on('click', function(){
     
     
     var conta  = $("#conta").val();
     if(conta.length == 0)   {  $('#conta').find('option').prop('selected', true) }  
 
 var post = $('#form1').serialize();
     
     $.post('despesas_load.php', post, function(dados){
   
     $("#divCarregado").html(dados).slideDown();
     
     });
 
 });    
     
});   
    

    </script>
    
<div id="tituloRelatorio" class="div_titulos size_18"> Relatórios de Despesas</div>
<div class="divMiniBloco">
   
        <form id="form1" class="formPadrao1" name="form1">
            <table width="200" border="0" cellpadding="5" cellspacing="5">
                <tr>
                    <th align="left">Conta<br>
                        <select name="conta[]" multiple class="inputSizeM" id="conta">
                            <?php foreach($sel_conta as $col ) { ?>
                            <option value="<?php echo $col['id'];  ?>"> <?php echo $col['conta'];  ?></option>
                            <?php } ?>
                        </select>
                    </th>
                    <th align="left">Categoria<br>
                        <select name="categoria[]" id="categoria" multiple class="inputSizeM">
                            <?php foreach($categoriaDespesa as $col ) { ?>
                            <option value="<?php echo $col['id'];  ?>" title="<?php echo $col['descricao'];  ?>"> <?php echo $col['categoria'];  ?></option>
                            <?php } ?>
                        </select>
                    </th>
                    <th align="left">Origem<br>
                        <select name="origem[]" id="origem" multiple class="inputSizeM">
                            <?php foreach($sel_origem as $col ) { ?>
                            <option value="<?php echo $col['origem'];  ?>" title="<?php echo $col['origem'];  ?>"> <?php echo $col['origem'];  ?></option>
                            <?php } ?>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th align="left">Recorrencia <br>
                        <select name="recorrencia[]" id="recorrencia" multiple class="inputSizeM">
                            <?php foreach($sel_recorrencia as $col ) { ?>
                            <option value="<?php echo $col['recorrencia'];  ?>" title="<?php echo $col['recorrencia'];  ?>"> <?php echo $col['recorrencia'];  ?></option>
                            <?php } ?>
                        </select>
                    </th>
            <th align="left"> Forma Pgto <br>
                        <select name="forma_pgto[]" id="forma_pgto" multiple class="inputSizeM">
                            <?php foreach($sel_forma_pgto as $col ) { ?>
                            <option value="<?php echo $col['forma_pgto'];  ?>" title="<?php echo $col['forma_pgto'];  ?>"> <?php echo $col['forma_pgto'];  ?></option>
                            <?php } ?>
                        </select>
                    </th>
                    <th align="left">
                        Exercício<br>

                         <table width="0" border="0" cellpadding="0" cellspacing="0">
                         <tr><td>De</td><td><input name="dataIni" type="month" id="dataIni" value="<?php echo date("Y-m");?>" ></td></tr>
                         <tr><td>até</td><td><input type="month"  name="dataFin" id="dataFin" value="<?php echo date("Y-m");?>"></td></tr>

                    </table>
                    
                    </th>
                </tr>
                <tr>
                    <th align="left">
                        <input type='button' name="btlancarDespesa"  class="inputSizeM" id='btlancarDespesa' value='Lançar Despesas'>
                    </th>
                    <th align="left">&nbsp; </th>
                    <th>
                        <input type='button' class="inputSizeM" id='btBuscaRelatorioDesepesas' value='Buscar'>
                    </th>
                </tr>
            </table>
    </form>
   
</div> 
