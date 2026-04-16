<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../auth.php";
include "../db.php";

checkRole('mentor');
$quiz_id = $_POST['quiz_id'];
$id = $_POST['question_id'];

$qCheck = "SELECT q.status 
           FROM question qs
           JOIN quiz q ON qs.quiz_id = q.quiz_id
           WHERE qs.question_id=$1";

$resCheck = pg_query_params($con,$qCheck,array($id));
$data = pg_fetch_assoc($resCheck);

if($data['status'] == 'active'){
    echo "Cannot edit question after publish!";
    exit();
}

$question = $_POST['question'];
$type = $_POST['type'];
$opt1 = $_POST['opt1'];
$opt2 = $_POST['opt2'];
$opt3 = $_POST['opt3'];
$opt4 = $_POST['opt4'];
if($type == 'mcq'){
    $correct = $_POST['correct_mcq'];
} else {
    $correct = $_POST['correct_tf'];
}
$marks = $_POST['marks'];

$query = "UPDATE question SET 
          question_text=$1,
          option1=$2,
          option2=$3,
          option3=$4,
          option4=$5,
          correct_answer=$6,
          marks=$7,
          type=$8
          WHERE question_id=$9";

$result = pg_query_params($con,$query,array(
    $question,
    $opt1,
    $opt2,
    $opt3,
    $opt4,
    $correct,
    $marks,
    $type,
    $id
));

if($result){
   header("Location: add_question.php?quiz_id=$quiz_id");
	exit();
} else {
    echo "Error updating question!";
}
?>
