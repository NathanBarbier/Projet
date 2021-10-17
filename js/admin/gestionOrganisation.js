$("#delete-organization-button").click(function() {
    $("#delete-organization-div").addClass('show');
});

$("#cancel-delete-btn").click(function() {
    $("#delete-organization-div").removeClass('show');
});

$("#password-update-btn").click(function() {
    $("#password-update-form").addClass('show');
    $("#email-update-form").removeClass('show');
});

$("#cancel-password-update").click(function() {
    $("#password-update-form").removeClass('show');
});

$("#email-info-btn").click(function() {
    $("#email-update-form").addClass('show');
    $("#password-update-form").removeClass('show');
});

$("#cancel-email-update").click(function() {
    $("#email-update-form").removeClass('show');
});