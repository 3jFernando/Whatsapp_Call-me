'use strict';

$(".btn-send-message").click(function () {

    $.ajax({
        url: "http://localhost/Whatsapp_Call-me/src/Server/Active.php",
        method: 'GET',
        data: {},
        success: function (result) {

            console.log(result);

        }, errors: function() {
            alert("Fallo al tratar de realizar la accion, por favor intentalo de nuevo, si el error persiste intentalo mas tarde.");
        }
    });

});