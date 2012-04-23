<?php
include_once('common.php');
include_once('dbconn.php');
require_once 'DB.php';

session_start();
$_SESSION['sign_in']=false;
$thispage = $_SERVER['PHP_SELF'];
$signin .= <<<EOF
<br><br>
<div class="content">
<span class="signin">Sign In</span><br><br>
<form method="post" action="$thispage">
<table cellpadding="3">
<tr>
<td>Username</td>
<td><input type="text" name="usrname" size="50"></td>
</tr>
<tr>
<td>Password</td>
<td><input type="password" name="pass" size="50"></td>
</tr>						
<tr>
<td><br><button type="submit" name="submit" value="Submit">Sign In</button></td>
</tr>
<tr>
<td><br><span><a href="visit.php">Visit</a></span></td>
<td><br><span class="sign_in">Don't have a account yet? <a href="signup.php">Register Now!</a></span></td>
</tr>
EOF;


if($_POST['submit'] == 'Submit')
{
	$username = trim($_POST['usrname']);
	$password = trim($_POST['pass']);
	if(!$username||!$password)
	{
		$signin .= '<span class = err>Invalid username or password</span>';
	}
	else
	{
		$query = "select * from users where username like \"%$username%\" and password like \"%$password%\"";
		$result = $db->query($query);
		if (DB::isError($result))
		{
			$errorMessage = $result->getMessage();
			die ($errorMessage);
		}
		$rows = $result->numRows();
		if(!$rows)
		{	
			$signin .= '<span class = err>Invalid username or password</span>';
		}
		else
		{
			$_SESSION['sign_in']=true;
			$_SESSION['user']=$username;
			header("location:http://localhost/blog/php/manage.php");
		}
	}
}

echo $signin;
?>

</table>
</form>
</div>
</div>
</body>
</html>
