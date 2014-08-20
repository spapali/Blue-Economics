$(document).ready(function(){
	$("#arrow1").addClass("visible");
	$("#arrow2").addClass("invisible");
	$("#arrow3").addClass("invisible");
	$("#box1 .resultsbox").addClass("blue");
	$("#box1 .labeltab").addClass("blue");
	$("#box1 .labeltab p").addClass("white");
	$("#box1 .resultsbox").click(function(){
		$("#arrow1").removeClass("visible");
		$("#arrow1").addClass("invisible");
		$("#box1 .labeltab").removeClass("blue");
		$("#box1 .labeltab p").removeClass("white")
		$("#box1 .resultsbox").removeClass("blue");

		$("#arrow2").removeClass("invisible");
		$("#arrow2").addClass("visible");
		$("#box2 .resultsbox").addClass("blue");
		$("#box2 .labeltab").addClass("blue");
		$("#box2 .labeltab p").addClass("white");
		$("#box2 .resultsbox").click(function(){
			$("#arrow2").removeClass("makevisible");
			$("#box2 .labeltab").removeClass("makeblue");
			$("#box2 .labeltab p").removeClass("makewhite")
			$("#box2 .resultsbox").removeClass("makeblue");

			$("#arrow3").addClass("makevisible");
			$("#box3 .resultsbox").addClass("makeblue");
			$("#box3 .labeltab").addClass("makeblue");
			$("#box3 .labeltab p").addClass("makewhite");
			$("#box3 .resultsbox").click(function(){
				$("#arrow3").removeClass("makevisible");
				$("#box3 .labeltab").removeClass("makeblue");
				$("#box3 .labeltab p").removeClass("makewhite")
				$("#box3 .resultsbox").removeClass("makeblue");
			});
		});
	});
});