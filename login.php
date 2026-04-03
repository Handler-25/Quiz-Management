<?php
session_start();
$_SESSION['username'] = $_POST['username'];
$_SESSION['user_id'] = $_POST['user_id'];
?>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="login.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
	function loginValidation(){

    var username = document.getElementById("username").value.trim();
    var password = document.getElementById("password").value.trim();

    var userField = document.getElementById("username");
    var passField = document.getElementById("password");
    
    if(password.length < 8){
		alert("Password must be at least 8 characters");
		return false;
	}
    return true;
      
}
</script>
<script>
	
    $(document).ready(function(){
	$("#loginForm").submit(function(e){
  	  e.preventDefault();    // stop page reload
    if(!loginValidation()){
        return;
    }

    $.ajax({

        url: "database1.php",
        type: "POST",
        data: $("#loginForm").serialize(),
        dataType: "json", 
        success: function(response){
			if(response.status == "success"){
		        if(response.role == "admin"){
		            window.location.href = "admin/registration.php";
		        }

		        else if(response.role == "mentor"){
		            window.location.href = "mentor/mentor_dashboard.php";
		        }

		        else if(response.role == "student"){
		            window.location.href = "student/student_dashboard.php";
		        }

		        else{
		            $("#loginMessage").html(
		                "<div class='alert alert-danger'>Invalid Username or Password</div>"
		            );
		        }
          }
        },

        error: function(){
            $("#loginMessage").html(
                "<div class='alert alert-danger'>Server Error</div>"
            )
        }

    });

});

});

	
</script>
</head>

<body class="bg-light m-5" style="color: black; bs-border-color-translucent: rgba(0, 0, 0, 0.175);">
<div class="container d-flex justify-content-center align-items-right vh-100">
    <form name="myform" id="loginForm" onsubmit="return loginValidation()" method=POST
          class="p-4 shadow rounded col-md-7">
          <div id="loginMessage"></div>

        <h3 class="text-center mb-4">Login</h3>

        <div class="mb-7">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="user@123" required">
            <div class="invalid-feedback">
                Please enter a valid username(email).
            </div><br>
        </div>

        <div class="mb-3 position-relative">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password"  name="password"
                   class="form-control" required>
            <img src="eyelock.png" id="eyeIcon" onclick="togglePassword()" style="width:22px; cursor:pointer; position:absolute; right:10px; top:40px;">
            <div class="invalid-feedback" style="color: lightred;">
                Password must be at least 8 characters(must contain letters & numbers).
            </div>
            <div class="form-text" style="color: gray;">
                Minimum 8 characters (letters & numbers).
            </div>
        </div>

        <input type="submit" value="Login" class="btn btn-primary w-100">
        <div class="form-text text-center mt-3">
            If not registered then contact below
            <a href="mailto:raksha.cs23100@mmcc.edu.in"><br>Contact Admin</a>
        </div>
    </form>
</div>
<script>
	var passField = document.getElementById("password");
	var eye = document.getElementById("eyeIcon");
	// Toggle input type
	console.log("clicked");
	function togglePassword(){
		if (passField.type === "password") {
		    passField.type = "text";
		    eye.src="eye.png";
		} else {
		    passField.type = "password";
		    eye.src="eyelock.png";
		}
	}

</script>
</body>
</html>

