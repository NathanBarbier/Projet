// On user delete click
initUserDeletebutton();

$('.role-select').on('change', function() {
    var role = $(this).val();

    if(role == '1') 
    {
        $(this).css('color', 'red');
    } 
    else if(role == '0')
    {
        $(this).css('color', 'grey');
    }
});

// On user delete cancel
$("#cancel-user-delete").on('click', function() {
    $("#user-delete-modal").modal('hide');

    $("#delete-user-id").val('');
});

// search bar
var searchIsEnabled = true;

$('#search-bar').on('input', function() {
    if(searchIsEnabled)
    {
        // empty all projects
        $('#tbody-users').empty();

        // the searched string
        var query = $(this).val().toString();

        if(query.length > 0)
        {
            $('#loading-modal').modal('show');

            // to avoid server overcharge
            searchIsEnabled = false;

            // display project that match pattern
            $.ajax({
                async: true,
                url: AJAX_URL+"admin/associateList.php?action=search&query="+query,
                success: function (data) {
                    var associates = JSON.parse(data);

                    if(associates)
                    {
                        var append = ''

                        l = associates.length;

                        // to avoid DOM overloading limit to 100
                        for(var i = 0; (i < l && i < 100); i++)
                        {
                            var associate = associates[i];

                            append += [
                                '<tr class="text-center">',
                                    '<form id="user-update-form-'+ associate.rowid +'" method="POST" action="'+CONTROLLERS_URL+'admin/associateList.php?action=userUpdate"></form>',
                                        '<td class="align-middle">',
                                            '<div class="row">',
                                                '<div class="col-12 col-sm-12 col-md-9 mx-auto">',
                                                    '<input class="form-control mb-1 text-center w-100 mx-auto" form="user-update-form-'+ associate.rowid +'" value="'+ associate.lastname +'" type="text" name="lastname" placeholder=" " required>',
                                                '</div>',
                                            '</div>',
                                        '</td>',
                                        
                                        '<td class="align-middle">',
                                            '<div class="row">',
                                                '<div class="col-12 col-sm-12 col-md-9 mx-auto">',
                                                    '<input class="form-control mb-1 text-center w-100 mx-auto" form="user-update-form-'+ associate.rowid +'" value="'+ associate.firstname +'" type="text" name="firstname" placeholder=" " required>',
                                                '</div>',
                                            '</div>',
                                        '</td>',
                    
                                        '<td class="align-middle">',
                                            '<div class="row">',
                                                '<div class="col-12 col-sm-12 col-md-9 mx-auto">',
                                                    '<input class="form-control mb-1 text-center w-100 mx-auto" form="user-update-form-'+ associate.rowid +'" value="'+ associate.email +'" type="text" name="email" placeholder=" " required>',
                                                '</div>',
                                            '</div>',
                                        '</td>',
                    
                                        '<td class="align-middle">',
                                            '<b>',
                            ].join('');

                            if(associate.admin == 1)
                            {
                                append += '<span style="color:red">Administrateur</span>';
                            }
                            else
                            {
                                append += '<span style="color:grey">Utilisateur</span>';
                            }

                            if(associate.rowid == idUser)
                            {
                                append += ' (Vous)'
                            }

                            append += [
                                        '</b>',
                                    '</td>',
                
                                    '<td class="align-middle">',
                                        '<div class="mt-4 row">',
                                            '<div class="col-12 col-sm-12 col-md-6 pb-2">',
                                                '<button type="button" id="user-delete-btn-'+ associate.rowid +'" class="w-100 custom-button danger double-button-responsive px-1" style="min-width: max-content;">',
                                                    'Supprimer',
                                                '</button>',
                                            '</div>',
                
                                            '<div class="col-12 col-sm-12 col-md-6">',
                                                '<input type="hidden" name="idUser" form="user-update-form-'+ associate.rowid +'" value="'+ associate.rowid +'">',
                                                '<button onclick="document.getElementById(\'user-update-form-'+ associate.rowid +'\').submit()" class="w-100 custom-button double-button-responsive px-1" style="min-width: max-content;">',
                                                    'Mettre à jour',
                                                '</button>',
                                            '</div>',
                                        '</div>',
                                    '</td>',
                                '</tr>',
                            ].join('');
                        }

                        // create users DOM elements
                        $('#tbody-users').append(append);

                        // attach event listeners
                        initUserDeletebutton();
                    }
                    // re-enable search
                    searchIsEnabled = true;

                    $('#loading-modal').modal('hide');

                    // because the loading modal force unfocus
                    $('#search-bar').focus();
                }
            });
        }
        else
        {
            $('#tbody-users').append(loadedAssociates);
            initLoadMoreLink();
        }
    }
});

