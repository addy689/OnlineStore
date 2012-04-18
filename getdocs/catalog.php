<?php # Script "catalog.php"

session_start();

if(!isset($_SESSION['userid']))
	$_SESSION['check'] =1;


$page_title = 'Product Catalog';
include('includes/header.html');

require_once('../connect.php');

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
	
	else
		$_SESSION['check'] = 1;
}

if($_SESSION['check'] == 2)
	$chk1 = ' selected = "selected" ';

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

		
echo '<h1>Product Catalog</h1>';
echo '<br />';

if($r)
{

?>
<div id="upd">
	<table align="center" cellspacing="13" cellpadding="6" width="90%">
	
	<tr>
		<th align="center">Add to Cart<br />(Click on image)</th>	
		<th align="left" colspan="2"><u>PRODUCT</u></th>
		<th>&nbsp;</th>
		<th align="left" colspan="2"><u>DESCRIPTION</u></th>		
		<th>&nbsp;</th>		
		<th align="left"><u>PRICE</u></th>
	</tr>
	
<?php
	$ctr=1;
	while($row = pg_fetch_array($r))
	{
		echo '<tr>'.
			'<td align="center"><a href="addtocart.php?prodid=' . $row['prodid'] . '"><img src="includes/images/2.png" alt="Add to Cart" /></a></td>'.
			'<td align="left" colspan="2"><b>' . $row['prodname'] . '</b></td>'.
			'<td>&nbsp;</td>'.
			'<td align="left" colspan="2">' . $row['description'] . '</td>'.			
			'<td>&nbsp;</td>'.			
			'<td align="left">' . $row['price'] . '</td></tr>';
			
		$ctr++;
	}
	pg_close($dbc);
?>
	</table>
	</div>
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
			</select></td>
		<td><input type="submit" name="submit2" value="Sort" /></td>
		</tr>
		<input type="hidden" name="submitted1" value="1" />
		</table>
	</form>
</div>
<?php
include('includes/footer.html');
?>
