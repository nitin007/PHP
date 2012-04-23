<?php
//include_once ("managefunc.php");
include("common.php");
include_once("dbconn.php");
require_once "DB.php";


session_start();

if($_SESSION['sign_in'])
{
	if($_POST['save_category']=='SAVE')
	{
		//add_category
		foreach ($_POST['category_list'] as $val) 
		{
			$cat_id = $_POST['edit'];
			if($val)
			{
				$cat_id?$query="update categories set name = '$val', last_modified = CURDATE() where id=$cat_id":$query="insert into categories(name,status,added_on,last_modified) VALUES ('$val','active',CURDATE(),CURDATE())";
				$result = $db->query($query);
				if (DB::isError($result))
				{
					$errorMessage = $result->getMessage();
					die ($errorMessage);
				}
			}
		
			else
			{
				$manage_cat2 .= '<span class=err>category cannot be empty!</span>';
			}
		
		}
	}

	//delete category
	if($_POST['sure'])
	{
		$cat_id = $_POST['remove_cat'];
		$query = "update categories set status='delete' where id=$cat_id";
		$result = $db->query($query);
		if (DB::isError($result1))
		{
			$errorMessage = $result->getMessage();
			die ($errorMessage);
		}	
	}


	//show category
	$query = "select id, name from categories where status='active'";
	$result = $db->query($query);
	if (DB::isError($result))
	{
		//echo 'jfvh';
		$errorMessage = $result->getMessage();
		die ($errorMessage);
	}


	while ($row = $result->fetchRow())
	{
		$select_str .= '<option class=saved id='.$row[0].'>'.$row[1].'</option>';
	}


	if($_POST['save_article']=='SAVE')
	{
		//add_article
		$title = $_POST['title'];
		$desc = $_POST['desc'];
		$category = $_POST['category'];
		if(!trim($title)||!trim($desc)||!$category)
		{
			$manage_art2 .= '<span class=err>title,desc or category was empty</span>';	
		}
		else
		{
			//add to database
			if(!$_POST['article_id'])
			{
				//new article
				$query1 = "select id from categories where name = '$category' and status='active'";
				$category_id = $db->query($query1);
				while ($row = $category_id->fetchRow())
				{
					$test = $row[0];
					$query2 = "insert into articles(title,description,category_id,status,posted_on,last_modified) VALUES ('$title','$desc',$test,'active',CURDATE(),CURDATE())";
				}
			}
		
			else
			{
				//edit article
				$article_id = $_POST['article_id'];
				$query1 = "select id from categories where name = '$category' and status='active'";
				$category_id = $db->query($query1);
				while ($row = $category_id->fetchRow())
				{
					$test = $row[0];
					$query2 = "update articles set title = '$title', description='$desc', category_id=$test, last_modified=CURDATE() where id='$article_id'";
				}
			}
			$result = $db->query($query2);
			if (DB::isError($result))
			{
				echo $category;
				$errorMessage = $result->getMessage();
				die ($errorMessage);
			}
		
		} 
	}


	//delete article
	if($_POST['del_article']=='SURE')
	{
		//echo $_POST['article_id'];
		$article_id = $_POST['article_id'];
		$query = "update articles set status='delete' where id='$article_id'";
		$result = $db->query($query);
		if (DB::isError($result))
		{
			$errorMessage = $result->getMessage();
			die ($errorMessage);
		}
	}


	//show articles
	$query = "select title,description,name,posted_on,articles.last_modified,articles.id from articles join categories on category_id=categories.id where articles.status = 'active' and categories.status='active'";
	$result = $db->query($query);
	if (DB::isError($result))
	{
		$errorMessage = $result->getMessage();
		die ($errorMessage);
	}
	while ($row = $result->fetchRow())
	{
		$row_str .= '<tr><td class=title>'.$row[0].'</td>';
		$row_str .= '<td class=desc>'.$row[1].'</td>';
		$row_str .= '<td class=cat>'.$row[2].'</td>';
		$row_str .= '<td class=posted>'.$row[3].'</td>';
		$row_str .= '<td class=modified>'.$row[4].'</td>';
		$row_str .= '<td><input type=button name="edit" value=edit id='.$row[5].'>';
		$row_str .= '<input type=button name="delete" value=delete id='.$row[5].'></td></tr>';
	}




	$thispage = $_SERVER['PHP_SELF'];
	$manage_cat1 .= <<< manage_cat
	<div class=to_other><a href=visit.php>Visit</a></div>
	<div class=to_other><a href=signin.php>Sign out</a></div><br>
	<div class="category_wrapper">
	<form method="POST" action="$thispage" id="category_form">
	<div class="manage_categories"> Manage Categories</div>
manage_cat;
	echo $manage_cat1;

	$manage_cat2 .= <<< manage_cat
	<div class="selectbox">
	<select  style="width:120" name="category_list[]" size=4 id=test>
	$select_str
	</select>
	</div>
	<br>
	<table>
	<tr>
	<td><input type="text" name="category" /></td>
	<td><input type="button" name="add_category" value="ADD" /></td>
	</tr>
	<tr>
	<td><input type="button" name="edit_category" value="EDIT" />
	<input type="button" name="remove_category" value="REMOVE" />
	<input type="submit" name="save_category" value="SAVE" /></td>
	</tr>
	</table>
	</form>
	</div>
manage_cat;
	echo $manage_cat2;


	//$thispage = $_SERVER['PHP_SELF'];
	$manage_art1 .= <<< manage_art
	<div class="article_wrapper">
	<form method="POST" action="$thispage" id="article_form">
	<center><div class="manage_articles"> Manage Articles</div></center>
	<br>
manage_art;
	echo $manage_art1;

	$manage_art2 .= <<< manage_art
	<table class="article_table" cellpadding="0px" cellspacing="0px">
	<tr>
		<td class="title">Title</td>
		<td class="desc">Description</td>
		<td class="cat_header">Category</td>
		<td class="posted">Posted on</td>
		<td class="modified">Last Modified</td>
		<td class="act">Action</td>
	</tr>
	$row_str
	</table>
	<br>
	<input type="button" name="add_article" value="ADD" />
	</form>
	</div>
manage_art;
	echo $manage_art2;

}
else
{
	header("location:http://localhost/blog/php/signin.php");
}
?>


</div>
</div>
</body>
</html>
