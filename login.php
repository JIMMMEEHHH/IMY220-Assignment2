<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	//$pic = isset($_POST["picToUpload[]"]) ? $_POST["picToUpload[]"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="James Lake">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				//$pic = isset($_POST["picToUpload"]) ? $_POST["picToUpload"] : false;	
				//echo $pic;
				$numFiles = 0;
				if(isset($_FILES["picToUpload"])){
					$uploadFile = $_FILES["picToUpload"];
					$numFiles = count($uploadFile["name"]);
				}
				

				
				if($row = mysqli_fetch_array($res)){
					if($numFiles){
						//echo $numFiles;
					for($i = 0; $i < $numFiles; $i++){
						if(($uploadFile["type"][$i] == "image/jpeg" || $uploadFile["type"][$i] == "image/jpg") && $uploadFile["size"][$i] < 10000000){
							if($uploadFile["error"][$i] > 0){
								echo "error: " . $uploadFile["error"][$i] . "<br/>";
							}else{
								
									move_uploaded_file($uploadFile["tmp_name"][$i], "gallery/" . $uploadFile["name"][$i]);
									//$servername = "localhost";
									//$username = "root";
									//$password = "";
									//$db = "tbgallery";
									//$conn = mysqli_connect($servername, $username, $password, $db);
									//$user = $row[user_id];
									$sql = "INSERT INTO tbgallery (image_id, user_id, filename)
										VALUES('0','" . $row['user_id'] ."',
										'" . $uploadFile['name'][$i] ."')";
										if(mysqli_query($mysqli, $sql)){
											//echo "done";
										}else{
											//echo "fuck";
										}
									
											
							}
						}else{
							echo "invalid file" . $uploadFile["name"][$i];
						}
				}
			}
					
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form action='login.php' method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload[]' id='picToUpload' multiple = 'multiple' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									<input type = 'hidden' id = 'loginEmail' name = 'loginEmail' value='$email' />
									<input type = 'hidden' id = 'loginPass' name = 'loginPass' value='$pass' />
								</div>
						  	</form>";

					echo '<h1>Image Gallery</h1>
							<div class = "row imageGallery">';
								$user = $row['user_id'];
								$getGal = "SELECT filename FROM tbgallery WHERE user_id = '$user'";
								$resG = mysqli_query($mysqli, $getGal);
								if($resG->num_rows > 0){
									while($gRow = $resG->fetch_assoc()){
										echo '<div class = "col-3" style="background-image: url(gallery/'. $gRow["filename"] .')"></div>';
									}
								}
								
							echo '</div>';
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>