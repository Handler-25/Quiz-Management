<?php
include "../db.php";

$attempt_id=$_POST['attempt_id'];
$score=0;
$marks = 0;

$sql = "UPDATE quiz_attempts 
        SET marks = 0, status = 'disqualified' 
        WHERE user_id = ".$_SESSION['user_id']." AND quiz_id = ".$_GET['quiz_id'].";";

$result=pg_query($con, $sql);
if(pg_num_rows($result)==0){
	echo "Marks not set!";
	exit();
}

foreach($_POST as $key=>$val){
	if(strpos($key,'q')===0){  //use to take the POST values starting with character 'q' and avoid taking other values like 'attempt_id'
		$qid=str_replace('q','',$key);
	
		// get correct answer by checking it using question table
		$q="SELECT correct_answer,marks FROM question WHERE question_id=$1";
		$res=pg_query_params($con,$q,array($qid));
		$row=pg_fetch_assoc($res);

		if(strtolower(trim($val)) == strtolower(trim($row['correct_answer']))){
			$score += $row['marks'];
		}
		// store answer
		pg_query_params($con,
		"INSERT INTO answers(attempt_id,question_id,selected_answer)
		VALUES($1,$2,$3)",
		array($attempt_id,$qid,$val));
	}
}

// update score
pg_query_params($con,"UPDATE quiz_attempts SET score=$1,end_time=NOW(),date=CURRENT_DATE WHERE attempt_id=$2",
array($score,$attempt_id));
echo $attempt_id;
?>
