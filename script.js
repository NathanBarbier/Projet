$(document).ready(function () {
  $(".alert").animate({ opacity: "0" }, 7500);
  setTimeout(() => {
    $(".alert").addClass("collapse");
  }, 7500);

  $("#close-sidebar").click(function() {
    $("#sideBar").removeClass('show');
    $(this).removeClass('show');
    $("#main").removeClass('col-md-12 col-lg-10').addClass('col-12');

    $("#open-sidebar").addClass('show');
  });

  $("#open-sidebar").click(function() {
    $("#sideBar").addClass('show');
    $(this).removeClass('show');
    $("#main").removeClass('col-12').addClass('col-md-12 col-lg-10');

    $('#close-sidebar').addClass('show');
  });
});