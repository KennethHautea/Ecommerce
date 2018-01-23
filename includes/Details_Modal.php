<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';

$id=$_POST['id'];
$id=(int)$id;
$sql="SELECT * FROM Products WHERE id ='$id'";
$query=mysqli_query($db,$sql);
$products=mysqli_fetch_assoc($query);

$brand_id=$products['brand'];

$sql1="SELECT * FROM brand WHERE id ='$brand_id'";
$query1=mysqli_query($db,$sql1);
$brand=mysqli_fetch_assoc($query1);

$sizestring=$products['sizes'];
echo $products['sizes'];
$size_array=explode('.',$sizestring);

 ?>
<?php ob_start(); ?>
<!-- Modal -->
<div id="details-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center"><?= $products['title'];?></h4>

      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="bg-danger" id="modal_errors"></div>
            <div class="col-md-6">
              <div class="center-block">
                <center> <img src="<?= $products['image'];?>" style="height:300px;" alt="<?= $products['title'];?>" class="img-responsive details"></center>
              </div>
            </div>

            <div class="col-md-6">
              <h4>Details</h4>
              <p><?= $products['description'];?></p>
              <hr>
              <p><b>Price:</b> <?= $products['price'];?></p>
              <p><b>Brand:</b><?= $brand['brand']?></p>
              <hr>
              <form action="add_cart.php" method="POST" id="add_product_form">
                <input type="hidden" name="product_id"  value="<?=$id;?>">
                <input type="hidden" name="available"  id="available" value="">
                <div class="form-group">
                  <div class="col-xs-12" style="margin-left:-12px;">
                    <label for="quantity">Quantity:</label>
                  <!-- </div> -->
                  <!-- <div class="col-xs-6"> -->
                    <input type="text" class="form-control" name="quantity" id="quantity">
                  </div>

                </div>
                <hr>

                <div class="form-group">
                  <div class="col-xs-12" style="margin-left:-12px;">
                  <label for="size">Size:</label>
                <!-- </div> -->
                <!-- <div class="col-xs-6"> -->
                <br>
                  <select class="form-control" id="size" name="size">
                    <option value=""></option>
                    <?php
                    foreach ($size_array as $string) {
                      $string_array=explode(':',$string);
                      $size=$string_array[0];
                      $available=$string_array[1];

                      echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.' '.'[ '.$available .' '.'Available ]</option>';

                    }?>

                  </select>
                </div>
                </div>
                <!-- <p><b>Price:</b> <= $available?></p> -->


              </form>
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="CloseModal()">Close</button>
        <button type="button" class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add to Cart</button>
      </div>
    </div>

  </div>
</div>

<?php echo ob_get_clean(); ?>

<script type="text/javascript">
jQuery('#size').change(function(){
var available=jQuery('#size option:selected').data("available");
jQuery('#available').val(available);
});
function CloseModal(){
jQuery('#details-modal').modal('hide');
setTimeout(function(){
  jQuery('#details-modal').remove();
},500);
}
function update_cart(mode,edit_id,edit_size)
{
  var data={"mode":mode,"edit_id":edit_id,"edit_size":edit_size};
  jQuery.ajax({
    url:'/Ecommerce/admin/parser/update_cart.php',
    method: "post",
    data:data,
    success:function(){
      location.reload();
    },
    error:function(){
      alert('Something Wrong !');
    }
  });
}

function add_to_cart()
{
  jQuery('#modal_errors').html("");
  var size =jQuery('#size').val();
  var quantity =jQuery('#quantity').val();
  var available =jQuery('#available').val();
  var error ='';
  var data=jQuery('#add_product_form').serialize();
  if(size=='' || quantity=='' || quantity==0)
  {
    error+='<p class="text-danger text-center">You Must Choose a size and Quantity !</p>';
    jQuery('#modal_errors').html(error);
  }
  else if (quantity > available) {
    error+='<p class="text-danger text-center">There are Only '+available+' are available !</p>';
    jQuery('#modal_errors').html(error);
    return;
  }
  else {
    jQuery.ajax({
      url:'/Ecommerce/admin/parser/add_cart.php',
      method: "post",
      data:data,
      success:function(){
        location.reload();
      },
      error:function(){
        alert('Something Wrong !');
      }
    });
  }
}
</script>
