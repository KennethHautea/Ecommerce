<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
var_dump($_POST);

 $product_id = isset($_POST['product_id'])? sanitize($_POST['product_id']):'';
 $size = isset($_POST['size'])? sanitize($_POST['size']):'';
 $available = isset($_POST['available'])? sanitize($_POST['available']):'';
 $quantity = isset($_POST['quantity'])? sanitize($_POST['quantity']):'';
 $item = array();
 $item[] = array(
  'id'       =>$product_id,
  'size'     =>$size,
  'quantity' =>$quantity,

 );

 $domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
 $domain = '';
 $query = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
 $product = mysqli_fetch_assoc($query);
 $_SESSION['success_flash'] = $product['title'].' Added To Your Cart';



 // check if the cart cookie exist
 if($new_cart_id != '')
 {
  $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$new_cart_id}'");
  $cart = mysqli_fetch_assoc($cartQ);
  $previous_items = array();
  $previous_items = json_decode($cart['items'],true);
  $item_match = 0;
  $new_items = array();
  foreach((array)$previous_items as $pitem)
  {
   if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size'])
   {
    $pitem['quantity'] = $pitem['quantity'] + $item[0]['quantity'];
    if($pitem['quantity'] > $available)
    {
     $pitem['quantity'] = $available;
    }
    $item_match = 1;
   }
   $new_items[] = $pitem;
  }
  if($item_match != 1)
  {
   $new_items = array_merge($item,(array)$previous_items);
  }
  $items_json = json_encode($new_items);
  $cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
  $db->query("UPDATE cart SET items = '{$items_json}', expire_date = '{$cart_expire}' WHERE id = '{$new_cart_id}'");
  setcookie(CART_COOKIE,'',1,"/",$domain,false);
  setcookie(CART_COOKIE,$new_cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
 }
 else
 {
  //add the cart to the database and set cookies
  $items_json = json_encode($item);
  $cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
  $db->query("INSERT INTO cart (items,expire_date) VALUES('{$items_json}','{$cart_expire}')");

  $new_cart_id = $db->insert_id; //$db->insert_id will return the last id from the database and will set it to new_cart_id
  setcookie(CART_COOKIE,$new_cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
 }
?>﻿
