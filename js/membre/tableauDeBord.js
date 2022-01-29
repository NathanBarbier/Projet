$("#delete-account-btn").click(function() {
    $("#current-projects-col").removeClass('show');

    // Afficher alert de confirmation de deletion
    $("#account-delete-confirmation").addClass('show');
});

$("#cancel-account-deletion").click(function() {
    $("#current-projects-col").addClass('show');

    $("#account-delete-confirmation").removeClass('show');
});