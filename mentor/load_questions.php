<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include "../auth.php";
include "../db.php";

checkRole('mentor');

$quiz_id=$_GET['quiz_id'] ?? null;
if(!$quiz_id){
	exit("Missing Quiz ID");
}

$query = "SELECT * FROM question WHERE quiz_id=$1";
$result = pg_query_params($con,$query,array($quiz_id));

if(!$result){
echo pg_last_error($con);
exit();
}

$i=1;
while($row=pg_fetch_assoc($result))
{
echo "<div style='border:1px solid black;padding:10px;margin:5px;'>";
echo "<b>Q.$i:</b> ".$row['question_text']."<br>";
echo "<p>Marks: ".$row['marks']."</p>";

if($row['type']=="mcq"){
echo "A) ".$row['option1']."<br>";
echo "B) ".$row['option2']."<br>";
echo "C) ".$row['option3']."<br>";
echo "D) ".$row['option4']."<br>";
}

echo "Correct: ".$row['correct_answer']."<br><br>";
echo "<a style='margin-right: 3px;' href='edit_question_form.php?question_id=".$row['question_id']."&quiz_id=".$row['quiz_id']."'>
        <button>✏️Edit</button>
      </a>";
echo "<button onclick='deleteQuestion(".$row['question_id'].")'>Delete</button>";

echo "</div>";
$i++;
}
?>
