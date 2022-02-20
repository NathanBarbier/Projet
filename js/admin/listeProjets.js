$(".del-project-btn").click(function() {
    $("#del-project-confirmation").addClass('show');

    var projectId = $(this).parents(".row").first().prevAll(".project-id").first().val();
    var url = $("#delete-project-btn-conf").attr('href');

    if(projectId.length > 0 && url.length > 0) 
    {
        url = url.split('?')[0];
        url += "?action=deleteProject&projectId="+projectId
        $("#delete-project-btn-conf").attr('href', url);
    }
});

$("#cancel-delete-btn").click(function() {
    $("#del-project-confirmation").removeClass('show');
});