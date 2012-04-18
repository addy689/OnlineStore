<?php #Script - loggedin.php

session_start();

if(!isset($_SESSION['userid']) || $_SESSION['admin']!=1)
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../index.php';
        header('Location: ' . $home_url);
	exit();
}

$page_title = 'Logged In!';

include('includes/header.html');
echo '<h1>Welcome Administrator!</h1><div id="loggd"><p>You are now logged in, <b>'.strtoupper($_SESSION['name']).'</b></p></div>';


include('../includes/footer.html');

?>
