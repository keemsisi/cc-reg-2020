

var responsePayload = {};
var xml = new XMLHttpRequest();
var checkCharge = new XMLHttpRequest();
var alreadyRegistered;
var status;
var formFields = new FormData(document.getElementById('form-data'));
// const API_publicKey = "FLWPUBK-08b9b714bde7cf75b92041a3f987b210-X"; //production key
const API_publicKey = "FLWPUBK-c03c8b729c68969b6bdbef3f94898484-X" ; // testing environment key
document.getElementById('process-payment').onclick = (event) => {
    let email = document.getElementById('email_address').value;
    let phone_number = document.getElementById('phone_number').value;
    let partner_phone_number = document.getElementById('partner_phone_number').value;
    let partner_email_address = document.getElementById('partner_email_address').value;
    amount_to_pay = responsePayload.amount_paid;
    let firstname = document.getElementById("firstname").value;
    let lastname = document.getElementById("surname").value
    alreadyRegistered = false;
    status = 200;
    console.log(email, phone_number, partner_phone_number, partner_email_address)
    checkCharge.open("GET", `exists.php?email_address=${email}&&phone_number=${phone_number}&&partner_email_address=${partner_email_address}&&partner_phone_number=${partner_phone_number}`);
    checkCharge.onreadystatechange = () => {
        if (checkCharge.readyState === 4 && checkCharge.status === 409) {
            alreadyRegistered = true;
            status = checkCharge.status;
            swal("Already Registered", "You have registered before, you can not register again", "error");
            return;
            // rave.close();
        } else if (checkCharge.readyState === 4 && checkCharge.status == 200) {
            registerNewGuest();
            return;
        }
        else if (checkCharge.readyState === 4 && checkCharge.status == 500) {
            console.log("Server Error... please contact the admin")
            alert("ERROR ::: 500");
        }
    }
    //console.log(responsePayload)
    checkCharge.send(JSON.stringify(responsePayload)); //send the form data
    function registerNewGuest() {
        /*********************************************
         * Process another payment for the new guest
         *********************************************
         */
        function HashCodeGenerator() { }
        HashCodeGenerator.prototype = {}; // object prototype
        HashCodeGenerator.prototype.hashCode = function (hashVariable) {
            var hash = 0, i, chr;
            if (this.length === 0) return hash;
            for (i = 0; i < this.length; i++) {
                chr = this.charCodeAt(i);
                hash = ((hash << 5) - hash) + chr;
                hash |= 0; // Convert to 32bit integer
            }
            return hash;
        };
        const hashedValue = new HashCodeGenerator().hashCode(email + firstname + lastname + phone_number + amount_to_pay);
        // getpaidSetup is Rave's inline script function. it holds the payment data to pass to Rave.
        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: email,
            amount: amount_to_pay,
            customer_phone: phone_number,
            currency: "NGN",
            txref: "rave-" + new Date().toDateString(),
            meta: [{
                metaname: "flightID",
                metavalue: "AP1234"
            }],
            onclose: function () {
                swal("Transaction was closed!", "Try again!", "error");
            },
            callback: function (response) {
                flw_ref = response.tx.flwRef;// collect flwRef returned and pass to a server page to complete status check.
                if (response.tx.status === "successful" && response.tx.chargeResponseCode == "00") {
                    x.close();
                    console.log(response)
                    document.getElementById("ripple").setAttribute("visibility", "visible");
                    var $form = $("#form-data");
                    var data = getFormData($form);
                    const query_params = `surname=${data['surname']}&&
                                firstname=${data['firstname']}&&
                                email_address=${data['email_address']}&&
                                sex=${data['sex']}&&
                                birthday=${data['birthday']}&&
                                educational_status=${data['educational_status']}&&
                                phone_number=${data['phone_number']}&&
                                coming_from=${data['coming_from']}&&
                                times_attended=${data['times_attended']}&&
                                partner_surname=${data['partner_surname']}&&
                                partner_firstname=${data['partner_firstname']}&&
                                partner_email_address=${data['partner_email_address']}&&
                                partner_sex=${data['partner_sex']}&&
                                partner_birthday=${data['partner_birthday']}&&
                                partner_educational_status=${data['partner_educational_status']}&&
                                partner_phone_number=${data['partner_phone_number']}&&
                                partner_coming_from=${data['partner_coming_from']}&&
                                age_of_courtship=${data['age_of_courtship']}&&
                                name_of_pastor=${data['name_of_pastor']}&&
                                name_of_church=${data['name_of_church']}&&
                                address_of_church=${data['address_of_church']}&&
                                your_pastor_phone_number=${data['partner_coming_from']}&&
                                marital_status=${data['marital_status']}&&                                
                                amount_paid=${amount_to_pay}`;
                    console.log(query_params);
                    sendData();
                    function sendData() {
                        var xml = new XMLHttpRequest();
                        xml.open(`GET`, `save_response.php?${query_params}`);
                        xml.onreadystatechange = () => {
                            if (xml.readyState === XMLHttpRequest.DONE && xml.status === 200) {
                                window.open("success.html", "_self");
                                console.log(xml.response);
                            } else if (xml.readyState === XMLHttpRequest.DONE && xml.status === 500) {
                                setTimeout(() => {
                                    swal("Failed to Send", "... retrying. DON'T CLOSE THE PAGE", "error");
                                    sendData(); //send the data again
                                }, 1000);
                                console.log(xml.statusText);
                                console.log("ERROR OCCURRED...sending again");
                            }
                        }
                        xml.send(); //send the form data
                    }
                    function getFormData($form) {
                        var unindexed_array = $form.serializeArray();
                        var indexed_array = {};
                        $.map(unindexed_array, function (n, i) {
                            indexed_array[n['name']] = n['value'];
                        });
                        return indexed_array;
                    }
                } else {
                    // redirect to a failure page.
                    swal("Payment Was not Successful", response.tx.status, "error");
                }
            }
        }); // end get paid set up
    }
}
var elements = null;
var elementLenght = 0;
var ms = null;
var checker = true;
setInterval(() => {
    document.getElementById('marital_status').onchange = () => {
        if (ms.value === "Single") {
            document.getElementById("amount").innerHTML = "Amount : <h3 style=\"color:green\">N3,000.00<h3>";
            //console.log(elements);
            responsePayload["marital_status"] = ms.value;
            responsePayload["amount_paid"] = 3000;
        }
        if (ms.value === "Married") {
            document.getElementById("amount").innerHTML = "Amount : <h3 style=\"color:green\">N5,000.00<h3>";
            responsePayload["marital_status"] = ms.value;
            responsePayload["amount_paid"] = 5000;
        }
    }
    elements = document.querySelectorAll('input,select');
    elementLenght = elements.length;
    ms = document.getElementById("marital_status");
    //console.log("Marital Status Value =>",ms.value);
    for (let i = 0; i < elementLenght; i++) {
        elements[i].value === "" ? checker = true : checker = false;
        if (checker == true) {
            break;
        } else {
            responsePayload[elements[i].name] = elements[i].value
        }
    }
    document.getElementById("process-payment").disabled = checker; // production stage
}, 100);
