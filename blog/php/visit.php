<?php
include("common.php");
include_once("dbconn.php");
require_once "DB.php";


session_start();

function dberror($result)
{
	if (DB::isError($result))
	{
		$errorMessage = $result->getMessage();
		die ($errorMessage);
	}
}


$thispage = $_SERVER['PHP_SELF'];
$user = $_SESSION['user'];


if($_SESSION['sign_in'])
{
	$navlist =<<< navlist
	<span id=user>Hi&nbsp$user</span>&nbsp&nbsp&nbsp&nbsp
	<span><a href=manage.php>Manage</a></span>&nbsp&nbsp&nbsp&nbsp
	<span><a href=index.php>Signout</a></span>&nbsp&nbsp&nbsp&nbsp
	<form method=get action=$thispage id=visit>
		<span id=all><a href=#>All Articles</a></span>&nbsp&nbsp&nbsp&nbsp
		<span id=my><a href=#>My Articles</a></span>
	</form>
navlist;
echo $navlist;

	$query1 = "select id from users where username='$user'";
	$result = $db->query($query1);
	dberror($result);
	
	$row = $result->fetchRow();
	$id = $row[0];
	if($_GET['default']=='all')
	{
		$query = "select * from articles join categories on categories.id=category_id where articles.status='active' and categories.status='active'";
		$_GET['default']?$thispage=$thispage.'?default='.$_GET['default']:$thispage;
	}
	elseif($_GET['my']=='mine')
	{
		$query = "select title, description, posted_on, articles.last_modified from articles join categories on categories.id=category_id join users on articles.posted_by=users.id where articles.status='active' and categories.status='active' and posted_by=$id";
		$thispage=$thispage.'?my='.$_GET['my'];
	}
	else
	{
		$query = "select * from articles join categories on categories.id=category_id where articles.status='active' and categories.status='active'";
	}
}

else
{
	echo ' <a href=index.php>Signin</a>&nbsp&nbsp&nbsp&nbsp';
	echo ' <a href=signup.php>Signup</a>';
	$query = "select * from articles join categories on categories.id=category_id where articles.status='active' and categories.status='active'";
}
$result = $db->query($query);
$num_of_rows = $result->numRows();

dberror($num_of_rows);


if($_POST['next'])
{
	if($_SESSION['current']+5<=$num_of_rows)
	{
		$i = $_SESSION['current']+3;
		$j=3;
		$_SESSION['current'] = $i;
	}
	else
	{
		$i=$_SESSION['current']+3;
		$j=$num_of_rows%3;
		$_SESSION['current']=$i;
	}
}

elseif($_POST['prev'])
{
	if($_SESSION['current']-3>=0)
	{
		$i = $_SESSION['current']-3;
		$j=3;
		$_SESSION['current'] = $i;
	}
}
else
{
	$i=0;
	$j=3;
	$_SESSION['current'] = $i;
}



if($_GET['default'])
{
	$query = "select title, description, posted_on, articles.last_modified from articles join categories on categories.id=category_id where articles.status='active' and categories.status='active' limit $i,$j";
}
else
{
	$query = "select title, description, posted_on, articles.last_modified from articles join categories on categories.id=category_id  join users on posted_by=users.id where articles.status='active' and categories.status='active' and posted_by=$id limit $i,$j";
}


$result = $db->query($query);
dberror($result);

while ($row = $result->fetchRow())
{
	$str .= '<div class=wrap><div class="title"><div><center>'.$row[0].'</center></div></div>';
   	$str .=	'<div class="description">'.$row[1].'</div><br>';
   	$str .= '<div class="post">Posted On: '.$row[2].'</div>';
   	
   	if($_SESSION['flag']=='mine')
   	{
   		$str .= '<div class="modify">Last Modified: '.$row[3].'</div>';
   		$str .= '<div class="modify">Posted By: '.$user.'</div></div><hr><br>';
   	}
   	else
   	{
   		$str .= '<div class="modify">Last Modified: '.$row[3].'</div></div><hr><br>';
   	}
}  

$str1 =<<< str1
<div class="articles">
<div class="articles_header">
<center>Articles</center>
</div>
<div class="blog_content">
<div class="current">
$str
str1;
echo $str1;



$str2 =<<< str2
<form method=post action=$thispage>
<table>
<tr><td><input type=submit name=prev value=PREV></td>
<td><input type=submit name=next value=NEXT></td></tr>
</table>
</form>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
str2;
echo $str2;

?>

<script type="text/javascript">
	var n = <?php echo $num_of_rows; ?>;
	var i = <?php echo $i; ?>;
	if(n-i<=3)
	{
		$("input[name=next]").attr('disabled','disabled');
	}
	else if(i==0)
	{
		$("input[name=prev]").attr('disabled','disabled');
	}
</script>
