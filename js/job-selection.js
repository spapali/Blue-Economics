//Loads jobs that correspong to the users selection of an industry
function loadJob(industry) {
	$.ajax({url:"/jobs?industry="+industry,success:function(result){
			$("#box2 .resultsbox").html(result);
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
					html  = "<a href='#' onclick=\"return loadJobDetails('" + item.name + "')\" style='color:#333333;'>" + item.name + "</a><br/>";
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
	$.ajax({url:"/jobs",success:function(result){
		$("#box2 .resultsbox").html(result);
	}});
	//Pulls all jobs on page load
	$.ajax({url:"/industries",success:function(result){
		$("#box1 .resultsbox").html(result);
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
					html  = "<a href='#' onclick=\"return loadJobDetails('" + result[row].Name.trim() + "')\" style='color:#333333;'>" + result[row].Name.trim() + "</a><br/>"
					$(html).appendTo("#jobslist");
				}
			},
			type: "POST",
			dataType: "json",
			data: formData
		})
	});
});