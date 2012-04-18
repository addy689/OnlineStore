<?php # Script "view_mod_prod.php"

session_start();
if(!isset($_SESSION['userid']) || $_SESSION['admin']!=1)
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../index.php';
        header('Location: ' . $home_url);
	exit();
}

$page_title = 'View / Modify Users';
include('includes/header.html');

require_once('../../connect.php');

//Assigns default as ORDER BY prodname
$ch = 'prodname'; 

$chk1 = '';
$chk2 = '';
$chk3 = '';

//Sorting display
if(isset($_POST['submitted1']))
{	
	$sort = $_POST['sort'];
	
	if($sort == 'price')
	{
		$ch = 'price';
		$_SESSION['check'] = 2;
	}
	
	else if($sort == 'qty')
	{
		$ch = 'qty';
		$_SESSION['check'] = 3;
	}
	
	else if($sort == 'date')
	{
		$ch = 'date';
		$_SESSION['check'] = 4;
	}

	else
		$_SESSION['check'] = 1;
}

if($_SESSION['check'] == 2)
	$chk1 = ' selected = "selected" ';
else if($_SESSION['check'] == 3)
	$chk2 = ' selected = "selected" '; 
else if($_SESSION['check'] == 4)
	$chk3 = ' selected = "selected" '; 

//Handles the DELETE PRODUCT part
if(isset($_POST['submit']))
{	
	if(!empty($_POST['todelete']))
	{
		$cnt = 0;
		
		foreach ($_POST['todelete'] as $del)
		{	
			$q1 = "DELETE FROM product WHERE prodid = $del";
			pg_query($dbc,$q1);
			$cnt++;
		}
		echo '<div id="notify"><b>&#x2714; '. $cnt.' Product(s) REMOVED!</b></div>';
	}
}

$q = "SELECT * FROM product order by $ch";
$r = pg_query($dbc,$q);

		
echo '<h1>Modify Inventory</h1>';
echo '<br />';

if($r)
{

?>
<div id="upd">
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<table align="center" cellspacing="8" cellpadding="2" width="100%">
	
	<tr>
		<th>Delete Product<br />(Tick &#x2714;)</th>		
		<th align="left"><u>PRODUCT</u></b></th>
		<th align="left"><u>DESCRIPTION</u></th>		
		<th align="left"><u>PRICE</u></th>
		<th align="center"><u>QUANTITY</u></th>
		<th align="center"><u>DATE ADDED</u></th>
		<th align="left">Edit Product Details<br />(Click the link)</th>
		
	</tr>
	
<?php
	$ctr=1;
	while($row = pg_fetch_array($r))
	{
		//Display products along with the remove checkbox   	
		echo '<tr><td align="center"><input type="checkbox" value="' . $row['prodid'] . '" name="todelete[]" /></td>'.
			'<td align="left"><b>' . $row['prodname'] . '</b></td>'.
			'<td align="left">' . $row['description'] . '</td>'.			
			'<td align="left">' . $row['price'] . '</td>'.
			'<td align="center">' . $row['qty'] . '</td>'.
			'<td align="center">' . $row['date'] . '</td>'.
			'<td align="left"><a href="editprod.php?prodid=' . $row['prodid'] . '">Edit \''.$row['prodname'].'\'</a></td></tr>';
		$ctr++;
	}
	pg_close($dbc);
?>
	<tr valign="center">
	<td rowspan="3" align="center"><input type="submit" name="submit" value="Delete Products" /></td>

	<?php for($i=0;$i<4;$i++)
		echo '<td rowspan="3">&nbsp;</td>';
	?>	
	</tr>
	<?php for($i=0;$i<3;$i++)
		echo '<tr><td colspan="6">&nbsp;</td></tr>';
	?>
	
	<input type="hidden" name="submitted" value="1" />
	</table>
	</form></div>
<?php
}

else echo '<p class="error"><b>&#x2718; The Products listing could not be retrieved.</b></p>';

?>

<div id="psort">
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table align="center" cellspacing="5" cellpadding="3" width="21%">
		<tr>
		<td><select name="sort">
			<option value="prod">by Product</option>
			<option value="price"<?php echo $chk1; ?>>by Price</option>
			<option value="qty"<?php echo $chk2; ?>>by Quantity</option>
			<option value="date"<?php echo $chk3; ?>>by Date Added</option>
			</select></td>
		<td><input type="submit" name="submit2" value="Sort" /></td>
		</tr>
		<input type="hidden" name="submitted1" value="1" />
		</table>
	</form>
	</div>
<?php
include('../includes/footer.html');
?>
