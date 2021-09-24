$(document).ready(function() {
    $(".alert").animate({ opacity: '0'}, 5000);
    setTimeout(() => { 
        $(".alert").addClass("collapse");
    }, 5000);
});