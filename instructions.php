

<?php 

session_start();

$url = "/quiz";

if(isset($_GET['quiz_id']) && !empty($_GET['quiz_id'])) {
	$quiz_id = $_GET['quiz_id'];

	$servername = "localhost";
	$admin = "root";
	$password = "";
	$dbname = "quiz_system";



	$conn = new mysqli($servername, $admin, $password, $dbname);

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
		header('Location: '.$url);
	} 

	$sql = "SELECT quizes.name, quizes.time, lkp_category.name AS category_name FROM quizes INNER JOIN lkp_category ON quizes.category_id=lkp_category.id WHERE quizes.id = '$quiz_id'";

	$result = $conn->query($sql);


	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$quiz_name = $row['name'];
			$category_name = $row['category_name'];
			$time = $row['time'];
		}
	}

	else
	{
		header('Location: '.$url);
	}
}

else
{
	header('Location: '.$url);
}

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Quiz Page</title>

	<link rel ="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-page">
  <div class="form" id="quiz-form" >
    <form class="login-form" action="admin.php" method="post">
		<h2>
			So you want to try your luck at the QUIZ !
		</h2>      
		<div style="text-align:left;">
			<strong>Here are some rules:</strong>
			<li>You can not take the same quiz twice</li>
			<li>You can not proceed without entering the quiz code</li>
			<li>Quiz name: <?php echo $quiz_name ?></li>
			<li>Quiz category: <?php echo $category_name ?></li>
			<li>Quiz duration: <?php echo $time ?></li>
		</div>
		<br/>
		<input type="text" placeholder="Enter your quiz code here" name="quiz_code"/>

		<button>Start the QUIZ</button>
    </form>
  </div>
</div>

</body>
</html>
