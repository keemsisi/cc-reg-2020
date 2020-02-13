<?php
require "db.config.php" ;
__init__db__table__($conn);
function __init__db__table__ ($conn) {
    $SQL_QUERY_NEW_TABLE = "CREATE TABLE IF NOT EXISTS cc_reg_2020.attendees (
        surname varchar(300) NOT NULL,
        firstname varchar(300) NOT NULL,
        email_address varchar(300) unique NOT NULL,
        sex varchar(15) NOT NULL,
        birthday varchar(20) NOT NULL,
        educational_status varchar(200) NOT NULL,
        phone_number varchar(13) primary key,
        coming_from varchar(300)  NOT NULL,
        times_attended varchar(1) NOT NULL,
        partner_surname varchar(300) NOT NULL,
        partner_firstname varchar(300) NOT NULL,
        partner_email_address varchar (300) unique NOT NULL,
        partner_sex varchar(10) NOT NULL,
        partner_birthday varchar(30) NOT NULL,
        partner_educational_status varchar(30) NOT NULL,
        partner_phone_number varchar(13) unique,
        partner_coming_from varchar(300)  NOT NULL,
        age_of_courtship integer NOT NULL,
        name_of_pastor varchar(300) not null ,
        name_of_church varchar(300) not null, 
        address_of_church varchar(300) not null, 
        your_pastor_phone_number varchar(300) not null,
        marital_status varchar(300) NOT null,
        amount_paid varchar(20) not null,
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    if ($conn->query($SQL_QUERY_NEW_TABLE)) {
        echo "Table  member created successfully";
    }else {
        echo "TABLE_ALREADY_CREATED ::: " . $conn->error;
    }
}