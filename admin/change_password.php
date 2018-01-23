<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
if(!is_logged_in())
{
  login_error_redirect();
}
include 'includes/head.php';
$get_hashed=$user['password'];
$user_id=$user['id'];
$old_password=((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password=trim($old_password);
$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
$password=trim($password);
$confirm_password=((isset($_POST['confirm_password']))?sanitize($_POST['confirm_password']):'');
$confirm_password=trim($confirm_password);
$hashed=password_hash($password,PASSWORD_DEFAULT);
$errors=array();

?>
<style media="screen">
  body{
    background-image: url('/Ecommerce/images/headerlogo/background.png');
    background-attachment: fixed;
  }
</style>
<div id="login-form"
style="
    margin: 152px 452px;
    padding: 49px;
    box-shadow: 1px 1px 218px 52px green;
    border: 6px solid green;
    border-radius: 9px;">
  <div>
<?php
if($_POST){
    if(empty($_POST['old_password']) ||empty($_POST['password']) ||empty($_POST['confirm_password']))
    {
      $errors[]="You Must Provide All fields";
    }

    else {
     //if password does not matches with confirm password
      if($password!=$confirm_password)
      {
        $errors[]='The Password does not matches with confirm Password';
      }
      if(!password_verify($old_password,$get_hashed))
      {
        $errors[]='The Old Password does not matches';
      }
    }


      if(!empty($errors))
      {
        echo display_errors($errors);
      }
      else{
        $bsql="UPDATE users SET password ='$hashed' WHERE id='$user_id'";
        $bquery=mysqli_query($db,$bsql);
        $_SESSION['success_flash']="Your password has been Updated!";
        header('Location:login.php');
      }
    }
 ?>
   </div>
<h2 class="text-center">Change Password </h2>
<form  action="change_password.php" method="post">

<div class="form-group">
<label for="old_password">Old password:</label>
<input type="password" name="old_password" value="<?=$old_password?>"  class="form-control">
</div>
<div class="form-group">
<label for="password">Password:</label>
<input type="password" name="password" value="<?=$password?>" class="form-control ">
</div>
<div class="form-group">
<label for="Confirm password">Confirm Password:</label>
<input type="password" name="confirm_password" value="<?=$confirm_password?>" class="form-control ">
</div>

<div class="form-group">
  <a href="index.php" class="btn btn-default">Cancel</a>
  <input type="submit" value="Change Password" class="btn btn-success">
</div>
</form>

</div>

<?php include 'includes/footer.php';?>
