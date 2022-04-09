$("#create-switch-button").on('click', function() {
    switchTeamApp();
    $("#archive-team-button").removeClass("show");
    $("#open-team-button").removeClass("show");
});

$("#update-switch-button").on('click', function() {
    switchTeamApp();
});

$("#create-team-button").on('click', function() {
    $("#teamName-hidden-create").val($("#teamName").val());

   $("#add-team-form").trigger('submit');
});

$("#close-alert").on('click', function() {
    $(this).parent().removeClass('show alert-visible').css({'opacity': '0', 'display': 'none'});
});

$("#update-team-button").on('click', function() {
    // update team name
    $("#teamName-hidden-update").val($("#teamName").val());

    $("#update-team-form").trigger('submit');
});

// search bar
var searchIsEnabled = true;

$('#project-dashboard-search-bar').on('input', function() {
    if(searchIsEnabled)
    {
        // empty all projects
        $('#free-users-container').empty();
        $('#adding-users-container').empty();

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
                url: AJAX_URL+"admin/projectDashboard.php?action=search&query="+query,
                success: function (data) {
                    var users = JSON.parse(data);

                    if(users)
                    {
                        // free users
                        var append = ''

                        users.forEach(user => {
                            append += [
                                '<tr class="collapse show" id="free-user-'+ user.rowid +'">',
                                    '<td>'+ user.lastname +'</td>',
                                    '<td>'+ user.firstname +'</td>',
                                    '<td>',
                                        '<button onclick="addUserToTeam('+ user.rowid +')" class="custom-button success px-2">',
                                            'Ajouter',
                                        '</button>',
                                    '</td>',
                                '</tr>',
                            ].join('');                            
                        });

                        // create users DOM elements
                        $('#free-users-container').append(append);
                        
                        // adding users
                        append = ''

                        users.forEach(user => {
                            append += [
                                '<tr class="collapse" id="adding-user-'+ user.rowid +'">',
                                    '<td style="width: 33.33%">'+ user.lastname +'</td>',
                                    '<td style="width: 33.33%">'+ user.firstname +'</td>',
                                    '<td style="width: 33.33%">',
                                        '<button onclick="removeUserFromTeam('+ user.rowid +')" class="custom-button danger px-2">',
                                            'Retirer',
                                        '</button>',
                                    '</td>',
                                '</tr>',
                            ].join('');                            
                        });

                        // create users DOM elements
                        $('#adding-users-container').append(append);
                    }
                    // re-enable search
                    searchIsEnabled = true;

                    $('#loading-modal').modal('hide');

                    // because the loading modal force unfocus
                    $('#project-dashboard-search-bar').focus();
                }
            });
        }
        else
        {
            $('#free-users-container').append(loadedAssociates);
            $('#adding-users-container').append(loadedFreeUsers);
            initLoadMoreLink();
        }
    }
});

var loadedAssociates = $('#free-users-container').children();
var loadedFreeUsers = $('#adding-users-container').children();

initLoadMoreLink();

function initLoadMoreLink()
{
    $("#load-more").off('click').on('click', function() {
        $("#loading-modal").modal('show');
    
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/projectDashboard.php?action=loadmore&offset="+offset,
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
                            '<tr class="collapse show" id="free-user-'+ user.rowid +'">',
                                '<td>'+ user.lastname +'</td>',
                                '<td>'+ user.firstname +'</td>',
                                '<td>',
                                    '<button onclick="addUserToTeam('+ user.rowid +')" class="custom-button success px-2">',
                                        'Ajouter',
                                    '</button>',
                                '</td>',
                            '</tr>',
                        ].join('');
                    });
        
                    $('#free-users-container').append(append);

                    // adding users
                    append = ''

                    users.forEach(user => {
                        append += [
                            '<tr class="collapse" id="adding-user-'+ user.rowid +'">',
                                '<td>'+ user.lastname +'</td>',
                                '<td>'+ user.firstname +'</td>',
                                '<td>',
                                    '<button onclick="addUserToTeam('+ user.rowid +')" class="custom-button danger px-2">',
                                        'Retirer',
                                    '</button>',
                                '</td>',
                            '</tr>',
                        ].join('');                            
                    });

                    // create users DOM elements
                    $('#adding-users-container').append(append);

                    if(users.length == 30)
                    {
                        var append = [
                            '<tr id="load-more-line">',
                                '<td class="text-center" colspan="3">',
                                    '<a id="load-more" type="button" class="custom-link py-0" style="font-size: 2rem;">Load more</a>',
                                '</td>',
                            '</tr>'
                        ].join('');

                        $('#free-users-container').append(append);
                    }
                    
                    $("#loading-modal").modal('hide');
    
                    initLoadMoreLink();
                }
            }
        });
    });
}

