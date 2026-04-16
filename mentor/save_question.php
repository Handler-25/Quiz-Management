<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../auth.php";
include "../db.php";

checkRole('mentor');

$quiz_id=$_POST['quiz_id'];
$question=$_POST['question'];
$type=$_POST['type'];

$marks = $_POST['marks'];
$opt1=$_POST['opt1'] ?? null; //if there is value assigned to it the by $_POST[] otherwise null is assigned
$opt2=$_POST['opt2'] ?? null;  // '??' - this is known as "coalescing operator"
$opt3=$_POST['opt3'] ?? null;
$opt4=$_POST['opt4'] ?? null;

if($type=="mcq"){
	$correct=$_POST['correct_mcq'];
}else{
	$correct=$_POST['correct_tf'];
}

$query = "INSERT INTO question 
(quiz_id,question_text, option1, option2, option3, option4, correct_answer, type, marks)
VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9)";

$result=pg_query_params($con,$query,array($quiz_id,$question,$opt1,$opt2,$opt3,$opt4,$correct,$type,$marks));

if(!$result){
echo pg_last_error($con);
}


?>
