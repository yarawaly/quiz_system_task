<?php 
session_start();


$servername = "localhost";
$admin = "root";
$password = "";
$dbname = "quiz_system";
$url = "/quiz";


$conn = new mysqli($servername, $admin, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header('Location: '.$url);
} 

// Login
if (isset($_POST["username"]) && !empty($_POST["username"])) {

	$username = $_POST["username"];
	$password = $_POST["password"];
	
	$_SESSION["username"] = $username;

	$sql = "SELECT password FROM users WHERE username = '$username'";

	$result = $conn->query($sql);


	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	if ($password == $row["password"])
	    	{
	    		$_SESSION["isAdmin"] = 1;
 	    	}
	    	else
	    	{
	    		$_SESSION["isAdmin"] = 0;
	    		$_SESSION["wrongPWD"] = 1;
	    		header('Location: '.$url);
	    	}
	    }
	} else {
		$_SESSION["isAdmin"] = 0;
		$_SESSION["wrongUSER"] = 1;
		$url = "/quiz";
		header('Location: '.$url);
	    //echo "Username not found.";
	}

}



else if(isset($_SESSION['isAdmin']) && !empty($_SESSION['isAdmin'])) {

	if ($_SESSION['isAdmin']!=1)
	{
		header('Location: '.$url);
	}

	else 
	{
		// Admin Registration
		if (isset($_POST["username_reg"]) && !empty($_POST["username_reg"])) {
			$email = $_POST["email_reg"];
			$username = $_POST["username_reg"]; 
			$password = $_POST["password_reg"];

			$sql = "SELECT username FROM users WHERE username = '$username'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				//problem
				echo "Duplicate admin username.";
			}
			else{
				$sql = "INSERT INTO `users` ( `username`, `email`, `password` ) VALUES ( '$username', '$email', '$password');";
				$result = $conn->query($sql);
				echo "Done adding new admin.";

			}
		}
		// ADD QUIZ
		if (isset($_POST["quiz_name"]) && !empty($_POST["quiz_name"])) {
			$names = $_POST["quiz_name"];
			$category=$_POST["question_category"];
		    $duration = $_POST["duration"];
		    $maxpoints = $_POST["Max_point"];
		    $passpoints = $_POST["pass_point"];

			$sql = "SELECT name FROM quizes WHERE name = '$names'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				//problem
				echo "Duplicate quiz.";
			}
			else{
				$sql = "INSERT INTO `quizes` (`id`, `name`, `category_id`, `time`, `total_points`, `pass_points`, `created_at`, `updated_at`) VALUES (NULL, '$names', '$category', '$duration', '$maxpoints', '$passpoints', CURRENT_TIMESTAMP, NULL);";
				$result = $conn->query($sql);
				echo "Done adding new quiz.";

			}
		}


		// update password 

		if (isset($_POST["new_pass"]) && !empty($_POST["new_pass"])){

			$newpassword = $_POST["new_pass"];
			$user = $_SESSION["username"] ;
			$sql= "UPDATE `users` SET `password`= '$newpassword' WHERE username = '$user' ; ";
			$result = $conn->query($sql);

		}


		//RESET ALL USERS

		if (isset($_POST["reset_all"]) && !empty($_POST["reset_all"])){

		$sql= "DELETE FROM `users`  ; ";
		$result = $conn->query($sql);

		$sql1=  "INSERT INTO `users` ( `username`, `password` ) VALUES ( 'admin',  '12345'); ";
		//$sql1=  "UPDATE `users` SET `username`= 'admin' , `password`= '12345'  ; ";
		$result1 = $conn->query($sql1);

		}
  		

  



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
 	<title>Admin Page</title>
 	<link rel = "stylesheet" href="css/navbar.css">
 	<link rel = "stylesheet" href="css/modal.css">
 	<link rel = "stylesheet" href="css/registration.css">



 </head>
 <body>
 

 <ul class="main-navigation">
  <li><a href='/quiz/admin.php'>Hello <?php echo $_SESSION["username"] ?></a></li>
  <li><a>Manage Questions</a>
  	<ul>
  		<li><a>Create a Question</a>
  		<ul>
  			<li><a id="btnAddQuestion">True/False</a></li>
  			<li><a id="btnMCQAddQuestion">Multiple Choice</a></li>
  		</ul>
  		</li>
  		<li><a>View All Questions</a></li>
  		<li><a>Edit a Question</a></li>
  		<li><a>Delete Some Questions</a></li>
  		<li><a>Delete All Questions</a></li>

  	</ul>
  </li>

  <li><a>Quiz Management</a>
  <ul>
  	<li><a id="quiz">+ Add New Quiz</a></li>
  	<?php





         $sql = "SELECT id, name FROM quizes";
	     $result = $conn->query($sql);
  		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	#$row["name"]=$quiz;
		    	$_SESSION["name"]=$row["name"];
		    	echo "<li><a>".$_SESSION["name"]."</a>" ;
		    	echo "
		    	<ul>
		    	<li><a>Quiz Settings</a></li>
		    	<li><a>Manage Questions</a>
		    	<ul>
		    		<li><a>View All Questions</a></li>
		    		<li><a>Edit a Question</a></li>
		    		<li><a>Delete Some Questions</a></li>
		    	  </ul>
		    	</li>
		    	<li><a>Results</a></li>
		    	</ul>

		    	</li>";
		    }
		}
		

  	?>
  </ul>
  </li>
  <li><a>Settings</a>
  <ul>
  
    <li><a href ="#" id="myBtn" >Register an admin</a>




     

  	<li><a id="pass">Change Password</a></li>
  	<li><a id="del_acc">Delete Account</a></li>
  	<li><a id="reset">Reset all Tables</a></li>
  	<li><a href="/quiz/index.php?logout=1">Logout</a></li>
  </li>
