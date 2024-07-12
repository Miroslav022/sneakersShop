<?php
// include '../config/connection.php';

function getNavLinks(){
    global $conn;
    $nav = $conn->prepare("SELECT * FROM navigation");
    $nav->execute();
    $nav = $nav->fetchAll(PDO::FETCH_ASSOC);
    return $nav;
}

function checkFieldWithRegex($field, $regex){
        
    if(!preg_match($regex, $field)) {
        return false;
    } else {
        return true;
    }
    
}

function selectOperation($table) {
    global $conn;
    $select = $conn->prepare("SELECT * FROM $table");
    $select->execute();
    $select = $select->fetchAll(PDO::FETCH_ASSOC);
    return $select;
}

function redirect($page) {
    header('Location: $page');
}

function checkSession() {
    session_start();
    if(!isset($_SESSION['user'])) {
        header('location: ../index.php');
    }
}

function getSingleProduct($id) {
    global $conn;
    $singleProduct = $conn->prepare("SELECT * FROM products prod INNER JOIN prices price ON prod.product_id = price.product_id INNER JOIN discounts d ON price.discount_id=d.discount_id INNER JOIN categories c ON prod.category_id = c.category_id INNER JOIN brands b ON prod.brand_id = b.brand_id INNER JOIN product_inventory pi ON prod.product_id = pi.product_id WHERE prod.product_id=:id");
    $singleProduct->bindParam(":id", $id);
    $singleProduct->execute();
    $singleProduct = $singleProduct->fetch(PDO::FETCH_ASSOC);
    return $singleProduct;
}

function getItems($item, $id = null){
    global $conn;
    if($id!==null){
        $allItems = $conn->prepare("SELECT * FROM $item WHERE product_id=:id");
        $allItems->bindParam(":id", $id);
        $allItems->execute();
        $allItems = $allItems->fetchAll(PDO::FETCH_ASSOC);
        return $allItems;
    }
    $hiddenItems = ['products', 'categories', 'brands', 'discounts'];
    if (in_array($item, $hiddenItems))
    {
        $allItems = $conn->prepare("SELECT * FROM $item WHERE is_deleted=0");
        $allItems->execute();
        $allItems = $allItems->fetchAll(PDO::FETCH_ASSOC);
        return $allItems;
    } else {
        $allItems = $conn->prepare("SELECT * FROM $item");
        $allItems->execute();
        $allItems = $allItems->fetchAll(PDO::FETCH_ASSOC);
        return $allItems;
    }
    
}
function getProductSizes($id){
    global $conn;
        $allItems = $conn->prepare("SELECT * FROM sizes s INNER JOIN product_size ps ON s.size_id = ps.size_id WHERE ps.product_id=:id");
        $allItems->bindParam(":id", $id);
        $allItems->execute();
        $allItems = $allItems->fetchAll(PDO::FETCH_ASSOC);
        return $allItems;
   
    
}

function getSingleItem($id, $table, $table_id) {
    if($table=='users') {
        global $conn;
        $singleItem = $conn->prepare("SELECT * FROM users u INNER JOIN roles r ON u.role_id=r.role_id WHERE $table_id=:id");
        $singleItem->bindParam(':id', $id);
        $singleItem->execute();
        $singleItem = $singleItem->fetch(PDO::FETCH_ASSOC);
        return $singleItem;
    } else {
        global $conn;
    $singleItem = $conn->prepare("SELECT * FROM $table WHERE $table_id=:id");
    $singleItem->bindParam(':id', $id);
    $singleItem->execute();
    $singleItem = $singleItem->fetch(PDO::FETCH_ASSOC);
    return $singleItem;
    }
    
}

