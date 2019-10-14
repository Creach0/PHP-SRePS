/**
* Author: Rory Free
* Target: findrecord.php
* Purpose: Takes input from page and validates it using regex
* Created: 14/10/2019
* Last updated: 14/10/2019
*/

function validateInput()
{
    console.log("validateInput begin");
    var errMsg = "";

    var saleid = document.getElementById("saleid").value;
    var productId = document.getElementById("product").value;
    var quantity = document.getElementById("quantity").value;
    var price = document.getElementById("price").value;
    var date = document.getElementById("date").value;

    //Makes sure that it's not one or the other
    if (isNaN(saleid) && saleid != "") {
        errMsg += "Please make sure the sale ID is a non-negative integer.\n";
    }
    else if (parseInt(saleid) < 0) {
        errMsg += "Please make sure sale ID is non-negative.\n";
    }
    if (isNaN(quantity) && quantity != "") {
        errMsg += "Please make sure your quantity is a non-negative integer.\n";
    }
    else if (parseInt(quantity) < 0) {
        errMsg += "Please make sure your quantity is non-negative.\n";
    }
    if (isNaN(price) && price != "")
    {
        errMsg += "Please make sure the price is a  decimal or integer.\n";
    }
    else if (!price.match("^[0-9]*(\.[0-9]{1,2})?$"))
    {
        errMsg += "Please make sure the price is a positive decimal or integer.\n";
    }
    

    var inputValid = errMsg == "";
    
    //returns lowercase true or false in inputValid field.
    document.getElementById("inputValid").value = inputValid.toString();

    console.log("IN validateInput: inputValid found to be " + inputValid.toString() + ", errMsg reads \"" +errMsg+"\"");

	if (!inputValid) {
		alert(errMsg)
    }
}