<?php

session_start();
if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $home_url);
	exit();
}

if(isset($_SESSION['post']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/bill.php';
        header('Location: ' . $home_url);
	exit();
}
	
else if(!isset($_GET['checkin']) || empty($_SESSION['cart']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/viewcart.php';
        header('Location: ' . $home_url);
	exit();	
}

if($_GET['checkin'] == 1)
{
	$page_title = 'Checkout';	
	include('includes/header.html');
		
?>
		<h1>Confirm Checkout</h1>'
		<div class="bitch">
			
			<div id="ryt"><img src='includes/images/ok.png' height="40" width="40" alt="Go!" /><p><b>To purchase the products you have selected in the Shopping Cart, click below:</b></p><br /><br />
				<form action="bill.php" method="POST">
					<input type="submit" name="submit" value="PURCHASE >>" />
					<input type="hidden" name="submitted" value="1" />
				</form>
			</div>
			 
			<div id="lft"><img src='includes/images/x.png' height="40" width="40" alt="No Go!" /><p><b>If you wish to make any changes in the Shopping Cart, click below:</b></p><br /><br />
				<a href="viewcart.php"><b>&#60;&#60; Back To Shopping Cart</b></a>
			</div>
		</div>
		<br />
		<div id="warn"></div><div id="warntxt"><p><b><u>IMPORTANT</u> :-</b><em> After clicking <b>Purchase</b>, your order will be made final, and no further modifications will be allowed.</em></p></div>		

		
<?php
}
	include('includes/footer.html');
?>
