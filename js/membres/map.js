init();
initCol();

// NEW COLUMN
$("#add-column-btn").click(function() {
    $("#add-column-form").toggleClass('show');
    $("#task-details").removeClass('show');
    $("#column-details").removeClass('show');
    $(this).toggleClass('show');
});

$("#cancel-column").click(function() {
    $("#add-column-form").toggleClass('show');
    $("#add-column-btn").toggleClass('show');
});

$("#add-column-form").find('#create-column').click(function() {
    $("#add-column-form").removeClass('show');
    columnName = $("#columnName-input").val();
    columnName = columnName.length == 0 ? " " : columnName;

    columnNameInput = $(this).prev();
    btnColumnForm = $(this);
    
    // insert in bdd
    $.ajax({
        async: true,
        url: AJAX_URL+"membres/map.php?action=addColumn&columnName="+columnName+"&teamId="+teamId,
        success: function(result) {
            $.ajax({
                async: true,
                url: AJAX_URL+"membres/map.php?action=getLastColumnId",
                success: function(data) {
                    columnId = data;    
                    columnId = columnId.replace("\"", ' ').replace("\"", ' ');
                    
                    columnNameInput.val("");
                    btnColumnForm.addClass('show');
                
                    $("#columns-container").append("<div class='project-column'><input class='columnId-input' type='hidden' value='"+columnId+"'><div class='column-title text-center pt-2'><ul><li class='me-2'><b>"+columnName+"</b><button class='btn btn-outline-dark add-task-btn'>New</button></li><li class='mt-2 me-2'><button class='btn btn-outline-danger delete-col-btn'>Delete</button></li></ul></div><div class='column-content'></div></div>");
                    $("#add-column-btn").toggleClass('show');
            
                    initTask();
                    initCol();
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
        $("#column-details").removeClass('show');

        $(".task-check").removeClass('show');
        $(".task-delete").removeClass('show');
        $(".arrow-img-btn").removeClass('show');

        $("#task-comment-container").children().remove();
        $("#task-members-container").children().remove();

        var title = $(this).val();
        $("#task-title").val(title);

        $(this).parent().nextAll(".task-check").first().addClass('show');
        $(this).parent().nextAll(".task-delete").first().addClass('show');
        $(this).parent().nextAll(".arrow-img-btn").first().addClass('show').next().addClass('show');

        columnId = $(this).parents('.project-column').first().find('.columnId-input').val();
        task = $(this).parents('.task').first();
        taskId = task.find(".taskId-input").val();
        
        //up task
        $("#up-task-btn").off('click').click(function() {
            $.ajax({
                async: true,
                url: AJAX_URL+"membres/map.php?action=upTask&taskId="+taskId+"&columnId="+columnId,
                success: function (data) {
                    prevTask = task.prevAll('.task').first();
                    task.insertBefore(prevTask);
                }
            });
        });
        
        // down task
        $("#down-task-btn").off('click').click(function() {
            $.ajax({
                async: true,
                url: AJAX_URL+"membres/map.php?action=downTask&taskId="+taskId+"&columnId="+columnId,
                success: function (data) {
                    nextTask = task.nextAll('.task').first(); 
                    task.insertAfter(nextTask);
                }
            });
        });

        // load task comments
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=getTaskComments&taskId="+taskId,
            success: function (data) {
                if(data != undefined)
                {
                    comments = $.parseJSON(data);
                    l = comments.length;
                    for(i = 0; i < l; i++)
                    {
                        note = comments[i].note;
                        note = note == null ? '' : note,
                        author = comments[i].author;
                        authorId = comments[i].fk_user;
                        $("#task-comment-container").prepend("<div class='task-comment-div'><input type='hidden' class='comment-task-id' value='"+comments[i].rowid+"'><textarea "+(authorId =! idUser ? "disabled" : "")+" class='mt-3 card task-comment px-2 pt-3 text-center' name='' cols='30' rows='3'>"+note+"</textarea><div class='d-flex justify-content-start mt-1'><button class='btn btn-outline-classic comment-author'>"+author+"</button></div></div>")
                    }
                    initComment();
                }

                // load task members
                $.ajax({
                    async: true,
                    url: AJAX_URL+"membres/map.php?action=getTaskMembers&taskId="+taskId,
                    success: function (data) {
                        members = $.parseJSON(data);

                        l = members.length;

                        for(i = 0; i < l; i++)
                        {
                            $("#task-members-container").prepend("<div class='task-member'><input type='hidden' class='task-member-id' value='"+members[i].rowid+"'><div class='w-90 sticker mx-auto mt-2 hover text-center pt-3'>"+members[i].lastname+" "+members[i].firstname+"</div></div>");
                        }


                        $("#members-switch-button").off('click').click(function() {
                            $(".members-label").toggleClass('show');
                            // $(".members-buttons").toggleClass('show');
                            $("#team-members-container").toggleClass('show');
                            $("#task-members-container").toggleClass('show');
                
                            $("#desattribute-member-button").removeClass('show');
                            $("#attribute-member-button").removeClass('show');
                        });
                
                        // member onclick userid
                        $(".team-member").off('click').click(function() {
                            memberId = $(this).find('.team-member-id').val();
                            memberId = memberId.replace("\"", ' ').replace("\"", ' ');
                            memberName = $(this).find('.sticker').text();
                
                            $("#attribute-member-button").addClass('show');
                        })
                
                        $(".task-member").off('click').click(function() {
                            memberId = $(this).find('.task-member-id').val();
                            memberId = memberId.replace("\"", ' ').replace("\"", ' ');
                
                            $("#desattribute-member-button").addClass('show');
                        })
                
                        $("#attribute-member-button").off('click').click(function() {
                            btn = $(this);
                            $.ajax({
                                async: true,
                                url: AJAX_URL+"membres/map.php?action=attributeMemberToTask&taskId="+taskId+"&memberId="+memberId,
                                success: function(data) {
                                    btn.removeClass('show');

                                    $("#task-members-container").prepend("<div class='task-member'><input type='hidden' class='task-member-id' value='"+memberId+"'><div class='w-90 sticker mx-auto mt-2 hover text-center pt-3'>"+memberName+"</div></div>");

                                    $(".task-member").off('click').click(function() {
                                        memberId = $(this).find('.task-member-id').val();
                                        memberId = memberId.replace("\"", ' ').replace("\"", ' ');
                            
                                        $("#desattribute-member-button").addClass('show');
                                    })
                                }
                            });
                        });
                
                        $("#desattribute-member-button").off('click').click(function() {
                            btn= $(this);
                            $.ajax({
                                async: true,
                                url: AJAX_URL+"membres/map.php?action=desattributeMemberToTask&taskId="+taskId+"&memberId="+memberId,
                                success: function(data) {
                                    btn.removeClass('show');
                                    $(".task-member-id[value='"+memberId+"']").parent().remove();
                                }
                            });
                        });
                    }
                });
            }
        });
        $("#task-details").addClass("show");
    });
    
    initTask();

    $("#add-comment-btn").off('click').click(function() {
        // INSERT INTO tasks_comments with empty note
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=addTaskNote&taskId="+taskId,
            success: function(data) {
                commentId = data;
                commentId = commentId.replace("\"", ' ').replace("\"", ' ');
                $("#task-comment-container").prepend("<div class='task-comment-div'><input type='hidden' class='comment-task-id' value='"+commentId+"'><textarea class='mt-3 card task-comment px-2 pt-3 text-center' name='' cols='30' rows='3'></textarea><div class='d-flex justify-content-start mt-1'><button class='btn btn-outline-classic comment-author'>"+username+"</button></div></div>")

                initComment();
            }
        });
    });

    initComment();
}

function initTask()
{
    $(".add-task-btn").off('click').click(function() {
        // HTML CREATE NEW TASK
        addTaskBtn = $(this);
        columnId = addTaskBtn.parents(".column-title").prev().val();
        // INSERT NEW TASK IN BDD    
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=addTask&columnId="+columnId,
            success: function(data) {
                $.ajax({
                    async: true,
                    url: AJAX_URL+"membres/map.php?action=getLastTaskId",
                    success: function(data) {
                        taskId = data;
                        taskId = taskId.replace("\"", ' ').replace("\"", ' ');
                        
                        addTaskBtn.parents(".column-title").next().prepend("<div class='task'><input class='taskId-input' type='hidden' value='"+taskId+"'></input><div class='task-bubble mt-2 pt-3 mb-1 mx-2'><textarea class='task-bubble-input text-center'></textarea></div><a class='ms-2 btn btn-outline-success task-check collapse'>Check</a><a class='ms-2 btn btn-outline-danger task-delete collapse'>Delete</a><a class='ms-1 btn btn-outline-dark arrow-img-btn task-to-left collapse'><img src='"+IMG_URL+"left.png' alt='left arrow' width='30px'></a><a class='ms-1 btn btn-outline-dark arrow-img-btn task-to-right collapse'><img src='"+IMG_URL+"right.png' alt='right arrow' width='30px'></a></div>");

                        init();
                    }
                });
            }
        });
    });

    $(".task-check").off('click').click(function() {
        $(this).removeClass("show");
        $(this).nextAll(".task-delete").first().removeClass("show");
        $(this).nextAll(".arrow-img-btn").first().removeClass("show").next().removeClass("show");

        taskName = $(this).prevAll(".task-bubble").first().find(".task-bubble-input").val();

        taskId = $(this).prevAll(".taskId-input").first().val();

        // INSERT INTO BDD
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=updateTask&taskId="+taskId+"&taskName="+taskName,
        });
    });

    $(".task-delete").off('click').click(function() {

        taskId = $(this).prevAll(".taskId-input").first().val();
        // DELETE FROM BDD
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=deleteTask&taskId="+taskId,
        });

        task = $(this).parents(".task").first();
        // remove task html
        task.remove();

        $("#task-details").removeClass('show');
    });

    $(".task-to-left").click(function() {
        // update fk_column in bdd
        task = $(this).parents(".task");
        taskId = $(this).prevAll(".taskId-input").first().val();
        newColumn = $(this).parents(".project-column").prevAll(".project-column").first();

        updateTaskColumn(task, taskId, newColumn);
    });

    $(".task-to-right").click(function() {
        // update fk_column in bdd
        task = $(this).parents(".task");
        taskId = $(this).prevAll(".taskId-input").first().val();
        newColumn = $(this).parents(".project-column").nextAll(".project-column").first();

        updateTaskColumn(task, taskId, newColumn);
    });
}

