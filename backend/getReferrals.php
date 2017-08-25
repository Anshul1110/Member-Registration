<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    include('db_connect.php');   
    include('random.php');   

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    $user = json_decode(file_get_contents('php://input'), true);
    
    switch ($user['r']) {
        case "Online Entrepreneur":
            $table = 'agent';
            $id_field = 'a_id';
            break;
        case "Customer":
            $table = 'customer';
            $id_field = 'c_id';
            break;
        case "Merchant":
            $table = 'merchant';
            $id_field = 'm_id';
            break;
    }

    $db_id = $user[$id_field];
    $userArr = array();
    $query  =  "SELECT r_child_id
                FROM referral
                WHERE r_parent_id = '$db_id'";
    $result = $conn->query($query);  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr = array();
            $id = $row["r_child_id"];
            $query  =  "SELECT *
                FROM $table
                WHERE $id_field = '$id'";
            $newresult = $conn->query($query);
            while($newrow = $newresult->fetch_assoc()) {
                $arr[] = $newrow;
            }
            $userArr[] = $arr[0];
        }
    }   
    $message["referrals"] = $userArr;
    echo json_encode($message);                                        
    $conn->close();
?>