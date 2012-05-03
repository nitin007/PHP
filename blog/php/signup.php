<?php
include_once('common.php');
include_once('dbconn.php');
require_once 'DB.php';
?>
<br>
<div class="content">
<span class="signin">Sign Up</span><br><br>

<?php

function dberror($result)
{
	if (DB::isError($result))
	{
		$errorMessage = $result->getMessage();
		die ($errorMessage);
	}
}


$username = $_POST['usrname'];

$thispage = $_SERVER['PHP_SELF'];
if($_POST['submit'] == 'Submit')
{
	if(!$_POST['usrname'])
	{
		$signup .= '<span class="err">Name can\'t be empty<br></span>';
	}

	if($_POST['pass'])
	{
		if(strlen($_POST['pass']) < 8)
		{
			$signup .= '<span class="err">password must be of atleast 8 characters.<br></span>';
		}
		elseif($_POST['pass']!=$_POST['cpass'])
		{
			$signup .= '<span class=err>passwords didn\'t match</span>';
		
		}
		else
		{
			$flag = 1;
		}
	}
	else
	{
		$signup .= '<span class="err">password fields can\'t be empty </span>';
	}
}



$signup .= <<<EOF
<form method="post" action="$thispage">
<table cellpadding="3">
<tr>
<td class=form_text>Username</td>
<td><input type="text" name="usrname" value = "$username" size="50"></td>
</tr>
<tr>
<td class=form_text>Password</td>
<td><input type="password" name="pass" size="50"></td>
</tr>						
<tr>
<td class=form_text>Confirm</td>
<td><input type="password" name="cpass" size="50"></td>
</tr>	
<tr>
<td><br><button type="submit" name="submit" value="Submit">Sign Up</button></td>
<td><br><a href="index.php" style="margin-left:130">Sign In</a></td>
<td><br><a href="visit.php" style="float:right">Visit</a></td>
</tr>
EOF;

if($_POST['usrname'] && $flag)
{
	$username = trim($_POST['usrname']);
	$password = trim($_POST['pass']);
	$query = "select username from users where username like \"%$username%\"";
	$result = $db->query($query);
	dberror($result);
	
	$rows = $result->numRows();
	if(!$rows)
	{
		$query = "insert into users(username,password) values('$username','$password')";
		$result = $db->query($query);
		dberror($result);
		$signup = 'thanx for registering with us';
	}
	else
	{
		$signup .= "<span class = err>$username already taken</span>";
	}
}


echo $signup;

?>

</table>
</form>
</div>
</div>
</body>
</html>