var loadedAssociates = $('#tbody-users').children();

initLoadMoreLink();

function initLoadMoreLink()
{
    $("#load-more").off('click').on('click', function() {
        $("#loading-modal").modal('show');
    
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/associateList.php?action=loadmore&offset="+offset,
            success: function (data) {
    
                var users = JSON.parse(data);

                // hide the loadmore button
                $('#load-more-line').remove();

                // if there are no users
                if(Array.isArray(users) && users.length > 0)
                {
                    offset += 30;
                    var append = '';

                    users.forEach(user => {
                        append += [
                            "<tr class='text-center'>",
                                "<form id='user-update-form' method='POST' action='" + CONTROLLERS_URL + "admin/associateList.php?action=userUpdate'>",
                                    "<td class='align-middle'>",
                                        "<div class='row'>",
                                            "<div class='col-12 col-sm-12 col-md-9 mx-auto'>",
                                                "<input class='form-control mb-1 text-center w-100 mx-auto' value='" + user.lastname + "' type='text' name='lastname' placeholder='' required>",
                                            "</div>",
                                        "</div>",
                                    "</td>",
                                    
                                    "<td class='align-middle'>",
                                        "<div class='row'>",
                                            "<div class='col-12 col-sm-12 col-md-9 mx-auto'>",
                                                "<input class='form-control mb-1 text-center w-100 mx-auto' value='" + user.firstname + "' type='text' name='firstname' placeholder='' required>",
                                            "</div>",
                                        "</div>",
                                    "</td>",
            
                                    "<td class='align-middle'>",
                                        "<div class='row'>",
                                            "<div class='col-12 col-sm-12 col-md-9 mx-auto'>",
                                                "<input class='form-control mb-1 text-center w-100 mx-auto' value='" + user.email + "' type='text'", "name='email' placeholder='' required>",
                                            "</div>",
                                        "</div>",
                                    "</td>",
            
                                    "<td class='align-middle'>",
                                        "<b>"
                        ].join('');
        
                        if(user.admin == 1)
                        {
                            append += '<span style="color:red">Administrateur</span>';
                        }
                        else
                        {
                            append += '<span style="color:grey">Utilisateur</span>';
                        }
        
                        if(user.rowid == idUser)
                        {
                            append += '&nbsp;(You)';
                        }
            
                        append += [
                                        "</b>",
                                    "</td>",
            
                                    "<td class='align-middle'>",
                                        "<div class='mt-4 row'>",
                                            "<div class='col-12 col-sm-12 col-md-6 pb-2'>",
                                                "<button type='button' id='user-delete-btn-" + user.rowid + "' class='w-100 custom-button danger double-button-responsive px-1' style='min-width: max-content;'>",
                                                    "Supprimer",
                                                "</button>",
                                            "</div>",
            
                                            "<div class='col-12 col-sm-12 col-md-6'>",
                                                "<input type='hidden' name='idUser' value='" + user.rowid + "'>",
                                                "<button onclick='document.getElementById('user-update-form').submit()' class='w-100 custom-button double-button-responsive px-1' style='min-width: max-content;''>",
                                                    "Mettre à jour",
                                                "</button>",
                                            "</div>",
                                        "</div>",
                                    "</td>",
                                "</form>",
                            "</tr>"
                        ].join('');
                    });
        
                    $('#tbody-users').append(append);

                    if(users.length == 30)
                    {
                        var append = [
                            '<tr id="load-more-line">',
                                '<td class="text-center" colspan="5">',
                                    '<a id="load-more" type="button" class="custom-link py-0" style="font-size: 2rem;">Load more</a>',
                                '</td>',
                            '</tr>'
                        ].join('');

                        $('#tbody-users').append(append);
                    }
                    
                    $("#loading-modal").modal('hide');
    
                    initLoadMoreLink();
                }
            }
        });
    });
}

function initUserDeletebutton()
{
    $("[id^='user-delete-btn-']").on('click', function() {
        var idUser = $(this).attr('id').split('user-delete-btn-')[1];
        idUser = parseInt(idUser);
    
        $("#delete-user-id").val(idUser);
    
        $("#user-delete-modal").modal('show');
    });
}