<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include "../auth.php";
include "../db.php";

checkRole('mentor');

$quiz_id=$_GET['quiz_id'] ?? null;

if(!$quiz_id){
exit("Quiz ID missing");
}

$query = "SELECT * FROM quiz WHERE quiz_id=$1";
$result = pg_query_params($con,$query,array($quiz_id));

if(!$result){
echo pg_last_error($con);
exit();
}
$quiz = pg_fetch_assoc($result);
?>
<html>
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<style>
body{
    display: flex;
    justify-content: center;
    align-items: left;
    min-height: 100vh;
    background: linear-gradient(135deg, #0d9488 0%, #2dd4bf 100%);
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

/* Ensure inputs don't wrap or break the layout */
.input-group {
   
}

input {
 	margin-bottom: 20px;
    text-align: left;
    width: 320px; /* Specific width so it doesn't move */
    padding: 12px;
    border: 2px solid #0d9488;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s;
}

input:focus {
    border-color: #0d9488;
}

.btn {
    width: 100%;
    padding: 14px;
    background-color: #0d9488;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 16px;
}</style>
</head>
<body class="container">
<div class="row">
	<div class="col-md-6">
		<div class="card shadow">
			<div class="card-header text-center bg-success text-white">
			<h3>Add Question</h3>
			</div>

			<div class="card-body p-4">

			Quiz Title: <?php echo $quiz['title']; ?><br>
			Duration: <?php echo $quiz['duration']; ?> minutes<br>

			<form id="questionForm" action="save_question.php" method="POST">
				<div class="form-group">
					<input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
					
					Question Type<br>
					<select  class="form-control" name="type" id="type" onchange="changeType()">
						<option value="mcq">MCQ</option>
						<option value="truefalse">True / False</option>
						</select><br><br>
					
					<label class="form-label">Question:</label><br>
					<textarea name="question" id="txtArea" class="form-control col-lg-6"></textarea><br><br>
					
					Marks<br>
					<input class="form-control" type="number" name="marks" value="1" min="1">
					
					<br><br>
					
					<div class="row">
						<div class="col-sm-10" id="mcq_options">
			
						<div class="form-group row">
							<label for="opt1" class="col-sm-4 col-form-label">Option A:</label>
							<div class="col-sm-8">
							<input type="text" name="opt1" class="form-control" id="opt1"><br>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="opt2" class="col-sm-4 col-form-label">Option B:</label>
							<div class="col-sm-8">
								<input type="text" name="opt2" class="form-control" id="opt2"><br>
							</div>
						</div>	
						
						<div class="form-group row">
							<label for="opt3" class="col-sm-4 col-form-label">Option C:</label>
							<div class="col-sm-8">
							<input type="text" name="opt3" class="form-control" id="opt3"><br>
							</div>
						</div>	

						<div class="form-group row">
							<label for="opt4" class="col-sm-4 col-form-label">Option D:</label>
							<div class="col-sm-8">
							<input type="text" name="opt4" class="form-control" id="opt4"><br><br>
							</div>
						</div>
						
							Correct Answer (MCQ)<br>
						<select class="form-control" name="correct_mcq">
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="D">D</option>
						</select><br><br>
				</div>		
						<div id="tf_options" style="display:none;">		
						
						Correct Answer<br>
						<select class="form-control" name="correct_tf">
						<option value="True">True</option>
						<option value="False">False</option>
						</select><br><br>
						
						</div>
							
						<button type="button" onclick="addQuestion()">Add Question</button>
						<button type="button" onclick="publishQuiz()">Save Quiz</button>
					</div>
				</div>
			</form>
			
		</div>
		</div>
		<br>
		<div class="card shadow">
			<div class="card-header text-center bg-dark text-white">
			<h3>Questions Added</h3>
			</div>
			<div class="card-body p-4" id="questionList"></div>
			<a href="mentor_dashboard.php?quiz_id=..." class="btn btn-primary">⬅ Back</a>
		</div>
		
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

function addQuestion()
{
    let form=document.getElementById("questionForm");
    let data=new FormData(form);

    fetch("save_question.php",{
        method:"POST",
        body:data
    })
    .then(response=>response.text())
    .then(result=>{
        loadQuestions();
        form.reset();
		changeType(); // reset UI properly	
    });
}

function loadQuestions()
{
    let quiz_id=document.querySelector("[name='quiz_id']").value;

    fetch("load_questions.php?quiz_id="+quiz_id)
    .then(response=>response.text())
    .then(data=>{
        document.getElementById("questionList").innerHTML=data;
    });
}

function deleteQuestion(id)
{
    if(!confirm("Delete this question?"))
        return;

    fetch("delete_question.php?id="+id)
    .then(response=>response.text())
    .then(result=>{
        loadQuestions();   // reload question list
    });
}
function publishQuiz()
{
    let quiz_id=document.querySelector("[name='quiz_id']").value;

    fetch("save_quiz.php",{
        method:"POST",
        body:new URLSearchParams({quiz_id:quiz_id})
    })
    .then(response=>response.text())
    .then(data=>{
        alert(data);
    });
}

window.onload=function(){
changeType();
loadQuestions();
}

</script>
</body>
</html>

