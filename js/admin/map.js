var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

// hide body overflow
$("body").css('overflow-y', 'hidden');

init();
initCol();

// NEW COLUMN
$("#add-column-btn").click(function() {
    $("#archive-btn").removeClass('show');
    $("#add-column-form").toggleClass('show');
    $("#task-details").removeClass('show');
    $("#column-details").removeClass('show');
    $(this).toggleClass('show');
});

$("#cancel-column").click(function() {
    $("#add-column-form").toggleClass('show');
    $("#add-column-btn").toggleClass('show');
    $("#archive-btn").addClass('show');
});

$("#archive-btn").click(function() {
    $("#columns-container").parent().removeClass('show');
    $("#details-section").removeClass('show');
    $("#archive-confirmation").addClass('show');
});

$("#cancel-archive").click(function() {
    $("#columns-container").parent().addClass('show');
    $("#details-section").addClass('show');
    $("#archive-confirmation").removeClass('show');
});

$("#close-details").click(function() {
    $(this).removeClass('show');
    $("#details-section").removeClass('show');
    $("#left-section").removeClass('col-sm-8 col-md-9 col-lg-10').addClass('col-12');
    $("#open-right-section").addClass('show');
});

$("#open-right-section").click(function() {
    $("#close-details").addClass('show');
    $("#details-section").addClass('show');
    $("#left-section").removeClass('col-12').addClass('col-sm-8 col-md-9 col-lg-10');
    $(this).removeClass('show');
});

// switch button
$("#members-switch-button").off('click').click(function() {
    $(".members-label").toggleClass('show');
    $("#team-members-container").toggleClass('show');
    $("#task-members-container").toggleClass('show');

    $("#desattribute-member-button").removeClass('show');
    $("#attribute-member-button").removeClass('show');
    $("#attributed-member-button").removeClass('show');
});

$(".close-alert").click(function() {
    $(this).parent().removeClass('show alert-visible');

    // decrement notification count
    notificationCount--;
    $(".notificationCount").text(notificationCount + "+");
});

$("#add-column-form").find('#create-column').click(function() {
    $("#archive-btn").addClass('show');
    $("#add-column-form").removeClass('show');

    columnName = $("#columnName-input").val();
    columnName = columnName.length == 0 ? " " : columnName;

    columnNameInput     = $(this).prev();
    btnColumnForm       = $(this);
    
    $("#loading-modal").modal('show');
    // insert in bdd
    $.ajax({
        async: true,
        url: AJAX_URL+"admin/map.php?action=addColumn&columnName="+columnName+"&teamId="+teamId+"&projectId="+projectId,
        success: function(result) {
            $.ajax({
                async: true,
                url: AJAX_URL+"admin/map.php?action=getLastColumnId"+"&teamId="+teamId+"&projectId="+projectId,
                success: function(data) {
                    result = $.parseJSON(result);
                    if(result.success)
                    {
                        columnId = data;    
                        columnId = columnId.replace("\"", '').replace("\"", '');

                        columnNameInput.val("");
                        btnColumnForm.addClass('show');

                        var append = [
                            "<div class='project-column'>",
                                "<input class='columnId-input' type='hidden' value='"+columnId+"'>",
                                "<div class='column-title text-center'>",
                                    "<div class='row'>",
                                        "<div class='col-7 pt-3 ps-2 ms-3 pe-0 column-title-name'>",
                                            "<div class='overflow-x'>",
                                                "<b class='column-title-text'>",
                                                    columnName,
                                                "</b>",
                                            "</div>",
                                        "</div>",
                                        "<ul class='offset-1 col-3 pt-2 ps-0'>",
                                            "<li class='me-2'>",
                                                "<button class='btn btn-outline-dark add-task-btn'>",
                                                    "New",
                                                "</button>",
                                            "</li>",
                                            "<li class='mt-2 me-2'>",
                                                "<button class='btn btn-outline-danger delete-col-btn'>",
                                                    "Delete",
                                                "</button>",
                                            "</li>",
                                        "</ul>",
                                    "</div>",
                                "</div>",
                                "<div class='column-content'>",
                                "</div>",
                            "</div>",
                        ].join("");
                    
                        $("#columns-container").append(append);
                        $("#add-column-btn").toggleClass('show'); 
                        initTask();
                        initCol();
                    }
                    
                    $("#loading-modal").modal('hide');                                            
                }
            });
        }
    });
});


