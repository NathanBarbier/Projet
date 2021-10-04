init();
newTaskInit();

// NEW COLUMN
$("#add-column-btn").click(function() {
    $("#add-column-form").toggleClass('show');
});

$("#add-column-form").find('button').click(function() {
    
    columnName = $("#columnName-input").val();
    
    // insert in bdd
    $.ajax({
        url: CONTROLLERS_URL+"membres/map.php?action=addColumn&columnName="+columnName+"&projectId="+projectId,
        success: function(result) {
            // console.log(result);
        }
    });

    // GET LAST INSERT COLUMN ROWID
    $.ajax({
        url: AJAX_URL+"membres/map.php?action=getLastColumnId",
    })    
    .done(function(data) {
        columnId = data
    });

    // create html
    $(this).prev().val("");
    $(this).toggleClass('show');

    $("#add-column-btn").parent().prev().after("<div class='project-column'><input class='columnId-input' type='hidden' value='"+columnId+"'><div class='column-title text-center pt-2'><ul><li class='me-2'><b>"+columnName+"</b><button class='btn btn-outline-dark add-task-btn'>New</button></li><li class='mt-2 me-2'><button class='btn btn-outline-danger delete-col-btn'>Delete</button></li></ul></div><div class='column-content'></div></div>");

    newTaskInit();
});



function newTaskInit()
{
    
    // INSERT NEW TASK IN BDD
    columnId = $(this).parents(".column-title").prev().val();
    
    $.ajax({
        url: CONTROLLERS_URL+"membres/map.php?action=addTask&columnId="+columnId+"&projectId="+projectId,
        success: function(result) {
            // console.log(result);
        }
    }).done(function() {
        // GET LAST INSERT TASK ROWID
        $.ajax({
            url: AJAX_URL+"membres/map.php?action=getLastTaskId",
        })
        .done(function(data) {
            taskId = data
        });
    });



    $(".add-task-btn").click(function() {
        $(this).parents(".column-title").next().prepend("<input class='taskId-input' type='hidden' value='"+taskId+"'></input><div class='task-bubble mt-2 pt-3 mb-1 mx-2'><textarea class='task-bubble-input text-center'></textarea></div><a class='ms-2 btn btn-outline-dark task-check collapse'>Check</a><a class='ms-2 btn btn-outline-danger task-delete collapse'>Delete</a>");

        init();
        
    });

    newColInit();
}

function init()
{
    $(".task-bubble").hover(function() {
        $(this).css({"background-color": "#eeeff0", "cursor": "pointer"});
        $(this).children().css({"background-color": "#eeeff0", "cursor": "pointer"});
    }, function() {
        $(this).css({"background-color": "white", "cursor": "default"});
    $(this).children().css({"background-color": "white", "cursor": "default"});
    });
    
    $(".task-bubble-input").focus(function() {
        $(this).parent().nextAll(".task-check").first().addClass('show');
        $(this).parent().nextAll(".task-delete").first().addClass('show');
    });
    
    $(".task-bubble").click(function() {
        $(this).children().focus();
    });
    
    $(".task-check").click(function() {
        $(this).removeClass("show");
        $(this).nextAll(".task-delete").first().removeClass("show");

        taskName = $(this).prevAll(".task-bubble").first().find(".task-bubble-input").val();
        taskId = $(this).prevAll(".taskId-input").first().val();

        // INSERT INTO BDD
        $.ajax({
            url: CONTROLLERS_URL+"membres/map.php?action=updateTask&taskId="+taskId+"&taskName="+taskName+"&projectId="+projectId,
            success: function(result) {
                // console.log(result);
            }
        });

    });
    $(".task-delete").click(function() {

        taskId = $(this).prevAll(".taskId-input").first().val();
        // DELETE FROM BDD
        $.ajax({
            url: CONTROLLERS_URL+"membres/map.php?action=deleteTask&taskId="+taskId+"&projectId="+projectId,
            success: function(result) {
                // console.log(result);
            }
        });

        // remove task html
        $(this).prevAll(".task-check").first().remove();
        $(this).prevAll(".task-bubble").first().remove();
        $(this).prevAll(".taskId-input").first().remove();
        $(this).remove();
    });   
}

function newColInit()
{
    $(".delete-col-btn").click(function() {
        // GIT COLUMN ID
        columnId = $(this).parents(".column-title").prev().val();

        console.log(columnId)
        // DELETE HTML COLUMN
        $(this).parents(".project-column").remove();
        
        // DELETE COLUMN IN BDD
        $.ajax({
            url: CONTROLLERS_URL+"membres/map.php?action=deleteColumn&columnId="+columnId+"&projectId="+projectId,
            success: function(result) {
                console.log(result);
            }
        });

    });
}