</ul>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>
	<br/>

	<!-- register new admin FORM-->

	<div class="form" id="form-reg">
		<form class="login-form" action="admin.php" method="post">
			<input type="email" placeholder="email" name="email_reg"/>
			<input type="text" placeholder="username" name="username_reg"/>
			<input type="password" placeholder="password" name="password_reg"/>
			<button id="popupmsg">REGISTER</button>
		</form>
	</div>

        <!-- ADD NEW TRUE/FALSE QUISTION FORM -->

	<div class="form" id="add-question">
		<form class="login-form" action="admin.php" method="post">
			<h3>True/False Question</h3>
			<textarea cols="40" rows="5" name="myname" placeholder="Type your question here."></textarea>
			<select name="question_category">
			  <option value="php">PHP</option>
			  <option value="JSE">Java SE</option>
			  <option value="mysql">MySQL</option>
			  <option value="python">Python</option>
			</select>
			<textarea cols="40" rows="5" name="code"  placeholder="Type your code here."></textarea>

			<input style="width:10%" type="radio" name="question_answer" value="true" checked/> True<br/>
			<input style="width:10%" type="radio" name="question_answer" value="false"/> False<br/>

			<button id="popupmsg">Add Question</button>
		</form>
	</div>

<!-- ADD NEW MC QUISTION FORM -->

	<div class="form" id="add-MCQquestion">
		<form class="login-form" action="admin.php" method="post">
			<h3>Multiple Choice Question</h3>
			<textarea cols="40" rows="5" name="myname" placeholder="Type your question here."></textarea>
			<select name="question_category">
			  <option value="php">PHP</option>
			  <option value="JSE">Java SE</option>
			  <option value="mysql">MySQL</option>
			  <option value="python">Python</option>
			</select>
			<textarea cols="40" rows="5" name="code"  placeholder="Type your code here."></textarea>

			<input  type="text" name="question_answer" placeholder="ANS1" /> <br/>
			<input  type="text" name="question_answer" placeholder="ANS2"/> <br/>
			<input  type="text" name="question_answer" placeholder="ANS3" /> <br/>
			<input  type="text" name="question_answer" placeholder="ANS4" /> <br/>

			<button id="popupmsg">Add Question</button>
		</form>
	</div>

    <!--ADD NEW QUIZ FORM -->

    <div class="form" id="qiuz-form">
		<form class="login-form" action="admin.php" method="POST">
			<input type="text" placeholder="Quiz Name" name="quiz_name"/>
			<select name="question_category">
			  <option value="1">PHP</option>
			  <option value="2">Java SE</option>
			  <option value="3">MySQL</option>
			  <option value="4">Python</option>
			</select>

			<input type="text" placeholder="Duration" name="duration"/>
			<input type="text" placeholder="Max points" name="Max_point"/>
			<input type="text" placeholder="PASS POINTS" name="pass_point"/>
			<button type="submit">ADD QUIZ</button>
		</form>
	</div>
	
	<!--CHANGE PASSWORD FORM -->

	<div class="form" id="pass_form">
		<form class="login-form" action="admin.php" method="post">
			<input type="password" placeholder="New Password" name="new_pass"/>
			<input type="password" placeholder="Repeat Your Password" name="new_pass"/>
			<button id="popupmsg"> CHANGE PASSWORD</button>
		</form>
	</div>
 
 <!--DELETE ACCOUNT CONFIRMATION  FORM -->

    <div class="form" id="del_acc_form" >
    	<form class="login-form" method="post" >
       		<h4>DO YOU WANT TO DELETE YOUR ACCCOUNT?</h4>
       		<p><button type="submit" action="/admin.php" >cancle</button></br></p>
       		<p><button type="submit" formaction ="index.php" name= "del_acc" value="del_acc">yes</button></br></p>
    	</form>
    </div>

    <!--RESET ALL TABLES CONFIRMATION  FORM -->

    <div class="form" id="reset_all_form" >
    	<form class="login-form" method="post" >
       		<h4>All Users will have the same username and password!</h4>
             <h4>ARE YOU SURE ?</h4>
       		<p><button type="submit" action="admin.php" >cancle</button></br></p>
       		<p><button type="submit" formaction ="admin.php" name= "reset_all" value="reset_all">yes</button></br></p>
    	</form>
    </div>

    </p>
  </div>


