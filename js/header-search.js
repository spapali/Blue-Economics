$(document).ready(function() {
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
	});
	//Clears input box when input box is clicked
	$("#searchBox").click(function() {
		$("#searchBox").val('');
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