$("#delete-organization-button").click(function() {
    $("#delete-organization-div").addClass('show');
});

$("#cancel-delete-btn").click(function() {
    $("#delete-organization-div").removeClass('show');
});

$("#password-update-btn").click(function() {
    $("#password-update-form").addClass('show');
});

$("#cancel-password-update").click(function() {
    $("#password-update-form").removeClass('show');
});