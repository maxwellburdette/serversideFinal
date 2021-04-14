<!-- 
    File: form.php (Shows open jobs and lets you add more)
    Server Side Development / Project: Term Project
    Maxwell Burdette / burdettm@csp.edu
    04/11/2021
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
					/>';
					echo '<label for="floatingInput">'.$value.'</label>';
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
			//Action for form 
			$self = $_SERVER['PHP_SELF'];
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
						echo '<div class="shadow container-sm position-relative">';
						echo '<div class="position-absolute bottom-0 end-0">';
						echo '<h1 style="color: red">' .$job. ' added</h1>';
						echo '</div>';
						echo '</div>';
					}
					// Free results
					$stmt->free_result( );
					// Close the statement
					$stmt->close( );
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
					
					>
 						<?PHP
					 		$sql = "SELECT companyName, companyID FROM company";
					 		$result = $conn->query($sql);
							echo '<option value=""></option>';
					 		while($row = $result->fetch_assoc()) {
								$name = $row['companyName'];
								$id = $row['companyID'];
								echo "<option value='" . $id . "'>" . $name . "</option>";
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
						  style="height: 200px; resize: none"></textarea>';
  						echo'<label for="floatingTextarea">Job Description</label>';
						echo '</div>';
					?>
					<input type='hidden' name='hidSubmitFlag' id='hidSubmitFlag' value='01' />
 					<input class="btn-lg btn-secondary my-3 " type="submit" value="Submit" />	
					
				</form>
			
		</div>


		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
			crossorigin="anonymous"
		></script>
	</body>
</html>
