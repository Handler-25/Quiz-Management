<?php
session_start();
include "../db.php";

$query = "SELECT * FROM Users ORDER BY role";
$rs = pg_query($con,$query);

if($rs && pg_num_rows($rs) > 0){
	
	echo "<h3>List of Users:</h3>";
		echo "<table border='1'><tr>
			<th>Name</th>
			<th>Username</th>
			<th>Email</th>
			<th>Role</th>
			</tr>";
		while($row = pg_fetch_assoc($rs)){
		
			$user_id = $row['user_id'];
			
			echo "<tr>
			<td>".$row['name']."</td> 
			<td>".$row['username']."</td>
			<td>".$row['email']."</td>
			<td>".$row['role']."</td>
			</tr>";
		}
		echo "</table>";
		
	}
	else{
		$_SESSION['message'] = "Error in diplaying contents!".pg_last_error($con);
	}
			
	echo "<br>
		<a href=registration.php>
		<button type=button>Go Back</button>
		</a>";
?>
