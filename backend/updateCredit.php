<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    include('db_connect.php');   
    include('random.php');   

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };
    $agent = json_decode(file_get_contents('php://input'), true)['agent'];
    $action = json_decode(file_get_contents('php://input'), true)['action'];
    
    $arr = array();
    $message = array();
    $a_id = $agent;

    $query  =  "SELECT a_credits
                FROM agent
                WHERE a_id = '$a_id'";
    $result = $conn->query($query);  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }
    }
    $credits = $arr[0]['a_credits'];
    switch($action){
        case 'add' : $credits = intval($credits) + 1; break;
        case 'subtract' : $credits = intval($credits) - 1; break;
    }
    $query  =  "UPDATE agent
                SET a_credits = '$credits'
                WHERE a_id = '$a_id'";
    $result = $conn->query($query);  
    $message['result'] = $result;
    if($result){
        $message['message'] = "Entrepreneur credits changed!";
    }else{
        $message['message'] = "An error occured please try again!";     
    }
    
    echo json_encode($message);                                        
    $conn->close();
?>