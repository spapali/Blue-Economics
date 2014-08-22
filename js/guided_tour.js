$(document).ready(function(){
	$("#arrow1").addClass("visible");
	$("#arrow2").addClass("invisible");
	$("#arrow3").addClass("invisible");
	$("#box1 .resultsbox").addClass("blue");
	$("#box1 .labeltab").addClass("blue");
	$("#box1 .labeltab p").addClass("white");
	$("#box1 a.selectable_result").addClass("orange");
	$("#box1 .resultsbox").click(function(){
		$("#arrow1").removeClass("visible");
		$("#arrow1").addClass("invisible");
		$("#box1 .labeltab").removeClass("blue");
		$("#box1 .labeltab p").removeClass("white");
		$("#box1 .resultsbox").removeClass("blue");
		$("#arrow2").removeClass("invisible");
		$("#arrow2").addClass("visible");
		$("#box2 .resultsbox").addClass("blue");
		$("#box2 .labeltab").addClass("blue");
		$("#box2 .labeltab p").addClass("white");
		$("#box2 .resultsbox").click(function(){
			$("#arrow2").removeClass("visible");
			$("#arrow2").addClass("invisible");
			$("#box2 .labeltab").removeClass("blue");
			$("#box2 .labeltab p").removeClass("white")
			$("#box2 .resultsbox").removeClass("blue");
			$("#arrow3").addClass("visible");
			$("#box3 .resultsbox").addClass("blue");
			$("#box3 .labeltab").addClass("blue");
			$("#box3 .labeltab p").addClass("white");
			$("#box3 .resultsbox").click(function(){
				$("#arrow3").removeClass("visible");
				$("#box3 .labeltab").removeClass("blue");
				$("#box3 .labeltab p").removeClass("white")
				$("#box3 .resultsbox").removeClass("blue");
			});
		});
	});
});