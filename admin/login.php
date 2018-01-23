<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
include 'includes/head.php';
$email=((isset($_POST['email']))?sanitize($_POST['email']):'');
$email=trim($email);
$password=((isset($_POST['password']))?sanitize($_POST['password']):'');
$password=trim($password);
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
if(empty($_POST['email']) ||empty($_POST['password']))
{
  $errors[]="You Must Provide Email and Password";
}
else {
  //check Existance of email
  $sql="SELECT * FROM users WHERE email='$email' ";
  $query=mysqli_query($db,$sql);
  $user=mysqli_fetch_assoc($query);
  $userCount=mysqli_num_rows($query);
  if($userCount <1)
  {
    $errors[]="Email Does'nt Registered";
  }
  if(!password_verify($password,$user['password']))
  {
    $errors[]="Please Provide Correct Password";

  }
}


if(!empty($errors))
{
  echo display_errors($errors);
}else{
  $user_id=$user['id'];
  login($user_id);
}
}
 ?>
   </div>
<h2 class="text-center">Login </h2>
<form  action="login.php" method="post">

<div class="form-group">
<label for="email">Email:</label>
<input type="email" name="email" value="<?=$email?>"  class="form-control">
</div>
<div class="form-group">
<label for="password">Password:</label>
<input type="password" name="password" value="<?=$password?>" class="form-control ">
</div>
<!-- <center><a href="change_password.php">Forget Password</a></center> -->
<div class="form-group">
  <input type="submit" value="Login" class="btn btn-success">
</div>
</form>

</div>

<?php include 'includes/footer.php';?>
