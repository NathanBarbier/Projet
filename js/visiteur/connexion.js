jQuery(function() {
    // toastr parameters
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "5",
        "hideDuration": "1000",
        "timeOut": "10000",
        // "timeOut": "500000", // for tests
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $("#connexion-form").on('submit', function(event) {
        // to avoid the submit to reload the page
        event.preventDefault();

        var formData = {
            envoi : $('[name="envoi"]').val(),
            email : $('#email').val(),
            password : $('#password').val(),
            rememberMe: $('[name="rememberMe"]').val(),
        };

        $.ajax({
            type: "POST",
            url: AJAX_URL+"visiteur/connexion.php",
            data: formData,
            dataType: "json",
            encode: true,
            success: function(response) {

                if(response.success) 
                {
                    request = 'on=1&type=info&title=bienvenue&msg=connexion';

                    if(response.rights == 'admin') {
                        location.href = CONTROLLERS_URL+'admin/index.php?'+request;
                    } else if(response.rights == 'user') {
                        location.href = CONTROLLERS_URL+'membre/tableauDeBord.php?'+request;
                    } else if(response.rights == 'needConsent') {
                        location.href = ROOT_URL+'index.php';
                    }
                }
                else if(response.error) 
                {
                    Command: toastr["error"](response.error, "Erreur");

                    var prepend = '<i class="bi bi-exclamation-octagon toastr-icon"></i>';
                    $('.toast.toast-error').find('.toast-title').prepend(prepend);
                }
            }
        });
    

    })
});