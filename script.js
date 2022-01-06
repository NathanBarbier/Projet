$(document).ready(function () {
  $(".alert").animate({ opacity: "0" }, 7500);
  setTimeout(() => {
    $(".alert").addClass("collapse");
  }, 7500);
});
