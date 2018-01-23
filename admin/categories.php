<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
if(!is_logged_in())
{
  login_error_redirect();
}

include 'includes/head.php';
include 'includes/navigation.php';

$sql="SELECT * FROM categories WHERE parent = 0 ";
$query=mysqli_query($db,$sql);
$errors1=array();

if (isset($_POST) && !empty($_POST)) {
  $post_parent=$_POST['parent'];
  $category=$_POST['Category'];

  if($category=='')
    {
      $errors1[]="You Must Enter a Category !";
    }


  $sql6="SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' ";
  $query6=mysqli_query($db,$sql6);
  $count=mysqli_num_rows($query6);

  if($count>0)
  $errors1[]=" Already This Category Exist !";

  if(!empty($errors1)){
    echo display_errors($errors1);
  }

  else {
    //Add Categories to database
    $sql2="INSERT INTO categories(category, parent) VALUES ('$category','$post_parent') ";
    $query2=mysqli_query($db,$sql2);
    header('Location:categories.php');

  }
}

//Edit Category's Items

if(isset($_GET['edit']) && !empty($_GET['edit']))
{
  $edit_id=(int)$_GET['edit'];
  $edit_id=sanitize($edit_id);
  $esql1="SELECT * FROM Categories WHERE id='$edit_id'";
  $equery1=mysqli_query($db,$esql1);
  $edit_category=mysqli_fetch_assoc($equery1);
}

$category_value='';
$parent_value=0;
$post_parent='';
if(isset($_GET['edit']))
{
  $category_value=$edit_category['category'];
  $parent_value=$post_parent;
}



//Delete Category's Items

if(isset($_GET['delete']) && !empty($_GET['delete']))
{
  $delete_id=(int)$_GET['delete'];
  $delete_id=sanitize($delete_id);

  $dsql1="SELECT * FROM Categories WHERE id='$delete_id'";
  $dquery1=mysqli_query($db,$dsql1);
  $category=mysqli_fetch_assoc($dquery1);

  if($category['parent']==0)
  {
    $dsql2="DELETE FROM Categories WHERE parent='$delete_id'";
    $dquery2=mysqli_query($db,$dsql2);
  }

   $dsql="DELETE FROM Categories WHERE id='$delete_id'";
   $dquery=mysqli_query($db,$dsql);
   header('Location:categories.php');


}
?>


<!-- Process Form -->

<h2 class="text-center" style="margin:35px;15px">Categories</h2>
<div class="row" style="margin:15px;">
  <div class="col-md-6">
    <h2><?=((isset($_GET['edit']))?'Edit':'Add ');?> Category</h2>
    <form action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'')?>" method="post">
  <div class="form-group">
    <label for="parent">Parent</label>
    <select class="form-control" name="parent" id="parent">
      <option value="0" <?=(($parent_value==0)?'selected="selected"':'')?>>Parent</option>
      <?php
      $sql5="SELECT * FROM categories WHERE parent = 0 ";
      $query5=mysqli_query($db,$sql5);
      while($form_parent=mysqli_fetch_assoc($query5)) { ?>
      <option value="<?=$form_parent['id']?>"<?=(($parent_value==$form_parent['id'])?'selected="selected"':'');?>><?=$form_parent['category']?></option>
    <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label for="Category">Category</label>
    <input type="text" class="form-control" id="Category" name="Category" value="<?=$category_value?>">
  </div>

  <input type="submit" class="btn btn-success" value="<?=((isset($_GET['edit']))?'Edit':'Add ');?> Category" name="">
</form>


  </div>

  <div class="col-md-6">
    <table class="table table-bordered" >
    <thead>
      <tr>
        <th>Parent</th>
        <th>Category</th>
        <th>Update</th>
      </tr>
    </thead>
    <tbody>

      <?php while($parent=mysqli_fetch_assoc($query)) { ?>
        <?php
        $parent_id=$parent['id'];
        $sql1="SELECT * FROM categories WHERE parent = '$parent_id' ";
        $query1=mysqli_query($db,$sql1);
        ?>
      <tr class="bg-primary">
        <td><?= $parent['category']?></td>
        <td>Parent</td>
        <td>
          <a href="categories.php?edit=<?= $parent['id']?>" class="btn btn-xs btn-primary"> <span class="glyphicon glyphicon-pencil"></span> </a>
          <a href="categories.php?delete=<?= $parent['id']?>" class="btn btn-xs btn-danger"> <span class="glyphicon glyphicon-remove-sign"></span> </a>
        </td>
      </tr>

      <?php while($child_category=mysqli_fetch_assoc($query1)) { ?>
      <tr class="bg-info">
        <td><?= $child_category['category']?></td>
        <td><?= $parent['category']?>t</td>
        <td>
          <a href="categories.php?edit=<?= $child_category['id']?>" class="btn btn-xs btn-primary"> <span class="glyphicon glyphicon-pencil"></span> </a>
          <a href="categories.php?delete=<?= $child_category['id']?>" class="btn btn-xs btn-danger"> <span class="glyphicon glyphicon-remove-sign"></span> </a>
        </td>
      </tr>
  <?php } ?>
<?php } ?>



    </tbody>
  </table>

  </div>
</div>
 <?php include 'includes/footer.php';?>
