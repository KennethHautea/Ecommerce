<?php
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/header_partial.php';
  // include 'includes/coursel.php';

  if(isset($_GET['cat']))
  {
    $cat_id=sanitize($_GET['cat']);
  }
  else {
    $cat_id='';
  }

 ?>


 <?php
 $sql="SELECT * FROM Products WHERE categories ='$cat_id'";
 $query=mysqli_query($db,$sql);
 $category=get_category($cat_id);
 // var_dump($category);
 ?>



<!--Content -->
<div class="container-fluid">

<!--Left-->
  <?php include 'includes/leftbar.php'; ?>

<!--Middle-->
  <div class="col-md-8">
    <h2 class="text-center" style="margin:45px;"><?=$category['parent']?></h2>

    <div class="row">

<?php while($product=mysqli_fetch_assoc($query)) { ?>
      <!--Product 1-->
      <div class="col-md-3">
        <center>
        <h4><?= $product['title']?></h4>
        <img src="<?= $product['image']?>" alt="<?= $product['title']?>" class="img-thumb "/>
        <p class="list-price text-danger">List Price:Rs. <strike> <?= $product['list_price']?> </strike></p>
        <p class="price text-success">Our Price:Rs. <?= $product['price']?></p>
        <button type="button" name="button" class="btn btn-sm btn-success" onclick="Details_Modal_Button(<?=$product['id']?>);" > Details</button>
      </center>
      </div>

    <?php } ?>

    </div>
  </div>

<!--Right-->
<?php include 'includes/rightbar.php'; ?>

</div>


<!--Footer-->
<?php include 'includes/footer.php'; ?>
  </body>
  <script type="text/javascript" src="js/main.js"></script>

</html>
