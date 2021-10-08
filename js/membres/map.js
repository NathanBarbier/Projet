init();
newTaskInit();
newColInit();

// NEW COLUMN
$("#add-column-btn").click(function() {
    $("#add-column-form").toggleClass('show');
});

$("#add-column-form").find('button').click(function() {
    
    columnName = $("#columnName-input").val();

    columnNameInput = $(this).prev();
    btnColumnForm = $(this);
    
    // insert in bdd
    $.ajax({
        url: AJAX_URL+"membres/map.php?action=addColumn&columnName="+columnName+"&teamId="+teamId,
        success: function(result) {
            $.ajax({
                url: AJAX_URL+"membres/map.php?action=getLastColumnId",
                success: function(data) {
                    columnId = data;
                    while(!columnId){}      

                    columnId = columnId.replace("\"", ' ').replace("\"", ' ');

                    console.log(columnId);
                    // columnId = colmunId.substr(1);
                    
                    columnNameInput.val("");
                    btnColumnForm.toggleClass('show');
                
                    $("#add-column-btn").parent().before("<div class='project-column'><input class='columnId-input' type='hidden' value='"+columnId+"'><div class='column-title text-center pt-2'><ul><li class='me-2'><b>"+columnName+"</b><button class='btn btn-outline-dark add-task-btn'>New</button></li><li class='mt-2 me-2'><button class='btn btn-outline-danger delete-col-btn'>Delete</button></li></ul></div><div class='column-content'></div></div>");
            
                    newTaskInit();
                    newColInit();
                }
            });
        }
    });
});



function newTaskInit()
{
    $(".add-task-btn").off('click').click(function() {
        // HTML CREATE NEW TASK
        addTaskBtn = $(this);
        columnId = addTaskBtn.parents(".column-title").prev().val();
        // INSERT NEW TASK IN BDD    
        $.ajax({
            url: AJAX_URL+"membres/map.php?action=addTask&columnId="+columnId,
            success: function(result) {
                $.ajax({
                    url: AJAX_URL+"membres/map.php?action=getLastTaskId",
                    success: function(data) {
                        taskId = data;
                        taskId = taskId.replace("\"", ' ').replace("\"", ' ');
    
                        while(!taskId){}
                        
                        addTaskBtn.parents(".column-title").next().prepend("<div class='task'><input class='taskId-input' type='hidden' value='"+taskId+"'></input><div class='task-bubble mt-2 pt-3 mb-1 mx-2'><textarea class='task-bubble-input text-center'></textarea></div><a class='ms-2 btn btn-outline-dark task-check collapse'>Check</a><a class='ms-2 btn btn-outline-danger task-delete collapse'>Delete</a></div>");

                        init();
                    }
                });
            }
        });
    });
}

function init()
{
    $(".task-bubble").off('hover').hover(function() {
        $(this).css({"background-color": "#eeeff0", "cursor": "pointer"});
        $(this).children().css({"background-color": "#eeeff0", "cursor": "pointer"});
    }, function() {
        $(this).css({"background-color": "white", "cursor": "default"});
    $(this).children().css({"background-color": "white", "cursor": "default"});
    });

    $(".task-bubble-input").hover(function() {
        $(this).blur();
    });
    
    $(".task-bubble-input").off('focus').focus(function() {
        $(this).parent().nextAll(".task-check").first().addClass('show');
        $(this).parent().nextAll(".task-delete").first().addClass('show');
        $(this).parent().nextAll(".arrow-img-btn").first().addClass('show').next().addClass('show');
    });
    
    $(".task-bubble").off('mouseup').mouseup(function() {
        $(this).children().focus();
    });
    
    $(".task-check").off('click').click(function() {
        $(this).removeClass("show");
        $(this).nextAll(".task-delete").first().removeClass("show");
        $(this).nextAll(".arrow-img-btn").first().removeClass("show").next().removeClass("show");

        taskName = $(this).prevAll(".task-bubble").first().find(".task-bubble-input").val();

        taskId = $(this).prevAll(".taskId-input").first().val();

        // INSERT INTO BDD
        $.ajax({
            url: AJAX_URL+"membres/map.php?action=updateTask&taskId="+taskId+"&taskName="+taskName,
        });
    });

    $(".task-delete").off('click').click(function() {

        taskId = $(this).prevAll(".taskId-input").first().val();
        // DELETE FROM BDD
        $.ajax({
            url: AJAX_URL+"membres/map.php?action=deleteTask&taskId="+taskId,
        });

        task = $(this).parents(".task").first();
        // remove task html
        task.remove();
    });

    $(".task-to-left").click(function() {
        // update fk_column in bdd
        task = $(this).parents(".task");
        taskId = $(this).prevAll(".taskId-input").first().val();
        // console.log($(this).prevAll(".holaquetal").first());
        newColumn = $(this).parents(".project-column").prevAll(".project-column").first();

        updateTaskColumn();
    });

    $(".task-to-right").click(function() {
        // update fk_column in bdd
        task = $(this).parents(".task");
        taskId = $(this).prevAll(".taskId-input").first().val();
        // console.log($(this).prevAll(".holaquetal").first());
        newColumn = $(this).parents(".project-column").nextAll(".project-column").first();

        updateTaskColumn();
    })
}

function newColInit()
{
    $(".delete-col-btn").off('click').click(function() {
        // GIT COLUMN ID
        columnId = $(this).parents(".column-title").prev().val();
        // DELETE HTML COLUMN
        $(this).parents(".project-column").remove();
        
        // DELETE COLUMN IN BDD
        $.ajax({
            url: AJAX_URL+"membres/map.php?action=deleteColumn&columnId="+columnId,
            success: function(result)
            {
                console.log(result);
            }
        });

    });
}

function updateTaskColumn()
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
            url: AJAX_URL+"membres/map.php?action=taskColumnUpdate&taskId="+taskId+"&columnId="+newColumnId,
            success: function(data)
            {
                // prepend html from column a to column b
                task.prependTo(newColumn.find(".column-content").first());
            }
        });
    }
}