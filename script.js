$(document).ready(function() {
    $(".alert-success").animate({ opacity: '0'}, 5000);
    setTimeout(() => { 
        $(".alert-success").addClass("collapse");
    }, 5000);
});