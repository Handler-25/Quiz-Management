<?php
	session_start();
	include "../db.php";
?>
<html>
<form method=post>
SET <br>
Column (whose value to be set): <input type=text name=column_value placeholder="e.g.column1 = value1, column2 = value2,...."><br>
WHERE [condition required to set specific values]: <input type=text name=where_value><br>
<input type=submit name=submit>
</form>
</html>

<?php
	$column = $_POST['column_value'];
	$where = $_POST['where_value'];
	
	if($where){
		$query = "UPDATE Users SET ".$column." WHERE ".$where;
		$rs = pg_query($con,$query);
		if($rs){
		$_SESSION['success'] = "Updated content successfully!";
		echo "<script>alert('Updated')</script>";
		}
		else{
			$_SESSION['message'] = "Error in updating contents!".pg_last_error($con);
			echo "<script>alert('Not Updated')</script>";
		}
	}	
	echo "<br>
		<a href=registration.php>
		<button type=button>Go Back</button>
		</a>";
?>
