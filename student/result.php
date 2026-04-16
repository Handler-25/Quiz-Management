<?php
include "../db.php";

//checkRole('student');


$attempt_id = $_GET['attempt_id'];

// get quiz_id + student_id
$q1 = "SELECT * FROM quiz_attempts WHERE attempt_id=$1";
$res1 = pg_query_params($con, $q1, array($attempt_id));
$data = pg_fetch_assoc($res1);

$quiz_id = $data['quiz_id'];
$student_id = $data['student_id'];

$qTotal =  "SELECT SUM(marks) FROM question WHERE quiz_id=$1";
$resT = pg_query_params($con,$qTotal,array($quiz_id));
$total = pg_fetch_result($resT,0,0);

$percentage = ($data['score'] / $total) * 100;

?>
<head>
<link href="http://localhost/PROJECT/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-around">
<span class="border rounded-lg m-5">
	<div class="justify-content-center position-relative p-4 shadow"  style="width: 729px; height: 600px;">
	<h3 class="border border-success justify-content-center rounded-pill p-4">Score: <?php echo $data['score']." / ".$total; ?></h3>
	<h3 class="border border-success justify-content-center rounded-pill p-4">Percentage: <?php echo round($percentage)."%"; ?></h3>
<?php
// rank query
$q2 = "SELECT student_id, score,
       RANK() OVER (ORDER BY score DESC) as rank
       FROM quiz_attempts
       WHERE quiz_id=$1";

$res2 = pg_query_params($con, $q2, array($quiz_id));

$rank = 0;

while($row = pg_fetch_assoc($res2)){
    if($row['student_id'] == $student_id && $row['score'] == $data['score']){
        $rank = $row['rank'];
        break;
    }
}

?> <h3 class="border border-success justify-content-center rounded-pill p-4 ">🏆 Your Rank: <?php echo $rank; ?></h3>

<a class="btn btn-outline-success text-white-lg" href="detailed_result.php?attempt_id=<?php echo $attempt_id; ?>">
    📄 View Detailed Analysis
</a>
</div>
</span>
</body>
