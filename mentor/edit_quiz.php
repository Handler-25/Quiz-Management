<?php
include "../auth.php";
include "../db.php";

checkRole('mentor');

$quiz_id = $_GET['quiz_id'];
/*if(!id){
	exit("Invalid request");
}*/

$q = "SELECT * FROM quiz WHERE quiz_id=$1";
$res = pg_query_params($con,$q,array($quiz_id));
$row = pg_fetch_assoc($res);

if(!$row){
    echo "Quiz not found!";
    exit();
}
?>
<head><link href='http://localhost/PROJECT/bootstrap-5.3.8-dist/css/bootstrap.min.css' rel='stylesheet'>
	<style>body{
		margin: 70px; padding: 20px; border: 1px solid gray; border-radius: 30px;
		}
	</style></head>
	<body>

		<form method="post">
		<h2>✏️ Edit Quiz</h2>
			<?php if($row['status']=='draft'){ ?>
				<span style="color:orange; bg-color: ">📝 Draft</span>
			<?php } else { ?>
				<span style="color:green;">✅ Published</span>
			<?php }
			
			if($row['status'] == 'active'){
			echo "<h3 class='border border-outline-danger' style='padding: 15px; margin:10px'> This quiz is already published and cannot be edited.</h3>";
			echo "<a style='margin-left: 10px;' class='btn btn-outline-dark' href='mentor_dashboard.php'>Go Back</a>";
			exit();
			}
			?>
			<div class="form-group">
			<label class="form-label">Title:</label><br>
			<input class="form-control" type="text" name="title" value="<?php echo $row['title']; ?>" required><br><br>

			<label class="form-label">Duration (minutes):</label><br>
			<input class="form-control" type="number" name="duration" value="<?php echo $row['duration']; ?>" min="1" required><br><br>

			<input type="submit" class="btn btn-outline-warning w-30" name="update" value="Update Quiz">

			<a href="mentor_dashboard.php">
				<button type="button" class="btn btn-outline-dark w-30">Back</button>
			</a>
		</form>

		<hr>

		<a class="btn btn-outline-primary text-blue" href="add_question.php?quiz_id=<?php echo $quiz_id; ?>"> Add / Edit Questions</a>
	</div>
	</body>

<?php
if(isset($_POST['update'])){

    $title = trim($_POST['title']);
    $duration = $_POST['duration'];

    // ✅ validation
    if($title == "" || $duration <= 0){
        echo "<p style='color:red;'>Invalid input!</p>";
        exit();
    }

    $update = "UPDATE quiz SET title=$1, duration=$2 WHERE quiz_id=$3";
    $result = pg_query_params($con,$update,array($title,$duration,$quiz_id));

    if($result){
        echo "<p style='color:green;'>✅ Quiz Updated Successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error updating quiz!</p>";
    }
	header("Location: mentor_dashboard.php");
	exit();		
}
?>
