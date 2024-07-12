<?php 
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

include 'models/functions.php';
include 'config/connection.php';
include 'models/logFileFunctions.php';
insertIntoLogFile();
ob_start();
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">
  <?php include 'views/fixed/head.php'?>

  <body>
    <!-- Start Header Area -->
    <?php include 'views/fixed/nav.php' ?>
    <!-- End Header Area -->

   <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    switch ($page) {
      
      case 'login':
        include 'views/pages/login.php';
        break;
      case 'shop':
        include 'views/pages/shop.php';
        break;
      case 'cart':
        include 'views/pages/cart.php';
        break;
      case 'author':
        include 'views/pages/author.php';
        break;
      case 'elements':
        include 'views/pages/elements.php';
        break;
      case 'single-product':
        include 'views/pages/single-product.php';
        break;
      case 'edit-user':
        include 'views/pages/editUser.php';
        break;
      case 'register':
        include 'views/pages/register.php';
        break;
      
      default: include 'views/pages/home.php';
    }
   ?>

    <!-- start footer Area -->
	<?php include 'views/fixed/footer.php'?>
  </body>
</html>
