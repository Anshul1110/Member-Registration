<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Content-Type');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    include('db_connect.php');   
    include('random.php');   

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    };

    function insertIntoDB($agent, $product, $uploadedFile, $conn){
        $pcode = $product["pcode"];
        $arr = array();
        $query  =  "SELECT p_code, a_id
                    FROM product
                    WHERE p_code = '$pcode'";
        $result = $conn->query($query);  
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
        }

        //check if product exists already
        if(count($arr) > 0){
            //check if from same user?
            $message = array(
                "existing"=>$arr,
                "message"=>"This product already exists in the database. It will be added to Pending status, till approved by admin.", 
                "inserted" => false
            );

            $found = 0;
            for($i = 0; $i < count($arr); $i++){
                if($arr[$i]['a_id'] == $agent["a_id"]){
                    //Give duplicate notification dont insert
                    $found = 1;
                    break;
                }    
            }
            if($found){        
                echo json_encode($message); 
            }else{                
                //Insert as duplicate product
                insertProductDetails("Pending", $agent, $product, $message, $conn, $uploadedFile);
            }
        }else{
            $message = array(
                "message"=>"Product added successfully. 1 Credit has been deducted from your account.", 
            );
            $id = $agent["a_id"];
            $arr = array();
            $query  =  "SELECT *
                        FROM agent
                        WHERE a_id = '$id'";
            $result = $conn->query($query);  
            while($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
            $cr = intval($arr[0]["a_credits"]) - 1;
            $stmt = $conn->prepare("UPDATE agent SET
                                    a_credits = ?
                                    WHERE a_id = ?");
            $stmt->bind_param("ss", $cr, $id);
            if($stmt->execute()){                            
                //Do real addition of product
                insertProductDetails("Active", $agent, $product, $message, $conn, $uploadedFile);
            }else echo "Whoops!"; 
        }        
    }

    function insertProductDetails($status, $agent, $product, $message, $conn, $uploadedFile){
        $p_id = 'P'.generateRand();
        $product['id'] = $p_id;
        $p_code  = $product['pcode'];
        $p_name  = $product['pname'];
        $p_desc  = $product['pdesc'];
        $p_size  = $product['psize'];
        $p_price = $product['pprice'];
        $p_cat   = $product['pcat'];
        $p_brand = $product['pbrand'];
        $p_det   = $product['pdet'];
        $a_id    = $agent["a_id"];
        $p_img   = "";
        $stmt = $conn->prepare("INSERT INTO product ( 
                                    p_id,
                                    a_id,
                                    p_code, 
                                    p_name, 
                                    p_descrip,
                                    p_size,
                                    p_price,
                                    p_cat,
                                    p_brand,
                                    p_det,
                                    p_img,
                                    p_status
                                )
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssss", 
                            $p_id,
                            $a_id,
                            $p_code,
                            $p_name,
                            $p_desc,
                            $p_size,
                            $p_price,
                            $p_cat,
                            $p_brand,
                            $p_det,
                            $p_img,
                            $status
                        );
        if($stmt->execute()){
            if(isset($uploadedFile["uploadedFile"])){
                $file = $uploadedFile["uploadedFile"][0];
                $url = "../upload/".$agent['a_id']."/".$product['id']."/";    
                if(!file_exists($url)){
                    mkdir($url, 0755, true);
                    $fileurl = $url.$file["name"];
                    if(move_uploaded_file($file["tmp_name"], $fileurl)){                    
                        $message["uploadedFile"] = $file;    
                        $stmt = $conn->prepare("UPDATE product SET
                                                p_img = ?
                                                WHERE p_id = ?");
                        $stmt->bind_param("ss", $fileurl, $p_id);
                        if($stmt->execute()){                            
                            sendSuccessMessage($p_id, $conn, $message, $status);                        
                        }else echo "Whoops!";                                   
                    }else{
                        $message = array(
                            "message"=>"Product could not be added, please try again.", 
                            "result" => false
                        );
                        echo json_encode($message);
                    }
                }else{
                    echo "Whoops!";
                    //Code for adding different image for same product
                }
            }else{
                sendSuccessMessage($p_id, $conn, $message, $status);
            }  
        }else{
            $message = array(
                "message"=>"Product could not be added, please try again.", 
                "result" => false
            );
            echo json_encode($message);
        }
    }

    function sendSuccessMessage($p_id, $conn, $message, $status){
        $arr = array();
        $query  =  "SELECT *
                    FROM product
                    WHERE p_id = '$p_id'";
        $result = $conn->query($query);  
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $arr[] = $row;
            }
        }   
        $message["inserted"] = true;
        $message["products"] = $arr;        
        echo json_encode($message);
    }

    function normalizeFiles($files = []) {
        $normalized_array = [];
        foreach($files as $index => $file) {
            if (!is_array($file['name'])) {
                $normalized_array[$index][] = $file;
                continue;
            }
            foreach($file['name'] as $idx => $name) {
                $normalized_array[$index][$idx] = [
                    'name' => $name,
                    'type' => $file['type'][$idx],
                    'tmp_name' => $file['tmp_name'][$idx],
                    'error' => $file['error'][$idx],
                    'size' => $file['size'][$idx]
                ];
            }
        }
        return $normalized_array;
    }

    $agent = json_decode($_REQUEST["user"], true);
    $product = json_decode($_REQUEST["product"], true);
    $uploadedFile = normalizeFiles($_FILES);
    insertIntoDB($agent, $product, $uploadedFile, $conn);                                    
    $conn->close();
?>