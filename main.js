
$(function() {
	// Initialize datepicker
	$("input[name='DateOfBirth']").datepicker({
		dateFormat: 'yy-mm-dd', // Specify the date format
		changeMonth: true,
		changeYear: true,
		yearRange: "-120:+0", // Allow selection of dates up to 120 years ago
		onSelect: function(dateText, inst) {
			// Calculate age based on selected date
			var selectedDate = new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay);
			var today = new Date();
			var age = today.getFullYear() - selectedDate.getFullYear();
			var m = today.getMonth() - selectedDate.getMonth();
			if (m < 0 || (m === 0 && today.getDate() < selectedDate.getDate())) {
				age--;
			}
			
			// Validate age
			if (age < 5 || age > 120) {
				alert("Please select a valid date of birth (age must be between 5 and 120 years).");
				$(this).val(""); // Clear the selected date
			}
		}
	});
})

function validateForm() {
	var fullName = document.forms["surveyForm"]["Name"].value;
	var email = document.forms["surveyForm"]["Email"].value;
	var dateOfBirth = document.forms["surveyForm"]["DateOfBirth"].value;
	var contactNumber = document.forms["surveyForm"]["ContactNumber"].value;
	var checkboxes = document.querySelectorAll('input[type="checkbox"][name="select"]');
	var radiosMovies = document.querySelectorAll('input[type="radio"][name="movies"]');
	var radiosRadio = document.querySelectorAll('input[type="radio"][name="radio"]'); 
	var radiosEatOut = document.querySelectorAll('input[type="radio"][name="eatOut"]');
	var radiosWatchTV = document.querySelectorAll('input[type="radio"][name="watchTV"]');
	
	// Validate text fields
	if (fullName == "" || email == "" || dateOfBirth == "" || contactNumber == "") {
	  alert("Please fill out all fields");
	  return false;
	}
	
	// Validate checkboxes
	var checkboxChecked = false;
	checkboxes.forEach(function(checkbox) {
	  if (checkbox.checked) {
		checkboxChecked = true;
	  }
	});
	if (!checkboxChecked) {
	  alert("Please select at least one favorite food");
	  return false;
	}
	
	// Validate radio buttons
	var radioChecked = false;
	[radiosMovies, radiosRadio, radiosEatOut, radiosWatchTV].forEach(function(radios) {
	  var radioGroupChecked = false;
	  radios.forEach(function(radio) {
		if (radio.checked) {
		  radioGroupChecked = true;
		}
	  });
	  if (!radioGroupChecked) {
		radioChecked = true;
	  }
	});
	if (radioChecked) {
	  alert("Please rate your level of agreement for all statements");
	  return false;
	}
}
		
		
		