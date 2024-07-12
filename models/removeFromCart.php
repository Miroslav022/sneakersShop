<?php
    include 'functions.php';
    include '../config/connection.php';
    header('Content-type: application/json');

    if($_SERVER['REQUEST_METHOD']==="POST"){
 
        try{
            
            $cart_id = $_POST['cart_item_id'];
            global $conn;
            $remove= $conn->prepare("DELETE from cart_item WHERE cart_item_id=:cart_id");
            $remove->bindParam(":cart_id",$cart_id);
            $remove->execute();
            http_response_code(204);
            echo json_encode('success');

        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
