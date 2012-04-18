<?php #Script "login.php"

session_start();

// If the user is logged in, redirect him back to the logged in page
if (isset($_SESSION['userid']))
{
	if($_SESSION['admin']!=1)
		$temp = '/loggedin.php';
	else 
		$temp = '/admin/loggedin.php';

	$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $temp;
        header('Location: ' . $home_url);
	exit();
}


$page_title='Login';
include('includes/header.html');
	
$flag = FALSE;
$form = FALSE;
$errors = array();
	
if(isset($_POST['submitted']))
	{
		if(!isset($_COOKIE['check']))
		{
			echo "<h1>Turn Cookies On</h1><p>Your Browser's Cookie Functionality is turned off. Please turn it on.</p>";
			exit();
		}
		
		if(!empty($_POST['un']))
		{
			require_once('../connect.php');
			
			$un = pg_escape_string(strip_tags(trim($_POST['un']))); 
			$q = "SELECT * FROM users WHERE email_id LIKE '{$un}'";
			$r = pg_query($dbc,$q);
					
			if(pg_num_rows($r)==1)
			{	
				$row = pg_fetch_array($r);
				if(!empty($_POST['pass']))
				{
					$pass = md5(trim($_POST['pass']));
					
					if($pass==$row['password'])
					{
						$name = $row['fname'] . ' ' . $row['lname'];
						
						if($row['perm']=='admin')
						{
							$flag = TRUE;
							$form = TRUE;
							if(isset($_POST['login']))
							{
								if($_POST['login']=='admin')
								{
								 	$_SESSION['admin'] = 1;
									$temp = '/admin/loggedin.php';
								}
								else $temp = '/loggedin.php';
								
								$_SESSION['name'] = $name;
								$_SESSION['userid'] = $row['userid'];
								
								pg_close($dbc);
								$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $temp;
		        					header('Location: ' . $home_url);
								exit();
							}
						}
														
						else 
						{
							$_SESSION['name'] = $name;
							$_SESSION['userid'] = $row['userid'];

							$temp = '/loggedin.php';
							pg_close($dbc);
							$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . $temp;
        						header('Location: ' . $home_url);
							exit();
						}	
					}
					else 
					{
						$form = TRUE;
						$errors['upass'] = 'The username or password you entered is incorrect.';
					}
				}
				
				else
				{
					$form = TRUE;
					$errors['pass'] = 'Enter your password';
				}
			}

			else
			{
				$form = TRUE;
				$errors['upass'] = 'The username or password you entered is incorrect.';
			}
		}
		else
		{
			$form = TRUE;
			$errors['uname'] = 'Enter your email address';
		}
	
		pg_close($dbc);
	}

	else
	{	
		setcookie('check','1');
		$form = TRUE;
	}
	
	if($form)	
	{
?>

<h1>Login</h1>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<p><label for="un">Email ID</label><input type="text" name="un" id="un" size="30" value="<?php echo $_POST['un']; ?>" />
		<?php if($_POST['submitted'] && !empty($errors['uname'])) echo '<div class="errormsg" id="errormsg_0_uname">'.$errors['uname'].'</div>';?></p>		
	
		<p><label for="pass">Password</label><input type="password" name="pass" size="30" id="pass" value="<?php if($flag==TRUE) echo $_POST['pass']; ?>" />
		<?php if($_POST['submitted'])
			{	echo '<div class="errormsg" id="errormsg_0_passwd">';
				if(!empty($errors['pass'])) echo $errors['pass']; 
				if(!empty($errors['upass'])) echo $errors['upass']; 
				echo '</div>';
			}
		?></p>
		
		<?php if($flag==TRUE)
			{	?>
		<div id="login"><p><b>Do you want to login as Administrator?</b>
		<input type="radio" id="login" name="login" value="admin" />Yes
		<input type="radio" id="login" name="login" value="no" />No</p></div>
		<?php } ?>
		
		<div class="submit"><input type="submit" name="submit" value="Login" /></div>
		<input type="hidden" name="submitted" value="1" />		
	</form> 
	<div id="reg">
			<!--<p>Forgot Password?</p>-->			
			<p><b>New to our Co-op Store?</b> <a href="register.php">Click here to Sign Up!</a></p>
	</div>
	
<?php
	}
	
	include('includes/footer.html');

?>
		
