
<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
  if(!is_logged_in())
  {
    login_error_redirect();
  }

  if(!has_permission('admin')){
    permission_error_redirect('index.php');
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

  if(isset($_GET['delete'])){
    $delete_id=sanitize($_GET['delete']);
    $dsql="DELETE FROM users WHERE id='$delete_id'";
    $dquery=mysqli_query($db,$dsql);
    $_SESSION['success_flash']='User has been deleted';
    header('Location:users.php');

  }

  if(isset($_GET['add'])){
    $name=((isset($_POST['name']) && $_POST['name']!='')?sanitize($_POST['name']):'');
    $email=((isset($_POST['email']) && $_POST['email']!='')?sanitize($_POST['email']):'');
    $password=((isset($_POST['password']) && $_POST['password']!='')?sanitize($_POST['password']):'');
    $confirm_password=((isset($_POST['confirm_password']) && $_POST['confirm_password']!='')?sanitize($_POST['confirm_password']):'');
    $permission=((isset($_POST['permission']) && $_POST['permission']!='')?sanitize($_POST['permission']):'');
    $errors=array();

    if($_POST){

      $esql="SELECT * FROM users WHERE email='$email'";
      $equery=mysqli_query($db,$esql);
      $count_email=mysqli_num_rows($equery);
      if($count_email!=0)
      {
        $errors[]='The Email Already exist !';
      }

        $required=array('name','email','password','confirm_password','permission');
        foreach ($required as $f) {
          if(empty($_POST[$f])){
            $errors[]="You must fill out all fields !";
            break;
          }
        }
        if($password!=$confirm_password)
         $errors[]="Confirm Password Does Not match !";

        if(!empty($errors)){
          echo display_errors($errors);
        }
        else {
          $hashed=password_hash($password,PASSWORD_DEFAULT);
          $isql="INSERT INTO users(`full_name`,`email`,`password`,`permission`) VALUES ('$name','$email','$hashed','$permission')";
          $iquery=mysqli_query($db,$isql);
          $_SESSION['success_flash']='User Has been added !';
          header('Location:users.php');
        }
    }
    ?>

    <h2 class="text-center">Add New User</h2>
    <div class="container-fluid">

    <form action="users.php?add=1" method="POST">
      <div class="form-group col-md-6">
        <label for="name">Full name</label>
        <input type="text" name="name" id="name" class="form-control" value="<?=$name?>">
      </div>

      <div class="form-group col-md-6">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control" id="email" value="<?=$email?>">
      </div>

      <div class="form-group col-md-6">
        <label for="Password">Password</label>
        <input type="password" name="password" class="form-control" id="password" value="<?=$password?>">
      </div>

      <div class="form-group col-md-6">
        <label for="Confirm_Password">Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" id="confirm_password" value="<?=$confirm_password?>">
      </div>

      <div class="form-group col-md-6" style="margin-top:24px;">
        <label for="Permission">Permission</label>
        <select class="form-control" name="permission">
          <option value=""<?=(($permission == '')?'selected':'')?>></option>
          <option value="editor"<?=(($permission == 'editor')?'selected':'')?>>Editor</option>
          <option value="admin,editor"<?=(($permission == 'admin,editor')?'selected':'')?>>Admin & Editor</option>
        </select>
      </div>

      <div class="form-group col-md-6">
        <a href="users.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="Add User" class="btn btn-success">
      </div>

    </form>
  </div>

    <!-- // $delete_id=sanitize($_GET['delete']);
    // $dsql="DELETE FROM users WHERE id='$delete_id'";
    // $dquery=mysqli_query($db,$dsql);
    // $_SESSION['success_flash']='User has been deleted';
    // header('Location:users.php'); -->

<?php
  }
  else {

  $ssql="SELECT * FROM users ORDER BY full_name";
  $squery=mysqli_query($db,$ssql);

 ?>

 <h2 class="text-center">Users</h2>
 <div class="container-fluid">
   <a href="users.php?add=1" class="btn btn-success">Add New User</a>
<hr>
<table class="table table-bordered table-striped">
  <thead>
<th></th>
<th>Name</th>
<th>Email</th>
<th>Join Date</th>
<th>Last Login</th>
<th>Permissions</th>
  </thead>
  <tbody>
    <?php
    while($result=mysqli_fetch_assoc($squery)){
   ?>
   <tr>

    <td>
    <?php if($result['id'] !=$user['id'] ){ ?>
      <a href="users.php?delete=<?=$result['id']?>"> <button type="button" name="button"><span class="glyphicon glyphicon-remove-sign"></span></button></a>
    <?php }
      else {
        ?>
        <button type="button" name="button"><span class="glyphicon glyphicon-pencil"></span></button>
    <?php  }?>
    </td>
    <td><?=$result['full_name']?></td>
    <td><?=$result['email']?></td>
    <td><?=preety_date($result['join_date'])?></td>
    <td><?=(($result['last_login']=='0000-00-00 00:00:00')?'Never':preety_date($result['last_login']))?></td>
    <td><?=$result['permission']?></td>
  <?php } ?>
</tr>

  </tbody>

</table>
</div>

<?php
include 'includes/footer.php';
}

?>
