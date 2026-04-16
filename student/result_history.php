<?php
include "../db.php";

$student_id = $_SESSION['user_id'];

$query = "SELECT * FROM quiz_attempts WHERE student_id=$student_id";
$res = pg_query($con, $query);

while($row = mysqli_fetch_assoc($res)){
    echo "Quiz ID: ".$row['quiz_id']." Score: ".$row['score']."<br>";
}
?>
