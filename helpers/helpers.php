<?php
function display_errors($errors)
  {
    $display='<ul class="bg-danger">';
    foreach($errors as $error){
      $display.='<li class="text-danger">'.$error.'</li>';
    }
    $display.='</ul>';

    return $display;
  }


  function sanitize($dirty)
  {
    return htmlentities($dirty,ENT_QUOTES,"UTF-8");
  }

  function login($user_id){
    $_SESSION['SBUser']=$user_id;
    global $db;
    $date=date("Y-m-d H:i:s");
    $sql="UPDATE users SET last_login='$date' WHERE id='$user_id' ";
    $query=mysqli_query($db,$sql);
    $_SESSION['success_flash']=' You Are Sucessfully Logged in !';
    header('Location:index.php');
  }

  function is_logged_in()
  {
    if(isset($_SESSION['SBUser']) && $_SESSION['SBUser'] > 0)
    {
      return true;
    }
      return false;
  }

  function login_error_redirect($url='login.php')
  {
    session_start();
    $_SESSION['error_flash']="You must login to Acess Page";

    header('Location:'.$url);
  }

  function permission_error_redirect($url='login.php')
  {
    $_SESSION['error_flash']="You have not permission To Acess Page";
    header('Location:'.$url);

  }

  function has_permission($permission = 'admin')
  {
    global $user;
    $permissions=explode(',',$user['permission']);
    if(in_array($permission,$permissions,true)){
      return true;
    }
      return false;
  }
  function preety_date($date){
    return date("M d, Y h:i A",strtotime($date));
  }

  function get_category($child_id){
    global $db;
    $id=sanitize($child_id);
    $csql="SELECT p.id AS 'pid' , p.category AS 'parent' , c.id AS 'cid',c.category AS 'child' FROM categories c
    INNER JOIN categories p ON c.parent=p.id
    WHERE c.id='$id'";
    $cquery=mysqli_query($db,$csql);
    $category=mysqli_fetch_assoc($cquery);
    return $category;

  }
 ?>
