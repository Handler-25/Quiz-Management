<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../auth.php";
include "../db.php";

checkRole('mentor');

$quiz_id = $_GET['id'] ?? null;
if(!$quiz_id){
	exit("Invalid request");
}
//delete all questions
$q1 = "DELETE FROM question WHERE quiz_id=$1";
pg_query_params($con, $q1, array($quiz_id));

// delete quiz
$q2 = "DELETE FROM quiz WHERE quiz_id=$1";
$result = pg_query_params($con,$q2,array($quiz_id));

if(!$result){
    echo "Error: " . pg_last_error($con);
} else {
    if(pg_affected_rows($result) > 0){
        echo "Quiz Deleted Successfully";
    } else {
        echo "No quiz found!";
    }
}
?>


