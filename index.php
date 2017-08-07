<?php 
if (!isset($_SESSION))
  {
    
    session_start();


$servername = "localhost";
$admin = "root";
$password = "";
$dbname = "quiz_system";


$conn = new mysqli($servername, $admin, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully 	";




if (isset($_POST["del_acc"]) && !empty($_POST["del_acc"])){

$delacc= $_POST["del_acc"];
$user = $_SESSION["username"] ;
 $sql= "DELETE FROM `users`  WHERE username = '$user' ; ";
 $result = $conn->query($sql);

}





    
  }





 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>

	<link rel ="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-page">
  <div class="form">
    <form class="login-form" action="admin.php" method="post">
    	<h3>
	    	<?php if(isset($_SESSION['wrongPWD']) && !empty($_SESSION['wrongPWD'])) {
			   echo 'Wrong password.';
			   session_destroy();

			}

			if(isset($_SESSION['wrongUSER']) && !empty($_SESSION['wrongUSER'])) {
			   echo 'Wrong username.';
			   session_destroy();
			}

			if(isset($_GET['logout']) && !empty($_GET['logout'])) {
				echo 'Successfull Logout.';
			   session_destroy();
			}

			?>
		</h3>
      <input type="text" placeholder="username" name="username"/>
      <input type="password" placeholder="password" name="password"/>
      <button>login</button>
    </form>
  </div>
</div>

</body>
</html>
