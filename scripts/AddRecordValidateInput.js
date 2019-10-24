/**
* Author: Rory Free
* Target: addrecord.php
* Purpose: Takes input from page and validates it using regex
* Created: 24/09/2019
* Last updated: 14/10/2019
*/

function validateInput()
{
    console.log("validateInput begin");
    var errMsg = "";

    var productId = document.getElementById("product").value;
    var quantity = document.getElementById("quantity").value;
    var price = document.getElementById("price").value;
    var date = document.getElementById("date").value;

    if (productId == "") {
        errMsg += "Please enter a product name.\n";
    }
    if (quantity == "") {
        errMsg += "Please enter a quantity.\n";
    }
    if (isNaN(quantity)) {
        errMsg += "Please make sure your quantity is a non-negative integer.\n";
    }
    else if (parseInt(quantity) < 0) {
        errMsg += "Please make sure your quantity is non-negative.\n";
    }
    if (price == "") {
        errMsg += "Please enter a price.\n";
    }
    else if (!price.toString().match(/[0-9]*(\.[0-9]{1,2})?/))
    {
        errMsg += "Please make sure the price is a positive decimal or integer.\n";
    }
    if (date == "") {
        errMsg += "Please enter a date.\n";
    }


    var inputValid = errMsg == "";

    //returns lowercase true or false in inputValid field.
    document.getElementById("inputValid").value = inputValid.toString();

    console.log("IN validateInput: inputValid found to be " + inputValid.toString() + ", errMsg reads \"" +errMsg+"\"");

	if (!inputValid) {
		alert(errMsg);
        return false;
    } else {
        return true;
    }
}
