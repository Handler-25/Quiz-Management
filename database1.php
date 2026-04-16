<?php
session_start();
	include "db.php";
	
		$userName = $_POST['username'];
		$password = $_POST['password'];
		
		$sql = "SELECT * FROM users WHERE username = '$userName'";
		$rs=pg_query($con,$sql);
		if(pg_num_rows($rs)==1)
		{
			$user = pg_fetch_assoc($rs);
			
			$stored_password = $user['password'];
			if(password_verify($password,$stored_password)){
				
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['name'] = $user['name'];
				$_SESSION['role'] = $user['role'];
			}
			
			if(isset($_SESSION['role'])){
				echo json_encode([
				"status"=>"success",
				"role"=>$user['role']
				]);
				
			}	
			else{
				echo json_encode([
				"status"=>"failed"
				]);
			}
		}
		else{
			echo json_encode([
				"status"=>"failed"
				]);
		}
		
?>		

