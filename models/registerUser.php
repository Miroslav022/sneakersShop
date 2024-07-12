<?php
    include 'functions.php';
    include '../config/connection.php';
    header("content-type: application/json");
    if($_SERVER['REQUEST_METHOD']==="POST") {
        try{
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = [];
            $num=0;
            $nameRegex = "/^[A-Za-z]{2,}$/";
            $emailRegex = "/^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,}$/";
            $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
            if(!checkFieldWithRegex($password, $passwordRegex)) {
                $num++;
                $errors[] = "Password is incorect, please enter correct password";
            }
            if(!checkFieldWithRegex($firstName, $nameRegex)) {
                $num++;
                $errors[] = "Invalid first name";
            }
            if(!checkFieldWithRegex($lastName, $nameRegex)) {
                $num++;
                $errors[] = "Invalid last name";
            }
            if(!checkFieldWithRegex($email, $emailRegex)) {
                $num++;
                $errors[] = "Invalid email";
            }
            if($num!==0) {
                http_response_code(422);
                echo json_encode($errors);
            } else {
                global $conn;

                $password = md5($password);

                $insert = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES(:first_name, :last_name, :email, :password)");
                $insert->bindParam(":first_name", $firstName);
                $insert->bindParam(":last_name", $lastName);
                $insert->bindParam(":email", $email);
                $insert->bindParam(":password", $password);
                $insert->execute();
                http_response_code(201);
                echo json_encode("Successfully registered!");
            }
            
        } catch(PDOException $e){
            http_response_code(500);
            echo json_encode("Email already exist, or we have problem with database");
        }
    } else {
        echo json_encode('You dont have permission for this page');
        http_response_code(401);
        header('location: ../index.php');
    }