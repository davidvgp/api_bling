// JavaScript Document


   $('input').keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
        }
    });





    function InNumCalc($v) {

        return parseFloat($v.replace(",", "."));
    }
    
    
$(document).ready(function () {


});
