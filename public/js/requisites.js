/**
 * Created by eduardojunior on 21/07/16.
 */

function populate(id) {

    $('.requisites .glyphicon-plus').click();
    $.ajax({
        type: 'GET',
        url: '/requisite/get/' + id,
        dataType: 'json',
        success: function (data) {
            $('.requisites form #id').val(data.id);
            $('.requisites form #projectId').val(data.projectId);
            $('.requisites form #name').val(data.name);
            $('.requisites form #description').val(data.description);
            $('.requisites form #expectedAmount').val(data.expectedAmount);
            $('.requisites form #spentAmount').val(data.spentAmount);
            $('.requisites form #name').focus();
        }
    });
}

$(document).ready(function () {

    $(document).on( "click", ".requisites-card .edit", function(e) {
        populate(
            parseInt($(this).attr('attr-row'))
        );
        return false;
    });

    $(document).on("change", "#checkRequisites :checkbox", function () {
        var total = parseInt($('.total_horas').text());
        var expectedAmount = parseInt($(this).nextUntil('.expectedAmount').find('.expectedAmount').text());
        if (this.checked) {
            var total = total + expectedAmount;
            $('.total_horas').html(total);
        } else {
            var total = total - expectedAmount;
            $('.total_horas').html(total);
        }
    });

    $(document).on('submit', '.requisites .form', function () {
        var dados = $(this).serialize();
        var form = this;
        $.ajax({
            type: "POST",
            url: "/requisite/create",
            data: dados,
            dataType: "json",
            success: function (data) {

                if ($('.thumbnail[rel=' + data.id + ']').length > 0){
                    $('.thumbnail[rel=' + data.id + '] h4').html(data.name);
                    $('.thumbnail[rel=' + data.id + '] .description').html(data.description);
                    $('.thumbnail[rel=' + data.id + '] .expectedAmount').html('<i class="glyphicon glyphicon-time"></i> ' + data.expectedAmount + 'h estimadas');
                    if ($('.thumbnail[rel=' + data.id + '] .spentAmount').length > 0){
                        $('.thumbnail[rel=' + data.id + '] .spentAmount').html('<i class="glyphicon glyphicon-time"></i> ' + data.spentAmount + 'h gastas');
                    } else {
                        $('.thumbnail[rel=' + data.id + '] .caption').append('<p class="spentAmount"><i class="glyphicon glyphicon-time"></i> ' + data.spentAmount +'h gastas</p>')
                    }
                } else {
                    var template;
                    var camada = $('<div class="col-md-4">');
                    var thumbnail = $('<span class="thumbnail" rel="' + data.id + '">');
                    var caption = $('<div class="caption">');
                    var anchor = $('<a data-href="/requisite/delete/' + data.id + '" data-toggle="confirmation" class="glyphicon glyphicon-remove" title="Apagar requisito">');
                    anchor.on('click', anchor.confirmation());
                    var edit = $('<a href="#" title="Editar Requisito" class="edit" attr-row="' + data.id + '"><i class="glyphicon glyphicon-pencil"></i>editar</a>');

                    var expectedAmount = data.expectedAmount.toString();
                    expectedAmount = expectedAmount.replace(".", "");
                    var description = data.description;
                    var spentAmount = data.spentAmount;
                    if (data.description == "") {
                        description = "&nbsp;";
                    }
                    var spent;
                    if (spentAmount != ""){
                        spent = $('<p class="spentAmount">').append('<i class="glyphicon glyphicon-time"></i> ' + data.spentAmount + 'h gastas')
                    }
                    template = camada.append(
                        thumbnail.append(
                            caption.append(
                                $('<h4>').append(data.name),
                                $('<p class="description">').append(description),
                                $('<p class="expectedAmount">').append('<i class="glyphicon glyphicon-time"></i> ' + expectedAmount + 'h estimadas'),
                                spent
                            ),
                            edit,
                            anchor
                        )
                    );

                    $('.requisites-card').prepend(template);

                }

                $(form).find('#id').val('');
                $(form)[0].reset();
                $(".requisites h5 .glyphicon-minus").click();

            },
            error: function (data) {
                console.log(data);
            }
        });
        return false;
    });

});