function init()
{
    var taskId;
    var taskNote;
    var commentId;
    var memberId;

    $(".task-bubble").off('hover').hover(function() {
        $(this).css({"background-color": "#eeeff0", "cursor": "pointer"});
        $(this).children().css({"background-color": "#eeeff0", "cursor": "pointer"});
    }, function() {
        $(this).css({"background-color": "white", "cursor": "default"});
    $(this).children().css({"background-color": "white", "cursor": "default"});
    });

    $(".task-bubble-input").off('focus').focus(function() {
        // disable all task click during loading except the clicked task
        $(this).addClass("not");
        $(".task-bubble-input:not(.not)").prop('disabled', true);
        $(this).removeClass("not");

        // hide team-members (affectation) during loading
        $(".team-member").removeClass('show');

        // show the right details column and content
        $("#column-details").removeClass('show');
        $("#add-column-btn").addClass('show');
        $("#archive-btn").addClass('show');

        $(".task-buttons-container").removeClass('show');

        $("#check-comment-btn").removeClass('show');
        $("#delete-comment-btn").removeClass('show');

        $("#task-comment-container").children().remove();
        $("#task-members-container").children().remove();

        // members buttons
        $("#attributed-member-button").removeClass('show');
        $("#attribute-member-button").removeClass('show');
        $("#desattribute-member-button").removeClass('show');

        // var title = $(this).val();
        // $("#task-title").val(title);

        $(this).parent().next().find(".task-buttons-container").first().addClass('show');

        columnId = $(this).parents('.project-column').first().find('.columnId-input').val();
        taskDiv = $(this).parents('.task').first();
        taskId = taskDiv.find(".taskId-input").val();
        
        //up task
        $("#up-task-btn").off('click').click(function() {
            $("#loading-modal").modal('show');
            $.ajax({
                async: true,
                url: AJAX_URL+"admin/map.php?action=upTask&taskId="+taskId+"&columnId="+columnId+"&teamId="+teamId+"&projectId="+projectId,
                success: function (data) {
                    
                    if(taskDiv.prevAll('.task').first().length > 0)
                    {
                        prevTask = taskDiv.prevAll('.task').first();
                        taskDiv.insertBefore(prevTask);
                    }
                    $("#loading-modal").modal('hide');
                }
            });
        });
        
        // down task
        $("#down-task-btn").off('click').click(function() {
            $("#loading-modal").modal('show');
            $.ajax({
                async: true,
                url: AJAX_URL+"admin/map.php?action=downTask&taskId="+taskId+"&columnId="+columnId+"&teamId="+teamId+"&projectId="+projectId,
                success: function (data) {
                    if (taskDiv.nextAll('.task').first().length > 0)
                    {
                        nextTask = taskDiv.nextAll('.task').first(); 
                        taskDiv.insertAfter(nextTask);
                    }
                    $("#loading-modal").modal('hide');
                }
            });
        });

        // load task comments
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=getTaskComments&taskId="+taskId+"&teamId="+teamId+"&projectId="+projectId,
            success: function (data) {
                data = $.parseJSON(data)
                if(data)
                {
                    comments = data.comments;
                    l = comments.length;
                    for(i = 0; i < l; i++)
                    {
                        note        = comments[i].note;
                        note        = note == null ? '' : note;
                        admin       = comments[i].admin;
                        author      = comments[i].author;
                        authorId    = comments[i].fk_user;
                        tms         = comments[i].tms;

                        var prepend = [
                            "<div class='task-comment-div'>",
                                "<input type='hidden' class='comment-task-id' value='"+comments[i].rowid+"'>",
                                "<input type='hidden' class='comment-author-id' value='"+authorId+"'>",
                                "<textarea "
                        ].join("");
                        
                        if(comments[i].isAuthor == false)
                        {
                            prepend += "readonly ";
                        }

                        prepend += [
                            "class='mt-3 card task-comment px-2 pt-3 text-center' name='' cols='30' rows='3'>",
                                note,
                            "</textarea>",
                            "<div class='mt-1'>",
                                "<div class='row w-100' style='margin-left:0'>",
                                    "<div class='col-7 px-0'>",
                                        "<button class='btn w-100 btn-unactive "
                        ].join("");
                        
                        if(admin == 1)
                        { 
                            prepend += 'btn-outline-danger'; 
                        }
                        else
                        { 
                            prepend += 'btn-outline-classic'; 
                        } 
                        
                        prepend += [
                                        " comment-author'>",
                                            author,
                                        "</button>",
                                    "</div>",
                                    "<div class='col-5 pe-0'>",
                                        "<span class='w-100' style='color:grey;font-size:small'>",
                                            tms,
                                        "</span>",
                                    "</div>",
                                "</div>",
                            "</div>"
                        ].join("");

                        $("#task-comment-container").prepend(prepend);
                    }
                    initComment();
                }
            }
        });

        // refresh team members & task members display
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=getTeamMembers&taskId="+taskId+"&teamId="+teamId+"&projectId="+projectId,
            success: function (data) {
                // clean team & task members containers
                $("#team-members-container").children().remove()
                $("#task-members-container").children().remove()

                data = $.parseJSON(data);

                // users that are attributed to the task
                affectedUsers = data.affectedUsers;
                affectedUsers.forEach(user => {

                    // task-member thumbnail
                    var prepend = [
                        "<div class='task-member'>",
                            "<input type='hidden' class='task-member-id' value='"+user.rowid+"'>",
                            "<input class='w-90 sticker mx-auto mt-2 hover text-center form-control "+(idUser == user.rowid ? "underline" : "")+"' readonly value='"+user.lastname+" "+user.firstname +"'>",
                        "</div>"
                    ].join("");

                    $("#task-members-container").prepend(prepend);

                    
                    // team-member thumbnail
                    var append = [
                        "<div class='team-member collapse show'>",
                            "<input type='hidden' class='team-member-id' value='"+user.rowid+"'>",
                            "<input type='text' class='affected-team-member form-control sticker mx-auto mt-2 hover text-center w-90 "+(idUser == user.rowid ? "underline" : "")+"' readonly  value='"+ user.lastname + ' ' + user.firstname +"'>",
                        "</div>"
                    ].join("");

                    $("#team-members-container").append(append);;

                });

                // users that are not attributed to the task
                freeUsers = data.freeUsers;
                freeUsers.forEach(user => {

                    var append = [
                        "<div class='team-member collapse show'>",
                            "<input type='hidden' class='team-member-id' value='"+user.rowid+"'>",
                            "<input type='text' class='form-control sticker mx-auto mt-2 hover text-center w-90 "+(idUser == user.rowid ? "underline" : "")+"' readonly  value='"+ user.lastname + ' ' + user.firstname +"'>",
                        "</div>"
                    ].join("");

                    // create element in DOM
                    $("#team-members-container").append(append);;
                });


                // Attach event listeners to new DOM elements

                $(".team-member").off('click').click(function() {
                    // save the id of the clicked member to attribute him later
                    memberId = $(this).find('.team-member-id').val();
                    memberId = memberId.replace("\"", ' ').replace("\"", ' ');
                    memberName = $(this).find('.sticker').val();

                    if($(this).find(".form-control").hasClass("affected-team-member"))
                    {
                        // can't attribute users that are already attributed
                        $("#attributed-member-button").addClass('show');
                        $("#attribute-member-button").removeClass('show');
                    }
                    else
                    {
                        // can attribute free users
                        $("#attribute-member-button").addClass('show');
                        $("#attributed-member-button").removeClass('show');
                    }

                })

                $(".task-member").off('click').click(function() {
                    // save the id of the clicked member to desattribute him later
                    memberId = $(this).find('.task-member-id').val();
                    memberId = memberId.replace("\"", ' ').replace("\"", ' ');

                    $("#desattribute-member-button").addClass('show');
                })

                $("#attribute-member-button").off('click').click(function() {
                    btn = $(this);
                    // if the user is not attributed
                    if($(".task-member-id[value='"+memberId+"']").length == 0)
                    {
                        $.ajax({
                            async: true,
                            url: AJAX_URL+"admin/map.php?action=attributeMemberToTask&taskId="+taskId+"&memberId="+memberId+"&teamId="+teamId+"&projectId="+projectId,
                            success: function(data) {
                                // disable like appearance on team member input
                                $(".team-member-id[value='"+memberId+"']").nextAll(".form-control").first().addClass('affected-team-member');
                                
                                btn.removeClass('show');

                                var prepend = [
                                "<div class='task-member'>",
                                    "<input type='hidden' class='task-member-id' value='"+memberId+"'>",
                                    "<input class='w-90 sticker mx-auto mt-2 hover text-center form-control' readonly value='"+memberName+"'>",
                                "</div>"
                                ].join("")

                                $("#task-members-container").prepend(prepend);

                                $(".task-member").off('click').click(function() {
                                    memberId = $(this).find('.task-member-id').val();
                                    memberId = memberId.replace("\"", ' ').replace("\"", ' ');
                        
                                    $("#desattribute-member-button").addClass('show');
                                })
                            }
                        });
                    }
                });

                $("#desattribute-member-button").off('click').click(function() {
                    btn = $(this);
                    $.ajax({
                        async: true,
                        url: AJAX_URL+"admin/map.php?action=desattributeMemberToTask&taskId="+taskId+"&memberId="+memberId+"&teamId="+teamId+"&projectId="+projectId,
                        success: function(data) {
                            // remove disable like appearance on team member input
                            $(".team-member-id[value='"+memberId+"']").nextAll(".form-control").first().removeClass('affected-team-member')
                            
                            btn.removeClass('show');
                            $(".task-member-id[value='"+memberId+"']").parent().remove();
                        }
                    });
                });

                // display team-members
                $(".team-member").addClass('show');

                // re-enable tasks click
                $(".task-bubble-input").prop('disabled', false);
            }
        });

        $("#task-details").addClass("show");
    });
    
    initTask();

    $("#add-comment-btn").off('click').click(function() {
        $("#loading-modal").modal('show');
        // INSERT INTO tasks_comments with empty note
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=addTaskComment&taskId="+taskId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                
                commentId = data;
                commentId = commentId.replace("\"", ' ').replace("\"", ' ');

                var prepend = [
                    "<div class='task-comment-div'>",
                        "<input type='hidden' class='comment-task-id' value='"+commentId+"'>",
                        "<input type='hidden' class='comment-author-id' value='"+idUser+"'>",
                        "<textarea class='mt-3 card task-comment px-2 pt-3 text-center' name='' cols='30' rows='3'></textarea>",
                        "<div class='d-flex justify-content-start mt-1'>",
                            "<button class='btn btn-outline-danger comment-author'>",
                                username,
                            "</button>",
                        "</div>",
                    "</div>"
                ].join("");

                $("#task-comment-container").prepend(prepend)
                $("#loading-modal").modal('hide');

                initComment();
            }
        });
    });

    initComment();
}

