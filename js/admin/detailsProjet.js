$("#create-switch-button").click(function() {
    switchTeamApp();
});
$("#update-switch-button").click(function() {
    switchTeamApp();
});

$("#create-team-button").click(function() {
    $("#teamName-hidden-create").val($("#teamName").val());

    freeUsersIds.forEach(function(id, key) {
        if($("#adding-user-"+id).hasClass("show"))
        {
            $("#add-team-form").append("<input type='hidden' name='addingUser"+key+"' value='"+id+"'>")
        }
    });


    $("#add-team-form").submit();
});

$("#update-team-button").click(function() {
    $("#teamName-hidden-update").val($("#teamName").val());

    // update team name
    $("#teamName-hidden-update").val($("#teamName").val());

    CurrentProjectTeams = CurrentProject["teams"];

    teamId = $("#team-id-update-input").val();

    CurrentProjectTeams.forEach(team => {
        members = team["members"];
        teamRowid = team["rowid"];
        if(teamId == teamRowid)
        {
            members.forEach(function(member, key) {
                userRowid = member["rowid"];
    
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

    $("#update-team-form").submit();
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

    l = projectTeamsIds.length;
    for(i = 0; i < l; i++)
    {
        if($(".team-members-"+projectTeamsIds[i]).hasClass("show"))
        {
            $(".team-members-"+projectTeamsIds[i]).removeClass("show");
        }
    }
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
    $("[id^=freeing-user-]").removeClass("show");
    $("[id^=adding-again-user-]").removeClass("show");

    teamIds = [];
    // add onclick showTeamMembers()
    $("[id^=team-sticker-]").each(function(index, element) {
        elementTeamRowid = $(element).attr('id');
        elementTeamRowid = elementTeamRowid.split("-");
        elementTeamRowid = elementTeamRowid.pop();

        console.log(elementTeamRowid, teamRowid, teamRowid == elementTeamRowid);

        $(element).removeAttr("onclick");
        if(elementTeamRowid != teamRowid)
        {
            teamIds.push(elementTeamRowid);
        }
    });

    $(teamIds).each(function(index, element) {
        elementTeamName = $("#team-sticker-"+element).children().first().html();
        $("#team-sticker-"+element).attr('onclick', "showTeamMembers("+element+",'"+elementTeamName+"')");
    })

    $(".team-members-"+teamRowid).addClass("show");

    // remplir input avec id de l'équipe a update
    $("#team-id-update-input").val(teamRowid);

    $("#teamName").val(teamName);
}