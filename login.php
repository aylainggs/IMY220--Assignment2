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
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false

	$query = "SELECT user_id FROM tbusers WHERE email = '$email' AND password ='$pass'";//getting user id 
	$result = mysqli_query($mysqli,$query);

	if ($row = mysqli_fetch_array($result)) 
	{
		$user_id = $row['user_id'];

	}

	

	if(isset($_POST["submitnew"]))
	{
		
		$target_dir = "gallery/"; //directoryof files to placed 
		$uploadFile = $_FILES['picToUpload'];//file being uploaded
		$target_file = $target_dir . basename($uploadFile["name"]); // path of file to be uploaded
		$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);//holds file extnesion type
		//print_r($uploadFile);
		$fileSize = ($uploadFile["size"]/1024);//file size in kb 
		//echo $fileSize;
		$imageTypeAllowed = array('jpeg', 'jpg');// files types allowed to be uploaded
		$fileSizeAllowed = 1024;//1MB in kb
		$fileName = $uploadFile["name"];//file name and extension
		$fileNameTemp = $uploadFile["tmp_name"];//temp file name and extension



		if (in_array($imageFileType, $imageTypeAllowed)) //checks file type is correct
		{
		   
		   if ($fileSize<=$fileSizeAllowed) //checks if file size is smaller than 1MB
			{
			
				
					move_uploaded_file($fileNameTemp,"gallery/" . $fileName);
					
					$queryImg= "INSERT INTO tbgallery (user_id,filename) 
					VALUES ('$user_id',
					'$fileName')";

					if(mysqli_query($mysqli,$queryImg ))
					{
    				echo '<div class="alert alert-success mt-3" role="alert"> Saved to database</div>';
					} 
					else{
    				echo "ERROR: Could not able to execute $mysqli. " . mysqli_error($mysqli);
					}
				
					
				
			}
			else
			{
				echo '<div class="alert alert-danger mt-3" role="alert">
	  							Please chose an image with a file size smaller than 1MB.
	  						</div>';
			}

			
		
		}

		else
		{

			 echo '<div class="alert alert-danger mt-3" role="alert">
			 Please choose an image that has the file extension of .jpg or .jpeg only </div> ';

		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Name Surname">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
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


				
					echo 	"<form action='login.php' method='POST' enctype='multipart/form-data'>
								<div class='form-group'>
								<input type='hidden' name='loginEmail' value='".$email."' />
								<input type='hidden' name='loginPass' value='".$pass."' />

								<input type='file' class='form-control' name='picToUpload' id='picToUpload' />
								<br/>
								<input type='submit' class='btn btn-standard' value='Upload Image' name='submitnew' />

								</div>
						  	</form>";
						  	 /*

							 $retrieveImgs = "SELECT * FROM tbgallery WHERE user_id = '$user_id'";
							 $galleryRes = mysqli_query($mysqli,$retrieveImgs);
							 $row_count=mysqli_num_rows($galleryRes);//fetches the all the rows of images 
							 if ($idrow = mysqli_fetch_array($galleryRes)) 
								{
									$image_id=0;

								}
														 

							
							
							for ($count=1; $count < $row_count ; $count++) 
							 	{ 
							 		$retrieveImgID = "SELECT filename  FROM tbgallery WHERE user_id = '$user_id' AND  image_id ";
							 		$idRes = mysqli_query($mysqli,$retrieveImgID);
							 		$image_id = $idrow['image_id'];*/


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

	<div class="container">
		<h2>Image Gallery</h2>
		<div class='row imageGallery'>
			<?php  
				$fileQuery = " SELECT filename FROM tbgallery WHERE user_id = '$user_id'";
				$file_res = mysqli_query($mysqli,$fileQuery);
				while ($picture = mysqli_fetch_array($file_res)) 
				{
					echo "<div class='col-3' style='background-image: url(gallery/".$picture['filename'].")'>
			</div>";
				}




			?>
			
		</div>
		
	</div>
</body>
</html>