<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Ecommerce/core/init.php';
$parent_ID=(int)$_POST['parent_ID'];
$selected=sanitize($_POST['selected']);
$sqlc="SELECT * FROM categories WHERE parent = '$parent_ID' ORDER BY category ";
$pqueryc=mysqli_query($db,$sqlc);
ob_start();?>
<option value=""></option>
<?php while($chil=mysqli_fetch_assoc($pqueryc)) { ?>
<option value="<?=$chil['id']?>"<?=(($selected !=$chil['id'])?'selected=':'')?>><?=$chil['category']?></option>
<?php } ?>
<?php  echo ob_get_clean();?>
