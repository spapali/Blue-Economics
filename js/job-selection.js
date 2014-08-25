function loadJob(industry) {
	if (industry != null) {
		//Loads jobs that correspong to the users selection of an industry
		$.ajax({
			url: "/jobs?industry=" + industry,
			success: function(result) {
				html = '';
				$.each(result, function(i, item) {
					html += "<a href='#' onclick=\"return loadJobDetails('" + item.name + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
				});
				$("#box2 .resultsbox").html(html);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert("Something didn't work");
			}
		});
	} else {
		//Pulls all jobs on page load
		$.ajax({
			url: "/jobs",
			success: function(result) {
				html = '';
				$.each(result, function(i, item) {
					html += "<a href='#' onclick=\"return loadJobDetails('" + item.name + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
				});
				$("#box2 .resultsbox").html(html);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert("Something didn't work");
			}
		});
	}
}

//Loads job description based on the users selection of a job name
function loadJobDetails(job) {
	$.ajax({
		url: "/job_description?" + job,
		dataType: 'json',
		success: function(result) {
			$("#box3 .resultsbox").html("<span class=\"descriptor\">Name: </span>" + result.Name + "<br />" + "<span class=\"descriptor\">Description: </span>" + result.Description + "<br />" + "<span class=\"descriptor\">Median Annual Pay:</span> $" + result.MedianPayAnnual + "<br />" + "<span class=\"descriptor\">Median Hourly Pay:</span> $" + result.MedianPayHourly + "/Hour <br />" + "<span class=\"descriptor\">Employment Openings: </span>" + result.EmploymentOpenings);
		},
		error: function(xhr, textStatus, errorThrown) {
				alert("Something didn't work");
		}
	});
}

function loadIndustry(selector) {
	if (selector != null) {

	} else {
		//Pulls all industries on page load
		$.ajax({
			url: "/industries",
			success: function(result) {
				html = '';
				$.each(result, function(i, item) {
					html += "<a href='#' onclick=\"return loadJob('" + item.id + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
				});
				$("#box1 .resultsbox").html(html);
			},
			error: function(xhr, textStatus, errorThrown) {
				alert("Something didn't work");
			}
		});
	}
}

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
	loadJob();
	loadIndustry();

	$("#menuwrapper ul li a:lt(3)").attr("href","jobs-faq.html");
	$("#menuwrapper ul li a:lt(2)").text("I want small business advice");
	$("#menuwrapper ul li a:lt(2)").attr("href","business-selection.html");
	$("#menuwrapper ul li a:lt(1)").text("I want to learn about better jobs");
	$("#menuwrapper ul li a:lt(1)").attr("href","jobs-selection.html");
	$("#menuwrapper ul li a:lt(1)").addClass("orange");

	//Changes job results based on education level selection from user
	$('input[name="education[]"]').change(function() {
		formData = $("#educationcheckbox").serialize();
		//FIXME: Is this how search results should be displayed?
		$.ajax({
			url: "/occupations",
			success: function(result) {
				console.log(result);
				$("#jobslist").children().remove();
				for (var row in result) {
					html = "<a href='#' onclick=\"return loadJobDetails('" + result[row].Name.trim() + "')\" class=\"selectable_result\">" + result[row].Name.trim() + "</a><br/>";
					$(html).appendTo("#jobslist");
				}
			},
			type: "POST",
			dataType: "json",
			data: formData
		});
	});
});