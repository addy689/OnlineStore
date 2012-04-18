<?php # Script "edit.php"

session_start();

if(!isset($_SESSION['userid']))
{
	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login.php';
        header('Location: ' . $home_url);
	exit();
}


$page_title = 'Edit Personal Information';
include('includes/header.html');


$errors = array();
$rw = array();

require_once('../connect.php');

$adm_id = $_SESSION['userid'];
$qry = "SELECT * FROM users WHERE userid = $adm_id";
$res = pg_query($dbc,$qry);
	
if($res)
	$rw = pg_fetch_array($res);
else
	echo 'Error!!';

if(isset($_POST['submitted']))
{
	$ctr=0;		
	
	if(!empty($_POST['fname']))
	{$fn = pg_escape_string(trim($_POST['fname'])); $ctr++;}
	
	if(!empty($_POST['lname']))
	{$ln = pg_escape_string(trim($_POST['lname'])); $ctr++;}
	
	if(!empty($_POST['email']))
	{
		$e = pg_escape_string(trim($_POST['email']));
		
		if($rw['email_id']!=$e)
		{
			$query = "SELECT email_id FROM users WHERE email_id like '$e'";
			$r = pg_query($dbc,$query);

			if(pg_num_rows($r)==1)
				$errors['reg_email'] = 'The email address is already registered.'; 
		
			else if (!filter_var("$e", FILTER_VALIDATE_EMAIL))
				$errors['not_email'] = 'The email address is not valid.';

  			else
				$ctr++;
		}
		else
			$ctr++;
	}
	
	if(!ctype_digit($_POST['phone']))
		$errors['ph'] = 'Phone number must be a positive integer.';
	else
	{
		$ph = trim($_POST['phone']); 
		
		if($ph>=7777777777 && $ph<=9999999999)
			$ctr++;
		else
			$errors['ph'] = 'The phone number is not valid.';
	}
	
	if(!empty($_POST['pass']))
	{
		if(strlen($_POST['pass'])<6)
			$errors['length']='Password must have a minimum of 6 characters.';
	
		else if($_POST['pass']==$_POST['cpass'])
		{$p = md5(trim($_POST['pass'])); $ctr++;}
	}
		
	else
		{$p = $rw['password']; $ctr++;}
					
	if($ctr==5)
	{
		$query = "UPDATE users SET (fname,lname,phone_no,email_id,password) = ('{$fn}','{$ln}',$ph,'{$e}','$p') WHERE userid = $adm_id"; 
		$r = pg_query($dbc,$query);
		
		if($r)
			{echo '<h1>Thank You!</h1><p><b>&#x2714; Your personal information has been UPDATED!</b></p>';}
		else
			 {echo '<h1>System Error</h1><p class="error"><b>&#x2718;</b> You could not be registered due to a system error. We apologize for any inconvenience.</p>';}

		pg_close($dbc);
		include('includes/footer.html');
		exit();
	}
}

	
?>	


<h1>Edit Personal Information</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

<p><label for="fname">First Name</label><input type="text" name="fname" id="fname" size="30" value="<?php if(isset($_POST['fname'])) echo $_POST['fname'];  else echo $rw['fname']; ?>" />
<?php if(isset($_POST['fname']) && empty($_POST['fname']))
	echo '<div class="errormsg" id="errormsg_0_fname">Required field cannot be left blank. </div>'; ?> </p>

<p><label for="lname">Last Name</label><input type="text" name="lname" id="lname" size="30" value="<?php if(isset($_POST['lname'])) echo $_POST['lname']; else echo $rw['lname'];?>" />
<?php if(isset($_POST['lname']) && empty($_POST['lname'])) 
	echo '<div class="errormsg" id="errormsg_0_lname">Required field cannot be left blank. </div>'; ?> </p>

<p><label for="email">Email ID</label><input type="text" name="email" id="email" size="27" maxlength="27" value="<?php if(isset($_POST['email'])) echo $_POST['email']; else echo $rw['email_id'];?>" />
<?php if(isset($_POST['email']) && empty($_POST['email'])) 
		echo '<div class="errormsg" id="errormsg_0_email">Required field cannot be left blank. </div>'; 
	else if(!empty($errors['reg_email']))
		echo '<div class="errormsg" id="errormsg_0_email">' . $errors['reg_email'] . '</div>';
	else if(!empty($errors['not_email']))
		echo '<div class="errormsg" id="errormsg_0_email">' . $errors['not_email'] . '</div>'; ?> </p>

<p><label for="phone">Phone</label>+91 <input type="text" name="phone" id="phone" size="10" maxlength="10" value="<?php if(isset($_POST['phone']) && is_numeric($_POST['phone'])) echo $_POST['phone']; else echo $rw['phone_no'];?>" />
<?php if(isset($_POST['phone']) && empty($_POST['phone'])) 
		echo '<div class="errormsg" id="errormsg_0_phone">Required field cannot be left blank. </div>'; 
	else if(!empty($errors['ph']))
		echo '<div class="errormsg" id="errormsg_0_phone">' . $errors['ph'] . '</div>';?> </p>

<p><label for="pass">Password</label><input type="password" name="pass" id="pass" size="25" maxlength="24" />
<?php if(isset($_POST['pass']) && empty($errors['reg_email'])) 
	{	echo '<div class="errormsg" id="errormsg_0_passwd">';
		if(empty($_POST['pass'])) echo 'Required field cannot be left blank.';
		else if(!empty($errors['length'])) echo $errors['length'];	
		else if($_POST['pass']==$_POST['cpass']) echo 'Please re-enter your desired password.';
		else if(!empty($_POST['cpass'])) echo 'Your password did not match the confirmed password.';
		echo '</div>';
	 }?> </p>

<p><label for="cpass">Confirm Password</label><input type="password" name="cpass" id="cpass" size="25" maxlength="24" />
<?php if(isset($_POST['cpass']) && empty($errors['length']))
	{	echo '<div class="errormsg" id="errormsg_0_cpasswd">';
		if(empty($_POST['cpass'])) echo 'Required field cannot be left blank.';
		else if($_POST['pass']==$_POST['cpass']) echo 'Please re-enter your desired password.';
		echo '</div>';
	 }?> </p>

<div class="submit"><input type="submit" name="submit" value="Update" /></div>
<input type="hidden" name="submitted" value="1" />

</form>		
<?php 
	include('includes/footer.html');
?>

