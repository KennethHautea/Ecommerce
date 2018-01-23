<?php
  require_once '../core/init.php';
  if(!is_logged_in())
  {
    login_error_redirect();
  }

  include 'includes/head.php';
  include 'includes/navigation.php';

 ?>

 <?php
 $sql="SELECT * FROM brand ORDER BY brand ";
 $query=mysqli_query($db,$sql);
 $errors=array();

 //Delete Brand
 if (isset($_GET['delete']) && !empty($_GET['delete'])) {
   $delete_id=(int)$_GET['delete'];
   $delete_id=sanitize($delete_id);

   $sql3="DELETE FROM brand WHERE id='$delete_id' ";
   $query1=mysqli_query($db,$sql3);
   header('Location:brands.php');

 }

 //Edit Brand
 if (isset($_GET['edit']) && !empty($_GET['edit'])) {
   $edit_id=(int)$_GET['edit'];
   $edit_id=sanitize($edit_id);

   $sql4="SELECT * FROM brand WHERE id='$edit_id' ";
   $query4 =mysqli_query($db,$sql4);
   $eBrand=mysqli_fetch_assoc($query4);

 }





 //Add Brand
 if(isset($_POST['add_submit'])){
   $brand=sanitize($_POST['brand']);
   // check if brand is blank
   if($_POST['brand']==' ')
   {
     $errors[]="You Must Enter a Brand !";
   }
   // check if brand is Exist in Database
   $sql1="SELECT * FROM brand WHERE brand='$brand' ";
   if(isset($_GET['edit']))
   {
     $sql1="SELECT * FROM brand WHERE brand='$brand' AND id!='$edit_id' ";

   }
   $query1=mysqli_query($db,$sql1);
   $count=mysqli_num_rows($query1);
   if($count>0)
   $errors[]=$brand." Already Exist !";

   if(!empty($errors)){
     echo display_errors($errors);
   }
   else {
     //Add Brands to database
     $sql2="INSERT INTO brand(brand) VALUES ('$brand') ";
     if(isset($_GET['edit']))
     {
       $sql2="UPDATE brand SET brand ='$brand' WHERE id='$edit_id'";
     }
     $query2=mysqli_query($db,$sql2);
     header('Location:brands.php');

   }

 }

 ?>

<center>
 <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post" style="margin-top:50px;">
   <div class="form-group">
     <?php
     $brand_value=" ";
     if(isset($_GET['edit']))
     {
       $brand_value=$eBrand['brand'];
     }
     else {
       if(isset($_POST['brand']))
       {
       $brand_value=sanitize($_POST['brand']);
       }
     }
      ?>
     <label for="brand"><?=((isset($_GET['edit']))?'&nbsp;&nbsp;Edit':'Add ');?>  Brand:</label>
     <input type="text" class="form-control" id="brand"  name="brand" value="<?=$brand_value?>">
   <?php if(isset($_GET['edit'])) {?>
   <a href="brands.php" class="btn btn-md btn-primary">Cancel</a>

 <?php }?>
     <input type="submit" name="add_submit" value=" <?=((isset($_GET['edit']))?'&nbsp;&nbsp;Edit':'Add ');?>  Brand" class="btn btn-success">
 </div>
 </form>
</center>



 <h2 class="text-center ">Brands</h2>

 <table class="table table-striped" border="1" style="width:50%;margin-left:350px;border-color:green;">
    <thead>
      <tr>
        <th>Edit</th>
        <th>Name</th>
        <th>Delete</th>

      </tr>
    </thead>
    <tbody>
      <?php while($brands=mysqli_fetch_assoc($query)) { ?>

      <tr>
        <td> <a href="brands.php?edit=<?= $brands['id'] ?>" class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-pencil"></span> </a> </td>
        <td><?= $brands['brand'] ?></td>
        <td> <a href="brands.php?delete=<?= $brands['id'] ?>" class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-remove-sign"></span> </a> </td>
      </tr>
    <?php } ?>

    </tbody>
  </table>

<?php
include 'includes/footer.php';

?>
