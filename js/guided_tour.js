function box1activate() {
	$("#arrow1").removeClass("invisible");
	$("#box1 .resultsbox").addClass("blue");
	$("#box1 .labeltab").addClass("blue");
	$("#box1 .labeltab p").addClass("white");
	$("#box1 .resultsbox a.selectable_result").addClass("orange");
}

function box1deactivate() {
	$("#arrow1").removeClass("visible");
	$("#arrow1").addClass("invisible");
	$("#box1 .labeltab").removeClass("blue");
	$("#box1 .labeltab p").removeClass("white");
	$("#box1 .resultsbox").removeClass("blue");
}

function box2activate() {
	$("#arrow2").removeClass("invisible");
	$("#box2 .resultsbox").addClass("blue");
	$("#box2 .labeltab").addClass("blue");
	$("#box2 .labeltab p").addClass("white");
	$("#box2 .resultsbox a.selectable_result").addClass("orange");
}

function box2deactivate() {
	$("#arrow2").removeClass("visible");
	$("#arrow2").addClass("invisible");
	$("#box2 .labeltab").removeClass("blue");
	$("#box2 .labeltab p").removeClass("white");
	$("#box2 .resultsbox").removeClass("blue");
}

function box3activate() {
	$("#arrow3").removeClass("invisible");
	$("#box3 .resultsbox").addClass("blue");
	$("#box3 .labeltab").addClass("blue");
	$("#box3 .labeltab p").addClass("white");
	$("#box3 .resultsbox a.selectable_result").addClass("orange");
}

function box3deactivate() {
	$("#arrow3").removeClass("visible");
	$("#arrow3").addClass("invisible");
	$("#box3 .labeltab").removeClass("blue");
	$("#box3 .labeltab p").removeClass("white");
	$("#box3 .resultsbox").removeClass("blue");
}

function box1active() {
	box1activate();
	box2deactivate();
	box3deactivate();
}

function box2active() {
	box1deactivate();
	box3deactivate();
	box2activate();
}

function box3active() {
	box1deactivate();
	box2deactivate();
	box3activate();
}

function noBoxesActive() {
	box1deactivate();
	box2deactivate();
	box3deactivate();
}

function selectionPages(){
	$("#box2 .resultsbox").click(function(){
		box3active();
	});

	$("#box3 .resultsbox").click(function(){
		noBoxesActive();
	});
}

function faqResults(){
	$("#box1 .labeltab p").text("FAQs");
	$("#box2 .labeltab p").text("Answers");
	$("#box3").addClass("invisible");
}

$(document).ready(function(){
	box1active();

	$("#box1 .resultsbox").click(function(){
		box2active();
	});

	//Checks which page is loading the searchresults partial
	var check = location.pathname;
	console.log(check);
	if (check == "/views/jobs-selection.html") {
		selectionPages();
	} else if (check == "/views/jobs-faq.html") {
		faqResults();
	} else if (check == "/views/business-selection.html") {
		$("#box1 .labeltab p").text("Category");
		$("#box2 .labeltab p").text("Topics");
		$("#box3 .labeltab p").text("Articles");
	} else if (check == "/views/business-faq.html") {
		faqResults();
	}
});