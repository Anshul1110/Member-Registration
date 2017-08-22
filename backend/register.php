<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    include('db_connect.php');   
    include('random.php');   
    

    //This line to get the input from front end
    $user = json_decode(file_get_contents('php://input'), true);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };

    //Yaha par likhna hai pura code:
    $message = array();
    $message["result"] = false;
    $u_id = 'A'.generateRand();

    $u_fname = $user['fname'];
    $u_lname = $user['lname'];
    /*aise baki fields*/
    $stmt = $conn->prepare("INSERT INTO agent ( 
                                a_id, 
                                a_fname, 
                                a_lname
                                baki fields
                            )
                            VALUES (?, ?, ?, ?, ?)"); /*same no of question marks as fields...ok*/

    $stmt->bind_param("sssss", /*same no of s as fields*/
                        $u_id, 
                        $u_fname, 
                        $u_lname
                        rest fields);
    if($stmt->execute()){
        $message["result"] = true;
        $message["user"] = $user;
    }
    echo json_encode($message);

    /*
    
    switch ($user['r']) {
        case "0":
            clientLogin($user, $conn);
            break;
        case "1":
            helpDeskLogin($user, $conn);
            break;
        case "2":
            adminLogin($user, $conn);
            break;
    }

    function saveLoginDetails($user, $conn){
        $stmt = $conn->prepare("INSERT INTO x_lgn_il354_x ( 
                                    x_user_name, 
                                    x_timestamp, 
                                    x_ip,
                                    x_user_agent,
                                    x_type
                                )
                                VALUES (?, ?, ?, ?, ?)");
        $un = $user['u'];
        $ts = date('Y-m-d H:i:s');
        $ip = $user['ip'];
        $ua = $user['ua'];
        $tp = $user['r'];
        $stmt->bind_param("sssss", 
                            $un, 
                            $ts, 
                            $ip, 
                            $ua,
                            $tp);
        if($stmt->execute()){
            $message["result"] = true;
            $message["user"] = $user;
        }

        $conn->close();
        echo json_encode($message);        
    }

    function clientLogin($user, $conn){
        $u = $user['u'];
        $p = md5($user['p']);
        $message["result"] = false;
        $arr = array();
        $query  =  "SELECT x_user_nm_k106, x_role1911_jar 
                    FROM x_cli_e4l_xp0
                    WHERE x_user_nm_k106 = '$u'
                    AND x_role1911_jar = 'client'";
        $result = $conn->query($query);  
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
        }

        if(count($arr) > 0){
            unset($arr);
            $arr = array();
            $query  =  "SELECT x_user_nm_k106, x_role1911_jar 
                    FROM x_cli_e4l_xp0
                    WHERE x_user_nm_k106 = '$u'
                    AND x_pwd_i201_m2a = '$p'";
            $result = $conn->query($query);          
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
            }
            if(count($arr) > 0){
                saveLoginDetails($user, $conn);
            }else{
                echo json_encode($message);
            }
        }
        
    }
    
    function helpDeskLogin($user, $conn){
        $u = $user['u'];
        $p = md5($user['p']);
        $message["result"] = false;
        $arr = array();
        $query  =  "SELECT x_user_nm_k106, x_role1911_jar 
                    FROM x_hdx_emp837
                    WHERE x_user_nm_k106 = '$u'
                    AND x_role1911_jar = 'hdemp'";
        $result = $conn->query($query);  
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
        }

        if(count($arr) > 0){
            unset($arr);
            $arr = array();
            $query  =  "SELECT x_user_nm_k106, x_role1911_jar 
                    FROM x_hdx_emp837
                    WHERE x_user_nm_k106 = '$u'
                    AND x_pwd_i201_m2a = '$p'";
            $result = $conn->query($query);          
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
            }
            if(count($arr) > 0){
                saveLoginDetails($user, $conn);
            }else{
                echo json_encode($message);
            }
        }
        
    }

    function adminLogin($user, $conn){
        $message = array();
        $u = $user['u'];
        $p = md5($user['p']);
        $message["result"] = false;
        $arr = array();
        $query  =  "SELECT x_user_nm_x870, x_role0834_exz 
                    FROM x_adm_p0x_ze3
                    WHERE x_user_nm_x870 = '$u'
                    AND x_role0834_exz = 'admin'";
        $result = $conn->query($query);  
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
        }

        if(count($arr) > 0){
            unset($arr);
            $arr = array();
            $query  =  "SELECT x_user_nm_x870, x_role0834_exz 
                    FROM x_adm_p0x_ze3
                    WHERE x_user_nm_x870 = '$u'
                    AND x_pwd_019_zex = '$p'";
            $result = $conn->query($query);          
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
            }
            if(count($arr) > 0){
                saveLoginDetails($user, $conn);                
            }else{
                echo json_encode($message);
            }
        }
    }*/

?>