function initCol()
{
    var columnId;

    $(".column-title").off('click').click(function() {
        $("#task-details").removeClass('show');
        $("#add-column-form").removeClass('show');
        $("#column-details-check-btn").removeClass('show');

        $("#column-details").addClass('show');
        $("#column-title").val($(this).find(".column-title-text").first().text());

        columnId = $(this).prevAll('.columnId-input').first().val();

    });


    $(".delete-col-btn").off('click').click(function() {
        // GET COLUMN ID
        columnId = $(this).parents(".column-title").prev().val();
        // DELETE HTML COLUMN
        $(this).parents(".project-column").remove();
        
        // DELETE COLUMN IN BDD
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=deleteColumn&columnId="+columnId,
        });

    });

    $("#column-details-delete-btn").off('click').click(function() {
        $(".columnId-input[value='"+columnId+"']").remove();

        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=deleteColumn&columnId="+columnId,
        });
    });

    $("#column-details-check-btn").off('click').click(function() {
        columnName = $("#column-title").val();
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=updateColumn&columnId="+columnId+"&columnName="+columnName,
            success: function(data) {
                $("#column-details-check-btn").removeClass('show');
                $(".columnId-input[value='"+columnId+"']").nextAll('.column-title').first().find('.column-title-text').first().text(columnName);
            }
        });
    });

    $("#column-title").off('focus').focus(function() {
        $("#column-details-check-btn").addClass('show');
    });
}

