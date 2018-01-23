<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/header_partial.php';

  if($new_cart_id!=''){
    $sql="SELECT * FROM cart WHERE id ='{$new_cart_id}'";
    $query=mysqli_query($db,$sql);
    $prouct=mysqli_fetch_assoc($query);
    $items=json_decode($prouct['items'],true);
    $i=1;
    $sub_total=0;
    $item_count=0;
  }

  ?>

  <div class="col-md-12">
    <div class="row">
      <h2 class="text-center">Your Shooping cart</h2>
      <hr>
      <?php if ($new_cart_id=='') {?>
        <div class="bg-danger">
          <p class="text-center text-danger">
            Your Shooping cart is Empty !
          </p>
        </div>

<?php } else { ?>
  <div class="container-fluid" style="maring-top:48px;padding-bottom:20px;">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>$</th>
          <th>Item</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Size</th>
          <th>Sub Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
         foreach($items as $item)
         {

          $product_id = $item['id'];
          $productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
          $product = mysqli_fetch_assoc($productQ);
          $sArray = explode(',', $product['sizes']);
          foreach($sArray as $sizeString)
          {
           $s = explode(':', $sizeString);
          }
          if($s[0] == $item['size'])
          {
           $available = $s[1];
          }


         ?>
         <tr>
          <td><?=$i;?></td>
          <td><?=$product['title'];?></td>
          <td><?=$product['title'];?></td>
          <td><?=$item['quantity'];?></td>
          <td><?=$item['size'];?></td>
          <td><?=($item['quantity'] * $product['price']);?></td>
         </tr>
        <?php
        $i++;
        $item_count+=$item['quantity'];
        $sub_total+=$product['price'] * $item['quantity'];
      }
      $tax=TAXRATE*$sub_total;
      $tax=number_format($tax,2);
      $grand_total=$tax * $sub_total;

      ?>
      </tbody>
    </table>
    <hr>
    <h2 class="text-center">Total</h2>
    <hr>
    <table class="table table-hover" style="margin-top:10px;">
    <thead style="color:black;font-size:14px;">
      <tr>
        <th></th>
        <th>Total Items</th>
        <th>Sub Total</th>
        <th>Tax</th>
        <th>=</th>

        <th>&nbsp;&nbsp;Total</th>

      </tr>
    </thead>
    <tbody>
      <tr>
        <th>#</th>
        <td><?=$item_count?></td>
        <td><?=$sub_total?></td>
        <td><?=$tax?></td>
        <td>=</td>

        <td class="bg-success"><?=$grand_total?></td>

      </tr>
    </tbody>
  </table>
<br>
  <button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#myModal"> <span class="glyphicon glyphicon-shopping-cart"></span>  Checkout >></button>

  <br>
  <br>
  <br>
  <br>
<br>
  </div>
  <?php } ?>
    </div>
  </div>


  <!-- Modal -->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  <script type="text/javascript">
  function check_address()
  {
    
  }
  </script>
