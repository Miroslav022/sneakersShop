<?php
    include 'functions.php';
    include '../config/connection.php';
    header('Content-Type: application/JSON');
    session_start();
    ob_start();
    if($_SERVER['REQUEST_METHOD']=="POST") {
        if(isset($_GET['table']) && $_GET['table']=='users'){

        //DATA
        $firstName = $_POST['fName'];
        $lastName = $_POST['LName'];
        $pass = $_POST['password'];
        $email =$_POST['email'];
        $role = $_POST['role'];

        //VALIDATION
        $allErrors = [];
        $errors = 0;

        $mailRegex = '/^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,}$/';
        $passwordRegex = '/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_-])[A-Za-z\d!@#$%^&*()_-]{8,}$/';

        if(empty($firstName) || strlen($firstName)<2){
            $errors++;
            $allErrors[] = 'First Name is required and must be at least 2 characters long';
        }
        if(empty($lastName) || strlen($lastName)<2){
            $errors;
            $allErrors[] = 'Last Name is required and must be at least 2 characters long';
        }
        if(!preg_match($mailRegex, $email)) {
            $errors++;
            $allErrors[]='Incorect email address! example(user@gmail.com)';
        }
        if(!preg_match($passwordRegex, $pass)) {
            $errors++;
            $allErrors[]='Invalid password! Must be at least 8 characters long. At least one uppercase letter, one lowercase letter, one number, and one special character';
        }
        if($role==0) {
            $errors++;
            $allErrors[] = 'Select user role';
        }

        if($errors>0){
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=users');
        } else {
            $pass = md5($pass);
            $data = array(
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => $pass,
                'role_id' => $role,
            );
            $params=":first_name,:last_name,:email,:password,:role_id";
            $table = "users";
            insert($params, $table, $data);
        }
       
    }else if(isset($_GET['table']) && $_GET['table']=='categories'){
        $category = $_POST['category'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($category)) {
            $errors++;
            $allErrors[]= "Category input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=categories');
        } else {
            $data = array(
                'category' => $category
            );
            $params = ':category';
            $table = 'categories';
            insert($params, $table, $data);
            header('Location: ../views/adminPanel/admin.php?page=list&type=categories');
        }
    }else if(isset($_GET['table']) && $_GET['table']=='brands'){
        $brands = $_POST['brand'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($brands)) {
            $errors++;
            $allErrors[]= "Brands input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=brands');
        } else {
            $data = array(
                'brand' => $brands
            );
            $params = ':brand';
            $table = 'brands';
            insert($params, $table, $data);
            
        }
        
    }else if(isset($_GET['table']) && $_GET['table']=='discounts'){
        $percentage = $_POST['percentage'];
        $errors = 0;
        $allErrors = [];
        if(empty($percentage)) {
            $errors++;
            $allErrors[]= "Percentage is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=discounts');
        } else {
            $data = array(
                'percentage' => $percentage
            );
            $params = ':percentage';
            $table = 'discounts';
            insert($params, $table, $data);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='sizes'){
        $size = $_POST['size'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($size)) {
            $errors++;
            $allErrors[]= "Size input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=sizes');
        } else {
            $data = array(
                'size' => $size
            );
            $params = ':size';
            $table = 'sizes';
            insert($params, $table, $data);
        }

    }else if(isset($_GET['table']) && $_GET['table']=='specifications'){
        $spec = $_POST['specification'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($spec)) {
            $errors++;
            $allErrors[]= "Specification input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=specifications');
        } else {
            $data = array(
                'specification' => $spec
            );
            $params = ':specification';
            $table = 'specifications';
            insert($params, $table, $data);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='genders'){
        $gender = $_POST['gender'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($gender)) {
            $errors++;
            $allErrors[]= "Gender input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=genders');
        } else {
            $data = array(
                'gender' => $gender
            );
            $params = ':gender';
            $table = 'genders';
            insert($params, $table, $data);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='roles'){
        $roles = $_POST['roles'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($roles)) {
            $errors++;
            $allErrors[]= "Role input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=roles');
        } else {
            $data = array(
                'role' => $roles
            );
            $params = ':role';
            $table = 'roles';
            insert($params, $table, $data);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='product_size'){
        $sizeId = $_POST['size_id'];
        $productId = $_POST['product_id'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($sizeId)) {
            $errors++;
            $allErrors[]= "size input is required";
        }
        if(empty($productId)) {
            $errors++;
            $allErrors[]= "product input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=roles');
        } else {
            $data = array(
                'size_id' => $sizeId,
                'product_id' => $productId
            );
            $params = ':size_id,:product_id';
            $table = 'product_size';
            insert($params, $table, $data);

            http_response_code(204);

        }
    }else if(isset($_GET['table']) && $_GET['table']=='product_specification'){
        $spec_id = $_POST['spec_id'];
        $value = $_POST['value'];
        $prod_id = $_POST['prod_id'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($spec_id)) {
            $errors++;
            $allErrors[]= "specification input is required";
        }
        if(empty($value)) {
            $errors++;
            $allErrors[]= "Specification value is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=additem&table=roles');
        } else {
            $data = array(
                'spec_id' => $spec_id,
                'value' => $value,
                'prod_id' => $prod_id
            );
            $params = ':spec_id,:prod_id,:value';
            $table = 'product_specification';
            insert($params, $table, $data);
            http_response_code(201);
            echo json_encode("success");
        }
    }else if(isset($_GET['table']) && $_GET['table']=='images') {
        $images = $_FILES['imgs'];
        $id = $_GET['id'];
        if($images["size"][0]>0) {
            for($i = 0; $i < count($_FILES['imgs']['name']); $i++) {
                $fileName = $images['name'][$i];
                $tmpFile = $images['tmp_name'][$i];
                $size = $images['size'][$i];
                $type = $images['type'][$i];


                $newName = time()."_".$fileName;
                $path = "../assets/ProductImages/allProductImages/".$newName;
                if(move_uploaded_file($tmpFile, $path)) {
                    echo 'usao';
                    $insertImage = $conn->prepare("INSERT INTO images (src, product_id) VALUES(:src, :product_id)");
                    $insertImage->bindParam(":src", $newName);
                    $insertImage->bindParam(":product_id", $id);
                    $insertImage->execute();
                    http_response_code(204);
                    header("location: ../views/adminPanel/admin.php?page=edit&table=products&id=$id");
                    ob_end_flush();
                }
            }
        }else {
            header("location: ../views/adminPanel/admin.php?page=edit&table=products&id=$id");
        }
    }else if(isset($_GET['table']) && $_GET['table']=='cart') {
        
        $product_id = $_POST['product_id'];
        $size_id = $_POST['size_id'];
        $qty = $_POST['qty'];
        $user_id = $_POST['user_id'];

        //GET USER CART ID
        $uid = issetVariable($user_id);
        $cart_id  = getUserCartId($user_id);
        $cart_id = $cart_id['cart_id'];
        if(!$cart_id) {
            $data = array (
                "user_id" => $user_id
            );
            $params = ':user_id';
            $table = 'cart';
            
            insert($params, $table, $data);
            echo json_encode('Success');
            http_response_code(201);
                
            $cart_id = $conn->lastInsertId();
        }

        // INSERT INTO CART_ITEM TABLE
        $pid = issetVariable($product_id); 
        $isSetQty = issetVariable($qty);
        $size = issetVariable($size_id);
        if($pid && $uid && $isSetQty && $size) {
            $data = array (
                'product_id' => $product_id,
                'size_id' => $size_id,
                'quantity' => $qty,
                'cart_id' => $cart_id,
            );
            $params = ':product_id,:cart_id,:qty,:size_id';
            $table = 'cart_item';
            
            
            insert($params, $table, $data);
            http_response_code(204);
            echo json_encode('Success');
            
        } else {
            header('Location: ../index.php?page=shop&error=Something is wrong');
        }
    }else if(isset($_GET['table']) && $_GET['table']=='order') {
        //Data
        $orders = $_POST['orders'];
        $location = $_POST['location'];
        $user_id = $_POST['user_id'];
        $total = $_POST['total'];

        //Location Data
        $country = $location['country'];
        $city = $location['city'];
        $address = $location['address'];

        global $conn;
        $insertLocation = $conn->prepare("INSERT INTO locations (country, city, address) VALUES(:country, :city, :address)");
        $insertLocation->bindParam(":country", $country);
        $insertLocation->bindParam(":city", $city);
        $insertLocation->bindParam(":address", $address);
        if($insertLocation->execute()) {
            $location_id = $conn->lastInsertId();
            $makeOrder = $conn->prepare("INSERT INTO orders (user_id, total) VALUES(:user_id, :total)");
            $makeOrder->bindParam(":user_id", $user_id);
            $makeOrder->bindParam(":total", $total);
            if($makeOrder->execute()){
                $order_id = $conn->lastInsertId();
                foreach($orders as $order) {
                    $product_id = $order['product_id'];
                    $size_id = $order['size_id'];
                    $price_id = $order['price_id'];
                    $qty = $order['qty'];
                    $cart_id = $order['cart_id'];
                    $insertProductOrder = $conn->prepare("INSERT INTO product_order (product_id, price_id, quantity, location_id, size_id, order_id) VALUES (:product_id, :price_id, :qty, :location_id, :size_id, :order_id)");
                    $insertProductOrder->bindParam(":product_id", $product_id);
                    $insertProductOrder->bindParam(":price_id", $price_id);
                    $insertProductOrder->bindParam(":qty", $qty);
                    $insertProductOrder->bindParam(":location_id", $location_id);
                    $insertProductOrder->bindParam(":size_id", $size_id);
                    $insertProductOrder->bindParam(":order_id", $order_id);
                    if($insertProductOrder->execute()){
                        //UPDATE PRODUCT QUANTITY
                        $updateProductQuantity = $conn->prepare("UPDATE product_inventory SET quantity = quantity - :qty WHERE product_id =:product_id");
                        $updateProductQuantity->bindParam(":qty", $qty);
                        $updateProductQuantity->bindParam(":product_id", $product_id);

                        //UPDATE CART
                        $updateCart = $conn->prepare("UPDATE cart SET is_purchased=1 WHERE cart_id = :cart_id");
                        $updateCart->bindParam(":cart_id", $cart_id);

                        if($updateProductQuantity->execute() && $updateCart->execute()){
                            echo json_encode('success');
                            http_response_code(204);
                        }

                        
                    }
                }
            }
        }


    }
    }else {
        header('location: ../index.php');
    }
    
    
     
    