function initComment()
{
    var taskNote;
    var commentId;

    $(".task-comment").off('focus').focus(function() {
        $("#check-comment-btn").addClass('show');
        $("#delete-comment-btn").addClass('show');
        $("#add-comment-btn").removeClass('show');

        commentId = $(this).prevAll(".comment-task-id").first().val();
        commentId = commentId.replace("\"", ' ').replace("\"", ' ');
    });

    $(".task-comment").off('keyup').keyup(function() {
        taskNote = $(this).val();
    });

    $("#check-comment-btn").off('click').click(function() {
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=updateTaskNote&commentId="+commentId+"&taskNote="+taskNote,
            success: function(result) {
                $("#check-comment-btn").removeClass('show');
                $("#delete-comment-btn").removeClass('show');
                $("#add-comment-btn").addClass('show');
            }
        });
    });

    $("#delete-comment-btn").off('click').click(function() {
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=deleteTaskNote&commentId="+commentId,
            success: function(result) {
                $("#check-comment-btn").removeClass('show');
                $("#delete-comment-btn").removeClass('show');
                $("#add-comment-btn").addClass('show');

                $(".comment-task-id[value='"+commentId+"']").parents('.task-comment-div').first().remove();
            }
        });
    });

}

function updateTaskColumn(task, taskId, newColumn)
{
    if(newColumn.length == 0)
    {
        newColumnId = false;
    }
    else
    {
        newColumnId = newColumn.find(".columnId-input").first().val();
    }

    if(newColumnId)
    {
        $.ajax({
            async: true,
            url: AJAX_URL+"membres/map.php?action=taskColumnUpdate&taskId="+taskId+"&columnId="+newColumnId,
            success: function(data)
            {
                // prepend html from column a to column b
                task.prependTo(newColumn.find(".column-content").first());
            }
        });
    }
}