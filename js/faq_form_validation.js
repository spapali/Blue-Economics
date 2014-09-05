//Checks whether form is open and/or empty
function check_form() {
	console.log("Initiated check_form");
	var step1formcheck = form_open_check();
	console.log(step1formcheck);
	if (step1formcheck == false) {
		return false;
    } else {
    	var step2formcheck = is_form_empty();
    	console.log(step2formcheck);
    	if (step2formcheck == true){
    		return false;
    	}  else {
    		return true;
    	}
	}
}

//Checks whether form is open
function form_open_check() {
	console.log("Initiated form_open_check");
	var cssCheck = $("#box3 .resultsbox").css("visibility");
		console.log(cssCheck);
		if (cssCheck == "visible") {
			return true;
		} else {
			return false;
		}
}

//Checks if user has entered any information into form
function is_form_empty() {
	console.log("Initiated is_form_empty");
	if (($($.trim("#faq_input1")).val() === "type your question here") || ($($.trim("#faq_input2")).val() === "") || ($($.trim("#faq_input1")).val() === "")){
		console.log("Initiated form check - empty inputs recognized");
		if (($($.trim("#faq_input1")).val() === "type your question here") || ($($.trim("#faq_input1")).val() === "")){
			console.log("Initiated form check - question input empty recognized");
			alert("please enter a valid question");
		}

		if ($($.trim("#faq_input2")).val() === "") {
			console.log("Initiated form check - e-mail input empty recognized");
			alert("please enter a valid e-mail address");
		}

		return true;
	} else {
		return false;
	}
}

//Alerts user if they have begun to fill out form and abandoned it
function alert_to_user() {
	console.log("Initiated alert_to_user");
	if (confirm("Are you sure you want to close the FAQ form? \nYou have already begun to fill it out. \nPress OK if you want to discard this information.") == true) {
		showFAQbutton();
		event.preventDefault();
    } else {
    	event.preventDefault();
    }
}

function is_email_formatted() {
	console.log("Initiated is_email_formatted");
	var email = ($("#faq_input2").val()).trim();
	var whereAt = email.indexOf("@");
	if (whereAt == -1) {
		alert("Please enter a valid e-mail address");
	} else {
		var whereDot = email.indexOf(".");
		if (whereDot == -1) {
			alert("Please enter a valid e-mail address");
		} else {
			alert("Thank you for your submission. \nWe'll send you an e-mail once your query has a response.");
			showFAQbutton();
		}
	}
}