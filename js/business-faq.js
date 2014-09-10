//Load the partial templates into the page
function loadPartials() {
	// Load the search bar
	$('#header-search').load('./partials/search-bar.html');
	// Load the nav bar
	$('#nav-bar').load('./partials/nav-bar.html');
	// Load 3 tabbed result boxes
	$('#three-tabs').load('./partials/searchresults.html');
}

$(document).ready(function() {
	loadPartials();
});