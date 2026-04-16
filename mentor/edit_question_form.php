<?php
include "../auth.php";
include "../db.php";

checkRole('mentor');

$quiz_id = $_GET['quiz_id'] ?? null;
$id = $_GET['question_id'] ?? null;

if(!$quiz_id){
exit("Quiz ID missing");
}

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

$q = "SELECT * FROM question WHERE question_id=$1";
$res = pg_query_params($con,$q,array($id));
$row = pg_fetch_assoc($res);
?>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
body{
    display: flex;
    justify-content: center;
    align-items: left;
    min-height: 100vh;
    background: linear-gradient(135deg,#fbbf24 0%, #f59e0b 100%);
}

.card {
    /* FIXED DIMENSIONS */
    width: 600px; 
    min-width: 600px; /* Prevents shrinking on zoom */
    padding: 40px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    text-align: left;
}
.btn{
	background-color: #f59e0b;
}
</style>

<body class="container">
<div class="row p-4">
	<div class="col-md-6">
		<div class="card shadow">
			<div class="card-header text-center bg-warning text-white">
			<form id="questionForm" action="edit_question.php" method="POST">
			<h2>✏️ Edit Question</h2>	
			</div>
			<div class="card-body p-4">
				<div class="form-group">
					<input type="hidden" name="quiz_id" value="<?php echo $quiz_id ?>">
					<input type="hidden" name="question_id" value="<?php echo $id ?>">
					
					Question Type<br>
					<select  class="form-control" name="type" id="type" onchange="changeType()">
						<option value="mcq">MCQ</option>
						<option value="truefalse">True / False</option>
						</select><br><br>
					
					<label class="form-label">Question:</label><br>
					<textarea name="question" id="txtArea" class="form-control col-lg-6"><?php echo $row['question_text']; ?></textarea><br><br>
					
					Marks<br>
					<input class="form-control" type="number" name="marks" value="1" min="1" value="<?php echo $row['marks']; ?>">
					
					<br><br>
					
					<div class="row">
						<div class="col-sm-10" id="mcq_options">
			
						<div class="form-group row">
							<label for="opt1" class="col-sm-4 col-form-label">Option A:</label>
							<div class="col-sm-8">
							<input type="text" name="opt1" class="form-control" id="opt1" value="<?php echo $row['option1']; ?>"><br>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="opt2" class="col-sm-4 col-form-label">Option B:</label>
							<div class="col-sm-8">
								<input type="text" name="opt2" class="form-control" id="opt2" value="<?php echo $row['option2']; ?>"><br>
							</div>
						</div>	
						
						<div class="form-group row">
							<label for="opt3" class="col-sm-4 col-form-label">Option C:</label>
							<div class="col-sm-8">
							<input type="text" name="opt3" class="form-control" id="opt3" value="<?php echo $row['option3']; ?>"><br>
							</div>
						</div>	

						<div class="form-group row">
							<label for="opt4" class="col-sm-4 col-form-label">Option D:</label>
							<div class="col-sm-8">
							<input type="text" name="opt4" class="form-control" id="opt4" value="<?php echo $row['option4']; ?>"><br><br>
							</div>
						</div>
						
							Correct Answer (MCQ)<br>
						<select class="form-control" name="correct_mcq">
							<option value="A" <?php if($row['correct_answer']=='A') echo 'selected'; ?>>A</option>
							<option value="B" <?php if($row['correct_answer']=='B') echo 'selected'; ?>>B</option>
							<option value="C" <?php if($row['correct_answer']=='C') echo 'selected'; ?>>C</option>
							<option value="D" <?php if($row['correct_answer']=='D') echo 'selected'; ?>>D</option>
						</select><br><br>
				</div>		
						<div id="tf_options" style="display:none;">		
						
						Correct Answer<br>
						<select class="form-control" name="correct_tf">
						<option value="True" <?php if($row['correct_answer']=='True') echo 'selected'; ?>>True</option>
						<option value="False" <?php if($row['correct_answer']=='False') echo 'selected'; ?>>False</option>
						</select><br><br>
						
						</div>
							
						<input class="btn btn-warning text-black" type="submit" value="Update Question">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
function changeType()
{
    var type = document.getElementById("type").value;
	
	var mcq = document.getElementById("mcq_options");
	var tf =  document.getElementById("tf_options");
	
	var mcqInputs = mcq.querySelectorAll("input,select");
    var tfInputs = tf.querySelectorAll("input,select");

    if(type == "mcq")
    {
       mcq.style.display = "block";
       tf.style.display = "none";
       
        mcqInputs.forEach(el => el.disabled = false);
        tfInputs.forEach(el => el.disabled = true);
    }
    else
    {
        mcq.style.display = "none";
        tf.style.display = "block";
        
        mcqInputs.forEach(el => el.disabled = true);
        tfInputs.forEach(el => el.disabled = false);
    }
}
</script>
</body>

