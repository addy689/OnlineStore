<?php #Script - loggedin.php

session_start();

if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $home_url);
	exit();
}

$page_title = 'Logged In!';

include('includes/header.html');
echo '<h1>Logged In!</h1><div id="vcrt"><p><b>&#x2714;</b> You are now logged in, <b>'.strtoupper($_SESSION['name']).'</b></p>'.
	'<br /><p>You can begin shopping by going to the <b><a href="catalog.php">Product Catalog</a></b> and adding items to your shopping cart.</p>'.
	'<p>You can <b> <a href="viewcart.php">View Shopping Cart</a></b> at any time. Once you\'re finished purchasing, you can avail the <b>Check Out</b> option provided in the Cart!</p>'.
	'<br /><p>To view the details of all purchases you have made in the past, go to <b> <a href=#>Order History</a></b>.</p></div>'.
	'<br /><div id="logged"><p><b>Happy Shopping!!</b></p>';

include('includes/footer.html');

?>
