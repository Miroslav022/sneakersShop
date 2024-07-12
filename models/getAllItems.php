<?php
    include 'functions.php';
    include '../config/connection.php';

    if($_SERVER['REQUEST_METHOD']==="GET") {
        if(isset($_GET['item']) && $_GET['item'] === 'product') {
            global $conn;
            $upit = '';
            if(isset($_GET['limit'])){
                $upit = 'SELECT * FROM products prod INNER JOIN prices price ON prod.product_id = price.product_id INNER JOIN discounts d ON price.discount_id=d.discount_id INNER JOIN categories c ON prod.category_id = c.category_id INNER JOIN brands b ON prod.brand_id = b.brand_id WHERE prod.is_deleted=0 LIMIT 0, 6';
            } else {
                $upit = "SELECT * FROM products prod INNER JOIN prices price ON prod.product_id = price.product_id INNER JOIN discounts d ON price.discount_id=d.discount_id INNER JOIN categories c ON prod.category_id = c.category_id INNER JOIN brands b ON prod.brand_id = b.brand_id WHERE prod.is_deleted=0";
            }
            $allProducts = $conn->prepare($upit);
            $allProducts->execute();
            $allProducts = $allProducts->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($allProducts);
            http_response_code(200);
        }else if(isset($_GET['item']) && $_GET['item'] === 'users'){
            global $conn;
            $allusers = $conn->prepare("SELECT * FROM users u INNER JOIN roles r ON u.role_id = r.role_id WHERE isDeleted=0");
            $allusers->execute();
            $allusers=$allusers->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($allusers);
            http_response_code(200);
        }else if(isset($_GET['item']) && $_GET['item'] === 'discounts'){
            global $conn;
            $allDiscounts = $conn->prepare("SELECT * FROM  discounts WHERE is_deleted = 0");
            $allDiscounts->execute();
            $allDiscounts=$allDiscounts->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($allDiscounts);
            http_response_code(200);
        }else if (isset($_GET['item']) && $_GET['item'] === 'product_size'){
            global $conn;
            $id = $_GET['id'];
            $allPsizes = $conn->prepare("SELECT * FROM product_size ps INNER JOIN sizes s ON ps.size_id=s.size_id WHERE product_id = $id");
            $allPsizes->execute();
            $allPsizes=$allPsizes->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($allPsizes);
            http_response_code(200);
        }else if(isset($_GET['item']) && $_GET['item'] === 'product_specification'){
            global $conn;
            $id = $_GET['id'];
            $allPspecifications = $conn->prepare("SELECT * FROM product_specification ps INNER JOIN specifications s ON ps.spec_id=s.specification_id WHERE prod_id = $id");
            $allPspecifications->execute();
            $allPspecifications=$allPspecifications->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($allPspecifications);
            http_response_code(200);
        }else if(isset($_GET['item']) && $_GET['item'] === 'images'){
            try{
                $item = $_GET['item'];
                $id = $_GET['id'];
                $data = getItems($item, $id);
                echo json_encode($data);
                http_response_code(200);
               } catch(Exception $e){echo 'nesto nije u redu';}
        }else if(isset($_GET['item']) && $_GET['item'] === 'cart'){
            try{
                global $conn;
                $user_id = $_GET['id'];
                $allCartItems = $conn->prepare("SELECT  c.cart_id, mainImg, p.product_id, p.name,price_id, percentage, price, s.size_id, size, pi.quantity as lagetQty, ci.quantity as quantity, ci.cart_item_id FROM cart c INNER JOIN cart_item ci ON c.cart_id = ci.cart_id INNER JOIN products p ON ci.product_id=p.product_id INNER JOIN sizes s ON ci.size_id=s.size_id INNER JOIN prices pr ON p.product_id=pr.product_id INNER JOIN discounts d ON d.discount_id=pr.discount_id INNER JOIN product_inventory pi ON p.product_id=pi.product_id WHERE c.user_id=:id AND c.is_purchased=0");
                $allCartItems->bindParam(":id", $user_id);
                $allCartItems->execute();
                $allCartItems = $allCartItems->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($allCartItems);
                http_response_code(200);
            }catch(PDOException $e){
                echo 'nesto nije u redu';
                http_response_code(500);
            }
            
            
        }else if(isset($_GET['item']) && $_GET['item'] === 'sizes'){
             global $conn;
            $sizes = $conn->prepare("SELECT * FROM  sizes WHERE isDeleted = 0");
            $sizes->execute();
            $sizes=$sizes->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($sizes);
            http_response_code(200);
        }else {
           try{
            $item = $_GET['item'];
            $data = getItems($item);
            echo json_encode($data);
            http_response_code(200);
           } catch(Exception $e){echo 'nesto nije u redu';}
        }
    } else {
        redirect('../index.php');
    }