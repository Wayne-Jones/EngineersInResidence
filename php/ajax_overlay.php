<link href="../css/style.css" rel="stylesheet" type="text/css" />
<?php
require "db.php";

//Load HTML overlay code of all engineers
if($stmt = $mysqli->prepare("SELECT DISTINCT eng_id, name, company, title, bio, website, imgURL FROM engineers")){
	$stmt->execute();
	$stmt->bind_result($engID, $name, $company, $title, $bio, $website, $imgURL);
	while($stmt->fetch()){
		echo '<span class="simpleOverlay" id="Bio'.$engID.'">
		<div class="overlayInner">
			<img src='.$imgURL.' alt="Photo of: "'.$name.' width="180px" height="330px">
			<div class="bioInfo">
				<a href="javascript:void(0);" class="closeButton">Close</a>
				<h3>'.$name.'</h3><br/>
				<h4>'.$company.'</h4>
				<h4>'.$title.'</h4>
				<a href="'.$website.'">'.$website.'</a><br/></div>
				<div class="bio"> 
				<p>'.$bio.'</p>
				</div>
				
		</div>
		</span>';	
	}
	$stmt->close();
}
if($stmt = $mysqli->prepare("SELECT DISTINCT fac_id, name, company, title, bio, website, imgURL FROM faculty")){
	$stmt->execute();
	$stmt->bind_result($facID, $name, $company, $title, $bio, $website, $imgURL);
	while($stmt->fetch()){
		echo '<span class="simpleOverlay" id="BioFac'.$facID.'">
		<div class="overlayInner">
			<img src='.$imgURL.' alt="Photo of: "'.$name.' width="180px" height="330px">
			<div class="bioInfo">
				<a href="javascript:void(0);" class="closeButton">Close</a>
				<h3>'.$name.'</h3><br/>
				<h4>'.$company.'</h4>
				<h4>'.$title.'</h4>
				<a href="'.$website.'">'.$website.'</a><br/></div>
				<div class="bio"> 
				<p>'.$bio.'</p>
				</div>
				
		</div>
		</span>';	
	}
	$stmt->close();
}
$mysqli->close();
?>