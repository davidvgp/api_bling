
function InNumCalc(v) {

    return parseFloat(v.replace(",", "."));
}


function OutNumCalc(v) {
    
    return v.replace(".", ",");
    
}

        

function notaRodape(dados){
    
       $("#notaRodape").html(dados).fadeIn();
    
        setTimeout(function() { 
            $("#notaRodape").fadeOut();
        }, 35000); // 15000 milissegundos equivalem a 30 segundos

}
    
function efeitoload(op) {

    switch (op) {

        case 'in':
            $("#absolut").fadeIn();
            $(".load").fadeIn();
            break;

        case 'out':
            $("#absolut").fadeOut();
            $(".load").fadeOut();
            break;

        default:
            $("#absolut").fadeOut();
            $(".load").fadeOut();
            break;
                }
            }

function carregaFornecedores(conta) {


    $.post('carrega_dinamicos_html.php', {

        func: 'nome_fornecedor',
        conta: conta

    }, function (dados) {

        $('#fornec').html(dados);

        efeitoload('out');
    });

}

function carrFornEmComum(conta) {


    $.post('carrega_dinamicos_html.php', {

        func: 'carrFornEmComum',
        conta: conta
  
    }, function (dados) {

        $('#fornec').html(dados);

        efeitoload('out');
    });

}


function carregaAnalisePreco() {

    var conta = $("#conta").val().length;
    var fornec = $("#fornec").val().length;
    var lojas = $("#lojas").val().length;
    var produtos = $("#produtos").val().length;
    var busca = $("#FiltraProd").val().length;

    if (conta == 0) {
        $('#conta').find('option').prop('selected', true)
    }

    //   if(fornec == 0)  { confirm("Selecione ao menos um FORNECEDOR!");   }
    //   if(lojas  == 0)  {  $('#lojas').find('option').prop('selected', true) }

    if (busca.length > 2) {

        if (produtos == 0) {
            $('#produtos').find('option').prop('selected', true);
        }

    }

    var post = $("#form1").serialize();

/*    
    $('#divCarregado').slideUp(function () {

        $.post("analise_preco_load.php", post, function (dados) {

            $("#divCarregado").html(dados).slideDown(800);
           
           // alert("chegou aqui!");
            

        });

    });
    */
    $('#divCarregado').slideUp(function () {
        
    $.post("analise_preco_load.php", post, function (dados) {
        
        if (!dados.trim()) { // Verifica se 'dados' está vazio ou contém apenas espaços
           
            alert("Nenhum dado foi retornado!");
            
        } else {
            
            $("#divCarregado").html(dados).slideDown(800);
        }
    });
        
});
    
      
}



function exibePedido(conta, fornec) {

    $.post("exibePedidoCompra.php", {
        fornec: conta,
        conta: fornec

    }, function (dados) {

        $("#exibePedido").html(dados).slideDown();

        efeitoload('out');

    });

}


function carregaAnuncios(conta, fornec) {

    $.post("carrega_dinamicos_html.php", {

        func: 'prodFornLojas',  // para trazer o idProduto
        conta: conta,
        fornec: fornec,

    }, function (dados) {

        $("#produtos").html(dados);

    });

    efeitoload('out');
}


function carregaProfFornCodigo(conta, fornec) {

    $.post("carrega_dinamicos_html.php", {

        func: 'prodFornCodigo', // para trazer o codigo ref do fornecedor. 
        conta: conta,
        fornec: fornec,

    }, function (dados) {

        $("#produtos").html(dados);

    });

    efeitoload('out');
}


function carregaProdNotaEntrada(conta, idnota, fornec) {

    $.post("carrega_dinamicos_html.php", {

        func: 'ProdNotaEntrada', 
        conta: conta,
        idnota: idnota,
        fornec: fornec

    }, function (dados) {

        $("#produtos").html(dados);
        
      //  notaRodape(dados);

    });

    efeitoload('out');
}

function carregaContaPara(conta) {

    $.post("carrega_dinamicos_html.php", {

        func: 'contaPara', // para trazer as outras conta diferente da selecionada. 
        conta: conta,
        }, function (dados) {

        $("#contaPara").html(dados);

    });

    efeitoload('out');
}




function statusPedidos() {

    $.post("carrega_dinamicos_html.php", {

        func: 'statusPedidos'

    }, function (dados) {

        $("#statusPedidos").html(dados);

    });

}


function carregaLojas() {


    $.post("carrega_dinamicos_html.php", {

        func: 'nome_lojas_tipo',
        conta: $("#conta").val()

    }, function (dados) {

        $("#lojas").html(dados);

    });

    efeitoload('out');
}




function FiltraProd(conta, fornec, busca) {


    if (busca.length > 2) {

        $.post("carrega_dinamicos_html.php", {

            func: 'FiltraProd',
            conta: conta,
            fornec: fornec,
            busca: busca

        }, function (dados) {


            $("#produtos").html(dados);

        });

    } else {

        carregaProfFornCodigo(conta, fornec);
    }

}


function carregFreteLojas() {

    $.post("carrega_dinamicos_value.php", {
        func: 'descFreteLoja',
        lojas: $("#lojas").val()

    }, function (dados) {

        $("#descFreteLoja").val(dados);

    });

}

$(document).ready(function () {
    
  $(".btnFechar").on("click", function() {
      
    $(this).closest("#corpoPedido").slideUp(); // Fecha apenas a div pai correspondente
  
  });     
    

    $("#notaRodape").on("dblclick", function () {

        $(this).fadeOut();
    });

    $(".trClick").dblclick(function () {

        $(this).css("background-color", "hsla(34,100%,50%,0.29)");

    });

    $(".trClick").dblclick(function () {

        $(this).css("background-color", "");

    });

    $('.copiarTextoImg').dblclick(function () {

        var texto = $(this).attr('alt');

        // Criar um elemento de texto temporário 
        var inputTemporario = $('<textarea>');

        $('body').append(inputTemporario);

        inputTemporario.val(texto).select();

        document.execCommand('copy');

        inputTemporario.remove();
        
    // Avisar o usuário que o texto foi copiado 
        notaRodape('Copiado pedido:' + texto);
    
    
    });

/*
    $(".trchave").dblclick(function () {

        var bkg = $(".trchave").css('background-color');


        if ($(this).css('background-color') === bkg || $(this).css('background-color') === "#FFF") {


            $(this).css('background-color', 'hsla(34,100%,50%,0.29)');

        } else {

            $(this).css('background-color', '');

        }

    });
*/

$(".tabela_padrao tr").dblclick(function () {
    trChave($(this));
});

//function que grifa a linha da tabela ao clicar 2x
function trChave(el) {
    var bkg = $(".trchave").css('background-color');

    if (el.css('background-color') === bkg || el.css('background-color') === "rgb(255, 255, 255)") {
        el.css('background-color', 'hsla(34,100%,50%,0.29)');
    } else {
        el.css('background-color', '');
    }
}

    $("#notaRodape").dblclick(function () {

        $("#notaRodape").hide("slow");

    });

    


    

});
 $(document).ready(function () {
     
    $('form').keypress(function (event) {
        if (event.which == 13) {
            event.preventDefault();
        }
    });
     
    });
        
     