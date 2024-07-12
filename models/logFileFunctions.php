<?php
    function insertIntoLogFile(){
    
        $open = fopen('data/log.txt', 'ab');
        if(isset($_SESSION['user'])) {
            $user =  $_SESSION['user'];

            $ime = $user['first_name'];
            $prezime = $user['last_name'];
            $role = $user['role_id'];
            $role = $role === 2 ? 'Administrator' : 'User';
        }else {
            $ime = 'Unknown';
            $prezime = 'Unknown';
            $role = 'Unregistered-visitor'; 
        }
        $date = date("Y/m/d");
        $page = '';
        if(isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 'home';
        }
        $string = $ime."\t".$prezime."\t".$role."\t".$page."\t".$date."\n";
        $write = fwrite($open, $string);
        fclose($open); 
    }

    function getAllPageViews($page){
        $otvori = file('../../data/log.txt');
        $posete = 0;
        for($i=0; $i<count($otvori); $i++){
            $singleLog = explode("\t", $otvori[$i]);
            if($singleLog[3]==$page) $posete++;
        }
        return $posete;
    }
    function getAllTodayPageViews($page){
        $otvori = file('../../data/log.txt', FILE_IGNORE_NEW_LINES);
        $posete = 0;
        $date = date("Y/m/d");
        for($i=0; $i<count($otvori); $i++){
            $singleLog = explode("\t", $otvori[$i]);
            if($singleLog[3]==$page && $date==$singleLog[4]) $posete++;
        }
        return $posete;
    }
    function visitInPercentage($page){
        $otvori = file('../../data/log.txt', FILE_IGNORE_NEW_LINES);
        $ukupanPristup = count($otvori);
        $posete = 0;
        for($i=0; $i<count($otvori); $i++){
            $singleLog = explode("\t", $otvori[$i]);
            if($singleLog[3]==$page) $posete++;
        }

        $finallResult = (100*$posete)/$ukupanPristup;
        return number_format((float)$finallResult, 2, '.', '');
    }
    function loggedInUsers($user){
        $otvori = fopen('../data/loggedInUsers.txt', 'ab');
        $email= $user['email'];
        $date = date("Y/m/d");
        $string = $email."\t".$date."\n";
        $upis = fwrite($otvori, $string);
        fclose($otvori);
    }
    function loggedUsersCount(){
        $users = file('../../data/loggedInUsers.txt', FILE_IGNORE_NEW_LINES);
        $date = date("Y/m/d");
        $count = 0;
        foreach($users as $user){
            $user = explode("\t", $user);
            if($user[1]==$date) $count++;
        }
        return $count;
    }
    