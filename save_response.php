<?php
//**author : Lasisi Akeem Adeshina */
//**github account : https://github.com/keemsisi */
//**email : keemsisi@gmail.com */
//**language : php*/
//**description : conference registration for singles and married men*/
//**year : Febraury - 12 - 2020 */
//**import the mysql database connection to this php file */
require "db.config.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Method: POST, GET , UPDATE , DELETE");
// header("Access-Control-Allow-Origin: *");
/**create a prepared SQL statement to be executed */
$sql = "INSERT INTO cc_reg_2020.attendees
(
    surname,
    firstname ,
    email_address,
    sex,
    birthday,
    educational_status ,
    phone_number,
    coming_from ,
    times_attended,
    partner_surname,
    partner_firstname ,
    partner_email_address ,
    partner_sex ,
    partner_birthday ,
    partner_educational_status ,
    partner_phone_number,
    partner_coming_from,
    age_of_courtship,
    name_of_pastor ,
    name_of_church ,
    address_of_church ,
    your_pastor_phone_number,
    marital_status,
    amount_paid
) VALUES
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
/* Prepared statement, stage 1: prepare */
if (!($stmt = $conn->prepare($sql))) {
    server_error_logger("Prepare failed: (" . $conn->errno . ") " . $conn->error , "500");
}
server_request_dumper(json_encode($_GET));
if (!$stmt->bind_param(
    "ssssssssssssssssssssssss",
    $_GET['surname'],
    $_GET['firstname'],
    $_GET['email_address'],
    $_GET['sex'],
    $_GET['birthday'],
    $_GET['educational_status'],
    $_GET['phone_number'],
    $_GET['coming_from'],
    $_GET['times_attended'],
    $_GET['partner_surname'],
    $_GET['partner_firstname'],
    $_GET['partner_email_address'],
    $_GET['partner_sex'],
    $_GET['partner_birthday'],
    $_GET['partner_educational_status'],
    $_GET['partner_phone_number'],
    $_GET['partner_coming_from'],
    $_GET['age_of_courtship'],
    $_GET['name_of_pastor'],
    $_GET['name_of_church'],
    $_GET['address_of_church'],
    $_GET['your_pastor_phone_number'],
    $_GET['marital_status'],
    $_GET['amount_paid'] 
)) {
    server_error_logger("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error , $stmt);
}
if (!$stmt->execute()) {
    server_error_logger("Execute failed: (" . $stmt->errno . ") " . $stmt->error ,"500");
    http_response_code(500);
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    close_prepared_statement($stmt);
}else {
    server_error_logger("A new member was registered at". time_generator() ."after payment was successful!", "200");
    echo "execution completed";
    echo $stmt->get_result(); // get the mysqli result
    http_response_code(200); //successfully inserted into the database 
}
/**functions to handle to operations on the server */
/**function to handle the server  error logging */
function server_error_logger(string $message , string $response_code ) {
    $file = fopen("server.log","a");
    fwrite($file ,  "############################ START ####################################\n");
    fwrite($file ,  "At ".time_generator()." the message was generated.\n");
    fwrite($file ,  $message." SERVER RESPONSE CODE :::: ".$response_code);
    fwrite($file ,  "\n############################# END ###################################\n\n");
}
//**dumped the request to the server when ever registeration is about to take place */
function server_request_dumper(string $message ) {
    $file = fopen("jsondumps.log","a");
    fwrite($file ,  "############################  START ####################################\n");
    fwrite($file ,  "At ".time_generator()." the request was dumped to he logger file.\n");
    fwrite($file ,  $message."\n\n");
    fwrite($file ,  "############################# END ###################################\n\n");
}
/**close the prepared statement */
function close_prepared_statement($stmt){
    $stmt->close() ; //close the prepared statement of the database connection 
}
/**generate the local time from the server and return a string of the time generated */
function time_generator(){
    return date('m/d/Y h:i:s a', time());
}
?>