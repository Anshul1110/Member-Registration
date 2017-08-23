<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    include('db_connect.php');   
    include('random.php');   

    $user = json_decode(file_get_contents('php://input'), true);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };

    $message = array();
    $message["result"] = false;
    $message["data"] = "anshul";

    switch ($user['r']) {
        case "Customer":
            customerRegister($user, $conn);
            break;
        case "Agent":
            agentRegister($user, $conn);
            break;
        case "Merchant":
            merchantRegister($user, $conn);
            break;
    }

    function merchantRegister($user, $conn){
        $m_id = 'M'.generateRand();
        $user['id'] = $m_id;
        $m_fname = $user['fname'];
        $m_lname = $user['lname'];
        $m_uname = $user['uname'];
        $m_pass = $user['pass'];
        $m_cpass = $user['cpass'];
        $m_add = $user['add'];
        $m_city = $user['city'];
        $m_state = $user['state'];
        $m_zip = $user['zip'];
        $m_comp = $user['comp'];
        $m_numb = $user['numb'];
        $m_email = $user['email'];
        $m_url = $user['url'];
        $stmt = $conn->prepare("INSERT INTO merchant ( 
                                    m_id, 
                                    m_fname, 
                                    m_lname,
                                    m_address,                                    
                                    m_city,
                                    m_state,
                                    m_zip,
                                    m_company,
                                    m_number,
                                    m_email,
                                    m_url
                                )
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
 
        $stmt->bind_param("sssssssssss", 
                            $m_id, 
                            $m_fname, 
                            $m_lname,
                            $m_add,
                            $m_city,
                            $m_state,
                            $m_zip,
                            $m_comp,
                            $m_numb,
                            $m_email,
                            $m_url);

        if($stmt->execute()){
            addLoginDetails($user, $conn);
        }
    }

    function agentRegister($user, $conn){
        $m_id = 'A'.generateRand();
        $user['id'] = $m_id;
        $m_fname = $user['fname'];
        $m_lname = $user['lname'];
        $m_uname = $user['uname'];
        $m_pass = $user['pass'];
        $m_cpass = $user['cpass'];
        $m_add = $user['add'];
        $m_city = $user['city'];
        $m_state = $user['state'];
        $m_zip = $user['zip'];
        $m_comp = $user['comp'];
        $m_numb = $user['numb'];
        $m_email = $user['email'];
        $m_url = $user['url'];
        $m_credits = $user['credits'];
        $stmt = $conn->prepare("INSERT INTO agent ( 
                                    a_id, 
                                    a_fname, 
                                    a_lname,
                                    a_address,                                    
                                    a_city,
                                    a_state,
                                    a_zip,
                                    a_company,
                                    a_number,
                                    a_email,
                                    a_url,
                                    a_credits
                                )
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
 
        $stmt->bind_param("ssssssssssss", 
                            $m_id, 
                            $m_fname, 
                            $m_lname,
                            $m_add,
                            $m_city,
                            $m_state,
                            $m_zip,
                            $m_comp,
                            $m_numb,
                            $m_email,
                            $m_url,
                            $m_credits
                            );

        if($stmt->execute()){
            addLoginDetails($user, $conn);
        }
    }

    function customerRegister($user, $conn){
        $c_id = 'C'.generateRand();
        $user['id'] = $c_id;
        $c_fname = $user['fname'];
        $c_lname = $user['lname'];
        $c_email = $user['email'];
        $c_nic = $user['nic'];
        $c_dob = $user['sqlDob'];
        $c_numb = $user['numb'];
        $stmt = $conn->prepare("INSERT INTO customer ( 
                                    c_id, 
                                    c_fname, 
                                    c_lname,
                                    c_email,
                                    c_nic,
                                    c_number,
                                    c_dob
                                )
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", 
                            $c_id, 
                            $c_fname, 
                            $c_lname,
                            $c_email,
                            $c_nic,
                            $c_numb,
                            $c_dob
                            );
        if($stmt->execute()){
            addLoginDetails($user, $conn);
        }
    }
    
    function addLoginDetails($user, $conn){
        $l_id = $user['id'];
        $l_role = $user['r'];
        $l_user = $user['uname'];
        if( $user['pass'] == $user['cpass']){
            $l_pass = md5($user['pass']);
            $stmt = $conn->prepare("INSERT INTO login ( 
                                        l_id,
                                        l_role, 
                                        l_user, 
                                        l_pass
                                    )
                                    VALUES (?, ?, ?, ?)"); 
            $stmt->bind_param("ssss", 
                                $l_id, 
                                $l_role, 
                                $l_user,
                                $l_pass
                                );
            if($stmt->execute()){
                $message["result"] = true;
                $message["user"] = $user;
                echo json_encode($message);
            }
        }else{
            $message['result'] = false;
            echo json_encode($message);
        }
        $conn->close();
    }
?>