<?php
session_start();

if($login_success==''){
    $_SESSION['msg'] = "<span style='color:green;'>Login Successful</span>";
}

$_SESSION['username'] = $_POST['username'];
$_SESSION['user_id'] = $_POST['user_id'];
?>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
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
	$("#loginForm").on("submit", function(e){
  	  e.preventDefault();    // stop page reload
  	  
  	   let username = $("input[name='username']").val();
        let password = $("input[name='password']").val();

        // Validation
        if(username === "" || password === ""){
            $("#msg").html("<div class='alert alert-danger'>All fields are required</div>");
            return;
        }
        
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
		         alert("Login Successful");
		            window.location.href = "admin/registration.php";
		        }

		        else if(response.role == "mentor"){
		        $("#loginMessage").html("<div class='alert alert-success'>Login Successful</div>");
		            window.location.href = "mentor/mentor_dashboard.php";
				}
		        else if(response.role == "student"){
		            window.location.href = "student/student_dashboard.php";
		        }
		        else{
		            $("#loginMessage").html(
		                "<div class='alert alert-danger'>Login Failed</div>"
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
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

body {
	position: cover;
    background-color: #f3f4f6; /* Soft light gray */
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 20px;
    line-height: 1.6;
    
     background: rgba(11,34,39,1);
}



/* The "Card" container for your tables and forms */
.bg-wrapper{
	position: contain;
    margin: 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    padding: 30px;
    max-width: 720px;
    margin: 40px auto;
    display: flex;
    justify-content: center;
    align-items: center;
     background-image: url('img1.jpeg');
    background-size: 640px 961px; 
     min-height: 100vh;    
    background-position: center;
    background-repeat: no-repeat;
   }
.container{
   background-position: bottom center;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    width: 400px; 
    min-width: 400px; /* Prevents shrinking on zoom */
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    text-align: center;
}
</style>
</head>

<body class="m-5 vh-100" style="color: black;  background-color:rgba();">  <!-- bs-border-color-translucent: rgba(0, 0, 0, 0.175); -->
<div class="bg-wrapper">
<div class="container p-4 shadow rounded col-md-7">
    <form name="myform" id="loginForm" method=POST>
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
            <img src="eyelock.png" id="eyeIcon" onclick="togglePassword()" style="width:22px; cursor:pointer; position:absolute; right:10px; top:46px;">
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

