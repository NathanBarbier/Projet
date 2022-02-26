toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "5",
    "hideDuration": "1000",
    "timeOut": "10000",
    // "timeOut": "500000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}


Command: toastr[type](message,title);
console.log(icon)
var prepend = '<i class="bi '+icon+' toastr-icon"></i>';
$('.toast.toast-'+type).find('.toast-title').prepend(prepend);