<?php

function userIdToNameAndLastName($userId, $isAnonym){
    $host="localhost";
    $db_user="root";
    $db_password="";
    $db_name='outdoor_shop';
    $debug=1;    if($isAnonym) return 'Anonim';
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        return "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    }else{
        try{
            if($result = $connection->query("SELECT name, lastname FROM users WHERE id=$userId")){
                if($result->num_rows<=0){
                    return "Unknown";
                }else{
                    $name = $result->fetch_assoc();
                    return $name['name'].' '.$name['lastname'];
                }

            }
        }catch (Exception $e){
            return $e;
        }
    }

}


function productIdToName($itemId){
    $host="localhost";
    $db_user="root";
    $db_password="";
    $db_name='outdoor_shop';
    $debug=1;
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        return "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    }else{
        try{
            if($result = $connection->query("SELECT name FROM items WHERE id=$itemId")){
                if($result->num_rows<=0){
                    return "Unknown";
                }else{
                    $name = $result->fetch_assoc();
//                    echo $name['name'];
//                    exit();
                    return $name['name'];
                }

            }
        }catch (Exception $e){
            return $e;
        }
    }
    return 'Unknown';
}

function getTotalPrice($basketId){
    $host="localhost";
    $db_user="root";
    $db_password="";
    $db_name='outdoor_shop';
    $debug=1;
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0 && $debug == 1) {
        return "Error:  " . $connection->connect_errno . " Description" . $connection->connect_error;
    }else{
        try{
            if($result = $connection->query("SELECT total_price FROM basket WHERE id=$basketId")){
                if($result->num_rows<=0){
                    return 0;
                }else{
                    $total_price = $result->fetch_assoc();
                    return $total_price['total_price'];
                }

            }
        }catch (Exception $e){
            return $e;
        }
    }
    return 0;
}




?>