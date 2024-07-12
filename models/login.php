<?php
    session_start();
    include '../config/connection.php';
    include 'logFileFunctions.php';
    include 'functions.php';
    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $errors = [];
        $num=0;
        $emailRegex = "/^[A-Za-z0-9._-]+@[A-Za-z0-9.-]+.[A-Za-z]{2,}$/";
        $passwordRegex = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
        $email = $_POST['email'];
        $password = $_POST['password'];
        if(!checkFieldWithRegex($password, $passwordRegex)) {
            $num++;
            $errors[] = "Password is incorect, please enter correct password";
        }
        if(!checkFieldWithRegex($email, $emailRegex)) {
            $num++;
            $errors[] = "Invalid email";
        }

        if($num!==0) {
            http_response_code(401);
            echo json_encode("Invalid parametars");
        } else {
            $password = md5($password);
            global $conn;
            //Da li postoji mail 
            $queryEmail = "SELECT * FROM users WHERE email=:emailNovi";
            $stmtEmail = $conn->prepare($queryEmail);
            $stmtEmail->bindParam(':emailNovi', $email);
            $stmtEmail->execute();
            $userForEmail = $stmtEmail->fetch();

            $max_tries = 3;

            if($userForEmail->role_id == 1){
                //Nije admin 
                if(!$userForEmail->is_blocked){
                    //Ako nije blokiran
                    $select = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
                    $select->bindParam(":email",$email);
                    $select->bindParam(":password",$password);
                    $select->execute();
                    $user=$select->fetch(PDO::FETCH_ASSOC);

                    
                    if($user){
                        //Ulogovan korisnik
                        loggedInUsers($user);
                        $_SESSION['user'] = $user;
                        echo json_encode(true);
                        http_response_code(200);
                    }else {
                        //Login fail
                        $file_name = "../data/login-mistake.txt";
                        $loginMistake = file($file_name, FILE_IGNORE_NEW_LINES);
                        $userEmail = $userForEmail->email;

                        $user_from_file = [];

                        foreach($loginMistake as $index => $w){
                            list($email,$datum,$number_of_tries) = explode("\t", $w);
        
                            if($userEmail == $email){
                                // Pronalazimo bas tog usera iz fajla, za taj email koji je uneo
                                $user_from_file = [$index, $email, $datum, $number_of_tries];
                            }
                        }
                        
                        $triesNum= 1;
                        
                        //User ne postoji u fajlu
                        if($user_from_file == []){
                            // Kada user ne postoji u fajlu
                            $wrong_login = fopen($file_name, "a");
        
                            $string = $userEmail . "\t" .  time() . "\t" . $triesNum. "\n";
        
                            fwrite($wrong_login, $string);
        
                            if(fclose($wrong_login)){
                                http_response_code(401);
                                echo json_encode("Login credentials are incorrect. You have  " . ($max_tries - $triesNum) . "  more tries.");
                            }
                        }else {
                            //Kada user postoji u fajlu
                            $time_for_reset = 5 * 60;
                            $time_difference =  time() - $user_from_file[2];
                            if($time_difference > $time_for_reset){
                                //Kad je vreme vece od 5 min
                                $id = $user_from_file[0];
        
                                $loginMistake_new = explode("\t", $loginMistake[$id]);
        
                                $loginMistake_new[1] = time();
                                $loginMistake_new[2] = $tries_number;
        
                                $wrong_login = fopen($file_name, "w");
        
                                $loginMistake[$id] = implode("\t",$loginMistake_new);
        
                                $string_for_wr = implode("\n", $loginMistake);
        
                                fwrite($wrong_login, $string_for_wr);
        
                                if(fclose($wrong_login)){
                                    http_response_code(401);
                                    echo json_encode("Login credentials are incorrect. You have " . ($max_tries - $triesNum) . " more tries.");
                                }
                            }else {
                                // Kada vreme nije vece od 5 min
                                if($user_from_file[3] == ($max_tries-1)){
                                    //Kada user ima 2 pokusaja
                                    blockUser($user_from_file[1]);
        
                                    //salje se mejl
        
                                    $id = $user_from_file[0];
        
                                    unset($loginMistake[$id]);
        
                                    $wrong_login = fopen($file_name, "w");
        
                                    $string_for_wr = implode("\n", $loginMistake);
        
                                    fwrite($wrong_login, $string_for_wr);
        
                                    if(fclose($wrong_login)){
                                        http_response_code(401);
                                        echo json_encode("Login credentials are incorrect. Your account is blocked.");
                                    }
        
                                }else{
                                    // Ako nema 2 pokusaja nego manje
                                    $id = $user_from_file[0];
        
                                    $loginMistake_new = explode("\t", $loginMistake[$id]);
        
                                    $loginMistake_new[1] = time();
                                    $number_wrong_now = $loginMistake_new[2];
                                    
                                    $number_wrong_now = intval($number_wrong_now);
        
                                    $loginMistake_new[2] = ++$number_wrong_now;
        
                                    $loginMistake[$id] = implode("\t",$loginMistake_new);
        
                                    $wrong_login = fopen($file_name, "w");
        
                                    $string_for_wr = implode("\n", $loginMistake);
        
                                    fwrite($wrong_login, $string_for_wr);
        
                                    if(fclose($wrong_login)){
                                        http_response_code(401);
                                        echo json_encode("Login credentials are incorrect. You have " . ($max_tries - $loginMistake_new[2]) . " more tries.");
                                    }
                                }
                            }
                        }

                    }
                } else {
                    http_response_code(401);
                    echo json_encode("Your account is blocked. Contact your administrator.");
                }
            } else {
                $select = $conn->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
                $select->bindParam(":email",$email);
                $select->bindParam(":password",$password);
                $select->execute();
                $user=$select->fetch(PDO::FETCH_ASSOC);
                if($user) {
                    loggedInUsers($user);
                    $_SESSION['user'] = $user;
                    echo json_encode(true);
                    http_response_code(200);
                }else {
                    http_response_code(401);
                    echo json_encode("Your login credentials are incorrect.");
                }
            }
            

            
        }
    } else {
        header("location: ../index.php");
    }

    function blockUser($email) {
        global $conn;
        $queryEmail = "UPDATE users SET is_blocked=1 WHERE email=:emailNovi";
        $stmtEmail = $conn->prepare($queryEmail);
        $stmtEmail->bindParam(':emailNovi', $email);
        $stmtEmail->execute();
    }