function initTask()
{
    $(".add-task-btn").off('click').click(function() {
        $("#add-column-btn").addClass('show');
        $("#archive-btn").addClass('show');
        // HTML CREATE NEW TASK
        addTaskBtn = $(this);
        columnId = addTaskBtn.parents(".column-title").prev().val();
        // INSERT NEW TASK IN BDD
        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=addTask&columnId="+columnId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                $.ajax({
                    async: true,
                    url: AJAX_URL+"admin/map.php?action=getLastTaskId"+"&teamId="+teamId+"&projectId="+projectId,
                    success: function(data) {
                        data = $.parseJSON(data);
                        
                        taskId = data.rowid;
                        taskId = taskId.replace("\"", '').replace("\"", '');

                        var prepend = [
                            "<div class='task'>",
                                "<input class='taskId-input' type='hidden' value='"+taskId+"'>",
                                "<button class='btn btn-outline-danger disabled line-height-40 mt-2 ms-2 px-0 w-50 overflow-x'>",
                                    username,
                                "</button>",
                                "<div class='task-bubble pt-2 mb-1 mt-1 mx-2'>",
                                    "<textarea class='task-bubble-input text-center'></textarea>",
                                "</div>",
                                "<div class='d-flex justify-content-between pe-2 ps-2'>",
                                    "<div class='collapse mx-auto task-buttons-container'>",
                                        "<i class='bi bi-check-lg btn btn-outline-success task-check'></i>",
                                        "<i class='bi bi-trash ms-1 btn btn-outline-danger task-delete'></i>",
                                        "<i class='bi bi-caret-left-fill ms-1 btn btn-outline-dark arrow-img-btn task-to-left'></i>",
                                        "<i class='bi bi-caret-right-fill ms-1 btn btn-outline-dark arrow-img-btn task-to-right'></i>",
                                        "<i class='bi bi-archive-fill task-archive ms-1 me-1 btn btn-outline-danger'></i>",
                                    "</div>",
                                "</div>",
                            "</div>"
                        ].join("");

                        addTaskBtn.parents(".column-title").next().prepend(prepend);
                        $("#loading-modal").modal('hide');

                        init();
                    }
                });
            }
        });
    });

    $(".task-check").off('click').click(function() {

        $(this).parent().removeClass('show');

        taskName = $(this).parents('.task').find('.task-bubble').first().find(".task-bubble-input").val();

        // taskId = $(this).prevAll(".taskId-input").first().val();
        taskId = $(this).parents(".task").find(".taskId-input").first().val();

        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=updateTask&taskId="+taskId+"&taskName="+taskName+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                
                $("#loading-modal").modal('hide');
            }
        });
    });

    $(".task-delete").off('click').click(function() {
        $("#add-column-btn").addClass('show');
        $("#archive-btn").addClass('show');

        taskId = $(this).parents(".task").first().find(".taskId-input").val();
        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=deleteTask&taskId="+taskId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                
                $("#loading-modal").modal('hide');
            }
        });

        task = $(this).parents(".task").first();
        // remove task html
        task.remove();

        $("#task-details").removeClass('show');
    });

    $(".task-to-left").click(function() {
        // update fk_column in bdd
        task        = $(this).parents(".task");
        taskId      = task.find(".taskId-input").first().val();
        newColumn   = task.parents(".project-column").prevAll(".project-column").first();

        updateTaskColumn(task, taskId, newColumn);
    });

    $(".task-to-right").click(function() {
        // update fk_column in bdd
        task        = $(this).parents(".task");
        taskId      = task.find(".taskId-input").first().val();
        newColumn   = task.parents(".project-column").nextAll(".project-column").first();

        updateTaskColumn(task, taskId, newColumn);
    });

    $(".task-archive").click(function() {
        // update task active
        task        = $(this).parents(".task");
        taskId      = task.find(".taskId-input").first().val();

        $("#loading-modal").modal('show');

        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=archiveTask&taskId="+taskId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                // remove task html
                task.remove();
                $("#loading-modal").modal('hide');
            }
        });
    })
}

