<?php # Script "update.php"

session_start();
if(!isset($_SESSION['userid']) || $_SESSION['admin']!=1)
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../index.php';
        header('Location: ' . $home_url);
	exit();
}

if(!isset($_POST['submitted']))
{
	if(isset($_GET['prodid']) && is_numeric($_GET['prodid']))
		$_SESSION['pid'] = (int) $_GET['prodid'];
}

$pid = $_SESSION['pid'];

require_once('../../connect.php');
$qry = "SELECT * FROM product WHERE prodid = $pid";
$res = pg_query($dbc,$qry);
	
if($res)
	$rw = pg_fetch_array($res);
else
	echo 'Error!!';
	
$page_title = 'Edit '.$rw['prodname'];
include('includes/header.html');

$errors = array();
$ctr=0;
		
if(isset($_POST['submitted']))
{
	if(empty($_POST['desc']))
			$desc = 'None';
		else
		$desc = pg_escape_string(trim($_POST['desc']));
		
	if(!empty($_POST['pname']))
	{$pn = pg_escape_string(trim($_POST['pname'])); $ctr++;}

	if(!empty($_POST['price']) && is_numeric($_POST['price']))
	{
		$pr = trim($_POST['price']);
			
		if($pr>0 && $pr<100000)
			$ctr++;
		else
			$errors['price'] = 'Only prices between 0 and 100000 are allowed.';
	} 

	if(empty($_POST['qty']))
			{$qty = 1; $ctr++;}
		
		else if(!ctype_digit($_POST['qty']))
			$errors['qty'] = 'Quantity must be a positive integer.';
		else 
			{$qty = trim($_POST['qty']); $ctr++;}
			
		
	if($ctr==3)
	{
		$query = "UPDATE product SET (prodname,price,description,qty,date) = ('{$pn}',$pr,'{$desc}',$qty,NOW()) WHERE prodid = $pid"; 
		$r = pg_query($dbc,$query);
			
		if($r)
			{echo '<h1>Thank You!</h1><p><b>&#x2714; \''.$rw['prodname'].'\' Product Information has been UPDATED!</b></p><p><a href="view_rem_prod.php">Back to Products Page</a></p>"';}
		else
			 {echo '<h1>System Error</h1><p class="error"><b>&#x2718;</b> You could not be registered due to a system error. We apologize for any inconvenience.</p>';}
					
		pg_close($dbc);
		include('../includes/footer.html');
		exit();
	}
}
?>	

<h1>Edit Product - <?php echo '\''.$rw['prodname'].'\''; ?></h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

<p><label for="pname">Product Name</label><input type="text" name="pname" id="pname" size="25" value="<?php if(isset($_POST['pname']) && $ctr!=3) echo $_POST['pname']; else echo $rw['prodname'];?>" />
<?php if(isset($_POST['pname']) && empty($_POST['pname']))
	echo '<div class="errormsg" id="errormsg_0_pname">Required field cannot be left blank. </div>'; ?> </p>

<p><label for="price">Price (in Rs.)</label><input type="text" name="price" id="price" size="10" value="<?php if(isset($_POST['price']) && empty($errors['price']) && $ctr!=3) echo $_POST['price']; else echo $rw['price']; ?>" />
<?php if(isset($_POST['price']) && empty($_POST['price'])) 
		echo '<div class="errormsg" id="errormsg_0_price">Required field cannot be left blank. </div>';
      else if(!empty($errors['price'])) echo '<div class="errormsg" id="errormsg_0_price">' . $errors['price'] . '</div>';   
?> </p>

<p><label for="qty">Quantity</label><input type="text" name="qty" id="qty" size="6" value="<?php if(isset($_POST['qty']) && empty($errors['qty']) && $ctr!=3) echo $_POST['qty']; else echo $rw['qty'];?>" />
<?php if(isset($_POST['qty']) && !empty($errors['qty'])) 
		echo '<div class="errormsg" id="errormsg_0_qty">' . $errors['qty'] . '</div>';   
?> </p>

<br />
<p><label for="desc">Description</label><textarea name="desc" id="desc" rows="3" cols="40"><?php if(isset($_POST['qty']) && $ctr!=3) echo $_POST['desc']; else echo $rw['description']; ?></textarea></p>

<div class="submit"><input type="submit" name="submit" value="Update" /></div>
<input type="hidden" name="submitted" value="1" />

</form>		

<?php 
	include('../includes/footer.html');
?>
