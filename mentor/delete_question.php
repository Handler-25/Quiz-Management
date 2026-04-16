<?php
include "../auth.php";
include "../db.php";

checkRole('mentor');

$id=$_GET['id'] ?? null;

if(!$id){
exit("Quiz ID missing");
}

$query="DELETE FROM question WHERE question_id=$1";

$result=pg_query_params($con,$query,array($id));

if(!$result){
echo pg_last_error($con);
}else{
echo "Deleted";
}
?>

