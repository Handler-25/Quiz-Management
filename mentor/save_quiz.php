<?php
include "../auth.php";
include "../db.php";

checkRole('mentor');

if(!isset($_POST['quiz_id'])){
    exit("Quiz ID missing!");
}

$quiz_id = $_POST['quiz_id'] ?? null;

$query = "UPDATE quiz SET status='active' WHERE quiz_id=$1";
$result = pg_query_params($con,$query,array($quiz_id));

if(!$result){
    echo "Error: " . pg_last_error($con);
    exit();
}

if(pg_affected_rows($result) > 0){
    echo "Quiz Published Successfully";
} else {
    echo "No quiz updated! Check quiz_id.";
}
?>
