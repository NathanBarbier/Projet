// event listeners on :
initProjectDeleteButton(); // project delete button (on click)
initLoadMoreLink();        // load more link        (on click)

// cancel delete
$("#cancel-delete-btn").on('click', function() {
    $("#delete-project-modal").modal('hide');
});

// search bar
var searchIsEnabled = true;

$('#search-bar').on('input', function() {
    if(searchIsEnabled)
    {
        // empty all projects
        $('#projects-container').empty();

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
                url: AJAX_URL+"admin/projectList.php?action=search&query="+query,
                success: function (data) {
                    var projects = JSON.parse(data);

                    // if there are at least one project
                    if(projects)
                    {
                        var append = ''

                        projects.forEach(project => {

                            append += [
                                '<div class="row sticker mx-2 mt-4 pb-2 h-auto d-flex align-items-center">',
                                    '<div class="col-3 text-center mx-auto"><b>'+ project.name +'</b></div>',
                                    '<div class="col-2 text-center mx-auto"><b>'+ project.type +'</b></div>',
                                    '<div class="col-3 text-center mx-auto"><b>',
                            ].join('');

                            if(project.active == 1)
                            {
                                append += '<span style="color:green">Ouvert</span>';
                            }
                            else
                            {
                                append += '<span style="color:red">Archivé</span>';
                            }
                                    
                            append += [     
                                    '</b></div>',
                                    '<div class="col-3 text-center mx-auto">',
                                        
                                        '<input type="hidden" class="project-id" value="'+ project.rowid +'">',
                                        
                                        '<div class="row">',
                                            '<div class="col-12 col-lg-6">',
                                                '<a href="'+CONTROLLERS_URL+'admin/projectDashboard.php?idProject='+ project.rowid +'" class="w-100 custom-button info btn-sm mt-1 px-1 pt-2 double-button-responsive">',
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

                        // create projects DOM elements
                        $('#projects-container').append(append);

                        // attach event listeners on new created DOM elements
                        initProjectDeleteButton();
                    }

                    // re-enable search
                    searchIsEnabled = true;

                    // hide the loading modal
                    $('#loading-modal').modal('hide');

                    // because the loading modal force unfocus
                    $('#search-bar').focus();
                }
            });
        }
        else
        {
            // append the projects that are already loaded if the pattern doesn't match any project
            $('#projects-container').append(loadedProjects);
            // event listeners
            initLoadMoreLink();
        }
    }
});

var loadedProjects = $('#projects-container').children();

// event listeners on the load more link
function initLoadMoreLink()
{
    $("#load-more").off('click').on('click', function() {
        $("#loading-modal").modal('show');

        // hide the loadmore link
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
        
                    // create projects DOM elements
                    $('#projects-container').append(append);

                    // atache event listener
                    initProjectDeleteButton();

                    if(projects.length > 10)
                    {
                        var append = [
                            '<div id="load-more-line" class="radius text-center mx-auto mt-2 border hover" style="height: 5vh;width:33%;font-size: x-large">',
                                '<a id="load-more" type="button" class="custom-link py-0" style="width: 100%; height: 100%">Load more</a>',
                            '</div>',
                        ].join('');

                        // create 'loadmore' link DOM elements
                        $('#projects-container').append(append);
                        
                        // attach event listener
                        initLoadMoreLink();
                    }
                    
                    // store in a Js variable the projects that are already loaded
                    loadedProjects = $('#projects-container').children();
                }
                // hide the loading modal
                $("#loading-modal").modal('hide');
            }
        });
    });
}

function initProjectDeleteButton()
{
    $(".del-project-btn").off('click').on('click', function() {
        $('#delete-project-modal').modal('show');
    
        var projectId = $(this).parents(".row").first().prevAll(".project-id").first().val();
        var url = $("#delete-project-btn-conf").attr('href');
    
        if(projectId.length > 0 && url.length > 0) 
        {
            url = url.split('?')[0];
            url += "?action=deleteProject&projectId="+projectId
            $("#delete-project-btn-conf").attr('href', url);
        }
    });
}