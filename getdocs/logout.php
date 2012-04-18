<?php #Script - logout.php

session_start();

if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header('Location: ' . $home_url);
	exit();
}

else
{
	setcookie('post','',time()-3600);
	
	$_SESSION = array();
	
	session_destroy();

	setcookie('PHPSESSID','',time()-3600);	
}
	
$page_title = 'Logged Out!';
include('includes/header.html');

echo "<h1>Logged Out!</h1> <div id=\"logout\"><p><b>&#x2714;</b> You are now logged out!</p>";

include('includes/footer.html');

?>
