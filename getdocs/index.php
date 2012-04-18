<?php # Script "index.php"

session_start();

	$page_title = 'Welcome to NITC Online Store!';
	include('includes/header.html');
?>

<h1>Welcome to the NITC Online Store!</h1>
		
	<div id="maintxt">
		<a href="http://studiobert.com/index.php" target="_blank"><img src="includes/images/1.png" alt="NITC CoopStore!" width="130" height="196.5" /></a>
				
		<p><b>National Institute of Technology, Calicut now has it very own online store!</b></p>
		<p>The Online Store lets you shop for merchandise such as Tshirts, Mugs, Posters, Keychains from the comforts of your home. After you place an order, we deliver it straight at your doorstep!</p>
		<p>You can check out a list of our products in the <b><a href="catalog.php">Product Catalog</a></b>.</p>
		<p>In order to avail our shopping facilities, visitors must first register by going to <b><a href="register.php">Sign Up</a></b>. <br />Then <b><a href="login.php">Login</a></b> to avail the variety of products that our store has to offer!</p><br />
	<div id="imgtitle"><b>&trade;</b><a href="http://studiobert.com/index.php" target="_blank"><i>Studio Bert Forma</i></a></div>
	</div>
	<div id="lo"><img src = "includes/images/nitc1.png" alt="National Institute Of Technology"  width="392.94" height="52.94"  /></div>


<?php 

	include('includes/footer.html');
?>


