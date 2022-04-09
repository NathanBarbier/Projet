$("#delete-organization-button").on('click', function() {
    $("#delete-organization-div").addClass('show');
});

$("#cancel-delete-btn").on('click', function() {
    $("#delete-organization-div").removeClass('show');
});

$("#password-update-btn").on('click', function() {
    $("#password-update-form").addClass('show');
    $("#email-update-form").removeClass('show');
});

$("#cancel-password-update").on('click', function() {
    $("#password-update-form").removeClass('show');
});

$("#email-info-btn").on('click', function() {
    $("#email-update-form").addClass('show');
    $("#password-update-form").removeClass('show');
});

$("#cancel-email-update").on('click', function() {
    $("#email-update-form").removeClass('show');
});

$("#delete-account-btn").on('click', function() {
    $("#current-projects-col").removeClass('show');

    // Afficher alert de confirmation de deletion
    $("#account-delete-confirmation").addClass('show');
});

$("#cancel-account-deletion").on('click', function() {
    $("#current-projects-col").addClass('show');

    $("#account-delete-confirmation").removeClass('show');
});

$("#update-profile-submit").on('click', function() {
    $("#profile-form").trigger('submit');
}); 