function initCol()
{
    var columnId;

    $(".column-title-name").off('click').click(function() {
        $("#task-details").removeClass('show');
        $("#add-column-form").removeClass('show');
        $("#column-details-check-btn").removeClass('visible').addClass('invisible');
        
        $("#add-column-btn").addClass('show');
        $("#archive-btn").addClass('show');
        $("#column-details").addClass('show');
        $("#column-title").val($(this).find(".column-title-text").first().text());
        if($("#column-title").val() === "Open" || $("#column-title").val() === "Closed" ) {
            $("#left-column-btn").addClass('collapse');
            $("#right-column-btn").addClass('collapse');
            $("#column-title").prop("disabled" ,true);
        } else {
            $("#left-column-btn").removeClass('collapse');
            $("#right-column-btn").removeClass('collapse');
            $("#column-title").prop("disabled" ,false);
        }

        columnId = $(this).parents('.column-title').first().prevAll('.columnId-input').first().val();
    });


    $(".delete-col-btn").off('click').click(function() {
        // GET COLUMN ID
        columnId = $(this).parents(".column-title").prev().val();
        // DELETE HTML COLUMN
        $(this).parents(".project-column").remove();
        
        $("#loading-modal").modal('show');

        // delete the column in db
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=deleteColumn&columnId="+columnId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                $("#column-details").removeClass('show');
                $("#loading-modal").modal('hide');
            }
        });
    });

    $("#left-column-btn").off('click').click(function() {
        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=leftColumn&columnId="+columnId+"&teamId="+teamId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                data = $.parseJSON(data);
                if(data) {
                    column = $(".columnId-input[value='"+columnId+"']").parents('.project-column').first()
                    column.insertBefore(column.prevAll('.project-column').first());
                }
                $("#loading-modal").modal('hide'); 
            }
        })
    });

    $("#right-column-btn").off('click').click(function() {
        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=rightColumn&columnId="+columnId+"&teamId="+teamId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                data = $.parseJSON(data);
                if(data) {
                    column = $(".columnId-input[value='"+columnId+"']").parents('.project-column').first();
                    column.insertAfter(column.nextAll(".project-column").first());
                }
                $("#loading-modal").modal('hide');
            }
        })
    });

    $("#column-details-delete-btn").off('click').click(function() {
        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=deleteColumn&columnId="+columnId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                $(".columnId-input[value='"+columnId+"']").parents('.project-column').first().remove();
                $("#column-details").removeClass('show');
                $("#loading-modal").modal('hide');
            }
        });
    });

    $("#column-details-check-btn").off('click').click(function() {
        $("#loading-modal").modal('show');
        $("#column-details-check-btn").removeClass('visible').addClass('invisible');
        columnName = $("#column-title").val();
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=updateColumn&columnId="+columnId+"&columnName="+columnName+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                $("#column-details-check-btn").removeClass('show');
                $(".columnId-input[value='"+columnId+"']").nextAll('.column-title').first().find('.column-title-text').first().text(columnName);
                $("#loading-modal").modal('hide');
            }
        });
    });

    $("#column-title").off('focus').focus(function() {
        $("#column-details-check-btn").addClass('visible').removeClass('invisible');
    });
}

