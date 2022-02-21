$(document).ready(function () {
  $(".alert").animate({ opacity: "0" }, 7500);
  setTimeout(() => {
    $(".alert").addClass("collapse");
  }, 7500);

  // resize #main responsivily
  var viewportWidth = $(window).width();

  resizeMain(viewportWidth);
  $(window).resize(function() {
    viewportWidth = $(window).width();
    resizeMain(viewportWidth);
  });

  // SIDEBAR RESPONSIVE COLLAPSE
  $("#close-sidebar").click(function() {
    $("#sideBar").removeClass('show');
    $(this).removeClass('show');
    $("#main").removeClass('col-10').addClass('col-12');

    $("#open-sidebar").addClass('show');
  });

  $("#open-sidebar").click(function() {
    $("#sideBar").addClass('show');
    $(this).removeClass('show');
    $("#main").removeClass('col-12').addClass('col-10');

    $('#close-sidebar').addClass('show');
  });

  // tooltip activation
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
		})

  function resizeMain(viewportWidth) {
    if(viewportWidth < 1230) {
      $("#main").removeClass("col-10").addClass("col-12");
    } else {
      $("#main").removeClass("col-12").addClass("col-10");
    }
  }
});
