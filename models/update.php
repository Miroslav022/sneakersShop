<?php
    include 'functions.php';
    header('content-type: application/json');
    if($_SERVER['REQUEST_METHOD']=='POST'){     
    include '../config/connection.php';
    session_start();
    ob_start();
    
    if(isset($_GET['table']) && $_GET['table']=='users'){

       //DATA
        $firstName = $_POST['fName'];
        $lastName = $_POST['LName'];
        $pass = $_POST['password'];
        $email =$_POST['email'];
        $role = $_POST['role'];
        $blocked = $_POST['blockOption'];
        $blocked = intval($blocked);
        echo $blocked;
        $id = $_POST['id'];
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
            $errors++;
            $allErrors[] = 'Last Name is required and must be at least 2 characters long';
        }
        if(!empty($pass)){
            if(!preg_match($passwordRegex, $pass)) {
                $errors++;
                $allErrors[]='Invalid password! Must be at least 8 characters long. At least one uppercase letter, one lowercase letter, one number, and one special character';
            }
        }
        if(!preg_match($mailRegex, $email)) {
            $errors++;
            $allErrors[]='Incorect email address! example(user@gmail.com)';
        }
        
        if($role==0) {
            $errors++;
            $allErrors[] = 'Select user role';
        }

        if($errors>0){
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=users&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            if(!empty($pass)){
                $pass = md5($pass);

                global $conn;
                $update = $conn->prepare("UPDATE users SET first_name=:first, last_name=:last, email=:email, password=:pass, role_id=:role_id, is_blocked=$blocked WHERE user_id=$id");
                $update->bindParam(':first',$firstName);
                $update->bindParam(':last',$lastName);
                $update->bindParam(':email',$email);
                $update->bindParam(':pass', $pass);
                $update->bindParam(':role_id',$role);
                $update->execute();
                header("Location: ../views/adminPanel/admin.php?page=edit&table=users&id=$id&success=Success");
                ob_end_flush();
                exit;
            } else {
                $pass = md5($pass);

                global $conn;
                $update = $conn->prepare("UPDATE users SET first_name=:first, last_name=:last, email=:email, role_id=:role_id, is_blocked=$blocked WHERE user_id=$id");
                $update->bindParam(':first',$firstName);
                $update->bindParam(':last',$lastName);
                $update->bindParam(':email',$email);
                $update->bindParam(':role_id',$role);
                $update->execute();
                header("Location: ../views/adminPanel/admin.php?page=edit&table=users&id=$id&success=Success");
                ob_end_flush();
                exit;
                
            }

            
        
            
            // update($table, $id, $data, $idColoum);
        }
       
       
    }else if(isset($_GET['table']) && $_GET['table']=='categories'){
        $id = $_POST['id'];
        $category = $_POST['category'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($category)) {
            $errors++;
            $allErrors[]= "Category input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=categories&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'category' => $category,
                
            );
            $idColoum = 'category_id';
            $table = 'categories';
            update($table, $id, $data, $idColoum);
            
        }
    }else if(isset($_GET['table']) && $_GET['table']=='brands'){
        $id = $_POST['id'];
        $brands = $_POST['brand'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($brands)) {
            $errors++;
            $allErrors[]= "Brands input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=brands&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'brand' => $brands
                
            );
            $idColoum = 'brand_id';
            $table = 'brands';
            update($table, $id, $data, $idColoum);;
        }
        
    }else if(isset($_GET['table']) && $_GET['table']=='discounts'){
        $id = $_POST['id'];
        $percentage = $_POST['percentage'];
        $errors = 0;
        $allErrors = [];
        if(empty($percentage)) {
            $errors++;
            $allErrors[]= "Percentage is required";
        }

        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=discounts&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'percentage' => $percentage
            );
            $idColoum = 'discount_id';
            $table = 'discounts';
            update($table, $id, $data, $idColoum);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='sizes'){
        $id = $_POST['id'];
        $size = $_POST['size'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($size)) {
            $errors++;
            $allErrors[]= "Size input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=sizes&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'size' => $size
            );
            $idColoum = 'size_id';
            $table = 'sizes';
            update($table, $id, $data, $idColoum);
        }

    }else if(isset($_GET['table']) && $_GET['table']=='specifications'){
        $spec = $_POST['specification'];
        $errors = 0;
        $allErrors = [];
        $id = $_POST['id'];
        
        if(empty($spec)) {
            $errors++;
            $allErrors[]= "Specification input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=specifications&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'specification' => $spec
            );
            $idColoum = 'specification_id';
            $table = 'specifications';
            update($table, $id, $data, $idColoum);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='genders'){
        $gender = $_POST['gender'];
        $errors = 0;
        $allErrors = [];
        $id = $_POST['id'];
        
        if(empty($gender)) {
            $errors++;
            $allErrors[]= "Gender input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=genders&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'gender' => $gender
            );
            $idColoum = 'gender_id';
            $table = 'genders';
            update($table, $id, $data, $idColoum);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='roles'){
        $roles = $_POST['roles'];
        $errors = 0;
        $allErrors = [];
        $id = $_POST['id'];
        
        if(empty($roles)) {
            $errors++;
            $allErrors[]= "Role input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=roles&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'role' => $roles
            );
            $idColoum = 'role_id';
            $table = 'roles';
            update($table, $id, $data, $idColoum);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='products'){
        
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $brand = $_POST['brand'];
        $description = $_POST['description'];
        $newImg = $_FILES['img'];
        $gender = $_POST['gender'];

        $allErrors = [];
        $regexCode = '/^[a-zA-Z0-9\.\"\s]+$/';
        $errors=0;
        if(!preg_match($regexCode, $name)) {
            $errors++;
            $allErrors[]= "Please enter product name";
        }
        if(empty($description)) {
            $errors++;
            $allErrors[]= "Please enter description";
        }
        if(empty($gender)) {
            $errors++;
            $allErrors[]= "Please select gender";
        }

        if($errors>0) { 
            $_SESSION['user']['errors']= $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=products&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            try{
                if($newImg['size']>0) {
                
                        $fileName = $newImg['name'];
                        $tmpFajl = $newImg['tmp_name'];
                        $size = $newImg['size'];
                        $type = $newImg['type'];
        
                        $noviNazivFajla = time()."_".$fileName;
                        $putanja = "../assets/ProductImages/allProductImages/".$noviNazivFajla;
                        
                        if(move_uploaded_file($tmpFajl, $putanja)){
                            list($width, $height) = getimagesize($putanja);
                            $ext = strtolower(pathinfo($putanja, PATHINFO_EXTENSION));
                            $newWidth = 255;
                            $newHeight = $height / ($width/$newWidth);
                            
                            $ext = $ext =='jpg' ? 'jpeg' : $ext;

                            $slika = "imagecreatefrom".$ext;
                            
                            if(function_exists($slika)){
                                echo 'usao';
                                $newCustomImg = call_user_func($slika, $putanja);
                            }
        
        
                            $background = imagecreatetruecolor($newWidth, $newHeight);
                            imagecopyresampled($background, $newCustomImg,0,0,0,0,$newWidth,$newHeight,$width,$height);
        
                            $fun = "image".$ext;
        
                            $thumbnailPath = "../assets/ProductImages/thumbnails/".$noviNazivFajla;
        
                            if(function_exists($fun)){
                                $finall = call_user_func($fun, $background, $thumbnailPath);
                            }    
                        

                            global $conn;
                            $id = $_POST['id'];
                            $idColoum = 'product_id';
                            $table = 'products';
                            $update = $conn->prepare("UPDATE products SET name=:name, category_id=:category, brand_id=:brand, mainImg=:mainImg, description=:description, gender_id = :gender WHERE product_id=:id");
                            $update->bindParam(':name', $name);
                            $update->bindParam(':category', $category);
                            $update->bindParam(':brand', $brand);
                            $update->bindParam(':mainImg', $noviNazivFajla);
                            $update->bindParam(':description', $description);
                            $update->bindParam(':gender', $gender);
                            $update->bindParam(':id', $id);
                            $update->execute();
                            header("Location: ../views/adminPanel/admin.php?page=edit&table=$table&id=$id&success=Success");
    
                           
                    }
                    }else {
                        global $conn;
                            $idColoum = 'product_id';
                            $table = 'products';
                            $update = $conn->prepare("UPDATE products SET name=:name, category_id=:category, brand_id=:brand, description=:description, gender_id = :gender WHERE product_id=:id");
                            $update->bindParam(':name', $name);
                            $update->bindParam(':category', $category);
                            $update->bindParam(':brand', $brand);
                            $update->bindParam(':description', $description);
                            $update->bindParam(':gender', $gender);
                            $update->bindParam(':id', $id);
                            $update->execute();
                            header("Location: ../views/adminPanel/admin.php?page=edit&table=$table&id=$id&success=Success");
                }
            } catch(PDOException $e) {
                echo $e;
            }
            
            
    
        }
    }else if(isset($_GET['table']) && $_GET['table']=='prices') {
        $price = $_POST['price'];
        $errors = 0;
        $allErrors = [];
        $regexPrice = '/^[0-9]+$/';
        if(!preg_match($regexPrice, $price)) {
            $errors++;
            $allErrors[]= "Please enter number for price";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../views/adminPanel/admin.php?page=edit&table=products&id=$id");
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'price' => $price
            );
            $id = $_POST['id'];
            $idColoum = 'price_id';
            $table = 'prices';
            update($table, $id, $data, $idColoum);
            header("Location: ../views/adminPanel/admin.php?page=edit&table=products&id=".$_GET['id']."&success=Success");
        }
    }else if(isset($_GET['table']) && $_GET['table']=='product_inventory') {
        $qty = $_POST['qty'];
        $errors = 0;
        $allErrors = [];
        $regexQty = '/^[0-9]+$/';
        if(!preg_match($regexQty, $qty)) {
            $errors++;
            $allErrors[]= "Please enter quantity";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=edit&table=products&id='.$_GET['id']);
        } else {
            // global $conn;
            // $update = $conn->prepare("UPDATE product_inventory SET quantity=:qty WHERE id=:id");
            // $update->bindParam(':qty', $qty);
            // $update->bindParam(':id', $id);
            // $update->execute();
            unset($_SESSION['user']['errors']);
            $data = array(
                'quantity' => $qty,
            );
            $id = $_POST['id'];
            $idColoum = 'inventory_id';
            $table = 'product_inventory';
            update($table, $id, $data, $idColoum);
            header("Location: ../views/adminPanel/admin.php?page=edit&table=products&id=".$_GET['id']."&success=Success");
        }
    }else if(isset($_GET['table']) && $_GET['table']=='productdiscounts') {
        $discount = $_POST['discount'];
        $errors = 0;
        $allErrors = [];
        if(empty($discount)) {
            $errors++;
            $allErrors[]= "Please enter discount";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=edit&table=products&id='.$_GET['id']);
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'discount_id' => $discount,
            );
            $id = $_POST['priceID'];
            $idColoum = 'price_id';
            $table = 'prices';
            update($table, $id, $data, $idColoum);
            header("Location: ../views/adminPanel/admin.php?page=edit&table=products&id=".$_GET['id']."&success=Success");
        }
    }else if(isset($_GET['table']) && $_GET['table']=='productsizes'){
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
            unset($_SESSION['user']['errors']);
            $data = array(
                'size' => $size
            );
            $id = $_POST['id'];
            $idColoum = 'size_id';
            $table = 'sizes';
            update($table, $id, $data, $idColoum);
        }
    }else if(isset($_GET['table']) && $_GET['table']=='productSpecifications'){
        $spec = $_POST['specification'];
        $id = $_POST['id'];
        $errors = 0;
        $allErrors = [];
        
        if(empty($spec)) {
            $errors++;
            $allErrors[]= "Specification input is required";
        }
        if($errors>0) {
            $_SESSION['user']['errors'] = $allErrors;
            header('Location: ../views/adminPanel/admin.php?page=edit&table=product&id='.$id);
        } else {
            unset($_SESSION['user']['errors']);
            $data = array(
                'value' => $spec
            );
            $idColoum = 'prod_spec_id';
            $table = 'product_specification';
            update($table, $id, $data, $idColoum);
            echo json_encode('Successfully updated');
            http_response_code(204);
            
        }
    }
    } else {
        header("location: ../index.php");
    }