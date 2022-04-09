  // check if consent to cookie and if not, then display modal to consent
  $.ajax({
    async: true,
    url: AJAX_URL + "setCookie.php?action=checkCookieConsent",
    success: function (data) {
      if (data === "false") {
        $("#cookiemodal").modal("show");
      }
    },
  });

  $(".consentToCookie").on('click', function () {
    $.ajax({
      async: true,
      url: AJAX_URL + "setCookie.php?action=consentCookie",
      success: function (data) {
        $("#cookiemodal").modal("hide");
        $("#cookiedetailsmodal").modal("hide");
      },
    });
  });
  
  // display cookie policy details
  $("#cookieDetails").on('click', function () {
    $("#cookiedetailsmodal").modal("toggle");
    $("#cookiemodal").modal("toggle");
  });

  // from the cookie details modal to first cookie modal
  $("#cookieback").on('click', function () {
    $("#cookiedetailsmodal").modal("toggle");
    $("#cookiemodal").modal("toggle");
  })