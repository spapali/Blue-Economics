$(document).ready(function(){
	$("#arrow1").addClass("makevisible");
	$("#box1 .resultsbox").addClass("makeblue");
	$("#box1 .labeltab").addClass("makeblue");
	$("#box1 .labeltab p").addClass("makewhite");
	$("#box1 .resultsbox").click(function(){
		$("#arrow1").removeClass("makevisible");
		$("#box1 .labeltab").removeClass("makeblue");
		$("#box1 .labeltab p").removeClass("makewhite")
		$("#box1 .resultsbox").removeClass("makeblue");

		$("#arrow2").addClass("makevisible");
		$("#box2 .resultsbox").addClass("makeblue");
		$("#box2 .labeltab").addClass("makeblue");
		$("#box2 .labeltab p").addClass("makewhite");
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