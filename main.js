'use strict';

const url = "http://localhost/Whatsapp_Call-me/src/Server/";

loadNumbers();

$("body").on('click', '.btn-edit-number', function() {

    const id = $(this).parents('li').attr('idd');
    const code = $(this).parents('li').attr('code');
    const number = $(this).parents('li').attr('number');

    $("#number-id").val(id);
    $("#number-code").val(code);
    $("#number-number").val(number);
    $("#number-link").val("https://wa.me/"+code+number);

    $("#form-number").attr('action', 2);

    $("#modal-edit-number").modal('show');

});

$("body").on('click', '.btn-new-number', function() {

    $("#number-id").val(0);
    $("#number-code").val(57);
    $("#number-number").val();
    $("#number-link").val("https://wa.me/57");

    $("#form-number").attr('action', 1);

    $("#modal-edit-number").modal('show');

});

$("#form-number").submit(function (event) {

    event.preventDefault();

    const id = $("#number-id").val();
    const code = $("#number-code").val();
    const number = $("#number-number").val();
    const action = $("#form-number").attr('action');

    formNumber(id, code, number, action);

});

$("#number-code").keyup(function () {
    updateLiunk();
});

$("#number-number").keyup(function () {
    updateLiunk();
});

function updateLiunk() {
    const code = $("#number-code").val();
    const number = $("#number-number").val();

    $("#number-link").val("https://wa.me/"+code+number);
}

function formNumber(id, code, number, action) {

    $.ajax({
        url: url+'Form.php',
        method: 'POST',
        data: {
            'id': id,
            'code': code,
            'number': number,
            'action': action
        },
        success: function (result) {

            if(result == true) {

                if(action == 2) {

                    $(".body-numbers li").each(function () {
                        if($(this).attr('idd') == id) $(this).remove();
                    });

                }

                pushItemToList(id, number, code, "https://wa.me/"+code+number);

                $("#modal-edit-number").modal('hide');
                alert("Proceso realizado con exito");

            } else {
                alert("No es posible realizar la accion.");
            }

        }, errors: function() {
            alert("Ocurrio un error al tratar de realiar la accion.");
        }
    });

}

function loadNumbers() {

    $.ajax({
        url: url+'Reader.php',
        method: 'GET',
        data: {},
        success: function (result) {

            if(result.length > 0) {

                for (let i = 0; i < result.length; i++) pushItemToList(result[i].id, result[i].number, result[i].code, result[i].link);

                $("#numbers-hability").html(result.length);

            } else {
                $(".body-numbers").html("");
                $("#numbers-hability").html(0);
            }

        }, errors: function() {
            alert("No es posible cargar los datos");
        }
    });

}

function pushItemToList(id, number, code, link) {

    $(".body-numbers").prepend("<li idd='"+id+"' number='"+number+"' code='"+code+"'> " +
        "<div>" +
        "<img src='./assets/icon.png' alt='...'>" +
        "<div><b>Número: </b><span>"+number+"</span><br> " +
        "<b>Code: </b><span>+"+code+"</span><br>" +
        "<a href='"+link+"' target='_blank'>Enviar mensaje</a>" +
        "</div> " +
        "</div> " +
        "<img src='./assets/edit.png' class='btn-edit-number' alt='...'>" +
        "</li>");

}