<?php
    
    include 'functions.php';
    include '../config/connection.php';
    header('Content-type: application/json');
    //ELEMENTS FOR HIDING

    if($_SERVER['REQUEST_METHOD']=="POST"){
        
        try{
            

            
            $id = $_POST['id'];
            $idName = $_POST['idName'];
            $table = $_POST['table'];
            $hiddenItems = ['products', 'categories', 'brands', 'discounts'];
            if (in_array($table, $hiddenItems))
            {
                global $conn;
                $update = $conn->prepare("UPDATE $table SET is_deleted=1 WHERE $idName=:id");
                $update->bindParam(':id', $id);
                $update->execute();
                
            }
            if($table=='users' || $table=='sizes'){
                global $conn;
                $update = $conn->prepare("UPDATE $table SET isDeleted=1 WHERE $idName=:id");
                $update->bindParam(':id', $id);
                $update->execute();
            }
            else {
                global $conn;
                $remove = $conn->prepare("DELETE FROM $table WHERE $idName=:id");
                $remove->bindParam(':id', $id);
                $remove->execute();
                
                
            }
            http_response_code(204);
            echo json_encode('success');

        } catch(PDOException $e) {
              http_response_code(500);
            echo json_encode("Error with database connection");
        }
    } else {
        header('location: ../index.php');
    }