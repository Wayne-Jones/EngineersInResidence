<link href="../css/style.css" rel="stylesheet" type="text/css" />
<?php
$mysqli = new mysqli("localhost", "root", "", "engineersinresidence");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

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
	if($stmt = $mysqli->prepare("SELECT DISTINCT eng_id, name, company, title, bio, website, imgURL FROM engineers")){
		$stmt->execute();
		$stmt->bind_result($id, $name, $company, $title, $bio, $website, $imgURL);
		while($stmt->fetch()){
			echo '<span class="peoplesection">
			<a href="javascript:void(0);" class="viewOverlay" rel='.$id.'>
			<img src='.$imgURL.' alt="Photo of: "'.$name.' width="180px" height="330px">
			<h1 class="peopleh1">'.$name.'</h1>
			<h2 class="peopleh2">'.$company.'</h2>
			<h3 class="peopleh3">'.$title.'</h3>
			</a>
			</span>
			';
			}
		$stmt->close();
		$mysqli->close();
	}
}
else{
	$userInput = explode(' ', $userInput);
	$arraySize = count($userInput);
	$findResult = false;
	if($arraySize == 1){
		//If user only entered one keyword, find the matching engineer(s) associated with that keyword
		if($stmt = $mysqli->prepare("SELECT DISTINCT eng_id, name, company, title, bio, website, imgURL FROM engineers NATURAL JOIN keywords WHERE keyword LIKE ?")){
			$param="%".$userInput[0]."%";
			$stmt->bind_param('s', $param); 
			$stmt->execute();
			$stmt->bind_result($id, $name, $company, $title, $bio, $website, $imgURL);
			while($stmt->fetch()){
				$findResult=true;
				//If there is a result, display the results
				echo '<span class="peoplesection">
				<a href="javascript:void(0);" class="viewOverlay" rel='.$id.'>
				<img src='.$imgURL.' alt="Photo of: "'.$name.'>
				<h1 class="peopleh1">'.$name.'</h1>
				<h2 class="peopleh2">'.$company.'</h2>
				<h3 class="peopleh3">'.$title.'</h3>
				</a>
				</span>';
				}
			$stmt->close();
		}
		if($findResult==false){
			//If there are no results, display no found message and display all
			if($stmt = $mysqli->prepare("SELECT DISTINCT eng_id, name, company, title, bio, website, imgURL FROM engineers")){
			$stmt->execute();
			$stmt->bind_result($id, $name, $company, $title, $bio, $website, $imgURL);
			echo '<p>No results were found with your input.</p>';
			while($stmt->fetch()){
				echo '<span class="peoplesection">
				<a href="javascript:void(0);" class="viewOverlay" rel='.$id.'>
				<img src='.$imgURL.' alt="Photo of: "'.$name.' width="180px" height="330px">
				<h1 class="peopleh1">'.$name.'</h1>
				<h2 class="peopleh2">'.$company.'</h2>
				<h3 class="peopleh3">'.$title.'</h3>
				</a>
				</span>
				';
				}
			$stmt->close();
			}
		}
		$mysqli->close();
	}
	else{
		//If user enters more than one keyword create the query based on number of keywords and return the matching engineer(s) associated with those keywords
		$query = "SELECT DISTINCT eng_id, name, company, title, bio, website, imgURL FROM engineers NATURAL JOIN keywords WHERE keyword LIKE ?";
		for($i=1; $i<$arraySize;$i++){
			$query .=" OR keyword LIKE ?";	
		}
		$findResult = false;
		if($stmt = $mysqli->prepare($query)){
			for($i=0;$i<$arraySize;$i++){
				$userInput[$i]="%".$userInput[$i]."%";
			}
			$userInput = array_merge(array(str_repeat('s', $arraySize)), array_values($userInput));
			call_user_func_array((array(&$stmt, 'bind_param')), refValues($userInput));
			$stmt->execute();
			$stmt->bind_result($id, $name, $company, $title, $bio, $website, $imgURL);
			while($stmt->fetch()){
				//If there is a result, display the results
				$findResult=true;
				echo '<span class="peoplesection">
				<a href="javascript:void(0);" class="viewOverlay" rel='.$id.'>
				<img src='.$imgURL.' alt="Photo of: "'.$name.'>
				<h1 class="peopleh1">'.$name.'</h1>
				<h2 class="peopleh2">'.$company.'</h2>
				<h3 class="peopleh3">'.$title.'</h3>
				</a>
				</span>';
				}
			$stmt->close();
		}
		if($findResult==false){
			//If there are no results, display no found message and display all
			if($stmt = $mysqli->prepare("SELECT DISTINCT eng_id, name, company, title, bio, website, imgURL FROM engineers")){
			$stmt->execute();
			$stmt->bind_result($id, $name, $company, $title, $bio, $website, $imgURL);
			echo '<p>No results were found with your input.</p>';
			while($stmt->fetch()){
				echo '<span class="peoplesection">
				<a href="javascript:void(0);" class="viewOverlay" rel='.$id.'>
				<img src='.$imgURL.' alt="Photo of: "'.$name.' width="180px" height="330px">
				<h1 class="peopleh1">'.$name.'</h1>
				<h2 class="peopleh2">'.$company.'</h2>
				<h3 class="peopleh3">'.$title.'</h3>
				</a>
				</span>
				';
				}
			$stmt->close();
			}
		}
		$mysqli->close();
	}
}
?>
