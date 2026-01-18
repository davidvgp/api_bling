// JavaScript Document

    
 $(document).ready(function(){
     
    
  $("#conta").on("change", function () {

    $.post("carrega_dinamicos_value.php", {
      func: 'aliqImpostoMes',
      conta: $("#conta option:selected").val(),
      month: $("#month").val()

    }, function (dados) {

      $("#aliqImp").val(dados);

    });

  });


     
  $("#month").on("change", function () {
      
  var mesAno = $("#month").val();
   var conta = $("#conta option:selected").val();
      
  
     $.post("carrega_dinamicos_value.php", {
        
      func: 'aliqImpostoMes',
      conta: conta,
      month: mesAno

    }, function (dados) {

      $("#aliqImp").val(dados);


    });


  });

     

    
  $(".formatNum").on("keyup", function () {

    var v = $(this).val();

    $(this).val(v.replace(",", "."));

  });


  /******************script cadastro custo frete***********************/


/******  carega novamente os nomes das lolas *************/    
     
  function NomeLojas() {

    $.post("carrega_dinamicos_html.php", {
      func: 'nome_lojas',
      conta: $("#conta2 option:selected").val()

    }, function (dados) {

      $("#lojas2").html(dados);

    });

  }


/* Listandos a tabela de forete cadastrada no banco */
  
 function listFreteLojas() {

    $.post("carrega_dinamicos_html.php", {

      func: "listFreteLojas",
      conta: $("#conta2 option:selected").val(),
      lojas: $("#lojas2 option:selected").val()

    }, function (dados) {

      $("#tbFrete").html(dados);
      $("#tbFrete").slideDown(800, function () {

        $(".load").hide();

        listDescFrete();
        listaTxFixa();

      });

    });
  }


/*** função que exibe o desconto de frete que está cadastrado ****************/
  function listDescFrete() {

    $.post("carrega_dinamicos_value.php", {

      func: "listDescFrete",
      conta: $("#conta2 option:selected").val(),
      loja: $("#lojas2 option:selected").val()

    }, function (dados) {

      $("#descFrete").val(dados);

    });

  }
     
/********* função que lista o valor da taxa fixa que está cadastrada ************/
  function listaTxFixa() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaTxFixa",
      conta: $("#conta2 option:selected").val(),
      loja: $("#lojas2 option:selected").val()

    }, function (dados) {

      $("#txFixa").val(dados);

    });

  }  
     
    function listaPedMin() {

    $.post("carrega_dinamicos_value.php", {

      func: "listaPedMin",
      conta: $("#conta2 option:selected").val(),
      loja: $("#lojas2 option:selected").val()

    }, function (dados) {

      $("#vPedMin").val(dados);

    });

  }

  

/********************************   SALVANDO DADOS ******************************************************/    

     
/****** função para salvar os valor des frete por faixa de peseo **********************/
  
function SalvaFrete() {

    $('.load').show();
 
    var post = $("#form2").serialize();


    $('#divCarregado').slideUp(function () {

     $.post("salva_formularios.php", post, function (dados) {

        $("#retornoSalvaFrete").html(dados);
        
          $('#retornoSalvaFrete').slideDown(800, function () {
           
              listFreteLojas();
          
              $('.load').hide();
              
         $('#retornoSalvaFrete').slideUp();      
        });

      });

    });

  }


     
/* função para Savlar a aliquota de imposto*/
    
  $("#btSalvaAliq").on("click", function () {
      
    var post = $("#form1").serialize();

 alert("salvar aliq");
      
     $.post("salva_formularios.php", post, function (dados) {

          
        $('#notaRodape').html(dados);
        $('#notaRodape').slideUP();
          
        });




    });


     
/*  função que salva o valor do desconto de frete */
     
  function salvaDescFrete() {

    $.post("salva_formularios.php", {


      func: "salvaDescFrete",
      desc: $("#descFrete").val(),
      conta: $("#conta2 option:selected").val(),
      loja: $("#lojas2 option:selected").val()

    }, function () {

      listFreteLojas();

    });


  }
/***** função que salva o valor da taxa fixa *********/
  function salvaTxFixa() {

    $.post("salva_formularios.php", {


      func: "salvaTxFixa",
      taxa: $("#txFixa").val(),
      conta: $("#conta2 option:selected").val(),
      loja: $("#lojas2 option:selected").val()

    }, function () {

      listaTxFixa();

    });


  }     
     
function salvaPedMin() {

    $.post("salva_formularios.php", {


      func: "salvaPedMin",
      vPedMin: $("#vPedMin").val(),
      conta: $("#conta2 option:selected").val(),
      loja: $("#lojas2 option:selected").val()

    }, function () {

      listaPedMin();

    });


  }     
      
     
/********** FIM DAS FUNÇÕES QUE SALVAM DADOS *********************/     
     
  /***************** CAPTURANDO O EVENTOS E  CHAMANDO AS FUNÇOES  *******************/
    
  $("#conta2").on("change", function () {
    NomeLojas();
  });
     
  $("#lojas2").on("change", function () {
    listDescFrete();
    listaTxFixa();
    listaPedMin();
    listFreteLojas();
      
      
  });
     
  $("#btSalvaDesc").on("click", function () {
    salvaDescFrete();
    listFreteLojas();
    listDescFrete();
  });
     
  $("#btSalvaTxFixa").on("click", function () {
    salvaTxFixa();
    listaTxFixa();
  });  
     
    $("#btSalvaPedMin").on("click", function () {
    salvaPedMin();
    listaPedMin();
  });
    
  $("#btSalvaFrete").on("click", function () {
    SalvaFrete();
  }); 
     
     
     

});
     
     
