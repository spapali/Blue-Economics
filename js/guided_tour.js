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
	$("#box3 .labeltab").addClass("invisible");
	$("#box3 .activearrow").addClass("invisible");
	$("#box3 .resultsbox").addClass("invisible");
}

function hideFAQbutton() {
	$("#open_faq_button").addClass("invisible");
	$("#open_faq_button").css({"position": "absolute"});
}

function FAQbutton_rollover(){
	$("#open_faq_button").mouseover(function() {
		$("#open_faq_button").css({"color": "#FFFFFF"});
	});
	$("#open_faq_button").mouseout(function() {
		$("#open_faq_button").css({"color": "#001C2F"});
	});
	$("#open_faq_button").click(function() {
		openFAQ();
	});
}

function openFAQ() {
	hideFAQbutton();
	$("#box3 .labeltab").removeClass("invisible");
	$("#box3 .activearrow").removeClass("invisible");
	$("#box3 .resultsbox").removeClass("invisible");
	$("#box3 .labeltab").addClass("visible");
	$("#box3 .activearrow").addClass("visible");
	$("#box3 .resultsbox").addClass("visible");
	$("#box3 .labeltab p").text("Question");
	box3active();
	$("#box3 .resultsbox").html('<p><h4>New Question:</h4><textarea id="faq_input1" rows"4" cols="1">type your question here</textarea></p><p><h4>Your E-Mail Address:</h4><input type="text" name="faq_e-mail" id="faq_input2" placeholder="type your e-mail address here"><span id="e-mail">we need your e-mail address to let you know when your question has been answered</span></p><a href="#" id="faq_submit">Submit</a>');
	$("#box3 .resultsbox h4").addClass("orange");
	$("#faq_input1, #faq_input2").addClass("faqinput");
	$("#e-mail").addClass("raleway");
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
		hideFAQbutton();
	} else if (check == "/views/jobs-faq.html") {
		faqResults();
		FAQbutton_rollover();
	} else if (check == "/views/business-selection.html") {
		$("#box1 .labeltab p").text("Category");
		$("#box2 .labeltab p").text("Topics");
		$("#box3 .labeltab p").text("Articles");
		hideFAQbutton();
	} else if (check == "/views/business-faq.html") {
		faqResults();
		FAQbutton_rollover();
	}
});