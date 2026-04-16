<?php
include "../auth.php";
include "../db.php";

//checkRole('student');

$attempt_id = $_GET['attempt_id'];

$qCheck = "SELECT * FROM quiz_attempts WHERE attempt_id=$1";
$resCheck = pg_query_params($con,$qCheck,array($attempt_id));
$data = pg_fetch_assoc($resCheck);

if(($data['student_id'] || checkRole('mentor'))!= $_SESSION['user_id']){
    echo "Unauthorized!";
    exit();
}

$query = "SELECT q.question_text, q.correct_answer, q.marks,
                 a.selected_answer
          FROM answers a
          JOIN question q ON q.question_id = a.question_id
          WHERE a.attempt_id=$1";

$result = pg_query_params($con,$query,array($attempt_id));
?>
<head>
<link href="http://localhost/PROJECT/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-around">
<div class="justify-content-center position-relative p-4" style="width: 729px; height: 600px;">
<h2> Detailed Result</h2>
<div class="table-responsive-md shadow">
<table class="table table-hover p-2" border="1" cellpadding="10">
<thead class="thead-dark"><tr>
    <th>Question</th>
    <th>Your Answer</th>
    <th>Correct Answer</th>
    <th>Marks</th>
    <th>Status</th>
</tr></thead>

<?php while($row = pg_fetch_assoc($result)) { 

    $isCorrect = strtolower(trim($row['selected_answer'])) == strtolower(trim($row['correct_answer']));
?>

<tr>
    <td><?php echo $row['question_text']; ?></td>

    <td><?php echo $row['selected_answer']; ?></td>

    <td><?php echo $row['correct_answer']; ?></td>

    <td><?php echo $row['marks']; ?></td>

    <td>
		<?php 
		if($isCorrect){
			echo $row['marks'];
		} else {
			echo "0";
		}
		?>
    </td>
</tr>

<?php } ?>

</table>
</div>
<?php
pg_result_seek($result, 0);

$total = 0;
$obtained = 0;

while($row = pg_fetch_assoc($result)){
    $total += $row['marks'];

    if(strtolower(trim($row['selected_answer'])) == strtolower(trim($row['correct_answer']))){
        $obtained += $row['marks'];
    }
}
?>
<h3>Total: <?php echo "$obtained / $total"; ?></h3>
<a class="btn btn-outline-dark" href="../logout.php" onclick="return confirm('Are you sure you want to logout?')">
			Logout
		</a>
</div>
</body>
