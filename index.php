<?php
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/coursel.php';

 ?>


 <?php
 $sql="SELECT * FROM products WHERE featured =1";
 $query=mysqli_query($db,$sql);
 ?>



<!--Content -->
<div class="container-fluid">

<!--Left-->
  <?php include 'includes/leftbar.php'; ?>

<!--Middle-->
  <div class="col-md-8">
    <h2 class="text-center" style="margin:45px;">Featured Products</h2>

    <div class="row">

<?php while($featured=mysqli_fetch_assoc($query)) { ?>
      <!--Product 1-->
      <div class="col-md-3">
        <center>
        <h4><?= $featured['title']?></h4>
        <img src="<?= $featured['image']?>" alt="<?= $featured['title']?>" class="img-thumb "/>
        <p class="list-price text-danger">List Price:Rs. <strike> <?= $featured['list_price']?> </strike></p>
        <p class="price text-success">Our Price:Rs. <?= $featured['price']?></p>
        <button type="button" name="button" class="btn btn-sm btn-success" onclick="Details_Modal_Button(<?=$featured['id']?>);" > Details</button>
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
