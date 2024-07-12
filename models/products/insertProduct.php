<?php
    session_start();
    include '../../config/connection.php';
    include "../functions.php";
    $specs = getItems("specifications");
    ob_start();
    
    if(isset($_POST['submitBtn'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $brand = $_POST['brand'];
        $description = $_POST['description'];
        $gender = $_POST['gender'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $discount = $_POST['discount'];
        $newImg = $_FILES['img'];
        $images = $_FILES['imgs'];
        $sizes = $_POST['sizes'];

        $allErrors = [];
        $regexCode = '/^[a-zA-Z0-9\s]+$/';
        $regexQty = '/^[0-9]+$/';
        $errors=0;
        if(empty($name)) {
            $errors++;
            $allErrors[]= "Please enter product name";
        }
        if(!isset($description)) {
            $errors++;
            $allErrors[]= "Please enter description";
        }
        if(!preg_match($regexQty, $qty)) {
            $errors++;
            $allErrors[]= "Please enter product quantity";
        }
        if(!preg_match($regexQty, $price)) {
            $errors++;
            $allErrors[]= "Please enter number for price";
        }
        if(!isset($gender)) {
            $errors++;
            $allErrors[]= "Please select gender";
        }
        if(!isset($sizes)) {
            $errors++;
            $allErrors[]= "Please select product size";
        }
        if($newImg['size']===0) {
            $allErrors[]= "Please insert product image";
            header("location: ../../views/adminPanel/admin.php?page=addProduct&error=Insert product image");
        }
        if($errors>0) { 
            $_SESSION['user']['errors']= $allErrors;
            header("location: ../../views/adminPanel/admin.php?page=additem&table=products");
        } else {
            $fileName = $newImg['name'];
            $tmpFajl = $newImg['tmp_name'];
            $size = $newImg['size'];
            $type = $newImg['type'];

            $noviNazivFajla = time()."_".$fileName;
            $putanja = "../../assets/ProductImages/allProductImages/".$noviNazivFajla;
            
            if(move_uploaded_file($tmpFajl, $putanja)){
                try{
                    //MAKE THUMBNAIL IMAGE
                    list($width, $height) = getimagesize($putanja);
                    $ext = strtolower(pathinfo($putanja, PATHINFO_EXTENSION));
                    $ext = $ext=='jpg' ? 'jpeg' : $ext;
                    $newWidth = 255;
                    $newHeight = $height / ($width/$newWidth);

                    $slika = "imagecreatefrom".$ext;
                    if(function_exists($slika)){
                        $newCustomImg = call_user_func($slika, $putanja);
                    }


                    $background = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($background, $newCustomImg,0,0,0,0,$newWidth,$newHeight,$width,$height);

                    $fun = "image".$ext;

                    $thumbnailPath = "../../assets/ProductImages/thumbnails/".$noviNazivFajla;

                    if(function_exists($fun)){
                        $finall = call_user_func($fun, $background, $thumbnailPath);
                    }

                    //INSERTING PRODUCT
                    global $conn;
                    $conn->beginTransaction();
                    $insertProduct = $conn->prepare("INSERT INTO products (name, brand_id, category_id, mainImg, description, gender_id) VALUES (:name, :brand_id, :category_id, :mainImg, :description, :gender_id)");
                    $insertProduct->bindParam(":name", $name);
                    $insertProduct->bindParam(":brand_id", $brand);
                    $insertProduct->bindParam(":category_id", $category);
                    $insertProduct->bindParam(":mainImg", $noviNazivFajla);
                    $insertProduct->bindParam(":description", $description);
                    $insertProduct->bindParam(":gender_id", $gender);
                    $isSuccess = $insertProduct->execute();
                    
                    if($isSuccess) {
                        
                        //INSERTING PRODUCT PRICE
                        $lastInsertedId = $conn->lastInsertId();
                        $insertPrice = $conn->prepare("INSERT INTO prices (price, product_id, discount_id) VALUES(:price, :product_id, :discount_id)");
                        $insertPrice->bindParam(":price", $price);
                        $insertPrice->bindParam(":product_id", $lastInsertedId);
                        $insertPrice->bindParam(":discount_id", $discount);
                        $insertPrice->execute();
    
                        //INSERTING PRODUCT QUANTITY
                        $status = $qty>0 ? 1 : 0;
                        $insertQty = $conn->prepare("INSERT INTO product_inventory(product_id, quantity, status) VALUES(:product_id, :quantity, $status)");
                        $insertQty->bindParam(":product_id", $lastInsertedId);
                        $insertQty->bindParam(":quantity", $qty);
                        
                        
                        $insertQty->execute();


                        //INSERTING PRODUCT GENDER
                        // $insertGender = $conn->prepare("INSERT INTO prod_gender (prod_id, gend_id) VALUES(:prod_id, :gender_id)");
                        // $insertGender->bindParam(":gender_id", $gender);
                        // $insertGender->bindParam(":prod_id", $lastInsertedId);
                        // $insertGender->execute();


                        //INSERTING PRODUCT IMAGES
                        if(count($images)>0) {
                            for($i = 0; $i < count($_FILES['imgs']['name']); $i++) {
                                $fileName = $images['name'][$i];
                                $tmpFile = $images['tmp_name'][$i];
                                $size = $images['size'][$i];
                                $type = $images['type'][$i];
                               
                                $newName = time()."_".$fileName;

                                $path = "../../assets/ProductImages/allProductImages/".$newName;
                                if(move_uploaded_file($tmpFile, $path)) {
                                    $insertImage = $conn->prepare("INSERT INTO images (src, product_id) VALUES(:src, :product_id)");
                                    $insertImage->bindParam(":src", $newName);
                                    $insertImage->bindParam(":product_id", $lastInsertedId);
                                    $insertImage->execute();
                                }
                            }
                        }

                        //INSERTING PRODUCT SPECIFICATION
                        foreach($specs as $spec){
                            $specification_id=$spec["specification_id"];
                            $vrednost = $_POST["spec".$spec['specification_id'].""];
                            if(!empty($vrednost)) {
                                $insertSpecifications = $conn->prepare("INSERT INTO product_specification (prod_id, spec_id, value) VALUES(:prod_id, :spec_id, :value)");
                                $insertSpecifications->bindParam(":prod_id", $lastInsertedId);
                                $insertSpecifications->bindParam(":spec_id", $specification_id);
                                $insertSpecifications->bindParam(":value", $vrednost);
                                $insertSpecifications->execute();
                            }
                        }

                        // INSERTING PRODUCT SIZES
                        foreach($sizes as $size){
                            $insertSizes = $conn->prepare("INSERT INTO product_size (product_id, size_id) VALUES(:product_id, :size_id)");
                            $insertSizes->bindParam(":product_id", $lastInsertedId);
                            $insertSizes->bindParam(":size_id", $size);
                            $insertSizes->execute();
                        }
                        //IS SUCCESS
                        if($insertPrice && $insertQty) {
                            
                            header("location: ../../views/adminPanel/admin.php?page=additem&table=products&success=Success");
                            ob_end_flush();
                            unset($_SESSION["user"]['errors']);
                        }
                        $conn->commit();
                }
            
                }
                catch(PDOException $e){
                    $conn->rollBack();
                    echo "nesto nije u redu";
                }
                
        } else {
            header("location: ../../views/adminPanel/admin.php?page=addProduct&error=Doslo je do greske prilikom uploada");
        }
    }
        
    } else {
        header('location: ../../index.php');
    }