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
    
    freeUsersIds.forEach(function(id, key) {
        if($("#adding-user-"+id).hasClass("show"))
        {
            $("#add-team-form").append("<input type='hidden' name='addingUser"+key+"' value='"+id+"'>")
        }
    });
    

   $("#add-team-form").trigger('submit');
});

$("#close-alert").on('click', function() {
    $(this).parent().removeClass('show alert-visible').css({'opacity': '0', 'display': 'none'});
});

$("#update-team-button").on('click', function() {
    // update team name
    $("#teamName-hidden-update").val($("#teamName").val());
    Obj = Project["teams"];
    ProjectTeams = [];
    
    // convert to array
    Object.keys(Obj).forEach(key => ProjectTeams.push({
        rowid: Obj[key]["rowid"],
        users: Obj[key]["users"]
    }));

    teamId = $("#team-id-update-input").val();

    ProjectTeams.forEach(team => {
        // get teams members ids
        Obj = team["users"];
        users = [];
        Object.keys(Obj).forEach(key => users.push({
            rowid: Obj[key]["rowid"],
        }));

        teamRowid = team["rowid"];
        if(teamId == teamRowid)
        {
            users.forEach(function(user, key) {
                userRowid = user["rowid"];
    
                if($("#freeing-user-"+userRowid).hasClass("show"))
                {
                    // delete from belong_to where fk_user = rowid
                    $("#update-team-form").append("<input type='hidden' name='removingUser"+key+"' value='"+userRowid+"'>");
    
                }    
            });

            // create belong_to where show
            // for free users
            freeUsersIds.forEach(function(userId, key) {
                if(!$("#free-user-"+userId).hasClass("show"))
                {
                    $("#update-team-form").append("<input type='hidden' name='addingUser"+key+"' value='"+userId+"'>");
                }
            });
        }
    });

    $("#update-team-form").trigger('submit');
});

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


function toggleUserToTeam(userRowid)
{
    $("#free-user-"+userRowid).toggleClass("show");
    $("#adding-user-"+userRowid).toggleClass("show");
}


function toggleUserToExistingTeam(userRowid)
{
    $("#freeing-user-"+userRowid).toggleClass("show");
    $("#adding-again-user-"+userRowid).toggleClass("show");
}

function showTeamMembers(teamRowid, teamName)
{
    // check if team is active
    $.ajax({
        async: true,
        url: AJAX_URL+"admin/projectDashboard.php?action=getTeamActive&teamId="+teamRowid,
        success: function(data) {
            
            teamActive = data;
            teamActive = teamActive.replace("\"", '').replace("\"", '');
            teamActive = parseInt(teamActive)

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