<?php
session_start();

$con = pg_connect("host=localhost dbname=project user=postgres password=postgres123")or die("Could not Connect");
$status = pg_connection_status($con);
if ($status === PGSQL_CONNECTION_OK ){
	
	$name=$_POST['Name'] ?? '';
	$username = $_POST['userName'] ?? '';
	$email = $_POST['email'] ?? '';
	$password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
	$role = $_POST['inputRole'] ?? '';
	
		switch($_POST['submit']){

		case "add":
		$check = pg_query_params($con,
		"SELECT * FROM users WHERE username=$1 OR email=$2",
		array($username,$email));

		if(pg_num_rows($check) > 0){
			$_SESSION['message'] = "User already exists!";
			header("Location: registration.php");
			exit();
		}
		$query = "INSERT INTO users(name,username,email,password,role) VALUES($1,$2,$3,$4,$5) RETURNING user_id";

		$rs = pg_query_params($con,$query,array($name,$username,$email,$password,$role));
		if(!$rs)
		{
			echo pg_last_error($con);
			exit();
		}
		
		$row = pg_fetch_assoc($rs);
			$_SESSION['user_id'] = $row['user_id'];
			$user_id = $_SESSION['user_id'];

			if($role == "admin")
			{
			$rs1 = pg_query_params($con,"INSERT INTO admin(user_id) VALUES($1)" ,array($user_id));
			}

			else if($role == "mentor")
			{
			$rs1 = pg_query_params($con,"INSERT INTO mentor(user_id) VALUES($1)" ,array($user_id));
			}

			elseif($role == "student")
			{
			$rs1 = pg_query_params($con,"INSERT INTO student(user_id) VALUES($1)" ,array($user_id));
			}
			else{
				$_SESSION['message'] = "$name not inserted";
			}
		
			
		header("Location: registration.php");
		exit();
		break;
		
		case "display":
		$_SESSION['display'] = true;
		header("Location: display.php");
		exit();
		break;

		case "delete":
		$query = "DELETE FROM users WHERE username=$1";
		$rs2 = pg_query_params($con,$query,array($username));
		if($rs2 && pg_affected_rows($rs2)>0){
		$_SESSION['success'] = "$name deleted successfully from user table";
		}else{
			$_SESSION['message']="No user were deleted!";
		}
		header("Location: registration.php");
		exit();
		break;
		
		case "update":
		header("Location: update_user.php");
		exit();
		break;
		
	}
}
?>
