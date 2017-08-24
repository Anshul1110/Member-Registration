<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    include('db_connect.php');   
    include('random.php');   
    include('MAIL/PHPMailerAutoload.php');

    $user = json_decode(file_get_contents('php://input'), true);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };

    $message = array();
    $message["result"] = false;
    
    checkIfUserExists($user, $conn);

    function checkIfUserExists($user, $conn){
        $uname = $user['uname'];
        $query  =  "SELECT l_id
                    FROM login
                    WHERE l_user = '$uname'";
        $result = $conn->query($query);  
        if ($result->num_rows > 0) {
            $message['message'] = "Please use a different username.";
            echo json_encode($message);
        }else{
            switch ($user['r']) {
                case "Customer":
                    customerRegister($user, $conn);
                    break;
                case "Online Entrepreneur":
                    agentRegister($user, $conn);
                    break;
                case "Merchant":
                    merchantRegister($user, $conn);
                    break;
            }
        }
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
        $m_ref = generateRefcode("M");
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
                                    m_url,
                                    m_ref
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
                            $m_ref);

        if($stmt->execute()){
            addLoginDetails($user, $conn);
        }
    }

    function agentRegister($user, $conn){
        $a_id = 'A'.generateRand();
        $user['id'] = $a_id;
        $a_fname = $user['fname'];
        $a_lname = $user['lname'];
        $a_uname = $user['uname'];
        $a_pass = $user['pass'];
        $a_cpass = $user['cpass'];
        $a_add = $user['add'];
        $a_city = $user['city'];
        $a_state = $user['state'];
        $a_zip = $user['zip'];
        $a_comp = $user['comp'];
        $a_numb = $user['numb'];
        $a_email = $user['email'];
        $a_url = $user['url'];
        $a_credits = $user['credits'];
        $a_ref = generateRefcode("A");
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
                                    a_credits,
                                    a_ref
                                )
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); 
 
        $stmt->bind_param("sssssssssssss", 
                            $a_id, 
                            $a_fname, 
                            $a_lname,
                            $a_add,
                            $a_city,
                            $a_state,
                            $a_zip,
                            $a_comp,
                            $a_numb,
                            $a_email,
                            $a_url,
                            $a_credits,
                            $a_ref
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
        $c_ref = generateRefcode("C");
        $stmt = $conn->prepare("INSERT INTO customer ( 
                                    c_id, 
                                    c_fname, 
                                    c_lname,
                                    c_email,
                                    c_nic,
                                    c_number,
                                    c_dob,
                                    c_ref
                                )
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", 
                            $c_id, 
                            $c_fname, 
                            $c_lname,
                            $c_email,
                            $c_nic,
                            $c_numb,
                            $c_dob,
                            $c_ref
                            );
        if($stmt->execute()){
            addLoginDetails($user, $conn);
        }
    }
    
    function addLoginDetails($user, $conn){
        //Check if referred
        $refCode = $user["c"];
        if($refCode!=""){
            $arr = array();
            switch ($user['r']) {
                case "Online Entrepreneur":
                    $table = 'agent';
                    $id_field = 'a_id';
                    $ref_field = 'a_ref';
                    break;
                case "Customer":
                    $table = 'customer';
                    $id_field = 'c_id';
                    $ref_field = 'c_ref';
                    break;
                case "Merchant":
                    $table = 'merchant';
                    $id_field = 'm_id';
                    $ref_field = 'm_ref';
                    break;
            }
            $query  =  "SELECT *
                        FROM $table
                        WHERE $ref_field = '$refCode'";
            $result = $conn->query($query);  
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $arr[] = $row;
                }
                $r_id = 'R'.generateRand();
                $parent_id = $arr[0][$id_field];
                $child_id = $user['id'];
                $stmt = $conn->prepare("INSERT INTO referral ( 
                                            r_id,
                                            r_code, 
                                            r_parent_id, 
                                            r_child_id
                                        )
                                        VALUES (?, ?, ?, ?)"); 
                $stmt->bind_param("ssss", 
                                    $r_id, 
                                    $refCode,
                                    $parent_id,
                                    $child_id
                                    );
                $stmt->execute();
            }
        }

        if( $user['pass'] == $user['cpass']){
            $l_id = $user['id'];
            $l_role = $user['r'];
            $l_user = $user['uname'];
            $l_status = "Pending";
            $id_md5 = md5($user['id']);
            $l_token = md5($user['r']).$id_md5;
            $l_pass = md5($user['pass']);
            $stmt = $conn->prepare("INSERT INTO login ( 
                                        l_id,
                                        l_role, 
                                        l_user, 
                                        l_pass,
                                        l_token,
                                        l_status
                                    )
                                    VALUES (?, ?, ?, ?, ?, ?)"); 
            $stmt->bind_param("ssssss", 
                                $l_id, 
                                $l_role, 
                                $l_user,
                                $l_pass,
                                $l_token,
                                $l_status
                                );
            if($stmt->execute()){
                $verifurl = "http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/projects/Member-Registration"."/backend/verify.php?xcj=".$id_md5."&tc=".$l_token;
                $message["result"] = true;
                $message["user"] = $user;
                $message["url"] = $verifurl;
                $message["message"] = "Thank you for registering, a verification E-Mail has been sent to your E-Mail ID. You can login after verifying successfully.";
                sendVerificationEmail($message, $user);
            }
        }else{
            $message['result'] = false;
            echo json_encode($message);
        }
    }

    function sendVerificationEmail($message, $user){
        $mail = new PHPMailer;
        $mail->IsSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'sg2plcpnl0102.prod.sin2.secureserver.net';  // Specify main and backup SMTP servers
        $mail->Port = 465;                                    // TCP port to connect to
        $mail->SMTPDebug  = 0;           
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->SMTPSecure = "ssl";
        $mail->Username = 'anurag@envisagecyberart.in';                 // SMTP username
        $mail->Password = 'sbb-4645752';                           // SMTP password
        $mail->IsHTML(true);  
        $mail->setFrom('anurag@envisagecyberart.in', 'Member Registration Admin');        
        $mail->addAddress($user["email"], $user["fname"]);     // Add a recipient
        $mail->Subject = 'Member Verification E-Mail | '.$user['r'].' '.$user['fname'].' '.$user['lname'];
        $body = '<div style="font-size:1.5em;">';
        $body.=     '<h3>Hello, '.$user["fname"]." ".$user["lname"].'!</h3>';
        $body.=     '<p>Thank you for registering with us as a '.$user['r'].'. Click on the below button to complete the verification process.</p><br/>';
        $body.=     '<a style="cursor:pointer;" href="'.$message["url"].'"><button style="font-size:1em; padding:0.5em;">Verify Account</button></>';
        $body.= '</div>';
        $mail->Body = $body;
        if(!$mail->send()) {
            $message['mailsent'] = false;
        } else {
            $message['mailsent'] = true;
        }
        echo json_encode($message);
    }

    $conn->close();
?>