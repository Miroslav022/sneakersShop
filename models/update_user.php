<?php
    session_start();
    include '../config/connection.php';
    if(isset($_POST['submitBtn'])) {
        $firstName = $_POST['fName'];
        $lastName = $_POST['lName'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $id = $_POST['id'];
        $errors = 0;
        $allErrors = [];
        $mailRegex = '/^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,}$/';
        $passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/';

        if(empty($firstName) || strlen($firstName)<2){
            $errors++;
            $allErrors[] = 'First Name is required and must be at least 2 characters long';
        }
        if(empty($lastName) || strlen($lastName)<2){
            $errors++;
            $allErrors[] = 'Last Name is required and must be at least 2 characters long';
        }
        if(!preg_match($mailRegex, $email)) {
            $errors++;
            $allErrors[]='Incorect email address! example(user@gmail.com)';
        }
        if(!empty($pass)){
            if(!preg_match($passwordRegex, $pass)) {
                $errors++;
                $allErrors[]='Invalid password! Must be at least 8 characters long. At least one uppercase letter, one lowercase letter, one number, and one special character';
            }
        }

        


        if($errors>0){
            $_SESSION['user']['errors'] = $allErrors;
            header("Location: ../index.php?page=edit-user");
        }else {
            unset($_SESSION['user']['errors']);
            if($_FILES['img']['size']>0){
                $newImg = $_FILES['img'];
                $fileName = $newImg['name'];
                $tmpFajl = $newImg['tmp_name'];
                $size = $newImg['size'];
                $type = $newImg['type'];
        
                $noviNazivFajla = time()."_".$fileName;
                $putanja = "../assets/userImages/".$noviNazivFajla;
                if(move_uploaded_file($tmpFajl, $putanja)){
                    if(!empty($pass)){
                        $pass= md5($pass);
                        global $conn;
                        $update = $conn->prepare("UPDATE users SET first_name=:first, last_name=:last, email=:email, password=:pass, profile_img=:image WHERE user_id=:id");
                        $update->bindParam(':first',$firstName);
                        $update->bindParam(':last',$lastName);
                        $update->bindParam(':email',$email);
                        $update->bindParam(':pass', $pass);
                        $update->bindParam(':image',$noviNazivFajla);
                        $update->bindParam(":id",$id);
                        $update->execute();
            
                        $_SESSION['user']['first_name'] = $firstName;
                        $_SESSION['user']['last_name'] = $lastName;
                        $_SESSION['user']['email'] = $email;
                        $_SESSION['user']['password'] = $pass;
                        $_SESSION['user']['profile_img'] = $noviNazivFajla;
                        
                        http_response_code(204);
                        header("Location: ../index.php?page=edit-user&success=Success");
                    }else{
                        global $conn;
                        $update = $conn->prepare("UPDATE users SET first_name=:first, last_name=:last, email=:email, profile_img=:image WHERE user_id=:id");
                        $update->bindParam(':first',$firstName);
                        $update->bindParam(':last',$lastName);
                        $update->bindParam(':email',$email);
                        $update->bindParam(':image',$noviNazivFajla);
                        $update->bindParam(":id",$id);
                        $update->execute();
            
                        $_SESSION['user']['first_name'] = $firstName;
                        $_SESSION['user']['last_name'] = $lastName;
                        $_SESSION['user']['email'] = $email;
                        $_SESSION['user']['password'] = $pass;
                        $_SESSION['user']['profile_img'] = $noviNazivFajla;
                        
                        http_response_code(204);
                        header("Location: ../index.php?page=edit-user&success=Success");
                    }
                    
                }
            } else {
                if(!empty($pass)){
                    $pass= md5($pass);
                    global $conn;
                    $update = $conn->prepare("UPDATE users SET first_name=:first, last_name=:last, email=:email, password=:pass WHERE user_id=:id");
                    $update->bindParam(':first',$firstName);
                    $update->bindParam(':last',$lastName);
                    $update->bindParam(':email',$email);
                    $update->bindParam(':pass', $pass);
                    $update->bindParam(":id",$id);
                    $update->execute();
        
                    $_SESSION['user']['first_name'] = $firstName;
                    $_SESSION['user']['last_name'] = $lastName;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['password'] = $pass;
        
                    http_response_code(204);
                    header("Location: ../index.php?page=edit-user&success=Success");
                }else {
                    global $conn;
                    $update = $conn->prepare("UPDATE users SET first_name=:first, last_name=:last, email=:email WHERE user_id=:id");
                    $update->bindParam(':first',$firstName);
                    $update->bindParam(':last',$lastName);
                    $update->bindParam(':email',$email);
                    $update->bindParam(":id",$id);
                    $update->execute();
        
                    $_SESSION['user']['first_name'] = $firstName;
                    $_SESSION['user']['last_name'] = $lastName;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['password'] = $pass;
        
                    http_response_code(204);
                    header("Location: ../index.php?page=edit-user&success=Success");
                }
               
            }
        }
        
      
    } else {
        header("location: ../index.php");
    }