<?php
include "../auth.php";
include "../db.php";

checkRole('mentor');

$mentor_id=$_SESSION['user_id'];

$query="SELECT * FROM quiz WHERE mentor_id=$1";
$result=pg_query_params($con,$query,array($mentor_id));

?>
<body>
<h3>Your Quiz</h3>

<table border="1">

<tr>
<th>ID</th>
<th>Title</th>
<th>Duration</th>
<th>Action</th>
</tr>

<?php
while($row=pg_fetch_assoc($result)){
?>

<tr>

<td><?php echo $row['quiz_id']; ?></td>
<td><?php echo $row['title']; ?></td>
<td><?php echo $row['duration']; ?></td>

<td>
<a href="add_question.php?quiz_id=<?php echo $row['quiz_id']; ?>">
Add Question
</a>
<button onclick="deleteQuiz(<?php echo $row['quiz_id']; ?>)">
Delete
</button>
</td>
</tr>

<?php
}
?>

</table>
<script>
function deleteQuiz(id)
{
    if(!confirm("Are you sure you want to delete this quiz?"))
        return;

    fetch("delete_quiz.php?id="+id)
    .then(response=>response.text())
    .then(data=>{
        alert(data);
        location.reload();   // refresh table
    });
}
</script>
</body>