</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');
var btnReg = document.getElementById("myBtn");
var btnAddQuestion = document.getElementById("btnAddQuestion");
var btnAddMCQuestion = document.getElementById("btnMCQAddQuestion");
var btnquiz = document.getElementById("quiz");
var btnpass = document.getElementById("pass");
var btndelacc = document.getElementById("del_acc");
var btnresetall = document.getElementById("reset");


var span = document.getElementsByClassName("close")[0];
var reg_form = document.getElementById("form-reg");
var add_question_form = document.getElementById("add-question");
var add_MCquestion_form = document.getElementById("add-MCQquestion");
var quiz_form = document.getElementById("qiuz-form");
var pass_form = document.getElementById("pass_form");
var del_acc_form = document.getElementById("del_acc_form");
var reset_all_form = document.getElementById("reset_all_form");



// When the user clicks the button, open the modal 
btnReg.onclick = function() {
    modal.style.display = "block";
    reg_form.style.display = "block";
}

btnAddQuestion.onclick = function() {
    modal.style.display = "block";
    add_question_form.style.display = "block";
}

btnAddMCQuestion.onclick = function() {
    modal.style.display = "block";
    add_MCquestion_form.style.display = "block";
}

btnquiz.onclick = function() {
    modal.style.display = "block";
    quiz_form.style.display = "block";
}

btnpass.onclick = function() {
    modal.style.display = "block";
    pass_form.style.display = "block";
}
btndelacc.onclick = function() {
    modal.style.display = "block";
    del_acc_form.style.display = "block";
}

btnresetall.onclick = function() {
    modal.style.display = "block";
    reset_all_form.style.display = "block";
}
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
    reg_form.style.display = "none";
    add_question_form.style.display = "none";
    add_MCquestion_form.style.display = "none";
    quiz_form.style.display = "none";
    pass_form.style.display = "none";
    del_acc_form.style.display = "none";
    reset_all_form.style.display = "none";

}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
         modal.style.display = "none";
        reg_form.style.display = "none";
        add_question_form.style.display = "none";
        add_MCquestion_form.style.display = "none";
        quiz_form.style.display = "none";
        pass_form.style.display = "none";
        del_acc_form.style.display = "none";
        reset_all_form.style.display = "none";
    }
}
</script>

 </body>
 </html>


 <?php 


$conn->close();

 ?>