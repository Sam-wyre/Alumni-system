<?php 
	$con = mysqli_connect('localhost','root','','tracerdata') or die("ERROR");
	if (isset($_POST['submit-signup_student']))
	{
		// Define $username and $password
		$student_number = $_POST['student_number'];
		$password = $_POST['password'];
		$re_password = $_POST['re_password'];
		$secret_question = $_POST['secret_question'];
		$secret_answer = $_POST['secret_answer'];
		
		
		// To protect MySQL injection for Security purpose
		$student_number = stripslashes($student_number);
		$password = stripslashes($password);
		$re_password = stripslashes($re_password);
		$secret_question = stripslashes($secret_question);
		$secret_answer = stripslashes($secret_answer);
		$student_number = mysqli_real_escape_string($con,$student_number);
		$password = mysqli_real_escape_string($con,$password);
		$re_password = mysqli_real_escape_string($con,$re_password);
		$secret_question = mysqli_real_escape_string($con,$secret_question);
		$secret_answer = mysqli_real_escape_string($con,$secret_answer);

 		//if password submit is equal the verify query perform
		if ($password == $re_password)
		{
			// if student has record perform add query
	
				// if account is not register perform register statement
		 			#$input = "$password";
		 			#include('../md5.php');
					$result = mysqli_query($con,"INSERT INTO `user_account` (user_level, user_name, user_password) VALUES ('1','$student_number','$password')");
					// geting the last insert created account
					$last_id = mysqli_insert_id($con);
					echo "<script>alert('Account created Successfully');
											window.location='../index.php';
										</script>";
				
			
		}
		// if password not match echo notmatch!
		else
		{
			echo "<script>alert('Password Not match');
											window.location='../index.php';
										</script>";
		}
		
	}
	if (isset($POST['submit-signup_teacher'])) {

	}

	if (isset($POST['submit-signup_admin'])) {
	}
?>