function switchTeamApp()
{
    // display switching button
    $("#create-switch-button").toggleClass("show");
    $("#update-switch-button").toggleClass("show");

    // display team title
    title = $("#team-title").text();
    if(title == "Création des équipes")
    {
        $("#team-title").text("Modification des équipes");
    }
    else
    {
        $("#team-title").text("Création des équipes");
    }

    // display submit button
    $("#create-team-button").toggleClass("show");
    $("#update-team-button").toggleClass("show");
    $("#delete-team-button").toggleClass("show");

    // show current project existing teams
    $("#project-teams-title").toggleClass("show");
    $("#project-teams-div").toggleClass("show");
    
    // empty teamName input on switching mode
    $("#teamName").val("");

    l = freeUsersIds.length;

    for(i = 0; i < l; i++)
    {
        if($("#free-user-"+freeUsersIds[i]).hasClass("show"))
        {
            $("#adding-user-"+freeUsersIds[i]).removeClass("show");
        }

        if($("#adding-user-"+freeUsersIds[i]).hasClass("show"))
        {
            $("#adding-user-"+freeUsersIds[i]).removeClass("show");
            $("#free-user-"+freeUsersIds[i]).addClass("show");
        }
    }

    l = teamIds.length;
    for(i = 0; i < l; i++)
    {
        if($(".team-members-"+teamIds[i]).hasClass("show"))
        {
            $(".team-members-"+teamIds[i]).removeClass("show");
        }
    }

    $("#delete-team-button").attr('href', CONTROLLERS_URL+'admin/projectDashboard.php?action=deleteTeam&idProject='+projectId);
};


function addUserToTeam(userRowid)
{
    // check a removal input exist
    $('[name=removingUser'+userRowid+']').remove();  
    
    // hide the add line
    $('#free-user-'+userRowid).hide();

    // display the removal button
    $('#adding-user-'+userRowid).show();
    $('#team-member-'+userRowid).show();

    // create an input to post
    $('#update-team-form').append("<input type='hidden' name='addingUser"+userRowid+"' value='"+userRowid+"'>");
}


function removeUserFromTeam(userRowid)
{
    // check an adding input exist
    $("[name=addingUser"+userRowid+"]").remove()

    // hide the removal lines
    $('#adding-user-'+userRowid).hide();
    $('#adding-again-user-'+userRowid).hide();

    // display the free-user <tr>
    $('#free-user-'+userRowid).show();

    // create an input to post
    $('#update-team-form').append("<input type='hidden' name='removingUser"+userRowid+"' value='"+userRowid+"'>");
}

function showTeamMembers(teamRowid, teamName)
{
    // check if team is active
    $.ajax({
        async: true,
        url: AJAX_URL+"admin/projectDashboard.php?action=getTeamActive&teamId="+teamRowid,
        success: function(data) {
            teamActive = JSON.parse(data);

            if(teamActive == 0)
            {
                $("#archive-team-button").removeClass("show");
                $("#open-team-button").addClass("show").attr('href', CONTROLLERS_URL+"admin/projectDashboard.php?action=openTeam&teamId="+teamRowid+"&idProject="+projectId);
            }
            else
            {
                $("#archive-team-button").addClass("show").attr('href', CONTROLLERS_URL+"admin/projectDashboard.php?action=archiveTeam&teamId="+teamRowid+"&idProject="+projectId);
                $("#open-team-button").removeClass("show");
            }
        }
    });


    $("[id^=freeing-user-]").removeClass("show");
    $("[id^=adding-again-user-]").removeClass("show");

    $(".team-members-"+teamRowid).addClass("show");

    // remplir input avec id de l'équipe a update
    $("#team-id-update-input").val(teamRowid);
    $("#delete-team-button").addClass('show');
    $("#delete-team-button").attr('href', CONTROLLERS_URL+'admin/projectDashboard.php?action=deleteTeam&teamId='+teamRowid+'&idProject='+projectId);
    $("#map-btn").attr('href', CONTROLLERS_URL+'admin/map.php?projectId='+projectId+'&teamId='+teamRowid);

    $("#teamName").val(teamName);

    teamIds.forEach(element => {
        elementTeamName = $("#team-sticker-"+element).children().first().text();
        $("#team-sticker-"+element).off('click').click(function() {
            showTeamMembers(element, elementTeamName);
        })
    });
}