<?php

require_once 'DB.php';
include_once('dbconn.php');


function add_category()
{
	$category = trim($_GET['category']);
	$query = "insert into categories(name) values('$category')";
	
	$result = $db->query($query);
	if (DB::isError($result))
	{
		$errorMessage = $result->getMessage();
		die ($errorMessage);
	}
	
}

function show_category()
{
	$query = "select name from categories";
	$result = $db->query($query);
	if (DB::isError($result))
	{
		$errorMessage = $result->getMessage();
		die ($errorMessage);
	}
	
	$i=0;
	while ($row = $result->fetchRow())
	{
		echo '<option>'.$row[0].'</option>';
		$i++;
	}
}
?>
