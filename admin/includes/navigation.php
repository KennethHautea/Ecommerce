<style media="screen">
  li{
    font-size: 18px;
  }
</style>
<!--navbar-->
    <nav class="navbar navbar-default navbar-fixd-top" style="margin-bottom:0px;">
      <div class="container">
        <a href="index.php" class="navbar-brand" style="font-size:20px;">[<span style="color:red;"> Admin</span> Panel ]</a>
        <ul class="nav navbar-nav">
          <li>  <a href="../index.php" >Home</a></li>
          <li>  <a href="brands.php" >Brands</a></li>
          <li>  <a href="categories.php" >Categories</a></li>
          <li>  <a href="products.php" >Products</a></li>
          <li>  <a href="Archived.php" >Archived</a></li>
          <?php if(has_permission('admin')){ ?>
          <li>  <a href="users.php" >Users</a></li>
        <?php } ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle bg-success" data-toggle="dropdown"> <?=$user['full_name']?> <span class="caret"></span>  </a>
          <ul class="dropdown-menu" role="menu">
          <li> <a href="change_password.php">Change Password</a> </li>
          <li> <a href="logout.php">Log Out</a> </li>
        </ul>
        </li>
        </ul>
      </div>
    </nav>
<!--navbar end-->
