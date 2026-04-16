<?php
include "../auth.php";
checkRole('admin');

if(isset($_SESSION['msg'])){
    echo "<div class='alert alert-success style='color:green;'>".$_SESSION['msg']."</div>";
    unset($_SESSION['msg']);
}
?>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
<style>
body{
background-image: url('puzzlePieces.jpeg');
background-size: cover; /* Resizes the image to cover the entire container */
background-position: center; /* Centers the image within the container */
background-repeat: no-repeat; /* Prevents the image from repeating */
padding: 20px; /* Add padding so content doesn't overlap the edges */
background-color: #f1f1f1; 
}
#f1{
	padding-left: 150px;
    padding-right: 50px;
    padding-bottom: 38px;
    padding-top: 30px;
    background-color: transparent;
    opacity: 2;
    color:white;
}
div.justify-content-center{
	display: flex;
	flex-wrap: wrap;
	align-content: center;
}
div{
	justify-content: center;
}
 input[type=radio]{
 	align-content: left;
  	max-width: 500px;
 	display: inline-block;
 	margin-left: 30px;
 	margin-right: 10px;
 }
 .col-3{
 	position:relative;
 	
 }
  .col-2{
   	position:relative;
   	align-content: center;
   	min-width: 300px;
 }
 
 .hideContent{
 	display: none;
 }
 
 .button-holder{
 	display: inline-block;
 	position: relative;
 	align-content: center;
 }
 button{
 	margin: 7px;
 	position: cover;
 }
 
 </style>
 <title>Registration Page</title>
</head>       <!--action ="database2.php" -->
<body>
<div class="justify-content-center align-items-right vh-75">
	<form id=f1 class="m-5 p-4 rounded shadow" action="database2.php" method=post>
		<!-- Name input -->
	<div class="row">
    <div class="col-3">
    <label for="inputName" class="col-sm-2 col-form-label">Name:</label>
    </div>
    <div class="col-6">
      <input type="text" class="form-control" id="inputName" name="Name" 
      value="<?php echo $_POST['Name']; ?>"><br>
    </div>
    </div>
    	<!-- UserName input -->
	<div class="row" required>
    <div class="col-3">
    <label for="inputUserName" class="col-md-2 col-form-label" style="display: inline;">User Name:</label>
    </div>
    <div class="col-6">
      <input type="text" class="form-control" id="inputUserName"
       name="userName" value="<?php echo $_POST['userName']; ?>"><br>
    </div>
    	<!-- Email input -->
    </div>
	 <div class="row">
    <div class="col-3">
    <label for="inputEmail" class="col-sm-2 col-form-label">Email: </label>
    </div>
    <div class="col-6">
      <input type="email" class="form-control" id="inputEmail"
      name="email" value="<?php echo $_POST['email']; ?>"><br>
    </div>
    </div>
   		 <!-- Password input -->
  <div class="row">
    <div class="col-3">
    <label for="inputPassword" class="col-sm-2 col-form-label">Password: </label>
    </div>
    <div class="password-wrapper col-6" style=" position: relative;">
      <input type="password" class="form-control" id="inputPassword" name="password" 
      value="<?php echo $_POST['password']; ?>" style="padding-right: 50px;">
      
      <img src="../eyelock.png" for="inputPassword" id="eyeIcon" onclick="togglePassword()"
      	style=" width:22px; cursor:pointer; position:absolute; right: 25px; top: 45%; transform: translateY(-100%);">
      	<br>
    </div>
  </div>
		<!-- Role input -->
  <div class="row">
    <div class="col-2">
    <label for="inputRole" class="col-sm-2 col-form-label">Role: </label>
    </div>
    <div class="col-6 justify-content-start gb-4">
      <input type="radio" class="form-check-input" name="inputRole" id="admin" value="admin">Admin
      <input type="radio" class="form-check-input" name="inputRole" id="mentor" value="mentor">Mentor
      <input type="radio" class="form-check-input" name="inputRole" id="student" onclick="showContent()" value="student" checked>Student
    </div>
     		<!-- Button input -->
	<div class="button-holder justify-content-center">
	  <button type="submit" name="submit" value="add" class="button-holder btn btn-primary">Add</button>
	
	  <button type="submit" name="submit" value="display" class="button-holder btn btn-warning">Display List</button>
	  <button type="submit" name="submit" value="update" class="button-holder btn btn-success">Update</button>
	  <button type="submit" name="submit" value="delete" class="button-holder btn btn-danger"
		onclick="return confirm('Are you sure you want to delete this user?')">Delete
		</button>
	  <a href="../logout.php" 
   onclick="return confirm('Are you sure you want to logout?')"
   class="button-holder btn btn-secondary">Log out
	</a>
	  <?php
	  if(isset($_SESSION['message'])){
		echo  "<br><div class='alert alert-danger'>".$_SESSION['message']."</div>";
		unset($_SESSION['message']);
		}
		
	  if(isset($_SESSION['success'])){
		echo "<br><div class='alert alert-success'>".$_SESSION['success']."</div>";
		unset($_SESSION['success']);
		}
	?>
	</div>
</form>
</div>
<script>
	const passField = document.getElementById("inputPassword");
	const eye = document.getElementById("eyeIcon");
	// Toggle input type
	console.log("clicked");
	function togglePassword(){
		if (passField.type === "password") {
		    passField.type = "text";
		    eye.src="../eye.png";
		} else {
		    passField.type = "password";
		    eye.src="../eyelock.png";
		}
	}

</script>
</body>
</html>
