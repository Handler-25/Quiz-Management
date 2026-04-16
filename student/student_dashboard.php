<?php
include "../auth.php";
include "../db.php";

checkRole('student');


$student_id = $_SESSION['user_id'];

// ✅ SINGLE CLEAN QUERY
$query = "SELECT q.*, qa.score, qa.attempt_id
          FROM quiz q
          LEFT JOIN quiz_attempts qa 
          ON q.quiz_id = qa.quiz_id 
          AND qa.student_id = $1
          WHERE q.status = 'active'";

$result = pg_query_params($con, $query, array($student_id));

if(!$result){
    echo "Error fetching quizzes!";
    exit();
}
?>
<head>
	<link href="http://localhost/PROJECT/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
	@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

body {
    background-color: #f3f4f6; /* Soft light gray */
    font-family: 'Inter', sans-serif;
    color: #1f2937;
    margin: 0;
    padding: 20px;
    line-height: 1.6;
}

/* The "Card" container for your tables and forms */
.position-relative {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    padding: 30px;
    max-width: 1000px;
    margin: 40px auto;
    border: 1px solid #e5e7eb;
}
	</style>
	</head>
	<body class="d-flex justify-content-center">
	
	<div class="position-relative justify-content-center my-5" style="min-width: 725px;">
<legend>
<?php 
	
if(isset($_SESSION['msg'])){
    echo "<div class='alert alert-success style='color:green;'>".$_SESSION['msg']."</div>";
    unset($_SESSION['msg']);
}?>
<h2>Welcome! <?php echo $_SESSION['name']; ?></h2></legend>
<?php
if(pg_num_rows($result) > 0){

    while($row = pg_fetch_assoc($result)){

?>
	
    <div class="position-relative justify-content-center rounded shadow p-4" style="border:1px solid #ccc; padding:15px; margin:10px; border-radius:10px;">

        <h3><?php echo $row['title']; ?></h3>
        <p>Duration: <?php echo $row['duration']; ?> mins</p>

        <?php if($row['attempt_id']) { ?>
            <p style="color:green;">✅ Attempted</p>
            <p>Score: <?php echo $row['score']; ?></p>

            <a class="btn btn-outline-success" href="result.php?attempt_id=<?php echo $row['attempt_id']; ?>">
                View Result
            </a>

        <?php } else { ?>
            <p style="color:red;">❌ Not Attempted</p>

            <a class="btn btn-outline-primary" href="start_quiz.php?quiz_id=<?php echo $row['quiz_id']; ?>">
                Start Quiz
            </a>
        <?php } ?>

    </div>
<?php
    }

} else {
    echo "<p>No quizzes available.</p>";
}
?>
 <a class="btn btn-outline-dark" href="../logout.php" onclick="return confirm('Are you sure you want to logout?')">
			Logout
		</a>
</div>
</body>
 
