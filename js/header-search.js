//header-search functionality
$(document).ready(function() {
	checkPage();
	//Hides clear button
	$("#clearbutton").addClass("invisible");
	//Hides clear button description
	$("#clear_text").addClass("invisible");
	//Shows clears button when mouse hovers over top box
	$("#searchbar").mouseover(function() {
		$("#clearbutton").removeClass("invisible");
		$("#clearbutton").addClass("visible");
	});
	//Hides button when mouse leaves top box
	$("#searchbar").mouseout(function() {
		$("#clearbutton").removeClass("visible");
		$("#clearbutton").addClass("invisible");
	});
	//Clear input box when clear button is clicked
	$("#clearbutton").click(function() {
		$("#searchBox").val('');
		$("#box1 .resultsbox").empty();
		$("#box2 .resultsbox").empty();
		$("#box3 .resultsbox").empty();
		box1active();
		var butonClickCheck = location.pathname;
		if (buttonClickCheck = "/views/jobs-selection.html") {
			loadIndustry();
			loadJob();
		}
	});
	//Clears input box when input box is clicked
	$("#searchBox").click(function() {
		$("#searchBox").val('');
		$("#box1 .resultsbox").empty();
		$("#box2 .resultsbox").empty();
		$("#box3 .resultsbox").empty();
		box1active();
		var inputClickCheck = location.pathname;
		if (inputClickCheck = "/views/jobs-selection.html") {
			loadIndustry();
			loadJob();
		}
	});
	//If input box is empty when mouse leaves top box, it fills it
	$("#searchbar").mouseout(function() {
		if ($($.trim("#searchBox")).val() === "") {
			$("#searchBox").val("type what you are looking for here");
		}
	});
	//Shows clear button description on mouse hover
	$("#clearbutton").mouseover(function() {
		$("#clear_text").removeClass("invisible");
		$("#clear_text").addClass("visible");
	});
	//Hides clear button description when mouse out
	$("#clearbutton").mouseout(function() {
		$("#clear_text").removeClass("visible");
		$("#clear_text").addClass("invisible");
	});
});
/*
$(document).ready(function() {
	$("#menu_button").mouseover(function() {
		$("#menuwrapper ul").addClass("visible");
		$("#menu_button").addClass("invisible");
	});
	$("#menuwrapper ul").mouseover(function() {
		$("#menuwrapper ul").addClass("visible");
		$("#menu_button").addClass("invisible");
	});
	$("#menuwrapper li").mouseover(function() {
		$("#menu_button").addClass("invisible");
	});
	$("#menuwrapper ul").mouseout(function() {
		$("#menuwrapper ul").removeClass("visible");
		$("#menu_button").removeClass("invisible");
	});
});*/

//Sets nav bar for all pages in the jobs section
function jobsNavBar() {
	$("#menuwrapper ul li a:lt(3)").attr("href","jobs-faq.html");
	$("#menuwrapper ul li a:lt(2)").text("I want small business advice");
	$("#menuwrapper ul li a:lt(2)").attr("href","business-selection.html");
	$("#menuwrapper ul li a:lt(1)").text("I want to learn about better jobs");
	$("#menuwrapper ul li a:lt(1)").attr("href","jobs-selection.html");
}

function faqHeader() {
	$("#searchbar h1").text("Ask a Question");
	$("#searchbar h2").html('Enter a Question of Keyword: <input type="text" name="mySearch" id="searchBox" placeholder="type what you are looking for here"><span id="clearbutton" alt="Click here to clear search bar">X</span>');
	hideEducation();
}

function hideEducation(){
	$("#searchbar h3").addClass("invisible");
	$("#educationcheckbox").addClass("invisible");
}

function questionActive(){
	$("#menuwrapper ul li a:lt(3)").addClass("orange");
	$("#menuwrapper ul li a:lt(2)").removeClass("orange");
	$("#menuwrapper ul li a:lt(1)").removeClass("orange");
}

function checkPage() {
	//Checks which page is loading the search-bar.html partial
	var check = location.pathname;
	console.log(check);
	if (check == "/views/jobs-selection.html") {
		//customizes nav-bar.html partial based on page
		jobsNavBar();
		$("#menuwrapper ul li a:lt(1)").addClass("orange");
		//loads search function appropriate to page
		$("form:first").submit(function() {
			event.preventDefault();
			var mydata = $(this).serialize();
			unserializeddata = mydata.slice(9);
			console.log(unserializeddata);
			$.ajax({
				url: "/search/" + unserializeddata,
				dataType: 'json',
				success: function(result) {
					isitempty = result.jobs.length;
					if (isitempty === 0) {
						alert("Your search had no results");
					}
					console.log(result);
					//var arr = [];
					$.each(result.jobs, function(i, item) {
						html = "<a href='#' onclick=\"return loadJobDetails('" + item.name + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
						console.log(html);
						//arr.push(html);
						$("#box2 .resultsbox").empty();
						$("#box2 .resultsbox").append(html);
					});

					$.each(result.industries, function(i, item) {
						html = "<a href='#' onclick=\"return loadJob('" + item.id + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
						console.log(html);
						//arr.push(html);
						$("#box1 .resultsbox").empty();
						$("#box1 .resultsbox").append(html);
					});
					//$('#joblist').html(arr);
				},
				error: function(xhr, textStatus, errorThrown) {
					alert("Something didn't work");
				}
			});

		});
	} else if (check == "/views/jobs-faq.html") {
		//customizes nav-bar.html partial based on page
		questionActive();
		jobsNavBar();
		//customizes search-bar.html partial based on page
		faqHeader();
	} else if (check == "/views/business-selection.html") {
		$("#menuwrapper ul li a:lt(1)").addClass("orange");
		$("#searchbar h1").text("Get Small Business Advice Here");
		$("#searchbar h2").html('Is there specific information you need: <input type="text" name="mySearch" id="searchBox" placeholder="type what you are looking for here"><span id="clearbutton" alt="Click here to clear search bar">X</span>');
		hideEducation();
	} else if (check == "/views/business-faq.html") {
		questionActive();
		faqHeader();
	}
}