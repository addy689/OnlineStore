<?php # Script "register.php"

$page_title = 'Sign Up';
include('includes/header.html');


$errors = array();

if(isset($_GET['check']))
{
	if($_GET['check'])
		echo '<h1>Thank You!</h1><div class="bitch"><p><b>&#x2713;</b> You are now registered.</p></div>';
	else
		echo '<h1>System Error</h1><div class="bitch"><p class="error"><b>&#x2718;</b> You could not be registered due to a system error. We apologize for any inconvenience.</p></div>';

	include('includes/footer.html');
	exit();
}

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

		require_once('../connect.php');
		$query = "SELECT email_id FROM users WHERE email_id like '$e'";
		$r = pg_query($dbc,$query);

		if(pg_num_rows($r)==1)
			$errors['reg_email'] = 'The email address is already registered.'; 
		
		else if (!filter_var("$e", FILTER_VALIDATE_EMAIL))
			$errors['not_email'] = 'The email address is not valid.';

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
		{$p = trim($_POST['pass']); $ctr++;}
	}
				
	if($ctr==5)
	{
		require_once('../connect.php');
		$query = "INSERT INTO users (fname,lname,phone_no,email_id,password,regdate,perm) VALUES ('{$fn}','{$ln}',$ph,'{$e}',md5('$p'),NOW(),'cust')";
		$r = pg_query($dbc,$query);

		pg_close($dbc);
		
		$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/register.php?check='.$r;
	        header('Location: ' . $home_url);
		exit();
	}
}

?>	

<h1>Sign Up</h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

<p><label for="fname">First Name</label><input type="text" name="fname" id="fname" size="30" value="<?php if(isset($_POST['fname'])) echo $_POST['fname']; ?>" />
<?php if(isset($_POST['fname']) && empty($_POST['fname']))
	echo '<div class="errormsg" id="errormsg_0_fname">Required field cannot be left blank. </div>'; ?> </p>

<p><label for="lname">Last Name</label><input type="text" name="lname" id="lname" size="30" value="<?php if(isset($_POST['lname'])) echo $_POST['lname']; ?>" />
<?php if(isset($_POST['lname']) && empty($_POST['lname'])) 
	echo '<div class="errormsg" id="errormsg_0_lname">Required field cannot be left blank. </div>'; ?> </p>

<p><label for="email">Email ID</label><input type="text" name="email" id="email" size="27" maxlength="27" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" />
<?php if(isset($_POST['email']) && empty($_POST['email'])) 
		echo '<div class="errormsg" id="errormsg_0_email">Required field cannot be left blank. </div>'; 
	else if(!empty($errors['reg_email']))
		echo '<div class="errormsg" id="errormsg_0_email">' . $errors['reg_email'] . '</div>';
	else if(!empty($errors['not_email']))
		echo '<div class="errormsg" id="errormsg_0_email">' . $errors['not_email'] . '</div>'; ?> </p>

<p><label for="phone">Phone</label>+91 <input type="text" name="phone" id="phone" size="10" maxlength="10" value="<?php if(isset($_POST['phone']) && is_numeric($_POST['phone'])) echo $_POST['phone']; ?>" />
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
<?php if(isset($_POST['cpass']) && empty($errors['length']) && empty($errors['reg_email']))
	{	echo '<div class="errormsg" id="errormsg_0_cpasswd">';
		if(empty($_POST['cpass'])) echo 'Required field cannot be left blank.';
		else if($_POST['pass']==$_POST['cpass']) echo 'Please re-enter your desired password.';
		echo '</div>';
	 }?> </p>

<div class="submit"><input type="submit" name="submit" value="Register" /></div>
<input type="hidden" name="submitted" value="1" />

</form>		
<?php 
	include('includes/footer.html');
?>

