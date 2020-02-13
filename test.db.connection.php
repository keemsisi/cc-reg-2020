<?php
    
    require "db.config.php";
    // $conn->qeury("SELECT * from cc_reg_2020")
    if ($conn != NULL ) {
        echo "database connection is running";
    }else {
        echo "database connection is not running yet...";
    }
    
?>