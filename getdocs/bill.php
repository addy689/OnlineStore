<?php

session_start();

if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $home_url);
	exit();
}

$page_title = 'Thanks!';
include('includes/header.html');
require_once('../connect.php');
echo '<h1>Thanks For Shopping!</h1>';

if(isset($_POST['submitted']))
{
	if(isset($_SESSION['cart']))
	{
		$q = "BEGIN; INSERT INTO purchase (user_id,timestmp,total) VALUES({$_SESSION['userid']},NOW(),{$_SESSION['tot']}); SELECT * FROM purchase WHERE user_id = {$_SESSION['userid']} AND (timestmp BETWEEN NOW()-time'00:00:02' AND NOW())";
		
		$r = pg_query($dbc,$q) or die('Server Error');

		if(pg_num_rows($r)==1)
		{	
			$row = pg_fetch_array($r);
			$id = $row['purchid'];
			$q = '';

			foreach($_SESSION['cart'] as $pid=>$val)
			{
				$q .= "INSERT INTO madefor (purch_id,prod_id,qnty) VALUES ($id,$pid,{$val['quantity']});";
				$q .= "UPDATE product SET qty = qty - {$val['quantity']} WHERE prodid = $pid;";
			}
			$q .= "COMMIT";
			$res = pg_query($dbc,$q) or die('Server Error2');
			
			unset($_SESSION['cart']);
			$_SESSION['purchid'] = $row['purchid'];
			$_SESSION['post'] = 1;
		}

		else
		{	
			echo pg_num_rows($r);
			echo '<p>This page was accessed in Error!</p>';
			include('includes/footer.html');
			exit();
		}
	}
}
echo '<p>Your Order ID: <b>#NSU'.$_SESSION['userid'].'P'.$_SESSION['purchid'].'</b></p><p align="center"><b>------ ORDER DETAILS ------</b></p><br />';
?>

<div id="vcart">
	<table border="0" width="85%" cellspacing="2" cellpadding="2" align="center">
	
	<tr>
		<th align="right" width="25%"><u>S. No.</u></th>
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
	
	echo '<tr><td colspan="5" align="right"><b>TOTAL = </b> Rs. ' . number_format($total, 2) . ' </td></tr></table>';
	
	echo '<br /><p>You can now collect your order at the NITC Cooperative Store. Please note that you will be asked to quote the above Order ID at the store. </p><p><b>You must collect your order within a period of 24 hours, otherwise your order will be cancelled.</b></p></div>';

include('includes/footer.html');

?>
