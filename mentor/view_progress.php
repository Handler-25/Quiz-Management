<?php
include "../auth.php";
include "../db.php";
checkRole('mentor');

$quiz_id = $_GET['quiz_id'] ?? null;

if(!$quiz_id){
    exit("Quiz ID missing!");
}

// ✅ total marks
$qTotal = "SELECT SUM(marks) FROM question WHERE quiz_id=$1";
$resTotal = pg_query_params($con,$qTotal,array($quiz_id));
$total_marks = pg_fetch_result($resTotal,0,0);

// ✅ get all students + attempts
$query = "SELECT u.user_id, u.name, qa.score, qa.attempt_id
          FROM users u
          LEFT JOIN quiz_attempts qa 
          ON u.user_id = qa.student_id 
          AND qa.quiz_id = $1
          WHERE u.role = 'student'
          ORDER BY u.name";

$result = pg_query_params($con,$query,array($quiz_id));

if(pg_num_rows($result) == 0){
    echo "No students found!";
}

?>

<h2>📊 Student Progress</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Student Name</th>
    <th>Status</th>
    <th>Score</th>
    <th>Action</th>
</tr>

<?php while($row = pg_fetch_assoc($result)) { ?>

<tr><!-- takes student names -->
    <td><?php echo $row['name']; ?></td>
	<!-- check whether the student attemped or not.If attempted then will print score and can view their results -->
    <?php if($row['attempt_id']) { ?>
        <td style="color:green;">✅ Attempted</td>

        <td>
            <?php echo $row['score']." / ".$total_marks; ?>
        </td>

        <td>
            <a href="../student/result.php?attempt_id=<?php echo $row['attempt_id']; ?>">
                View Result
            </a>
        </td>

    <?php } else { ?>
        <td style="color:red;">❌ Not Attempted</td>
        <td>—</td>
        <td>—</td>
    <?php } ?>

</tr>

<?php } ?>

</table>
<?php
$total = 0;
$attempted = 0;

pg_result_seek($result, 0);

while($row = pg_fetch_assoc($result)){
    $total++;
    if($row['attempt_id']){
        $attempted++;
    }
}

$not_attempted = $total - $attempted;

echo "<p>Total Students: $total</p>";
echo "<p>Attempted: $attempted</p>";
echo "<p>Not Attempted: $not_attempted</p>";
?><hr>
<?php
$qGraph = "SELECT score FROM quiz_attempts WHERE quiz_id=$1";
$resGraph = pg_query_params($con,$qGraph,array($quiz_id));
if(!$resGraph){
    echo pg_last_error($con);
}
$scores = [];

while($row = pg_fetch_assoc($resGraph)){
    $scores[] = $row['score'];
}
?>
<a class="btn btn-outline-dark" href="../mentor_dashboard.php">Go Back</a>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="chart" width="40" height="20"></canvas>

<script>
let scores = <?php echo json_encode($scores); ?>;

const ctx = document.getElementById('chart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: scores.map((_, i) => "Student " + (i+1)),
        datasets: [{
            label: 'Scores',
            data: scores
        }]
    }
});
</script>
