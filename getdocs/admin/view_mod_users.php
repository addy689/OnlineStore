<?php # Script "view_mod_users.php"

session_start();
if(!isset($_SESSION['userid']) || $_SESSION['admin']!=1)
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/../index.php';
        header('Location: ' . $home_url);
	exit();
}

$page_title = 'View / Modify Users';
include('includes/header.html');

require('../../connect.php');

//Assigns default as ORDER BY fname,lname
$ch = 'fname,lname'; 

$chk1 = '';
$chk2 = '';

//Sorting display by either Privilege or Reg. Date
if(isset($_POST['submitted1']))
{	
	$sort = $_POST['sort'];
	
	if($sort == 'priv')
	{
		$ch = 'perm';
		$_SESSION['check'] = 3;
	}
	
	else if($sort == 'date')
	{
		$ch = 'regdate';
		$_SESSION['check'] = 2;
	}
	
	else
		$_SESSION['check'] = 1;
}

//if(isset(
if($_SESSION['check'] == 2)
	$chk1 = ' selected = "selected" ';
else if($_SESSION['check'] == 3)
	$chk2 = ' selected = "selected" '; 

//Handles the DELETE USER part
if(isset($_POST['submit']))
{	
	if(!empty($_POST['todelete']))
	{
		$cnt = 0;
		foreach ($_POST['todelete'] as $del)
		{	
			$q1 = "DELETE FROM users WHERE userid = $del";
			pg_query($dbc,$q1);
			$cnt++;
		}
		echo '<div id="notify"><b>&#x2714; '. $cnt.' User(s) REMOVED!</b></div>';
	}
}

if(isset($_POST['submit1']))
{
	$q = "SELECT * FROM users order by $ch";
	$res = pg_query($dbc,$q);
	
	$ct = 1;
	$flg = 0;
	while($ro = pg_fetch_array($res))
	{	
		$id = $ro['userid'];
		$pm = $_POST['p'.$ct];
			
		//For printing "success" message only if permissions have been changed 
		if($pm != $ro['perm'])
			$flg++;			
			
		$q = "UPDATE users SET perm = '$pm' WHERE userid = $id AND perm NOT like '$pm'";
		$r = pg_query($dbc,$q);
		$ct++;
	}

	if($flg>0)
		echo '<div id="notify"><b>&#x2714; Privileges UPDATED!</b></div>';
}



$q = "SELECT * FROM users order by $ch";
$r = pg_query($dbc,$q);

		

echo '<h1>Modify Users</h1>';
echo '<br />';

if($r)
{

?>
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<table align="center" cellspacing="4" cellpadding="3" width="100%">
	
	<tr>
		<th>Delete User<br />(Tick &#x2713;)</th>		
		<th align="left"><u>NAME</u></b></th>
		<th align="left"><u>PHONE</u></th>
		<th align="left"><u>EMAIL ID</u></th>
		<th align="left"><u>REGIST. DATE</u></th>
		<th><u>PRIVILEGES</u><table align="center" cellspacing="5" cellpadding="3" width="100%">
					<tr align="center"><th>Admin</th>
					    <th>Cust</th>
					</tr>
					</table></th>
	</tr>
	
<?php
	$ctr=1;
	while($row = pg_fetch_array($r))
	{
		//If user is admin/cust, then tick the privileges radio button accordingly 
		$check1 = '';
		$check2 = '';
		if($row['perm']=='admin')
			$check1 = ' checked = "checked" ';			
		else if($row['perm']=='cust')
			$check2 = ' checked = "checked" ';

		//Display users along with the remove checkbox and the privilege radio buttons  	
		echo '<tr><td align="center"><input type="checkbox" value="' . $row['userid'] . '" name="todelete[]" /></td>'.
			'<td align="left">' . $row['fname']. ' ' . $row['lname'] . '</td>'.
			'<td align="left">' . $row['phone_no'] . '</td>'.
			'<td align="left">' . $row['email_id'] . '</td>'.
			'<td align="left">' . $row['regdate'] . '</td>'.
			'<td align="center"><table align="center" width="100%" cellspacing="5" cellpadding="3">'.
					'<tr align="center"><td><input type="radio" name="p' . $ctr . '" value="admin"'.$check1.'/></td>'.
					    		'<td><input type="radio" name="p' . $ctr . '" value="cust"'.$check2.'/></td></tr>'.
					'</table></td>'.
			'</tr>';
		$ctr++;
	}
	pg_close($dbc);
?>
	<tr valign="bottom">
	<td rowspan="3" colspan="3" align="left"><input type="submit" name="submit" value="Delete Users" /></td>
	<td rowspan="3" colspan="3" align="right"><input type="submit" name="submit1" value="Confirm Changes" /></td>
	</tr>
	<?php for($i=0;$i<3;$i++)
		echo '<tr><td colspan="6">&nbsp;</td></tr>';
	?>
	
	<input type="hidden" name="submitted" value="1" />
	</table>
	</form>
<?php
}

else echo '<p class="error">&#x2718; <b>The Users data could not be retrieved.</b></p>';

?>

<div id="sort">
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<table align="center" cellspacing="5" cellpadding="3" width="21%">
		<tr>
		<td><select name="sort">
			<option value="name">by Name</option>
			<option value="date"<?php echo $chk1; ?>>by Date</option>
			<option value="priv"<?php echo $chk2; ?>>by Privilege</option>
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
