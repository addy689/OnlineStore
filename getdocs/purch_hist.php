<?php

session_start();

if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $home_url);
	exit();
}

if(!isset($_GET['purch']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/purch_hist.php';
        header('Location: ' . $home_url);
	exit();
}
	
$page_title = 'Order Details';
include('includes/header.html');
require_once('../connect.php');
echo '<h1>Order Details</h1>&nbsp;&nbsp;Order ID: <b>#NSU' . $_SESSION['userid'] . 'P' . $_GET['purch'] . '</b><br /><br /><br />';
?>

<div id="vcart">
	<table border="0" width="80%" cellspacing="2" cellpadding="2" align="center">
	
	<tr>
		<th align="right" width="20%"><u>S. No.</u></th>
		<th width="15%"><u>Item</u></th>
		<!--<th align="left" width="19%"><u>DESCRIPTION</u></th>-->
		<th align="right" width="15%"><u>Price</u></th>		
		<th align="center" width="10%"><u>Quantity</u></th>
		<th align="right" width="20%""><u>Total per Item</u></th>
	</tr>
	<tr><td colspan="5">&nbsp;</td></tr>
	
<?php
	$qy = "SELECT prodname,price,qnty,qnty*price AS total FROM product,madefor WHERE purch_id={$_SESSION['purchid']} AND prodid=prod_id";
	$rs = pg_query($dbc,$qy);
	
	$total = 0;
	$ctr=1;
	while($row = pg_fetch_array($rs))
	{	
		$total += $row['total'];
		echo '<tr><td align="right">' . $ctr . '.</td>'.
			'<td align="center"><b>' . $row['prodname'] . '</b></td>'.
			'<td align="right">' . $row['price'] . '</td>'.
			'<td align="center">X ' . $row['qnty'] . '</td>'.
			'<td align="right">Rs. ' . number_format ($row['total'], 2) . '</td></tr>';
		$ctr++;
	}
	
	echo '<tr><td colspan="5">&nbsp;</td></tr>';
	echo '<tr><td colspan="5">&nbsp;</td></tr>';
	
	echo '<tr><td colspan="5" align="right"><b>TOTAL = </b> Rs. ' . number_format($total, 2) . ' </td></tr></table><br /><br />';

include('includes/footer.html');

?>
