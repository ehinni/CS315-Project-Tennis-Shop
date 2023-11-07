// make sure dom has loaded before anything else
document.addEventListener('DOMContentLoaded', () => {
	
	// gets JSON from local storage when page loads
	const json = localStorage.getItem("JSON");
	// display element on HTML page
	if (json) {
		document.getElementById("textJSON").innerHTML = json;
	}

	// do the same for the giveaway form json
	const giveawayJson = localStorage.getItem("giveawayJSON");
	if (giveawayJson) {
		let html = "<p>Giveaway form submitted successfully. Some JSON is displayed below:</p>"
		document.getElementById("giveawayJSON").innerHTML = html + giveawayJson;
	}

	const clearCartBtn = document.getElementById("clearCart");
	if (clearCartBtn) {
		clearCartBtn.addEventListener("click", () => {
			// remove items and prices from the cart thats stored in local storage
			localStorage.setItem("items", []);
			localStorage.setItem("prices", []);
			// refresh page so cart updates
			location.reload();
		});
	}

	let itemArr = [];
	let priceArr = [];

	// make event listener for the add to cart buttons. Need to use class name because all the
	// add to cart buttons do the same thing and we cant use id because id needs to be unique
	const addToCartBtns = document.getElementsByClassName("addToCartButton");
	if (addToCartBtns) {
		// make events for every class in addToCartBtns
		Array.from(addToCartBtns).forEach((addToCartBtn) => {
      addToCartBtn.addEventListener('click', (event) => {
				// every event target name is in the form "name|price"
				let namePriceString = event.target.name;
		
				// split the string to separte name from price
				let myArray = namePriceString.split("|");
		
				// preserve item and price arrays in storage if page refreshes
				if (localStorage.getItem("items")) {
					itemArr = localStorage.getItem("items").split(",");
				}
				if (localStorage.getItem("prices")) {
					priceArr = localStorage.getItem("prices").split(",");
				}
		
		
				// add item and price to their respective arrays
				itemArr = [...itemArr, myArray[0]];
				priceArr = [...priceArr, myArray[1]];
		
				// store items and prices into local storage to preserve across refreshes 
				localStorage.setItem("items", itemArr);
				localStorage.setItem("prices", priceArr);

				// alert user that item has been added to their cart
				window.alert(myArray[0] + " has been added to your cart\n You can see what's in your cart by clicking on the cart icon")
			});
    });
	}
	
	// put form into variable and then add event listener for when user submits the form (if form is there in the first place)
	const form = document.getElementById("paymentForm");
	if (form) {
		form.addEventListener("submit", (event) => {
			// get the submitButton and disable it to prevent form getting submitted many times
			const submitButton = document.getElementById("submit");
			submitButton.disabled = true;
	
			// form info which will later be converted to JSON
			let formInfo = {};
			if (validateForm(formInfo)) {
	
				// creating object and JSON from the form info
				let myTextJSON = JSON.stringify(formInfo);
				// submitted the JSON in the local computer
				localStorage.setItem("JSON", myTextJSON);

				// remove items and prices from the cart table since it's now empty
				localStorage.setItem("items", []);
				localStorage.setItem("prices", []);
			}
			else {
				// User didn't enter proper data. Stop form submission
				event.preventDefault();
			}
	
			// enable the button back after
			submitButton.disabled = false;
		});
	}

	// getting the element and making an event listener for the giveaway form
	const giveawayForm = document.getElementById("giveawayForm");
	if (giveawayForm) {
		giveawayForm.addEventListener("submit", (event) => {
			// get the submitButton and disable it to prevent form getting submitted many times
			const submitButton = document.getElementById("submitGiveaway");
			submitButton.disabled = true;

			// form info which will later be converted to JSON
			let giveawayFormInfo = {};
			if (validateGiveawayForm(giveawayFormInfo)) {
	
				// creating object and JSON from the form info
				let myJSON = JSON.stringify(giveawayFormInfo);
				// submitted the JSON in the local computer
				localStorage.setItem("giveawayJSON", myJSON);
			}
			else {
				// User didn't enter proper data. Stop form submission
				event.preventDefault();
			}
	
			// enable the button back after
			submitButton.disabled = false;
		});
	}

	function validateForm(formInfo) {
		// values of what is in the form
		const formValues = [
			"payment", "cardNum", "expire", "code", "fname", "lname", "city",
			"state", "zip", "address", "phone", "accept"
		];

		// loop through the form values
		for (let index = 0; index < formValues.length; index++) {
			// get the value of everything in the form
			let fieldValue = document.querySelector("#" + formValues[index]).value;

			// if the value is empty, we want to stop the submission and alert the user
			if (fieldValue == null || fieldValue == "") {
				// the field was empty. We need to alert the user
				window.alert("Part of the form is empty. Please try again");
				return false;
			}
			
			// checks if the card number is valid
			if (formValues[index] === "cardNum" && !isValidCardNumber(fieldValue)) {
				window.alert("Credit card number is invalid. Please make sure it is in the format:\n XXXX XXXX XXXX XXXX where X is a number");
				return false;
			}
			else if (formValues[index] === "expire" && !isValidExpiration(fieldValue)) {
				window.alert("Expiration date field is invalid. Make sure it is in the format:\n XX/XX where X is a number");
				return false;
			}
			else if (formValues[index] === "code" && !isValidSecurity(fieldValue)) {
				window.alert("Security Code field is invalid. Make sure it's a 3 digit number");
				return false;
			}
			else if (formValues[index] === "zip" && !isValidZip(fieldValue)) {
				window.alert("Zip Code field is invalid. Make sure it's a 5 digit number");
				return false;
			}
			else if (formValues[index] === "phone" && !isValidPhone(fieldValue)) {
				window.alert("Phone number field is invalid. Make sure it's in the format:\n XXX-XXX-XXXX where X is a number");
				return false;
			}
			

			// add onto formInfo object. Prevered across function call because objects are passed by reference
			formInfo[formValues[index]] = fieldValue;
		}

		// sees if the terms and conditons checkbox in checked. If not stop submission and alert user
		let isChecked = document.getElementById("accept");
		if (!isChecked.checked) {
			window.alert("Please accept the terms and conditions");
			return false;
		}

		// form is valid
		return true;
	}

	function validateGiveawayForm(formInfo) {
		const formValues = ["fnameGiveaway", "lnameGiveaway", "choiceItem", "reason"];

		// this form will only be invalid if a field is empty
		for (let index = 0; index < formValues.length; index++) {
			// get the value of everything in the form
			let fieldValue = document.querySelector("#" + formValues[index]).value;

			// if the value is empty, we want to stop the submission and alert the user
			if (fieldValue == null || fieldValue == "") {
				// the field was empty, alert the user
				window.alert("Part of the form is empty. Please try again");
				return false;
			}
			// add onto formInfo object. Prevered across function call because objects are passed by reference
			formInfo[formValues[index]] = fieldValue;
		}

		// form is valid. nothing is empty
		return true;
	}

	// reset local storage for future displays
	// (this is so the JSON doesn't remain on the page after user goes on and off the page)
	// not practical for actual website, just used for the purpose of this assignment
	localStorage.setItem("JSON", "");
	localStorage.setItem("giveawayJSON", "");

	// returns true if passed value is a number. False otherwise
	function isNum(number) {
		return !isNaN(parseFloat(number)) && isFinite(number);
	}

	// start of the creation of the table that displays the item and the price
	orderTable = "<tr> <th>Item</th> <th>Cost ($)</th> </tr>"

	// keep track of total
	let total = 0
	for (let i = 0; i < localStorage.getItem("prices").split(",").length; i++) {
		// for every item, add on the name and price
    orderTable+="<tr>";
		// note that localStorage.getItem("prices").split(",") is the saved array of prices. Same with items array
    orderTable+="<th>"+localStorage.getItem("items").split(",")[i]+"</th>";
    orderTable+="<th>"+localStorage.getItem("prices").split(",")[i]+"</th>";
    orderTable+="</tr>";
		// add on to total (the + before the expression converts the string to an int)
		total += +localStorage.getItem("prices").split(",")[i];
	}
	// add on a total row
	orderTable += "<tr> <th>Total</th> <th>"+total+"</th> </tr>"

	if (document.getElementById("orderTable")) {
		document.getElementById("orderTable").innerHTML = orderTable;
	}

	// checks if input is a valid card number
	function isValidCardNumber(cardNumber) {
		// card number without spaces
		let normalizedCardNumber = "";  
		for (let cardPos = 0; cardPos < cardNumber.length; cardPos++) {
			// add all numbers to the normalized string
			if (isNum(cardNumber[cardPos])) {
				normalizedCardNumber += cardNumber[cardPos];
			}
		}

		// make sure card number is 16 characters. If so, return true
		// normalized is already all numbers which would match format of a card number
		return normalizedCardNumber.length === 16;
	}

	// checks if user entered a valid expiration date
	function isValidExpiration(expirationDate) {
		// all expiration dates in the form XX/XX, which has length = 5 and a / in index 2
		if (expirationDate.length !== 5 || expirationDate[2] !== '/') {
			return false;
		}
		for (let index = 0; index < expirationDate.length; index++) {
			// these indexes (every index except 2) should all be numbers. If they are not, return false
			if (index !== 2 && !isNum(expirationDate[index])) {
				return false;
			}
		}

		// valid expiration date
		return true;
	}

	// checks if user entered a valid security code
	function isValidSecurity(securityCode) {
		// security codes are 3 digits and a number
		return isNum(securityCode) && securityCode.length === 3;
	}

	// returns true if user entered a valid zip code
	function isValidZip(zip) {
		// website is based in U.S. where all zip codes are 5 digit numbers
		return isNum(zip) && zip.length === 5;
	}

	// checks if user entered phone number in the valid format of XXX-XXX-XXXX
	function isValidPhone(phoneNumber) {
		// needs to be in the format XXX-XXX-XXXX. which has a - in index 3 and 7
		if (phoneNumber[3] !== "-" && phoneNumber[7] !== "-") {
			return false;
		}
		// split on -
		let normalizedPhone = phoneNumber.split("-");
		for (let index = 0; index < normalizedPhone.length; index++) {
			// if a part of the split phone number is not a number, return false
			if (!isNum(normalizedPhone[index])) {
				return false;
			}
		}

		// last thing to check is if the phone number length with the -'s is 12
		return phoneNumber.length === 12;
	}
	
});