function getAllSpecifications($productId){
    global $conn;
    $allSpecs = $conn->prepare("SELECT * FROM specifications s INNER JOIN product_specification ps ON s.specification_id = ps.spec_id WHERE ps.prod_id=:id");
    $allSpecs->bindParam(":id", $productId);
    $allSpecs->execute();
    $allSpecs = $allSpecs->fetchAll(PDO::FETCH_ASSOC);
    return $allSpecs;
}
function insert($params, $table, $data) {
    global $conn;
    $coloumNames = array_keys($data);
    $coloumnNamesString = implode(", ",$coloumNames);

    $values = array_values($data);

    $param = explode(",", $params);
    try {
        
        $insert = $conn->prepare("INSERT INTO $table ($coloumnNamesString) VALUES ($params)");
    
        foreach($param as $key => $value) {
            $insert->bindParam("$value", $values[$key]);
        }
        $insert->execute();
        http_response_code(204);
        header("Location: ../views/adminPanel/admin.php?page=additem&table=$table&success=Success");
        unset($_SESSION["user"]['errors']);

    }catch(PDOException $e){
        http_response_code(500);
        echo json_encode("Error with database connection");
    }
}

function update($table, $id, $data, $idColoum) {
    try{
        $updateParams='';
        $interations =  count($data);
        $i = 0;
        foreach($data as $key => $value) {
            $updateParams .= "$key=:$key";
            $i++;
            if($i<$interations) $updateParams .=', ';
        }
        global $conn;
        $update = $conn->prepare("UPDATE $table SET $updateParams WHERE $idColoum=$id");
        foreach($data as $key => $value) {
            $update->bindParam(":$key", $value);
        }
        $update->execute();
        http_response_code(204);
        header("Location: ../views/adminPanel/admin.php?page=edit&table=$table&id=$id&success=Success");
    } catch(PDOException $e){
        http_response_code(500);
        echo json_encode("Error with database connection");
    }
    
}

function issetVariable($name) {
    if(isset($name)) {
        return true;
    }
    return false;
}

function getUserCartId($user_id) {
    global $conn;
    $cart_id = $conn->prepare("SELECT cart_id FROM cart WHERE user_id=:user_id AND is_purchased=0");
    $cart_id->bindParam(":user_id", $user_id);
    $cart_id->execute();
    $cart_id = $cart_id->fetch(PDO::FETCH_ASSOC);
    return $cart_id;
}

function newestProducts(){
    global $conn;
    $allProducts = $conn->prepare("SELECT * FROM products prod INNER JOIN prices price ON prod.product_id = price.product_id INNER JOIN discounts d ON price.discount_id=d.discount_id INNER JOIN categories c ON prod.category_id = c.category_id INNER JOIN brands b ON prod.brand_id = b.brand_id WHERE prod.is_deleted=0 ORDER BY prod.created_at DESC LIMIT 0, 8");
    $allProducts->execute();
    $allProducts = $allProducts->fetchAll(PDO::FETCH_ASSOC);
    return $allProducts;
}

function topDiscountProducts(){
    global $conn;
    $allProducts = $conn->prepare("SELECT * FROM products prod INNER JOIN prices price ON prod.product_id = price.product_id INNER JOIN discounts d ON price.discount_id=d.discount_id INNER JOIN categories c ON prod.category_id = c.category_id INNER JOIN brands b ON prod.brand_id = b.brand_id WHERE prod.is_deleted=0 ORDER BY d.percentage DESC LIMIT 0, 8");
    $allProducts->execute();
    $allProducts = $allProducts->fetchAll(PDO::FETCH_ASSOC);
    return $allProducts;
}


// function getAllFromCart($user_id){
//     global $conn;
//     $allItems = $conn->prepare("SELECT * FROM cart c INNER JOIN products p ON c.product_id=p.product_id INNER JOIN sizes s ON c.size_id=s.size_id INNER JOIN prices pr ON p.product_id=pr.product_id INNER JOIN discounts d ON d.discount_id=pr.discount_id WHERE c.user_id=:id");
//     $allItems->bindParam(":id", $user_id);
//     $allItems->execute();
//     $allItems = $allItems->fetchAll(PDO::FETCH_ASSOC);
//     return $allItems;
// }