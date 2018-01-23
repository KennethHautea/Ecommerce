<?php
$sql="SELECT * FROM categories WHERE parent =0";
$pquery=mysqli_query($db,$sql);
?>

<!--navbar-->
    <nav class="navbar navbar-default navbar-fixd-top" style="margin-bottom:0px;font-size:16px;">
      <div class="container">
        <a href="index.php" class="navbar-brand" style="font-size:26px;">[<span style="color:green;"> Air</span> Products ]</a>
        <ul class="nav navbar-nav">
          <?php while($parent=mysqli_fetch_assoc($pquery)) { ?>
            <?php $parent_id=$parent['id'];
            $csql="SELECT * FROM categories WHERE parent ='$parent_id'";
            $cquery=mysqli_query($db,$csql);
            ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $parent['category']?><span class="caret"></span>  </a>
            <ul class="dropdown-menu" role="menu">


            <?php while($child=mysqli_fetch_assoc($cquery)) { ?>
              <li> <a href="category.php?cat=<?=$child['id']?>"> <?= $child['category'] ?></a> </li>
            <?php } ?>
            </ul>
          </li>
        <?php } ?>
        <li> <a href="cart.php">Your Cart &nbsp;<span class="pull-right glyphicon glyphicon-shopping-cart"></span> </a>  </li>
        </ul>

      </div>
    </nav>
<!--navbar end-->
