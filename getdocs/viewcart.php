<?php #Script - viewcart.php

session_start();

if(isset($_SESSION['post']))
	unset($_SESSION['post']);

if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $home_url);
	exit();
}

$page_title = 'View Your Shopping Cart';
include ('includes/header.html');

$msg = $error = '';
$flg = FALSE;
if(isset($_GET['update']))
{
	$uchk = $_GET['update'];
	if($uchk == 0)
		$msg = '<p><b>&#x2714; The product has been ADDED to your shopping cart!</b></p>';
	else if($uchk ==1)
		$msg = '<p><b>&#x2714; Product already present in cart. Quantity UPDATED by 1!</b></p>';
}

if(isset($_GET['check']))
{
	if($_GET['check']==1)
		$error = '<p><b>&#x2718; Entered quantity must be a positive integer!</b></p>';
	else if($_GET['check']==2)
		$error = '<p><b>&#x2718; Entered quantity EXCEEDS allowed shopping limit!</b></p>';
	else if($_GET['check']==3)
		$error = '<p><b>&#x2718; Sorry but the entered quantity EXCEEDS our Stock!</b></p>';
}

require_once('../connect.php');	
if(isset($_POST['submitted']))
{
	//change quantities
	foreach($_POST['qty'] as $k => $v)
	{
		if(!is_numeric($v))
		{
			$err = 1;			
			continue;
		}

		$pid = (int) $k;
		$qty = (int) $v;
	
		$qr = "SELECT * FROM product where prodid = $pid";
		$rs = pg_query($dbc,$qr);

		$rw = pg_fetch_array($rs);
		
		if($qty>60)
		{
			$err = 2;
			continue;
		}
		
		else if ($qty > $rw['qty'])
		{
			$err = 3;
			continue;			
		}
	
		else if ($qty==0) // Delete.
			unset ($_SESSION['cart'][$pid]);
			
		else if ( $qty > 0 ) // Change quantity.
			$_SESSION['cart'][$pid]['quantity'] = $qty;
	}
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/viewcart.php?check='.$err;
	header('Location: ' . $home_url);
}	
echo '<div id="notify">'.$msg.'</div>';
if(!empty($error))
	echo '<div id="notify1">'.$error.'</div>';

echo '<h1>Your Shopping Cart &nbsp;<img src="includes/images/2.png" /></h1>';
echo '<br />';

if(!empty($_SESSION['cart']))
{	
	$q = "SELECT * FROM product WHERE prodid IN (";

	foreach($_SESSION['cart'] as $pid => $value)
	{
		$q .= $pid.',';
	}
	
	$q = substr($q,0,-1).') ORDER BY prodname';
	$r = pg_query($dbc,$q);
	
?>	
	<div id="vcart">
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<table border="0" width="90%" cellspacing="5" cellpadding="3" align="center">
	
	<tr>
		<th width="12%">S. No.</th>
		<th align="left" width="19%"><u>PRODUCT</u></th>
		<th align="left" width="19%"><u>DESCRIPTION</u></th>
		<th align="right" width="14%"><u>PRICE</u></th>		
		<th align="center" width="14%"><u>QUANTITY</u></th>
		<th align="right" width="14%""><u>TOTAL PRICE</u></th>
	</tr>
	<tr><td colspan="6">&nbsp;</td></tr>
	
<?php
	$total=0;
	$ctr=1;
	while($row = pg_fetch_array($r))
	{	
		$subtotal = $_SESSION['cart'][$row['prodid']]['quantity'] * $_SESSION['cart'][$row['prodid']]['price']; 
		$total += $subtotal;
		
		echo '<tr><td align="center">'.$ctr.'.</td>'.
			'<td align="left"><b>' . $row['prodname'] . '</b></td>'.
			'<td align="left">' . $row['description'] . '</td>'.
			'<td align="right">' . $_SESSION['cart'][$row['prodid']]['price']. '</td>'.
			'<td align="center"> <input type="text" size="3" name="qty['.$row['prodid'].']" value="'.$_SESSION['cart'][$row['prodid']]['quantity'].'" /></td>'.
			'<td align="right">Rs. '.number_format ($subtotal, 2) . '</td></tr>';
		$ctr++;
	}
	
	for($i=0;$i<1;$i++)
		echo '<tr><td colspan="6">&nbsp;</td></tr>';

	echo '<tr><td colspan="5"align="right"><b>Total:</b></td>'.
	'<td align="right">Rs. ' . number_format($total, 2) . ' </td></tr></table>'.
	'<div align="center" class="submit2"><input type="submit" name="submit" value="Update My Cart" /></div>'.
	'<input type="hidden" name="submitted" value="1" />'.
	'</form><p align="center"><b>Enter a quantity of 0 to remove an item. Please note that the maximum quantity allowed per item is 60.</b><br /><br /><div id="left1"><a href="catalog.php"><b>&#60;&#60; BACK TO SHOPPING</b></a></div><div id="right1"><a href="checkout.php?checkin=1"><b>Done shopping? CHECKOUT &#62;&#62;</b></a></div></p></div><br />';

	$_SESSION['tot'] = $total;
} 
		
else
{
		echo '<div class="bitch"><p align="center"><b>Your cart is currently EMPTY!</b></p></div>';
}
pg_close($dbc);
include ('includes/footer.html');
?>


		
