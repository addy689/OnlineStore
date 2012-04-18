<?php #Script - orderhist.php

session_start();

if(isset($_SESSION['post']))
	unset($_SESSION['post']);

if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $home_url);
	exit();
}

$page_title = 'Order History';
include ('includes/header.html');

require_once('../connect.php');	
$q = "SELECT * FROM purchase WHERE user_id={$_SESSION['userid']} ORDER BY timestmp DESC";
$r = pg_query($dbc,$q);

echo '<h1>Order History</h1>';

if(pg_num_rows($r)>0)
{
	echo '<i>(Click on an Order ID to view its details)</i><br /><br />';
?>

<div id="upd">
	<table align="center" cellspacing="8" cellpadding="4" width="60%">
	<tr>
		<th width="30%"><u>ORDER DATE & TIME</u></th>
		<th align="left" width="30%"><u>ORDER ID</u></th>		
	</tr>
	<tr></tr>

<?php
	while($row = pg_fetch_array($r))
	{
		echo '<tr>'.
			'<td align="center">' . $row['timestmp'] . '</td>'.
			'<td align="left"><a href="purch_hist.php?purch=' . $row['purchid'] . '">#NSU' . $_SESSION['userid'] . 'P' . $row['purchid'] . '</a></td></tr>';
	}
	pg_close($dbc);
?>	
	</table>
	</div>
<?php
}

else
{
		echo '<div class="bitch"><p align="center"><b>You do not have an order history!</b></p></div>';
}

include('includes/footer.html');
?>
