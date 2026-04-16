<?php
include "../auth.php";
include "../db.php";
checkRole('mentor');
$mentor_id = $_SESSION['user_id'];

if(isset($_POST['create']))
{
$quiz_title=$_POST['quiz_title'];
$subject=$_POST['subject'];
$duration=$_POST['duration'];

 if($quiz_title=="" || $subject=="" || $duration==""){
        echo "Please fill all fields";
    }
 else{
 	
 	$check_query = "SELECT quiz_id FROM quiz WHERE title = $1 AND subject = $2";
 	$check_result = pg_query_params($con,$check_query,array($quiz_title,$subject));
 		
 		
    if(pg_num_rows($check_result) > 0)
    {
        $row = pg_fetch_assoc($check_result);
        $quiz_id = $row['quiz_id'];

        echo "
        <head><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css' rel='stylesheet'>
        <style>
        position: top center;
        </style></head>
        	<body><div class='alert bg-primary'>Quiz already created. Please use another title.";
        echo "<br>Existing Quiz ID: ".$quiz_id."</div></body>";
    }
 	else{
		$query="INSERT INTO quiz(title,subject,duration,mentor_id)VALUES($1,$2,$3,$4) ON CONFLICT (title,subject) DO NOTHING RETURNING quiz_id";

		$result=pg_query_params($con,$query,array($quiz_title,$subject,$duration,$mentor_id));
		
		if($result){
			echo "Quiz Created Successfully";
				$row = pg_fetch_assoc($result);
				$quiz_id = $row['quiz_id'];
				
				header("Location: add_question.php?quiz_id=".$quiz_id);
				exit();
			}
			else{
			echo "Quiz Not Created<br>";
			echo pg_last_error($con);
			}
		}
	}
}
?>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #0d9488 0%, #2dd4bf 100%);
}

.card {
    /* FIXED DIMENSIONS */
    width: 400px; 
    min-width: 400px; /* Prevents shrinking on zoom */
    padding: 40px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    text-align: center;
}

/* Ensure inputs don't wrap or break the layout */
.input-group {
   
}

input {
 	margin-bottom: 20px;
    text-align: left;
    width: 320px; /* Specific width so it doesn't move */
    padding: 12px;
    border: 2px solid #0d9488;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s;
}

input:focus {
    border-color: #0d9488;
}

.btn {
    width: 100%;
    padding: 14px;
    background-color: #0d9488;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 16px;
}</style>
<script>console.log("<?php echo $_SESSION['user_id']; ?>")</script>
</head>
<body>
<div class="card shadow">
	<div class="card-header bg-primary text-white">
	Create Quiz
	</div>

	<div class="card-body">
		<form method="post">

			<div class="mb-3">
			<label>Quiz Title</label>
			<input type="text" name="quiz_title"class="form-control">
			</div>

			<div class="mb-3">
			<label>Subject</label>
			<input type="text" name="subject" class="form-control">
			</div>

			<div class="mb-3">
			<label>Duration (minutes)</label>
			<input type="number" name="duration" class="form-control">
			</div>

			<button class="btn btn-success" name="create">Create Quiz</button>
		</form>
	</div>
</div>
<!--
<?php 
	if(isset($_POST['save'])){
		header("Location: save_quiz.php");
		exit();
	}
?> -->
</form>
</body>
</html>