function initComment()
{
    var taskNote = '';
    var commentId;

    $(".task-comment").off('focus').focus(function() {
        
        commentAuthorId = $(this).prevAll('.comment-author-id').first().val();
        commentAuthorId = commentAuthorId.replace("\"", ' ').replace("\"", ' ');

        if(commentAuthorId == idUser)
        {
            $("#check-comment-btn").addClass('show');
        }
        else
        {
            $("#check-comment-btn").removeClass('show');
        }

        $("#delete-comment-btn").addClass('show');
        $("#add-comment-btn").removeClass('show');

        commentId = $(this).prevAll(".comment-task-id").first().val();
        commentId = commentId.replace("\"", ' ').replace("\"", ' ');
    });

    $(".task-comment").off('keyup').keyup(function() {
        taskNote = $(this).val();
    });

    $("#check-comment-btn").off('click').click(function() {
        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=updateTaskNote&commentId="+commentId+"&taskNote="+taskNote+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {  
                $("#check-comment-btn").removeClass('show');
                $("#delete-comment-btn").removeClass('show');
                $("#add-comment-btn").addClass('show');
                $("#loading-modal").modal('hide');
            }
        });
    });

    $("#delete-comment-btn").off('click').click(function() {
        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=deleteTaskNote&commentId="+commentId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                $("#check-comment-btn").removeClass('show');
                $("#delete-comment-btn").removeClass('show');
                $("#add-comment-btn").addClass('show');

                $(".comment-task-id[value='"+commentId+"']").parents('.task-comment-div').first().remove();
                $("#loading-modal").modal('hide');
            }
        });
    });

}

function updateTaskColumn(task, taskId, newColumn)
{
    newColumnId = newColumn.length == 0 ? false : newColumn.find(".columnId-input").first().val();

    if(newColumnId)
    {
        $("#loading-modal").modal('show');
        $.ajax({
            async: true,
            url: AJAX_URL+"admin/map.php?action=taskColumnUpdate&taskId="+taskId+"&columnId="+newColumnId+"&teamId="+teamId+"&projectId="+projectId,
            success: function(data) {
                // prepend html from column a to column b
                task.prependTo(newColumn.find(".column-content").first());
                $("#loading-modal").modal('hide');
            }
        });
    }
}