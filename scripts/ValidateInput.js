/**
* Author: Rory Free
* Target: AddRecord.php
* Purpose: Takes input from page and validates it using regex
* Created: 24/09/2019
* Last updated: 30/09/2019
*/

function validateInput()
{
    var errMsg = "";

    var productId = document.getElementById("productId").value;
    var quantity = document.getElementById("quantity").value;
    var price = document.getElementById("price").value;
    var date = document.getElementById("dateBox").value;

    if (productId == "") {
        errMsg += "Please enter a product ID.\n";
    }
    else if (isNaN(productId)) {
        errMsg += "Please make sure your product ID is a non-negative integer.\n";
    }
    //parseInt may be unnecesary here, need to do more research on what value() returns based on context
    else if (parseInt(productId) < 0) {
        errMsg += "Please make sure your quantity is non-negative.\n";
    }
    if (quantity == "") {
        errMsg += "Please enter a quantity.\n";
    }
    else if (isNaN(quantity)) {
        errMsg += "Please make sure your quantity is a non-negative integer.\n";
    }
    else if (parseInt(quantity) < 0) {
        errMsg += "Please make sure your quantity is non-negative.\n";
    }
    if (price == "") {
        errMsg += "Please enter a price.\n";
    }
    else if (!price.match("^[0-9]*(\.[0-9]{1,2})?$"))
    {
        errMsg += "Please make sure the price is a positive decimal or integer.\n";
    }
    if (date == "") {
        errMsg += "Please enter a date.\n";
    }
    

    var inputValid = errMsg != "";

	if (!inputValid) {
		alert(errMsg)
	}

    return inputValid;
}
