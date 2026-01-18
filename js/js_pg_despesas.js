// JavaScript Document

function carregaListaDespesas() {


    var conta = $("#conta").val();

    if (conta.length > 0) {

        $.post("despesas_load.php", {

            conta: conta

        }, function (dados) {

            $("#divCarregado").html(dados).fadeIn();
        });
    }
}

function paginaDespeas(opcoes) {

        switch (opcoes) {

            case "relatorio":
                
                $.get("despesas_relatorio.php", function (dados) {
                   
                    $("#divDespesas").html(dados).slideDown();
                    $("#divCarregado").slideUp();
                    efeitoload('out');
                });
            break;

                
            case "cadastra":
                
             $.get("despesas_cadastrar.php", function (dados) {

                 $("#divDespesas").html(dados).slideDown();
                    
                 $("#divCarregado").slideUp();
                    efeitoload('out');

                });
            break;
        }

    }


