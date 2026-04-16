<?php
session_start();
include "../auth.php";
include "../db.php";

checkRole('student');

$attempt_id=$_GET['attempt_id'];

$qCheck = "SELECT * FROM quiz_attempts WHERE attempt_id=$1";
$resCheck = pg_query_params($con, $qCheck, array($attempt_id));

if(pg_num_rows($resCheck) == 0){
    echo "Invalid attempt!";
    exit();
}

$data = pg_fetch_assoc($resCheck);
// check authorization
if($data['student_id'] != $_SESSION['user_id']){
    echo "Unauthorized access!";
    exit();
}
// query to retrieve the quiz_attepmts details and its question to attemp the quiz by students
$q="SELECT q.* FROM question q
JOIN quiz_attempts a ON q.quiz_id=a.quiz_id
JOIN quiz z ON z.quiz_id=a.quiz_id
WHERE a.attempt_id=$1 AND z.status='active'";

$res=pg_query_params($con,$q,array($attempt_id));

//query to avail the quiz duration & quiz details and implement it so stuednt can complete the quiz only in given quiz duration and when it is in 'active' state.
$q1 = "SELECT duration FROM quiz_attempts a JOIN quiz q ON q.quiz_id=a.quiz_id
WHERE a.attempt_id=$1";
$res1 = pg_query_params($con,$q1,array($attempt_id));

if(pg_num_rows($res1)==0){
	echo "Quiz not available!";
	exit();
}
else{
$val = pg_fetch_assoc($res1);
$duration = $val['duration'];
}
?>
<html>
	<head>
		<link href="http://localhost/PROJECT/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<style>
		html{
		position: relative;
	  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    /* Or a subtle geometric pattern */
    background-color: #f8fafc;
    font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
	}
	 body{
	 position: relative;
	 background: white;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    padding: 2rem;
	border: 1px solid rgba(0,0,0,0.05);	
}
    </style>
	</head>
	<body class="mx-5 my-5 shadow p-4">
		<h2 class="border border-danger rounded p-2" style="hover: bg-color: red;">Attempt Quiz</h2>
		<div id="timer"></div>
		<form id="quizForm" method=POST>
			<input type="hidden" name="attempt_id" value="<?php echo $attempt_id; ?>">
			<?php while($row=pg_fetch_assoc($res)){ 
				?><div class="row"><div class="container col-md-12"><div class="col-md-7">
				<p id="questionNo"><?php echo $row['question_text'];?></p></div>
				<div class="col-md-2 justify-content-right">
				<p><?php echo "Marks: ".$row['marks'];?></p></div></div></div>
				<?php if($row['type']=="mcq"){ ?>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="q<?php echo $row['question_id']; ?>" value="A"> <?php echo $row['option1']; ?><br>
					<input class="form-check-input" type="radio" name="q<?php echo $row['question_id']; ?>" value="B"> <?php echo $row['option2']; ?><br>
					<input class="form-check-input" type="radio" name="q<?php echo $row['question_id']; ?>" value="C"> <?php echo $row['option3']; ?><br>
					<input class="form-check-input" type="radio" name="q<?php echo $row['question_id']; ?>" value="D"> <?php echo $row['option4']; ?>
				</div>
				<?php }else{ ?>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="q<?php echo $row['question_id']; ?>" value="True"><label class="check-form-label"> True</label><br>
						<input class="form-check-input" type="radio" name="q<?php echo $row['question_id']; ?>" value="False"><label class="check-form-label"> False</label><br>
					</div>
				<?php } ?>
				<hr>
			<?php } ?>

			<button type="button" class="btn btn-outline-danger mb-4" onclick="submitQuiz()">Submit</button><br>
			<div id="progressBar" style="background:#eee;height:20px;">
				<div class="progress-bar bg-success" id="progressFill" style="height:100%;width:0%"></div>
			</div>
			<p id="progressText">0% Completed</p>
			</form>
		<script>
			let time=<?php echo $duration; ?> * 60;

			let timer=setInterval(()=>{
			
			let minutes = Math.floor(time / 60);
			let seconds = time % 60;

			// format like 05:09
			seconds = seconds < 10 ? "0" + seconds : seconds;
			minutes = minutes < 10 ? "0" + minutes : minutes;

			document.getElementById("timer").innerText="Time Left: "+minutes+" : " + seconds;
			
			time--;
			
			if(time<=0){ 
				clearInterval(timer);
				submitQuiz();}
			},1000);
			
			function submitQuiz()
			{
				let form=document.getElementById("quizForm");
				let data=new FormData(form);

				fetch("submit_quiz.php",{
				method:"POST",
				body:data
				})
				.then(res=>res.text())
				.then(data=>{
				window.location="result.php?attempt_id="+data
				//document.querySelector('input[name="attempt_id"]').value  used to forward the value of attempt_id to result.php
				});
			} 
			
			//to avoid timer to reset after refreshing th attempt page
			
			let endTime = Date.now() + time * 1000;
			localStorage.setItem("endTime", endTime);
			
			let savedEnd = localStorage.getItem("endTime");

			if(savedEnd){
				time = Math.floor((savedEnd - Date.now()) / 1000);
			}
			//for avoiding tab switching
			let warningCount = 0;

			document.addEventListener("visibilitychange", function(){
				if(document.hidden){
					warningCount++;

					alert("⚠️ Warning! Tab switching is not allowed.");

					if(warningCount >= 3){
						alert("Quiz terminated due to cheating.");
					}
				}
			});
			
				// For fullscreen mode
			function enterFullscreen(){
				if (!document.fullscreenElement) {
					document.documentElement.requestFullscreen();
				}
			}

			// trigger once
			document.addEventListener("click", enterFullscreen, { once: true });
			
			document.addEventListener("fullscreenchange", () => {

				if (!document.fullscreenElement) {
					alert("⚠️ Fullscreen exited!");

					warningCount++;

					if(warningCount >= 3){
						alert("❌ Quiz auto-submitted.");
						submitQuiz();
					} else {
						enterFullscreen(); // force back
					}
				}

			});
		
		//for avoiding  any right-click
			document.addEventListener("contextmenu", e => e.preventDefault());
			
			function updateProgress(){
			let total = document.querySelectorAll("#questionNo").length;
			let answered = document.querySelectorAll("input[type=radio]:checked").length;

			let percent = (answered / total) * 100;

			document.getElementById("progressFill").style.width = percent + "%";
			document.getElementById("progressText").innerText = Math.round(percent) + "% Completed";
		}
	
		//Prevents from copy-paste
		document.addEventListener("copy", e => e.preventDefault());
		document.addEventListener("paste", e => e.preventDefault());
		
		//Prevents from using keyboard shorcuts
		document.addEventListener("keydown", function(e){

		// Ctrl+C, Ctrl+V, Ctrl+U, Ctrl+Shift+I
		if(e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'u')){
		    e.preventDefault();
		}

		if(e.ctrlKey && e.shiftKey && e.key === 'I'){
		    e.preventDefault();
		}

		// F12
		if(e.key === "F12"){
		    e.preventDefault();
		}

	});
		// update the progress-bar after select answers of each question to keep track of how much answers are completed
		document.addEventListener("DOMContentLoaded", function(){
			document.querySelectorAll("input[type=radio]").forEach(el=>{
				el.addEventListener("change", updateProgress);
			});
		});
		</script>
	</body>
</html>
<?php } ?>
