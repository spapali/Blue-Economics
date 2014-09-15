//Load the partial templates into the page
function loadPartials() {
	// Load the search bar
	$('#header-search').load('./partials/search-bar.html');
	// Load the nav bar
	$('#nav-bar').load('./partials/nav-bar.html');
	// Load 3 tabbed result boxes
	$('#three-tabs').load('./partials/searchresults.html');
}

function loadQuestions(selector) {
		//Pulls all Questions on page load
		$.ajax({
			url: "/questions",
			type: "GET",
			success: function(result) {
				html = '';
				$.each(result, function(i, item) {
					html += "<a href=\"#\" class=\"selectable_result\">" + item.text + "</a><br/>";
				});
				$("#box1 .resultsbox").html(html);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert("Something didn't work");
			}
		});
}

$(document).ready(function() {
	loadPartials();
	loadQuestions();
	console.log(location.pathname);
});