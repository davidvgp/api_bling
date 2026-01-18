<script src="js/jquery-3.7.1.js"></script>
<script>
 
$(document).ready(function(){
    
   
    
  $(".btX").on("click",  function (){ 
    
     var idfrete = $(this).attr("title");
      
  excluiUmFrete(idfrete); 
                     
  }); 
    
   
 function excluiUmFrete(idfrete) {
     
      $.post("carrega_dinamicos_value.php", {

        func: 'excluiUmFrete',
        idfrete: idfrete

      }, function () {

        listFreteLojas();

      });
    

  }      
      
function listFreteLojas() {

    $.post("exibe_tabela_frete.php", {

      func: "listFreteLojas",
      lojas: $("#lojas option:selected").val()

    }, function (dados) {

      $("#tbFrete").html(dados).slideDown();
        
        $(".load").hide();

        listDescFrete();
        listaTxFixa();

      });
 }
    
    });
 

</script>

<?php

require_once( "config.php" );


switch($_POST['func']){
        
    case 'listFreteLojas':{
        
        getlistFreteLojas( $_POST['lojas'] );
        break;
}

}

    

function getlistFreteLojas( $loja ) {

    $Class = new classMasterApi();

    $sql = new Sql();

 $sel = "SELECT 
tb_custo_frete.id,
tb_custo_frete.pesoDe,
tb_custo_frete.pesoAte,
tb_custo_frete.valor as 'Frete',
tb_canais_de_venda.descFrete as 'descFrete',
tb_custo_frete.valor * (1-tb_canais_de_venda.descFrete/100) as 'FreteDesc'
FROM tb_custo_frete
JOIN tb_canais_de_venda ON tb_canais_de_venda.tipo = tb_custo_frete.loja_tipo
WHERE tb_canais_de_venda.id_bling IN ('" . $loja . "') ORDER BY tb_custo_frete.pesoDe ASC";

    
 $res = $sql->select($sel);
     
    if ( count( $res ) > 0 ) {

        echo "<table id='tbTF' class='tabela_padrao size12'><tbody>";

        echo "<p>Tabela de frete <strong>" . $Class->getNomeLoja( $loja ) . " </strong></p>";

        echo "<tr>";
        echo "<th colspan='3'>Faixa de peso</th>";
        echo "<th>Frete</th>";
        echo "<th>Desc</th>";
        echo "<th>c/Desc</th>";
        echo "<td></td>";
        echo "</tr>";

        foreach ( $res as $k ) {

            echo "<tr>";

            echo "<td>" . $k[ 'pesoDe' ] . " kg</td>";
            echo "<td> até </td>";
            echo "<td>" . $k[ 'pesoAte' ] . " kg </td>";
            echo "<td style='text-align:right'>" . number_format( $k[ 'Frete' ], 2 ) . "</td>";
            echo "<td style='text-align:center'>" . number_format( $k[ 'descFrete' ], 1 ) . "%</td>";
            echo "<td style='text-align:right'><strong>" . number_format( $k[ 'FreteDesc' ], 2 ) . "</strong></td>";
            echo "<td><input type='button' class='btX'  value='x' title='" . $k[ 'id' ] . "'></td>";
            echo "</tr>";

        }
        echo "</tbody></table>";

    } else {

        echo "<strong>Não há tabela cadastrada</strong>";
    }

}
?>