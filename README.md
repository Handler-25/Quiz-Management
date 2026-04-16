*Overview*

The Online Quiz Management and Monitoring System is a web-based application designed to conduct, manage, and monitor quizzes efficiently. It provides a secure platform for teachers to create quizzes and evaluate student performance, while ensuring fair assessment through monitoring features.

Module Details:
1.Teacher / Mentor Module
 - Create, update, and delete quizzes
 - Add and manage questions
 - View student attempts and scores
 - Identify students who have attempted or not attempted quizzes
 - Analyze results with score and performance details

2.Student Module
 - Register and login securely
 - Attempt quizzes within a specified time
 - View results after submission
 - Automatic submission if time expires

***Monitoring & Security Features***
 - Detect if a student leaves the quiz window (basic anti-cheating)
 - Auto-submit quiz on suspicious activity
 - Prevent multiple attempts (if configured)

***Result & Evaluation System***
 - Automatic score calculation
 - Rank generation based on performance
 - Displays score, percentage

***Technologies Used:***
o Frontend: HTML, CSS, JavaScript, Bootstrap
o Backend: PHP
o Database: PostgresSQL

*Project Structure*
1) admin/ or mentor/ – Teacher functionalities
2) student/ – Student interface
- db.php – Database connection
- auth.php – Authentication system
