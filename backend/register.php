<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    include('db_connect.php');   
    include('random.php');   
    

    //This line to get the input from front end
    $user = json_decode(file_get_contents('php://input'), true);
    //ye user sab case me bhej raha hai?... haan
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };

    //Yaha par likhna hai pura code:
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
/*ye kya h boss? objmerchant ka id m se start hoga...chalo cont 'M' , 
'A' ? kya puch raha hai clearly pooch ye boss obj jo define kr rhe ho... 'M' 

tu bro php syntax me confuse ho raha hai
js me . use karte hain property ke liye object ki
php me . string join ke liye hota hai ...ohhhhkkkk yahi puchna th bas clearedok? yoo


*/
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
        //aise baki fields
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
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); //same no of question marks as fields...ok
 
        $stmt->bind_param("sssssssssss", //same no of s as fields kar abhiyooo
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
            $message["result"] = true;
            $message["user"] = $user;
        }
        echo json_encode($message);
        $conn->close();
    }

    function agentRegister($user, $conn){
        $u_id = 'A'.generateRand();

        $u_fname = $user['fname'];
        $u_lname = $user['lname'];
        $u_email = $user['email'];
        $u_numb = $user['numb'];
        $stmt = $conn->prepare("INSERT INTO agent ( 
                                    a_id, 
                                    a_fname, 
                                    a_lname,
                                    a_email,
                                    a_number
                              )
                                VALUES (?, ?, ?, ?, ?)"); //same no of question marks as fields...ok

        $stmt->bind_param("sssss", //same no of s as fields
                            $u_id, 
                            $u_fname, 
                            $u_lname,
                            $u_email,
                            $u_numb
                            );
        if($stmt->execute()){
            $message["result"] = true;
            $message["user"] = $user;
        }
        echo json_encode($message);
        $conn->close();
    }

    function customerRegister($user, $conn){
        $c_id = 'C'.generateRand();

        $c_fname = $user['fname'];
        $c_lname = $user['lname'];
        $c_email = $user['email'];
        $c_dep = $user['dep'];
        $c_numb = $user['numb'];
        $stmt = $conn->prepare("INSERT INTO customer ( 
                                    c_id, 
                                    c_fname, 
                                    c_lname,
                                    c_email,
                                    c_dep,
                                    c_number
                                )
                                VALUES (?, ?, ?, ?, ?, ?)"); //same no of question marks as fields...ok
        $stmt->bind_param("ssssss", //same no of s as fields
                            $c_id, 
                            $c_fname, 
                            $c_lname,
                            $c_email,
                            $c_dep,
                            $c_numb
                            );
        if($stmt->execute()){
            addLoginDetails($user, $conn);
            /*$message["result"] = true;
            $message["user"] = $user;*/
        }
       /* echo json_encode($message);
        $conn->close(); */
    }
    
    function addLoginDetails($user, $conn){
         $l_id = 'L'.generateRand();

        $l_role = $user['r'];
        $l_user = $user['uname'];
        $l_pass = md5($user['pass']);
        if( $user['pass'] == $user['cpass']){

             $stmt = $conn->prepare("INSERT INTO login ( 
                                        l_id,
                                        l_role, 
                                        l_user, 
                                        l_pass
                                    )
                                    VALUES (?, ?, ?, ?)"); //same no of question marks as fields...ok
            
            $stmt->bind_param("ssss", //same no of s as fields
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
        //yaha par login table me enter hoga, main a raha hun thodi der me1 sec// conf pas pass kaha lagana h?

//nahi re wo sirf check karne ke liye hai same hai ki nahi insert nahi karenge agar diff hai aur error de denge yoooo
        /*1.   check if cpass == pass, not then error code
        $message["result"] = false;
        2.  do md5 for pass
        3.  insert in db like th.e previous 3 tables
        loginid, user, md5(pass) and role
        ?..bol kuch nh .. cont yahi hai... krta hu r
        auknyh pdoubt?na na abhi to nh h
        :P
        login me insert karne ke baad login.php me login ka logic karenge
        beta abhi jane dunga nahi...
        12 ghANte hogaye :(
        3 to 3 
        kabhi mnc me job kiya hai? nh layak hi nh samjha unhone
        aise bante hain layak wohi krta hu rukho fatafat
        kitna kaam or bacha h apna ?
        aur karna hai? nh boss bht thak gya gym bh gya kl kat
        o subh uthkr krlunga jaldi

        kal kabtak ayega onlines aap batao? 2 baje bht late h ye to 
        yoo o
        hk k
        tu kal ke liye task lele karna shuru kar jab aata hai main 2 baje aunga me bhi
         boss abhi sach m bht neend arahi h 
         bilkul rest chahiye 
         
        */
    }

    /*teeno ho rahe hai reg ..haan boss
    kaisa lag raha hai?
    zindagi me pehli baar code is not so simple...focus hona bht jaruri h
    ye sab most complex cheezon me se hai...dat's good :P
    abhi do function bana 
    addLoginDetails($user, $conn)
    sendVerifEmail($user) - ek empty func bana
     1 sec bas sutta pee raha hu bas 
    ek agent ke liye bana fir dikha mereko,,,.. ok
    puchna hai kuch puch le yaar time thoda dena hoga abhi...boss agent ka isime hi banega ?
    done
    s
    switch ($user['r']) {
        case "Customer":
            customerRegister($user, $conn);
            break;
        case "Agent":
            agentRegister($user, $conn);
            break;
        case "2":
            adminLogin($user, $conn);
            break;
    }
    yaahi na? haan samajh agaya h 
    krta hu
     abhi merchant ka function bana aur insert kara merchants, fir copy paste hai mostly, because bada hai sabse...agent k to bana hi diya apne almost 
     :P
     kuch bhi doubt aye to message karna rreply kar dunga.........yoo boss 
     krta hu kam
     2 baje ek baar progress dekhte hain
     ok??? 
    
   
     function merchantRegister($user, $conn){
        $u_id = 'A'.generateRand();

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
        //aise baki fields
        $stmt = $conn->prepare("INSERT INTO merchant ( 
                                    m_id, 
                                    m_fname, 
                                    m_lname,
                                    m_uname,
                                    m_pass,
                                    m_cpass,
                                    m_add,
                                    m_city,
                                    m_state,
                                    m_zip,
                                    m_comp,
                                    m_numb,
                                    m_email,
                                    m_url
                                    
                                )
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); //same no of question marks as fields...ok

        $stmt->bind_param("sssssssssss", //same no of s as fields
                            $m_id, 
                            $m_fname, 
                            $m_lname,
                             $m_uname,
                                    $m_pass,
                                    $m_cpass,
                                    $m_add,
                                    $m_city,
                                    $m_state,
                                    $m_zip,
                                    $m_comp,
                                    $m_numb,
                                    $m_email,
                                    $m_url);
        if($stmt->execute()){
            $message["result"] = true;
            $message["user"] = $user;
        }
        $conn->close();
    }chala isko
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