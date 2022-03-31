// delete project
$(".del-project-btn").on('click', function() {
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

// cancel delete
$("#cancel-delete-btn").on('click', function() {
    $("#del-project-confirmation").removeClass('show');
});

// load more
loadMore();

function loadMore()
{
    $("#load-more").off('click').on('click', function() {
        $("#loading-modal").modal('show');

        // hide the loadmore button
        $('#load-more-line').remove();
    
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/projectList.php?action=loadmore&offset="+offset,
            success: function (data) {
    
                var projects = JSON.parse(data);

                // if there are no users
                if(Array.isArray(projects) && projects.length > 0)
                {
                    offset += 10;
                    var append = '';

                    projects.forEach((project, index) => {
                        
                        // display only the 10 or less projects
                        if(index == 10) {
                            return
                        }

                        if(project.active == 1)
                        {
                            var color = '<span style="color:green">Ouvert</span>';

                        }
                        else
                        {

                            var color = '<span style="color:red">Archivé</span>';
                        }

                        append += [
                            '<div class="row sticker mx-2 mt-4 pb-2 h-auto d-flex align-items-center">',
                                '<div class="col-3 text-center mx-auto"><b>'+ project.name +'</b></div>',
                                '<div class="col-2 text-center mx-auto"><b>'+ project.type +'</b></div>',
                                '<div class="col-3 text-center mx-auto"><b>'+ color +'</b></div>',
                                '<div class="col-3 text-center mx-auto">',
                                    
                                    '<input type="hidden" class="project-id" value="'+ project.rowid +'">',
                                    
                                    '<div class="row">',
                                        '<div class="col-12 col-lg-6">',
                                            '<a href="'+ CONTROLLERS_URL +'admin/projectDashboard.php?idProject='+ project.rowid +'" class="w-100 custom-button info btn-sm mt-1 px-1 pt-2 double-button-responsive">',
                                                'Détails',
                                            '</a>',
                                        '</div>',
                                        '<div class="col-12 col-lg-6">',
                                            '<button class="w-100 del-project-btn custom-button danger btn-sm mt-1 px-1 double-button-responsive">',
                                                'Supprimer',
                                            '</button>',
                                        '</div>',
                                    '</div>',
                                '</div>',
                            '</div>',
                        ].join('');
                    });
        
                    $('#projects-container').append(append);

                    if(projects.length > 10)
                    {
                        var append = [
                            '<div id="load-more-line" class="radius text-center mx-auto mt-2 border" style="height: 5vh;width:33%;font-size: x-large">',
                                '<a id="load-more" type="button" class="custom-link py-0">Load more</a>',
                            '</div>',
                        ].join('');

                        $('#projects-container').append(append);
                    }
    
                    loadMore();
                }
                // hide the loading modal
                $("#loading-modal").modal('hide');
            }
        });
    });
}