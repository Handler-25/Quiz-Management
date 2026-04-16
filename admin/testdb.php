<?php
$con = pg_connect("host=localhost dbname=project user=postgres password=postgres123");

$password ="Malti_Admin10";

$sql = "SELECT password FROM users WHERE username = $1";
$result = pg_query_params($con, $sql, array($password));

$row = pg_fetch_assoc($result);
$hashedPassword = $row['password'];

if(password_verify($password, $hashedPassword)){
    echo "Login successful";
} else {
    echo "Invalid password";
}
pg_close($con);
?>


