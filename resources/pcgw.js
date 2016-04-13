// Function for the social media icons in the footer.
function iconAnimations() {
	$(".icon-container").click(function() {
		$(this).addClass('click');
	});
}

function mobileTableOfContents() {
	$("#toctitle").click(function() {
		$("#toc").toggleClass('active');
	});
}

function mobileHeader() {
	var windowwidth = $(window).width();

	$("#pcgw-header-sidebar-toggle").click(function() {
		// Only run on mobile.
		if (windowwidth < '800') {
			$("#pcgw-header-sidebar-toggle").toggleClass('active');
			$("#pcgw-header-sidebar").toggleClass('active');
			$("body").toggleClass('no-scroll');
		}
	});

	$("#pcgw-header-search-toggle").click(function() {
		// Only run on mobile.
		if (windowwidth < '800') {
			$("#pcgw-header-search-toggle").toggleClass('active');
			$("#header-search").toggleClass('active');
		}
	});
}

var load = function() {
	iconAnimations();

	var windowwidth = $(window).width();

	// Only run if the browser window size doesn't suggest the browser is a mobile device.
	if (windowwidth > '800') {
		stickyTableOfContents();
	} else {
		mobileTableOfContents();
	}

	mobileHeader();
};

$(document).on("load", load());
