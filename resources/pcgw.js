// Function for the social media icons in the footer.
function iconAnimations() {
  $(".icon-container").click(function () {
    $(this).addClass("click");
  });
}

function stickyTableOfContents() {
  $("#toc").addClass("absolute");

  $(".dropdown-menu").hover(
    function () {
      $(this).prev().addClass("white-text");
    },
    function () {
      $(this).prev().removeClass("white-text");
    }
  );

  // Removes "display: none" style property from TOC.
  $("#toc > ul").attr("style", "");

  $(".article-title").waypoint(function (direction) {
    if (direction === "down") {
      $("#toc").attr("class", "fixed");
    } else if (direction === "up") {
      $("#toc").attr("class", "absolute");
    }
  });

  $("#toc").click(function () {
    $(this).toggleClass("expanded");

    $(this).bind("clickoutside", function (event) {
      $(this).removeClass("expanded");
      $(this).unbind("clickoutside");
    });
  });
}

function mobileTableOfContents() {
  $(".toctitle").click(function () {
    $("#toc").toggleClass("active");
  });
}

function mobileToggleMenuItems() {
  $(".dropdown-toggle").click(function () {
    if ($(window).width() < "801") {
      $(this).next().toggleClass("display-block");
    }
  });
}

function mobileHeader() {
  $("#pcgw-header-sidebar-toggle").click(function () {
    // Only run on mobile.
    if ($(window).width() < "801") {
      $("#pcgw-header-sidebar-toggle").toggleClass("active");
      $("#pcgw-header-sidebar").toggleClass("active");
      $("body").toggleClass("no-scroll");
    }
  });

  $("#pcgw-header-search-toggle").click(function () {
    // Only run on mobile.
    if ($(window).width() < "801") {
      $("#pcgw-header-search-toggle").toggleClass("active");
      $("#header-search").toggleClass("active");
    }
  });
}

var load = function () {
  iconAnimations();

  // Only run if the browser window size doesn't suggest the browser is a mobile device.
  if ($(window).width() > "800") {
    stickyTableOfContents();
  } else {
    mobileTableOfContents();
  }

  mobileToggleMenuItems();
  mobileHeader();
};

$(document).on("load", load());
