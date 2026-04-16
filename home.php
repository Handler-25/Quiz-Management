<html>
<head>
<title>Dashboard</title>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.wrapper {
    display: flex;
    height: 100vh;
}

.sidebar {
    width: 250px;
    background: #2c3e50;
    color: white;
    padding: 20px;
}

.sidebar a {
    color: white;
    display: block;
    padding: 10px 0;
    text-decoration: none;
}

.sidebar a:hover {
    background: #34495e;
}

.main {
    flex: 1;
    background: #f4f6f9;
}

.topbar {
    background: #34495e;
    color: white;
    padding: 15px;
    text-align: right;
}

.content {
    padding: 30px;
}
</style>
</head>

<body>

<div class="wrapper">
    <div class="sidebar">
        <h4 style="text-align: center;">Create New Exercise</h4>
        <hr>
        <a href="#">My Home</a>
        <a href="#">Library</a>
        <a href="#">Exercises</a>
        <a href="#">Analytics</a>
    </div>
<?php
	session_start();

	$con=pg_connect("host=localhost dbname=project user=postgres password=postgres123") or die("Could not Connect");
	if (pg_connection_status($con) === PGSQL_CONNECTION_OK ){
	
	echo "
    <div class=main>

        <div class=topbar>
            Welcome ."Tony!
        </div>

        <div class="content">

            <h3>My Home</h3>
            <div class="card mb-4">
                <div class="card-header">
                    Recently Assigned
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Exercise Title</th>
                                <th>Date Assigned</th>
                                <th>% Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Exercise 1</td>
                                <td>January 1, 2016</td>
                                <td>90%</td>
                            </tr>
                            <tr>
                                <td>Exercise 2</td>
                                <td>March 23, 2016</td>
                                <td>8%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Draft Exercises
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Exercise Title</th>
                                <th>Date Created</th>
                                <th>% Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Exercise 1</td>
                                <td>January 1, 2016</td>
                                <td>90%</td>
                            </tr>
                            <tr>
                                <td>Exercise 2</td>
                                <td>March 23, 2016</td>
                                <td>8%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>

</body>
</html>
