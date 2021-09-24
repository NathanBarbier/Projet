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
        // hasClass = $("free-user-"+i).hasClass("show");

        // console.log(hasClass);

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
};


function toggleUserToTeam(userRowid)
{
    $("#free-user-"+userRowid).toggleClass("show");
    $("#adding-user-"+userRowid).toggleClass("show");
}
