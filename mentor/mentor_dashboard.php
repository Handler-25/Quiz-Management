<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../auth.php";
include "../db.php";

checkRole('mentor');

if(isset($_SESSION['msg'])){
    echo "<div class='alert alert-success style='color:green;'>".$_SESSION['msg']."</div>";
    unset($_SESSION['msg']);
}

$mentor_id = $_SESSION['user_id'];

$query="SELECT * FROM quiz WHERE mentor_id=$1 ORDER BY quiz_id DESC";
$result=pg_query_params($con,$query,array($mentor_id));
?>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<style>
html{
	    background-color: #f5f7fb;
}

.d-flex{
	min-width: 600px;
	min-height: 300px;/* */
}
body{
	background: transparent;
form{
	   background: white;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.table-container {
    width: 100%;
    overflow-x: auto;
}

table{
    width: 100%;
    min-width: 400px;
    background-color: transparent !important;
}
.table th,
.table td {
    background-color: transparent !important;
}
.parent-div{
    overflow: hidden;
}

</style>
</head>
<body class="position-relative m-5" style="color: black;">
	<div class="d-flex justify-content-around">
		<div class="row justify-content-center h-100">
			<form method=POST class="p-4 shadow rounded col-md-12 position-relative">

			<h3 class="text-center mb-4">Welcome! <?php echo $_SESSION['name'] ?></h3>

			<h2>Your Quizzes</h2>
			<a class="btn btn-outline-primary m-2" href="create_quiz.php">+ Create New Quiz</a>
			<div class="table-responsive-md">
				<table class="table  table-hover p-2 mr-3">
					<thead class="thead-dark">
					<tr>
						<th>Title</th>
						<th>Duration</th>
						<th>Status</th>
						<th class="align-middle">Actions</th>
					</tr>
					</thead>
					<?php while($row=pg_fetch_assoc($result)){ ?>
					<tr>
						<td><?php echo $row['title']; ?></td>
						<td><?php echo $row['duration']; ?> mins</td>
						<td><?php echo $row['status']; ?></td>
						<td>
							
							<a class="btn btn-primary btn-sm mb-2 mr-2" href="view_progress.php?quiz_id=<?php echo $row['quiz_id']; ?>">View Progress</a> 
							<a class="btn btn-warning btn-sm text-white mb-2 mr-2" href="edit_quiz.php?quiz_id=<?php echo $row['quiz_id']; ?>">Edit</a> 
							<button class="btn btn-danger btn-sm mb-2 mr-2" onclick="deleteQuiz(<?php echo $row['quiz_id']; ?>)">Delete</button> 
							<button  class="btn btn-success btn-sm mb-2" onclick="publishQuiz(<?php echo $row['quiz_id']; ?>)">Publish</button> 
						</td>
					</tr>
					<?php } ?>
				</table>
					<div class="button-holder">
				<a class="btn btn-dark" href="../logout.php" onclick="return confirm('Are you sure you want to logout?')">
					Logout
				</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
function deleteQuiz(id)
{
    if(!confirm("Are you sure you want to delete this quiz?")) return;

    fetch("delete_quiz.php?id="+id)
    .then(res=>res.text())
    .then(data=>{
        alert(data);
        location.reload();
    });
}

function publishQuiz(id)
{
	if(!confirm("Want to publish this quiz?")) return;

    let data = new FormData();
    data.append("quiz_id", id);

	console.log("Before fetch");
    fetch("save_quiz.php", {
        method: "POST",
        body: data
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
    })
    .catch(err => console.error(err));
}


</script>
</body>
</html>
