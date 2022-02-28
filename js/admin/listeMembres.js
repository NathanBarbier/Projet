// On user delete click
$("[id^='user-delete-btn-']").on('click', function() {
    var idUser = $(this).attr('id').split('user-delete-btn-')[1];
    idUser = parseInt(idUser);

    $("#delete-user-id").val(idUser);

    $("#user-delete-modal").modal('show');
});

// On user delete cancel
$("#cancel-user-delete").on('click', function() {
    $("#user-delete-modal").modal('hide');

    $("#delete-user-id").val('');
});