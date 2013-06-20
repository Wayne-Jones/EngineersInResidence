<?php
require 'db.php';
session_start();
//if the user is already logged in, redirect them back to homepage
	if(isset($_SESSION["user"])) {
		header("Location: ../index.html");
		exit();
	}
	else {
		//if the user have entered both entries in the form, check if they exist in the database
		if((isset($_POST["email"]) && isset($_POST["password"]) && $_POST["email"]!=NULL && $_POST["password"]!=NULL)) {
			//check if entry exists in database
			$stmt=$mysqli->prepare("Select email from users where email= ? and password= ?");
				$password = md5($_POST["password"]);
				$email = $_POST["email"];
				$stmt->bind_param("ss", $email, $password);
				if($stmt->execute()){
					$stmt->bind_result($user);
					$stmt->fetch();
					if($user){
						$_SESSION["user"]=$user;
						$_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];
						$stmt->close();
						$mysqli->close();
						header("Location: ../index.html");
						exit();
					}
					else{
						//Username or password is incorrect
						$error = "Your username or password is incorrect. Please try again";
						$_SESSION["loginError"]=$error;
						$stmt->close();
						$mysqli->close();
						header("Location: ../index.html");
						exit();
					}
				}
				else{
					//There is a connection issue with the database
					$stmt->close();
					$mysqli->close();
					header("Location: ../index.html");
					exit();
				}
				
			}
		else{
			//The input values was not set
			header("Location: ../index.html");
			exit();
		}
		
	}
?>