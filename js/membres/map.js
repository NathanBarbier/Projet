$(".add-task-btn").click(function() {
    $(this).parent().next().prepend("<div class='task-bubble mt-2 mb-1 mx-2'><textarea class='task-bubble-input'></textarea></div><a class='ms-2 btn btn-outline-dark task-check collapse'>Check</a><a class='ms-2 btn btn-outline-danger task-delete collapse'>Delete</a>");

    
    $(".task-bubble").hover(function() {
        $(this).css({"background-color": "#eeeff0", "cursor": "pointer"});
        $(this).children().css({"background-color": "#eeeff0", "cursor": "pointer"});
    }, function() {
        $(this).css({"background-color": "white", "cursor": "default"});
        $(this).children().css({"background-color": "white", "cursor": "default"});
    });

    $(".task-bubble-input").focus(function() {
        $(this).parent().next().addClass('show');
        $(this).parent().next().next().addClass('show');
    });

    $(".task-bubble").click(function() {
        $(this).children().focus();
    });

    $(".task-check").click(function() {
        $(this).removeClass("show");
        $(this).next().removeClass("show");
    });
    $(".task-delete").click(function() {
        // remove task html
        $(this).prev().remove();
        $(this).prev().remove();
        $(this).remove();   
    });


});

$("#add-column-btn").click(function() {
    $("#add-column-form").toggleClass('show');
});

$("#add-column-form").find('button').click(function() {
    // insert in bdd

    // create html
    columnName = $(this).prev().val();
    $(this).prev().val("");
    $(this).toggleClass('show');

    $("#add-column-btn").parent().prev().after("<div class='project-column'><div class='column-title text-center pt-2'><b>"+columnName+"</b><button class='btn btn-outline-dark add-task-btn me-3'>New</button></div><div class='column-content'></div></div>")
});

