<link href="../css/style.css" rel="stylesheet" type="text/css" />
<?php
require "db.php";

function refValues($arr){ 
    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+ 
    { 
        $refs = array(); 
        foreach($arr as $key => $value) 
            $refs[$key] = &$arr[$key]; 
        return $refs;
    } 
    return $arr; 
}

$userInput=$_POST['value'];
if($userInput==''){
	//If userInput is blank (user just pressed enter or loadResidents is called) then display all
	//Check Faculty Table
	if($stmt = $mysqli->prepare("SELECT DISTINCT fac_id, name, company, title, bio, website, imgURL FROM faculty")){
		$stmt->execute();
		$stmt->bind_result($facID, $name, $company, $title, $bio, $website, $imgURL);
		while($stmt->fetch()){
			echo '<span class="facultysection">
			<a href="javascript:void(0);" class="viewFacultyOverlay" rel='.$facID.'>
			<img src='.$imgURL.' alt="Photo of: "'.$name.'>
			<h1 class="peopleh1">'.$name.'</h1>
			</a>
			</span>
			';
			}
		$stmt->close();
		$mysqli->close();
	}
}
else{
	$userInput = explode(',', $userInput);
	$arraySize = count($userInput);
	for($i=0; $i<$arraySize; $i++){
		$userInput[$i]=trim($userInput[$i]);
	}
	$findResult = false;
	if($arraySize == 1){
		//If user only entered one keyword, find the matching engineer(s) associated with that keyword
		//Check Faculty Table
		if($stmt = $mysqli->prepare("SELECT DISTINCT fac_id, name, company, title, bio, website, imgURL FROM faculty NATURAL JOIN keywords_faculty WHERE keyword LIKE ?")){
			$param="%".$userInput[0]."%";
			$stmt->bind_param('s', $param); 
			$stmt->execute();
			$stmt->bind_result($facID, $name, $company, $title, $bio, $website, $imgURL);
			while($stmt->fetch()){
				$findResult=true;
				//If there is a result, display the results
				echo '<span class="facultysection">
				<a href="javascript:void(0);" class="viewFacultyOverlay" rel='.$facID.'>
				<img src='.$imgURL.' alt="Photo of: "'.$name.'>
				<h1 class="peopleh1">'.$name.'</h1>
				</a>
				</span>';
				}
			$stmt->close();
		}
		if($findResult==false){
			//If there are no results, display no found message and display all
			echo '<p style="margin-left:40px">No NYU Poly Faculty Members were found with your keyword input.</p>';
		}
		$mysqli->close();
	}
	else{
		//If user enters more than one keyword create the query based on number of keywords and return the matching engineer(s) associated with those keywords
		//Check Faculty Table
		$query = "SELECT DISTINCT fac_id, name, company, title, bio, website, imgURL FROM faculty NATURAL JOIN keywords_faculty WHERE keyword LIKE ?";
		for($i=1; $i<$arraySize;$i++){
			$query .=" OR keyword LIKE ?";	
		}
		if($stmt = $mysqli->prepare($query)){
			for($i=0;$i<$arraySize;$i++){
				$userInput[$i]="%".$userInput[$i]."%";
			}
			$userInput = array_merge(array(str_repeat('s', $arraySize)), array_values($userInput));
			call_user_func_array((array(&$stmt, 'bind_param')), refValues($userInput));
			$stmt->execute();
			$stmt->bind_result($facID, $name, $company, $title, $bio, $website, $imgURL);
			while($stmt->fetch()){
				//If there is a result, display the results
				$findResult = true;
				echo '<span class="facultysection">
				<a href="javascript:void(0);" class="viewFacultyOverlay" rel='.$facID.'>
				<img src='.$imgURL.' alt="Photo of: "'.$name.'>
				<h1 class="peopleh1">'.$name.'</h1>
				</a>
				</span>';
				}
			$stmt->close();
		}
		if($findResult==false){
			//If there are no results, display no found message
			echo '<p style="margin-left:40px">No NYU Poly Faculty Members were found with your keyword input.</p>';
		}
		$mysqli->close();
	}
}
?>
	