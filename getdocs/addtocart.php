<?php # Script "addtocart.php"

session_start();
if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $home_url);
	exit();
}


$page_title = 'Add To Cart';
include('includes/header.html');

echo '<h1>Product Added To Cart!</h1>';
echo '<br />';

if(isset($_GET['prodid']) && is_numeric($_GET['prodid']))
{
	$pd = (int) $_GET['prodid'];

	if(isset($_SESSION['cart'][$pd]))
	{
		$_SESSION['cart'][$pd]['quantity']++;
		$ucheck = 1;
	}

	else
	{
		require_once('../connect.php');
		$q = "SELECT price FROM product WHERE prodid = $pd";
		$r = pg_query($dbc,$q);
	
		if(pg_num_rows($r)==1)
		{
			$row = pg_fetch_array($r);
		
			$price = $row['price'];
			$_SESSION['cart'][$pd] = array ('quantity' => 1, 'price' => $price);
			$ucheck = 0;		
		}
		
		else
		{
			echo 'Error!!';
		}
		
		pg_close($dbc);
	}
	
	if(isset($ucheck))
	{	
		$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/viewcart.php?update='.$ucheck;
	        header('Location: ' . $home_url);
		exit();		
	}

}

else
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/catalog.php';
        header('Location: ' . $home_url);
	exit();
}

include('includes/footer.html');
?>
