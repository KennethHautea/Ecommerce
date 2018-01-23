<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
if(!is_logged_in())
{
  login_error_redirect();
}

include 'includes/head.php';
include 'includes/navigation.php';


//Delete Product
if(isset($_GET['delete'])){
  $deleteid=sanitize($_GET['delete']);
  $dusql="UPDATE Products SET deleted = 1 WHERE id= '$deleteid'";
  $duquery=mysqli_query($db,$dusql);
  header('Location:products.php');


}


if(isset($_GET['add']) || isset($_GET['edit'])){

$bsql="SELECT * FROM brand ORDER BY brand";
$bquery=mysqli_query($db,$bsql);

$title=((isset($_POST['title']) && $_POST['title']!='')?sanitize($_POST['title']):'');
$brand=((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
$price=((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):'');
$list_price=((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):'');
$parent=((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');

$category=((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
$description=((isset($_POST['description']) && $_POST['description']!='')?sanitize($_POST['description']):'');
$sizes=((isset($_POST['sizes']) && $_POST['sizes']!='')?sanitize($_POST['sizes']):'');
$sizes=rtrim($sizes,'.');
$saved_image='';




if(isset($_GET['edit']))
{
  $edit_id=(int)$_GET['edit'];

  $rsql="SELECT * FROM Products WHERE id='$edit_id'";
  $rquery=mysqli_query($db,$rsql);
  $productresult=mysqli_fetch_assoc($rquery);
  if(isset($_GET['delete_image']))
  {
    $image_url=$_SERVER['DOCUMENT_ROOT'].$productresult['image'];
    echo $image_url;
    unlink($image_url);

    $usql="UPDATE Products SET image ='' WHERE id= '$edit_id'";
    $uquery=mysqli_query($db,$usql);
    header('Location:products.php?edit='.$edit_id);
    // header('Location:products.php');


  }
  $category=((isset($_POST['child']) && $_POST['child']!='')?sanitize($_POST['child']):$productresult['categories']);
  $title=((isset($_POST['title']) && $_POST['title']!='')?sanitize($_POST['title']):$productresult['title']);
  $brand=((isset($_POST['brand']) && $_POST['brand']!='')?sanitize($_POST['brand']):$productresult['brand']);

  $ssql="SELECT * FROM categories WHERE parent='$category'";
  $squery=mysqli_query($db,$ssql);
  $parentresult=mysqli_fetch_assoc($squery);

  $parent=((isset($_POST['parent']) && $_POST['parent']!='')?sanitize($_POST['parent']):$parentresult['parent']);


  $price=((isset($_POST['price']) && $_POST['price']!='')?sanitize($_POST['price']):$productresult['price']);
  $list_price=((isset($_POST['list_price']) && $_POST['list_price']!='')?sanitize($_POST['list_price']):$productresult['list_price']);
  $description=((isset($_POST['description']) && $_POST['description']!='')?sanitize($_POST['description']):$productresult['description']);
  $title=((isset($_POST['title']) && $_POST['title']!='')?sanitize($_POST['title']):$productresult['title']);
  $sizes=((isset($_POST['sizes']) && $_POST['sizes']!='')?sanitize($_POST['sizes']):$productresult['sizes']);
  $sizes=rtrim($sizes,'.');

  $saved_image=(($productresult['image']!='')?$productresult['image']:'');
  $dbpath=$saved_image;








}

$sizesArray=array();
if($_POST)
{
  $parent=sanitize($_POST['parent']);
  $price=sanitize($_POST['price']);
  $title=sanitize($_POST['title']);
  $list_price=sanitize($_POST['list_price']);
  $description=sanitize($_POST['description']);
  $sizes=sanitize($_POST['sizes']);
  // $dbpath='';

  $errors=array();
  if(!empty($_POST['sizes']))
  {
    $sizeString=sanitize($_POST['sizes']);
    $sizeString=rtrim($sizeString.',');
    $sizesArray=explode(',',$sizeString);
    $sArray[]=array();
    $qArray[]=array();
    foreach($sizesArray as $ss)
    {
      $s=explode(':',$ss);
      if(!empty($s[0]) &&!empty($s[1]))
      {
        $sArray[]=$s[0];
        $qArray[]=$s[1];
      }
    }
  }
  else{
    $sizesArray=array();
  }

  $required=array('title','brand','price','parent','sizes');
  foreach ($required as $field) {
    if($_POST[$field]=='')
    {
        $errors[]="All Fields are required";
        break;
    }
  }

   if(!empty($_FILES) )
   {
     $photo=$_FILES['photo'];
     $size=$photo['size'];
     if($size=='')
     {
       $errors[]='You Must Choose a Product Photo !';
     }
      else
      {
        $Type=explode('/',$photo['type']);
        if($Type[0]!='image')
          $errors[]='The Upload Must Be an Image.';

        $tmploc=$photo['tmp_name'];
        $uploadName=md5(microtime()).'.'.$Type[1];

        $uploadPath=BASEURL .'/images/products/'.$uploadName;
        $dbpath='/Ecommerce/images/products/'.$uploadName;
      }



   }

  if(!empty($errors)){
    echo display_errors($errors);
  }
  else {
    // Insert and Upload Product datas price and description
    move_uploaded_file($tmploc,$uploadPath);
    $isql="INSERT INTO Products(`title`,`price`,`list_price`,`brand`,`categories`,`image`,`description`,`sizes`) VALUES ('$title','$price','$list_price','$brand','$parent','$dbpath','$description','$sizes')";
    if(isset($_GET['edit']))
    {
    $isql="UPDATE Products SET title='$title' , price='$price' , list_price='$list_price' , brand='$brand' , categories='$categories' , image='$dbpath' , description='$description' , sizes='$sizes' WHERE id ='$edit_id'";
    }
    $iquery=mysqli_query($db,$isql);
    header('Location:products.php');

  }
}

?>

<!-- ADD Product Form -->
<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add')?> Product</h2>
<hr>

<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1')?>" style="padding:5px;" method="POST" enctype="multipart/form-data">
<div class="form-group col-md-3">
  <label for="title">Title</label>
  <input type="text" class="form-control" name="title" id="title" value="<?=$title;?>">
</div>

<div class="form-group col-md-3">
  <label for="brand">Brand</label>
  <select class="form-control" id="brand" name="brand">
    <option value=""<?=(($brand=='')?'selected':'')?>></option>
    <?php while($b=mysqli_fetch_assoc($bquery)) { ?>
    <option value="<?=$b['id']?>" <?=(($brand==$b['id'])?'selected':'')?>><?=$b['brand']?></option>
  <?php } ?>

  </select>
</div>

<?php
$sqlp="SELECT * FROM categories WHERE parent = 0 ORDER BY category ";
$pquery=mysqli_query($db,$sqlp);

 ?>

<div class="form-group col-md-3">
  <label for="parent">Parent Category</label>

  <select class="form-control" id="parent" name="parent">
    <option value=""<?=(($parent=='')?'selected':'')?>></option>
    <?php while($pa=mysqli_fetch_assoc($pquery)) {?>
    <option value="<?=$pa['id']?>" <?=(($parent==$pa['id'])?'selected':'')?>><?=$pa['category']?></option>

  <?php
  }
   ?>

  </select>

</div>

<div class="form-group col-md-3">
  <label for="child">Child Category</label>
  <select class="form-control" name="child" id="child">
  </select>
</div>

<div class="form-group col-md-3">
  <label for="price">Price</label>
  <input type="text" class="form-control" name="price" id="price" value="<?=$price?>">
</div>

<div class="form-group col-md-3">
  <label for="price">List Price</label>
  <input type="text" class="form-control" name="list_price" id="list_price" value="<?=$list_price?>">
</div>

<div class="form-group col-md-3" >
  <label for="price">Quantity & Sizes</label>
<button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Sizes</button>
</div>

<div class="form-group col-md-3" >
  <label for="sizes">Sizes & Qty Preview</label>
  <input type="text" class="form-control" name="sizes" id="sizes" value="<?=$sizes;?>"readonly>
</div>

<div class="form-group col-md-6" >
  <?php if($saved_image !=''){ ?>
    <div class="saved-image">
      <img src="<?=$saved_image?>" alt="saved_image" height="250px" width="250px;"> <br>
      <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" style="margin:2px;" class=" btn btn-danger">Delete Image</a>

    </div>
  <?php }else{ ?>
  <label for="photo">Product Photo:</label>
  <input type="file" name="photo" id="photo" class="form-control">
<?php } ?>
</div>

<div class="form-group col-md-6" >
  <label for="Description">Description:</label>
  <textarea name="description" rows="8" class="form-control" cols="80"><?=$description;?></textarea>
</div>
<div class=" form-group pull-right" style="padding-right:23px;">
  <input type="submit" name="" value="<?=((isset($_GET['edit']))?'Edit':'Add')?> Product" class=" btn btn-success ">
  <a href="products.php" class="btn btn-default">Cancel</a>
</div>



<div class="clearfix"></div>

</form>


<!-- Modal -->
<div id="sizesModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="sizesModalLabel">Sizes & Quanity</h4>
      </div>

      <div class="modal-body">
        <div class="container-fluid">

        <?php for($i=1;$i<=12;$i++){ ?>
          <div class="form-group col-md-4">
            <label for="size<?=$i;?>">Size</label>
            <input type="text" class="form-control" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'')?>">
          </div>

          <div class="form-group col-md-2">
            <label for="qty<?=$i;?>">Quantity</label>
            <input type="number" class="form-control" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'')?>" min="0">
          </div>

        <?php } ?>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="update_sizes();jQuery('#sizesModal').modal('toggle');return false;">Save Changes</button>

      </div>
    </div>

  </div>
</div>
<?php
}
else{

$sql="SELECT * FROM Products WHERE deleted = 0 ";
$query=mysqli_query($db,$sql);


if (isset($_GET['featured'])) {
  $id=(int)$_GET['id'];
  $featured=(int)$_GET['featured'];

  $sql1="UPDATE Products SET featured='$featured' WHERE id = '$id' ";
  $query1=mysqli_query($db,$sql1);
  header('Location:products.php');

}

?>

<div class="container-fluid">

<h2 class="text-center" style="margin:25px;">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-button">Add Products</a>
<div class="clearfix">

</div>
<hr>

<!--table -->

<table class="table table-striped t" border="1" style="margin-top:20px;">
    <thead>
      <tr>
        <th>Edit</th>
        <th>Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Featured</th>
        <th>Sold</th>
      </tr>
      </thead>
      <tbody>
        <?php while($product=mysqli_fetch_assoc($query)) { ?>
          <?php
          $childID=$product['categories'];

          $catsql="SELECT * FROM categories WHERE id = '$childID' ";
          $result=mysqli_query($db,$catsql);
          $child=mysqli_fetch_assoc($result);

          $parent_ID=$child['id'];
          $psql="SELECT * FROM categories WHERE parent = '$parent_ID' ";
          $presult=mysqli_query($db,$psql);
          $parent=mysqli_fetch_assoc($presult);





          ?>
        <tr>
          <td> <a href="products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-pencil"></span> </a>
          <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-remove-sign"></span> </a> </td>

          <td><?=$product['title']?></td>
          <td><?=$product['price']?></td>
          <td>  <?$categor=get_category($product['categories']);?><?=$categor['parent']?> - <?=$child['category']?></td>
          <td><a href="products.php?featured=<?=(($product['featured']==0)?'1':'0');?>&id=<?=$product['id'];?> " class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus')?> "></span> </a></td>
          <td><?=$product['deleted']?></td>

        </tr>
      <?php } ?>
      </tbody>
      </table>
</div>

<?php } ?>
<?php include 'includes/footer.php';?>
<script type="text/javascript">
  jQuery('document').ready(function(){
    get_child_option('<?=$category;?>');
    // update_sizes();
    // get_child_option();

  });
</script>
