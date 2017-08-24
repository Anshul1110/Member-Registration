<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    include('db_connect.php');   
    include('random.php');   

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    $product = json_decode(file_get_contents('php://input'), true);
    $arr = array();
    $message = array();
    $p_id = $product['p_id'];
    $p_code = $product['p_code'];

    $query  =  "UPDATE product
                SET p_status = 'Pending'
                WHERE p_code = '$p_code'
                AND p_status = 'Active'";
    $conn->query($query);  

    $query  =  "UPDATE product
                SET p_status = 'Active'
                WHERE p_id = '$p_id'";
    $result = $conn->query($query);  

    $message['result'] = $result;
    if($result){
        $message['message'] = "Product approved!";
    }else{
        $message['message'] = "An error occured please try again!";     
    }
    
    echo json_encode($message);                                        
    $conn->close();
?>