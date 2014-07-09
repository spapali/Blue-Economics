//Loads jobs that correspong to the users selection of an industry
function loadJob(industry) {
	$.ajax({url:"/jobs?industry="+industry,success:function(result) {
		html = '';
		$.each(result, function(i, item) {
			html += "<a href='#' onclick=\"return loadJobDetails('" + item.name + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
		});
		$("#box2 .resultsbox").html(html);
	}});
}

//Loads job description based on the users selection of a job name
function loadJobDetails(job) {
	$.ajax({url:"/job_description?"+job,
	dataType:'json',
		success:function(result){
			$("#box3 .resultsbox").html("<span class=\"descriptor\">Name: </span>" + result.Name + "<br />" + "<span class=\"descriptor\">Description: </span>" + result.Description + "<br />" + "<span class=\"descriptor\">Median Annual Pay:</span> $" + result.MedianPayAnnual + "<br />" + "<span class=\"descriptor\">Median Hourly Pay:</span> $" + result.MedianPayHourly + "/Hour <br />" + "<span class=\"descriptor\">Employment Openings: </span>" + result.EmploymentOpenings);
		}
	});
}

//Hits search function in the API and returns results
$(document).ready(function(){
  	$( "form:first" ).submit(function() {
  		event.preventDefault();
  		var mydata = $(this).serialize();
  		unserializeddata = mydata.slice(9);
  		console.log(unserializeddata);
  		$.ajax({url:"/search/"+unserializeddata,
			dataType:'json',
			success:function(result){
				isitempty = result.jobs.length;
				if (isitempty == 0) {
					alert("Your search had no results");
				}
				console.log(result);
				var arr = [];
				$.each(result.jobs, function(i, item) {
					html  = "<a href='#' onclick=\"return loadJobDetails('" + item.name + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
					console.log(html);
					//arr.push(html);
					$("#jobslist").append(html);
				});
				//$('#joblist').html(arr);
			},
			error:function(xhr, textStatus, errorThrown){
				alert("Something didn't work");
			}
		});

	});
});

$(document).ready(function(){
	//Pulls all industries on page load
	$.ajax({url:"/jobs",success:function(result) {
		html = '';
		$.each(result, function(i, item) {
			html += "<a href='#' onclick=\"return loadJobDetails('" + item.name + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
		});
		$("#box2 .resultsbox").html(html);
	}});
	//Pulls all jobs on page load
	$.ajax({url:"/industries",success:function(result){
		html = '';
		$.each(result, function(i, item) {
			html += "<a href='#' onclick=\"return loadJob('" + item.id + "')\" class=\"selectable_result\">" + item.name + "</a><br/>";
		});
		$("#box1 .resultsbox").html(html);
	}});
	//Changes job results based on education level selection from user
	$('input[name="education[]"]').change(function(){
		formData = $("#educationcheckbox").serialize();
		//FIXME: Is this how search results should be displayed?
		$.ajax({
			url: "/occupations",
			success: function(result) {
				console.log(result);
				$("#jobslist").children().remove();
				for( row in result) {
					html  = "<a href='#' onclick=\"return loadJobDetails('" + result[row].Name.trim() + "')\" class=\"selectable_result\">" + result[row].Name.trim() + "</a><br/>"
					$(html).appendTo("#jobslist");
				}
			},
			type: "POST",
			dataType: "json",
			data: formData
		})
	});
});


$(document).ready(function(){
	//Hides clear button
	$("#clearbutton").addClass("makeinvisible");
	//Hides clear button description
	$("#clear_text").addClass("clear_button_hover_text");
	//Shows clears button when mouse hovers over top box
    $("#searchbar").mouseover(function(){
		$("#clearbutton").removeClass("makeinvisible");
		$("#clearbutton").addClass("makevisible");
    });
    //Hides button when mouse leaves top box
    $("#searchbar").mouseout(function(){
		$("#clearbutton").removeClass("makevisible");
		$("#clearbutton").addClass("makeinvisible");
    });
    //Clear input box when clear button is clicked
    $("#clearbutton").click(function(){
    	$("#searchBox").val('');
    });
    //Clears input box when input box is clicked
    $("#searchBox").click(function(){
    	$("#searchBox").val('');
    });
    //If input box is empty when mouse leaves top box, it fills it
    $("#searchbar").mouseout(function(){
    	if($($.trim("#searchBox")).val()=="") {
    		$("#searchBox").val("type what you are looking for here");
    	}
    });
    //Shows clear button description on mouse hover
    $("#clearbutton").mouseover(function(){
    	$("#clear_text").removeClass("clear_button_hover_text");
    	$("#clear_text").addClass("clear_button_show");
    });
    //Hides clear button description when mouse out
    $("#clearbutton").mouseout(function(){
    	$("#clear_text").removeClass("clear_button_show");
    	$("#clear_text").addClass("clear_button_hover_text");
    });
});

$(document).ready(function(){
	$("#menu_button").mouseover(function(){
		$("#menuwrapper ul").addClass("makevisible");
		$("#menu_button").addClass("makeinvisible");
	});
	$("#menuwrapper ul").mouseover(function(){
		$("#menuwrapper ul").addClass("makevisible");
		$("#menu_button").addClass("makeinvisible");
	});
	$("#menuwrapper li").mouseover(function(){
		$("#menu_button").addClass("makeinvisible");
	});
	$("#menuwrapper ul").mouseout(function(){
		$("#menuwrapper ul").removeClass("makevisible");
		$("#menu_button").removeClass("makeinvisible");
	});
});