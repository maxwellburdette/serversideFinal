<!-- 
    File: form.php (Shows open jobs and lets you add more)
    Server Side Development / Project: Term Project
    Maxwell Burdette / burdettm@csp.edu
    04/23/2021
 -->
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title></title>
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link
			href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
			rel="stylesheet"
			integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
			crossorigin="anonymous"
		/>
		<link
			href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300&display=swap"
			rel="stylesheet"
		/>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<nav>
			<h1 class="home py-4" style="font-size: 1.8em"><a href="./index.html">JobFinder</a></h1>
		</nav>
		<?PHP

			function input($value, $id)
			{

				echo '<div class="form-floating m-1">';
					echo '<input
						type="text"
						name="'.$id.'"
						class="form-control"
						placeholder="placeholder"
						id = "'.$id.'"
						required
					/>';
					echo '<label for="floatingInput" class="form-label">'.$value.'</label>';
				echo '</div>';
			}
		?>
		<?PHP
			// Set up connection constants
			define("SERVER_NAME","localhost");
			define("DBF_USER_NAME", "root");
			define("DBF_PASSWORD", "mysql");
			define("DATABASE_NAME", "jobfinder");
			// Using default username and password for AMPPS  

			// Create connection object
			$conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$conn->select_db(DATABASE_NAME);
			//Start tracking session variables
			session_start( );
			if(array_key_exists('hidSubmitFlag', $_POST))
			{
 				// Look at the hidden submitFlag variable to determine what to do
 				//$submitFlag = $_POST['hidSubmitFlag'];
				$job = $_POST['job'];
				$description = $_POST['description'];
				$companyID = $_POST['employeeList'];
				
				//Call procedure
				$sql = "CALL insertJob(?, ?, ?)";
				
				//Query procedure using prepared statement
				if($stmt = $conn->prepare($sql))
				{
					$stmt->bind_param("ssi", $job, $description, $companyID) ;
					if($stmt->errno) {
						echo "stmt prepare( ) had error."; 
					}
					// Execute the query
					$stmt->execute();
					if($stmt->errno) {
						echo "Could not execute prepared statement";
					}
					else
					{

						$_SESSION['jobTitle'] = urlencode(serialize($job));
						
					}
					// Free results
					$stmt->free_result( );
					// Close the statement
					$stmt->close( );
				}


				if(array_key_exists('added',$_SESSION))
				{
					$jobAdded = urldecode(($_SESSION['jobTitle']));
					$decodedJob = unserialize($jobAdded);

					echo '<div class="shadow container-sm position-relative">';
					echo '<div class="position-absolute bottom-0 end-0">';
					echo '<h1 style="color: red">' .$decodedJob. ' added</h1>';
					echo '</div>';
					echo '</div>';
					
				}


			}

		?>
		<div class="shadow container-sm mt-5 mb-5 py-2 bg-white rounded">
			<h4 class="display-7" style="text-align: center">Select Company to Add Position</h4>
			
			
				<form
					method="POST"
 					name="postForm"
				>
				 	<select name="employeeList" size="1"
						class="form-select m-auto"
						aria-label="Default select example"
						style="width: 25%"
						required
					
					>
 						<?PHP
					 		$sql = "SELECT companyName, companyID FROM company";
					 		// $result = $conn->query($sql);
							echo '<option value=""></option>';
					 		
							//Prepared Statement for combobox data
							if($stmt = $conn->prepare($sql))
							{
								if($stmt->errno) {
									echo "stmt prepare( ) had error."; 
								}
								// Execute the query
								$stmt->execute();
								if($stmt->errno) {
									echo "Could not execute prepared statement";
								}
								$stmt->store_result( );

								$stmt->bind_result($name, $id);

								while($stmt->fetch())
								{
									echo "<option value='" . $id . "'>" . $name . "</option>";
								}
								// Free results
								$stmt->free_result( );
								// Close the statement
								$stmt->close( );
							}
 						?>
 					</select>	
					<?PHP
					
						echo '<div class="d-flex flex-wrap mb-2 pt-2 justify-content-center">';
						$title = "Job Title";
						$id = "job";
						input($title, $id);
						
						echo '</div>';
						echo '<div class="form-floating m-auto" style="width: 50%">';
  						echo '<textarea class="form-control" placeholder="Leave a comment here" name="description" id="description"
						  style="height: 200px; resize: none" required></textarea>';
  						echo'<label for="floatingTextarea">Job Description</label>';
						echo '</div>';
					?>
					<input type='hidden' name='hidSubmitFlag' id='hidSubmitFlag' value='01' />
 					<input class="btn-lg btn-secondary my-3 " type="submit" value="Submit" />	
					
				</form>
			
		</div>

		<h4 class="display-7 m-3" style="text-align: center; color: #fff">Procedure used when form is submitted</h4>	
		<img src="./graphics/PROCEDURE.png" class="rounded img-thumbnail mx-auto mb-5 d-block" alt="">
		<br />
		<br />
		<br />
		<br />
		


		<div
			class="buttonContainer position-fixed bottom-0 start-50 translate-middle-x mt-5"
		>
			<a class="readMeButton" href="./readMe.html">Read Me</a>
			<a class="readMeButton active" href="./form.php">Add Job</a>
			<a class="readMeButton" href="./view.php">View Jobs</a>
			<a class="readMeButton" href="./dbfCreate.php">Create Sample Data</a>
			<a class="readMeButton" href="./json.php">JSON data</a>
			<a class="readMeButton" href="./reflection.html">Reflection</a>
		</div>
		


		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
			crossorigin="anonymous"
		></script>
	</body>
</html>
