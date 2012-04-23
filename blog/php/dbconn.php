<?php
require_once 'DB.php';
$dsn = "mysql://root:1am0nly4r0mi@localhost/blog";
$db = DB::connect($dsn);
if (DB::isError($db)) 
{
	die ($db->getMessage());
}
?>
