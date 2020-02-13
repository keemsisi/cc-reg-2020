<?php

require 'db.config.php';

$SQL =  "SELECT * FROM cc_reg_2020.attendees
WHERE phone_number=?
OR email_address=? 
OR partner_phone_number=? 
OR partner_email_address=?
";
/* Prepared statement, stage 1: prepare */
if (!($stmt = $conn->prepare($SQL))) {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
}
/* Prepared statement, stage 2: bind and execute */
if (!$stmt->bind_param(
    "ssss", 
    $_GET['phone_number'],
    $_GET['email_address']  ,
    $_GET['partner_phone_number'] , 
    $_GET['partner_email_address'] 
)) {
   echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
echo json_encode($stmt->execute());
if (!$stmt->execute()) {
    $file = fopen("server.log","a");
    fwrite($file ,  "################################################################\n");
    fwrite($file ,  "At ".date('m/d/Y h:i:s a', time())." error occured\n");
    fwrite($file ,  "Response code sent "."500\n");
    fwrite($file ,  "################################################################\n\n");
    http_response_code(500);
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;

}else {
    $result = $stmt->get_result(); // get the mysqli result
    if ( $result->num_rows > 0 ) {
        $member = $result->fetch_assoc(); // fetch data 
        $file = fopen("server.log","a");
        fwrite($file ,  "###############################EXISTS CHECK OPEN #################################\n");
        fwrite($file ,  "At ".date('m/d/Y h:i:s a', time())." a visitor checked if account has already been registered\n");
        fwrite($file ,  "Request that was received at the time ".json_encode($_GET)."\n");
        fwrite($file ,  "Request time ".date('m/d/Y h:i:s a', time())."\n");
        fwrite($file ,  "Response code sent "."200\n");
        fwrite($file ,  "###############################EXISTS CHECK CLOSED #################################\n\n");
        http_response_code(409);
    }else {
        $file = fopen("server.log","a");
        fwrite($file ,  "############################### EXISTS CHECK OPEN #################################\n");
        fwrite($file ,  "At ".date('m/d/Y h:i:s a', time())." a visitor checked if account has already been registered\n");
        fwrite($file ,  "Request that was received at the time ".json_encode($_GET)."\n");
        fwrite($file ,  "Request time ".date('m/d/Y h:i:s a', time())."\n");
        fwrite($file ,  "Response code sent "."409\n");
        fwrite($file ,  "###############################EXISTS CHECK CLOSED #################################\n\n");
        http_response_code(200);
        echo "no member was found... member can proceed to register new account";
    }
}
$stmt->close() ;
?>