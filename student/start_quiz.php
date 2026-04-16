<?php  
session_start();
include "../db.php";

if(!isset($_SESSION['user_id'])){
    echo "Session expired. Please login again.";
    exit();
}

if(!isset($_GET['quiz_id'])){
    echo "Invalid request!";
    exit();
}
$quiz_id = $_GET['quiz_id'];
$student_id = $_SESSION['user_id'];

$qCheck = "SELECT * FROM quiz WHERE quiz_id=$1 AND status='active'";
$resCheck = pg_query_params($con,$qCheck,array($quiz_id));

if(pg_num_rows($resCheck) == 0){
    echo "Quiz not available!";
    exit();
}

$qExists = "SELECT * FROM quiz_attempts 
            WHERE student_id=$1 AND quiz_id=$2";

$resExists = pg_query_params($con,$qExists,array($student_id,$quiz_id));

if(pg_num_rows($resExists) > 0){
    $row = pg_fetch_assoc($resExists);
    $attempt_id = $row['attempt_id'];

    // redirect to existing attempt
    echo "<script>
    window.location.href='attempt_quiz.php?attempt_id=$attempt_id';
    </script>";
    exit();
}

$query = "INSERT INTO quiz_attempts(student_id,quiz_id) VALUES($1,$2) RETURNING attempt_id";
$result = pg_query_params($con, $query,array($student_id,$quiz_id));

if(!$result){
    echo pg_last_error($con);
    exit();
}

$row = pg_fetch_assoc($result);

if(!$row){
    echo "No attempt ID returned";
    exit();
}

$attempt_id = $row['attempt_id'];

echo "<script>
window.location.href='attempt_quiz.php?attempt_id=$attempt_id'&quiz_id=$quiz_id;
</script>";
exit();
?>
