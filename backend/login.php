<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    include('db_connect.php');   
    
    $user = json_decode(file_get_contents('php://input'), true);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };

    $u = $user['u'];
    $r = $user['r'];
    $p = md5($user['p']);
    
    $arr = array();
    $message = array();
    $message["result"] = false;
    $query  =  "SELECT l_id
                FROM login
                WHERE l_user = '$u'
                AND l_role = '$r'
                AND l_pass = '$p'
                AND l_status = 'Active'";
    $result = $conn->query($query);  
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arr[] = $row;
        }
    }

    if(count($arr) > 0){
        $db_id = $arr[0]["l_id"];
        unset($arr);
        $arr = array();
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
        $query  =  "SELECT *
                    FROM $table
                    WHERE $id_field = '$db_id'";
        $result = $conn->query($query);  
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
        }
        $message["message"] = "Login Successful.";
        $message["result"] = true;
        $message["user"] = $arr;
    }else{
        $message["message"] = "Wrong User ID/Password.";
    }
    echo json_encode($message);
    $conn->close();
?>