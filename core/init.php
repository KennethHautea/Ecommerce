<?php
$db=mysqli_connect('localhost','root','','Air_products');

if(mysqli_connect_errno())
{
  echo "Database Connection Faild !";
  die();
}
session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/config.php';
require_once BASEURL.'helpers/helpers.php';

$new_cart_id='';

if(isset($_COOKIE[CART_COOKIE])){
  $new_cart_id=sanitize($_COOKIE[CART_COOKIE]);
}


if(isset($_SESSION['SBUser'])){
  $user_id=$_SESSION['SBUser'];
  $sql="SELECT * FROM users WHERE id='$user_id' ";
  $query=mysqli_query($db,$sql);
  $user=mysqli_fetch_assoc($query);


}
// session_destroy();


if(isset($_SESSION['success_flash'])){
  echo '<div id="mydiv" class="bg-success" style="height:40px"><p class="text-success text-center">'.$_SESSION['success_flash'].' <div>';
  unset($_SESSION['success_flash']);
}

if(!empty($_SESSION['error_flash'])){
  echo '<div id="mydiv" class="bg-danger" style="height:20px"><p class="text-danger text-center">'.$_SESSION['error_flash'].' <div>';
  unset($_SESSION['error_flash']);

}

// session_destroy();


 ?>
