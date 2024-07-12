<?php
    header("content-type: appliation/json");
    include '../config/connection.php';
    
    if($_SERVER['REQUEST_METHOD']=="POST"){
        global $conn;
        $upit = 'SELECT * FROM products prod INNER JOIN prices price ON prod.product_id = price.product_id INNER JOIN discounts d ON price.discount_id=d.discount_id INNER JOIN categories c ON prod.category_id = c.category_id INNER JOIN brands b ON prod.brand_id = b.brand_id WHERE prod.is_deleted=0';

        if(isset($_POST['categories']) && $_POST['categories']!='') {
            $upit.= " AND prod.category_id IN ('".implode("','", $_POST['categories'])."')";  
        }
        if(isset($_POST['brands']) && $_POST['brands']!='') {
            $upit.= " AND prod.brand_id IN ('".implode("','", $_POST['brands'])."')";  
        }
        if(isset($_POST['genders']) && $_POST['genders']!='') {
            $upit.= " AND prod.gender_id IN ('".implode("','", $_POST['genders'])."')";  
        }
        if(isset($_POST['search']) && $_POST['search']!='') {
            $upit .= " AND prod.name LIKE('%".$_POST['search']."%')";
        }
        if(isset($_POST['sort']) && $_POST['sort']!='') {
            if($_POST['sort']=='asc'){
                $upit.= " ORDER BY (price.price - (price.price * d.percentage) / 100) ASC";
            }
            if($_POST['sort']=='desc'){
                $upit.= " ORDER BY (price.price - (price.price * d.percentage) / 100) DESC";
            }
            if($_POST['sort']=='newest'){
                $upit.= " ORDER BY prod.created_at DESC";
            }
        }

        //Number of products 
        $products = $conn->prepare($upit);
        $products->execute();
        $products = $products->fetchAll(PDO::FETCH_ASSOC);
        $finallNumber = count($products);

        $showPerPage = isset($_POST['showPerPage']) ? $_POST['showPerPage'] : 6;
        $page = $_POST['page'];
        $page = $page*$showPerPage;
        $upit.= " LIMIT $page, $showPerPage";
        
        $products = $conn->prepare($upit);
        $products->execute();
        $products = $products->fetchAll(PDO::FETCH_ASSOC);

        $data = array(
            'products' => $products,
            'number' => $finallNumber
        );

        if($products){
            echo json_encode($data);
            http_response_code(200);
        } else {
            echo json_encode("Sorry, we currently do not have this product.");
            http_response_code(204);

        }
    }else {
        redirect("location: ../index.php");
    }