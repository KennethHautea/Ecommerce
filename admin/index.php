
<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
  if(!is_logged_in())
  {
    header('Location:login.php');
  }

  include 'includes/head.php';
  include 'includes/navigation.php';
  // session_destroy();

 ?>

Admin adrea


<?php
include 'includes/footer.php';

?>
