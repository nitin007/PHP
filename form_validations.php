<HTML>
	<HEAD>
		<TITLE>Sample Form</TITLE>
		<style type = text/css>
			span
			{
				color:#ff0000;
				margin-left: 450px;
			}
			
			table
			{
				margin:0 auto;
				padding:0;
			}
		</style>
	</HEAD>
	<BODY>
	<CENTER><H2>Register With Us</H2></CENTER>


<?php

	$name = $_POST['name'];
	$email = $_POST['email'];

	if($_POST['submit'] == 'Submit')
	{
		if(!$_POST['name'])
		{
			$registration .= '<span>Name can\'t be empty<br></span>';
		}
	
		if(!$_POST['email'])
		{
			$registration .= '<span>email can\'t be empty<br></span>';
		}
	
		if($_POST['pass'] || $_POST['cpass'])
		{
			if(strlen($_POST['pass']) < 8)
			{
				$registration .= '<span>password must be of atleast 8 characters.<br></span>';
			}
		
			elseif($_POST['pass']!=$_POST['cpass'])
			{
				$registration .= '<span>passwords didn\'t match</span>';
			
			}
			else
			{
				$flag = 1;
			}
		}
		else
		{
			$registration .= '<span>password fields can\'t be empty </span>';
		}
	}

$thispage = $_SERVER['PHP_SELF'];
$registration .= <<<EOP
<form method="post" action="$thispage">
<TABLE WIDTH=500 VALIGN=TOP CELLPADDING=20>
<TR VALIGN=TOP>
<TD> Name: </TD>
<TD><input type="text" name="name" value = "$name"></td>
</TR>
<tr>
<td> Email: </td>
<TD><input type="text" name="email" value = "$email"></td>
</tr>
<tr>
<td> Password </td>
<TD><input type="password" name="pass"></td>
</tr>
<tr>
<td> Confirm Password </td>
<TD><input type="password" name="cpass"></td>
</tr>
<tr>
<td><input type="submit" name="submit" value = "Submit"></td>
</tr>
</TABLE>
</form>
</BODY>
</HTML>
EOP;

if($_POST['name'] && $_POST['email'] && $flag)
{
	$registration = '<h2>thanx for registring with us</h2>';
}
echo $registration;

?>

