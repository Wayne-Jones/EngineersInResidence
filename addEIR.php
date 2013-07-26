<?php
require "php/db.php";
require "php/WideImage/lib/wideimage.php";
session_start();

if(!isset($_SESSION['user'])){
	header("Location: dashboard.html");
	exit();
}

$name = $_POST['name'];
$company = $_POST['company'];
$title = $_POST['title'];
$website = $_POST['website'];
$keywords = $_POST['keywords'];
$bio = $_POST['bio'];
if(!is_uploaded_file($_FILES["file"]['tmp_name'])){
	//No File has been submitted, Insert default image as profile pic
	$imgPath = "images/bioPics/default_avatar.png";
	//Insert EIR Info into the database
	if($stmt = $mysqli->prepare("INSERT INTO engineers(name, company, title, bio, website, imgURL) VALUES (?, ?, ?, ?, ?, ?)")){
		$stmt->bind_param("ssssss", $name, $company, $title, $bio, $website, $imgPath);
		$stmt->execute();
		$stmt->close();
	}
	$engID="";
	//Fetch the eng_id that you have entered
	if($stmt = $mysqli->prepare("SELECT eng_id FROM engineers WHERE name = ? AND company = ? AND title = ?")){
		$stmt->bind_param("sss", $name, $company, $title); //Reduces the chance of two people having the same name but different IDs, safety measures
		$stmt->execute();
		$stmt->bind_result($engID);
		$stmt->fetch();
		$stmt->close();
	}
	//Insert keywords and attach it to that eng_id
	$keywordArr = explode(",", $keywords);
	for($i=0; $i<count($keywordArr); $i++){
		$keywordArr[$i]=trim($keywordArr[$i]);
	}
	foreach($keywordArr as $key => $keyword){
		if($stmt = $mysqli->prepare("INSERT INTO keywords(eng_id, Name, keyword) VALUES (?, ?, ?)")){
			$stmt->bind_param("iss", $engID, $name, $keyword);
			$stmt->execute();
			$stmt->close();
		}
	}
	$EIRInserted=$name." has been inserted into the database";
	$_SESSION["EIRInserted"]=$EIRInserted;
	header("Location: dashboard.html");
	exit();
}
else{
	//Upload Image File Script
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$tmp = explode(".", $_FILES["file"]["name"]);
	$extension = end($tmp);
	if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 4000000) && in_array($extension, $allowedExts))
	{
		$imgPath="";
		//Checks to see if the file is the correct type, has the correct extension, and checks to see if it's under 4MB
		if ($_FILES["file"]["error"] > 0)
		{
			$fileError = "There was an error uploading your file";
			$_SESSION["fileError"]=$fileError;
			header("Location: dashboard.html");
			exit();
		}
		else
		{
			$imgPath = "images/bioPics/".$_FILES["file"]["name"];
			$imgPath = str_replace(' ', '_', $imgPath);	
			if (file_exists($imgPath))
			{
				//Checks to see if the file already exists
				$fileExists="The uploaded file already exists";
				$_SESSION["fileExists"]=$fileExists;
				header("Location: dashboard.html");
				exit();
			}
			else
			{
				$img = WideImage::loadFromUpload('file');
				$croppedImg = $img->crop("center", "center", 200, 200);
				$croppedImg->saveToFile($imgPath);
				//Move the temp file and save it to the server
				//move_uploaded_file($_FILES["file"]["tmp_name"], $imgPath);
			}
		}
		//Insert EIR Info into the database
		if($stmt = $mysqli->prepare("INSERT INTO engineers(name, company, title, bio, website, imgURL) VALUES (?, ?, ?, ?, ?, ?)")){
			$stmt->bind_param("ssssss", $name, $company, $title, $bio, $website, $imgPath);
			$stmt->execute();
			$stmt->close();
		}
		$engID="";
		//Fetch the eng_id that you have entered
		if($stmt = $mysqli->prepare("SELECT eng_id FROM engineers WHERE name = ? AND company = ? AND title = ?")){
			$stmt->bind_param("sss", $name, $company, $title); //Reduces the chance of two people having the same name but different IDs, safety measures
			$stmt->execute();
			$stmt->bind_result($engID);
			$stmt->fetch();
			$stmt->close();
		}
		//Insert keywords and attach it to that eng_id
		$keywordArr = explode(",", $keywords);
		for($i=0; $i<count($keywordArr); $i++){
			$keywordArr[$i]=trim($keywordArr[$i]);
		}
		foreach($keywordArr as $key => $keyword){
			if($stmt = $mysqli->prepare("INSERT INTO keywords(eng_id, Name, keyword) VALUES (?, ?, ?)")){
				$stmt->bind_param("iss", $engID, $name, $keyword);
				$stmt->execute();
				$stmt->close();
			}
		}
		$EIRInserted=$name." has been inserted into the database";
		$_SESSION["EIRInserted"]=$EIRInserted;
		header("Location: dashboard.html");
		exit();
	}
	else{
		//File does not meet any of the qualifications, display error
		$invalidFile = "Your file does not meet the specified qualifications";
		$_SESSION["invalidFile"]=$invalidFile;
		header("Location: dashboard.html");
		exit();
	}
}
?>