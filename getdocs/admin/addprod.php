<?php # Script "addprod.php"

session_start();
if(!isset($_SESSION['userid']) || $_SESSION['admin']!=1)
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../index.php';
        header('Location: ' . $home_url);
	exit();
}
	
	$page_title = 'Add A Product';
	include('includes/header.html');

	$errors = array();
	$ctr=0;
	
	if(isset($_GET['check']))
	{
		if($_GET['check'])
			echo '<div id="notify"><b>&#x2714; Product has been Added!<b></div>';
		else 
			echo '<h1>System Error</h1><p class="error">Product could not be added due to a system error!</p><br /><br />';
	}

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
			require_once('../../connect.php');
			
			$query = "INSERT INTO product (prodname,price,description,qty,date) VALUES ('{$pn}',$pr,'$desc',$qty,NOW())"; 
			$r = pg_query($dbc,$query);
			pg_close($dbc);
			
			$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/addprod.php?check='.$r;
	        	header('Location: ' . $home_url);
		}
	}
?>	

<h1>Add A Product</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

<p><label for="pname">Product Name</label><input type="text" name="pname" id="pname" size="25" value="<?php if(isset($_POST['pname']) && $ctr!=3) echo $_POST['pname']; ?>" />
<?php if(isset($_POST['pname']) && empty($_POST['pname']))
	echo '<div class="errormsg" id="errormsg_0_pname">Required field cannot be left blank. </div>'; ?> </p>

<p><label for="price">Price (in Rs.)</label><input type="text" name="price" id="price" size="10" value="<?php if(isset($_POST['price']) && empty($errors['price']) && $ctr!=3) echo $_POST['price']; ?>" />
<?php if(isset($_POST['price']) && empty($_POST['price'])) 
		echo '<div class="errormsg" id="errormsg_0_price">Required field cannot be left blank. </div>';
      else if(!empty($errors['price'])) echo '<div class="errormsg" id="errormsg_0_price">' . $errors['price'] . '</div>';   
?> </p>

<p><label for="qty">Quantity</label><input type="text" name="qty" id="qty" size="6" value="<?php if(isset($_POST['qty']) && empty($errors['qty']) && $ctr!=3) echo $_POST['qty']; ?>" />
<?php if(isset($_POST['qty']) && !empty($errors['qty'])) 
		echo '<div class="errormsg" id="errormsg_0_qty">' . $errors['qty'] . '</div>';   
?> </p>

<br />
<p><label for="desc">Description</label><textarea name="desc" id="desc" rows="3" cols="40"></textarea></p>

<div class="submit"><input type="submit" name="submit" value="Add" /></div>
<input type="hidden" name="submitted" value="1" />

</form>		
<?php 
	include('../includes/footer.html');
?>


