<!-- 
    File: json.php (Creates json file, allows you to edit contents of json file)
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
		<link rel="preconnect" href="https://fonts.gstatic.com" />
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
		<link rel="stylesheet" href="style.css" />
		<?php

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
			//Select DB
			$conn->select_db(DATABASE_NAME);
			
			//Empty Array
			$data = array();
			$sql = "SELECT c.companyId, c.companyName, j.jobId, j.jobTitle, j.jobDescription 
            FROM company c
            JOIN openjobs j
            ON c.companyID = j.companyID
            ";
			$query = $conn->query($sql);
			while($row = $query->fetch_assoc()){
				$data[] = $row;
			}
 
			//convert to json
				$data = json_encode($data, JSON_PRETTY_PRINT);
 
			//create json file
			$filename = 'jobs.json';
			if(file_put_contents($filename, $data)){
			} 
			else{
				echo 'An error occured in creating the file';
			}


			if(array_key_exists('hdnReturning', $_POST))
			{
			$jsonFileName = "jobs.json";
			$thisArray = readJSON($jsonFileName);

			//Replace the data in array with the user input 
			for($i = 0; $i < count($thisArray); $i++)
			{
				$thisArray[$i]['jobTitle'] = $_POST['jobTitle'.$i];
				$thisArray[$i]['jobDescription'] = $_POST['jobDescription'.$i];
			}
			print_r($jsonData);
	
			writeJSON($thisArray, $jsonFileName);
			}

			//Reads JSON file 
			function readJSON($myFile) 
			{
				//Set up an array to hold the JSON dataEntry
				$consoleArray = array( );

				try {
					//Get data from the JSON file
					$jsonData = file_get_contents($myFile);
					//convert it into an array
					$consoleArray = json_decode($jsonData, true);
					return $consoleArray;
				}
				catch (Exception $e) {
					echo 'Caught exception: ', $e->getMessage(), '\n';
				}
			}

			//Writes JSON file
			function writeJSON($myArray, $myFile)
			{
				//Convert array to JSON formatted variable
				$jsonData = json_encode($myArray, JSON_PRETTY_PRINT);
				
				try {
					//write to the JSON file
					if(file_put_contents($myFile, $jsonData)){
					
					}
					else
					{
						echo 'There was an error writing to the ' . $myFile . ' file. <br />';
					}
				}
				catch (Exception $e) {
					echo 'Caught exception: ', $e->getMessage(), '\n';
				}
			}
 
		?>
	</head>
	<body>
		<nav>
			<h1 class="home py-4" style="font-size: 1.8em">
				<a href="./index.html">JobFinder</a>
			</h1>
		</nav>

		<h1 style="color: #fff">Display and edit JSON data</h1>

		<div class="container-sm my-2" >
			<form
				method="POST" 
				action="<?PHP echo $_SERVER['PHP_SELF'];?>"
			>
				<table class="shadow table table-hover table-dark rounded">
					<thead>
						<tr>
							<th style="border-right: 1px solid #fff">Company</th>
							<th style="border-right: 1px solid #fff">Job Title</th>
							<th>Job Description</th>	
						</tr>
					</thead>
				
					<tbody id="data">

					<tbody>
				</table>
				<input type="hidden" name="hdnReturning" value="returning">
				<input type="submit" class="btn btn-secondary" value="Submit">
			</form>
		</div>

		<h1 style="color: #fff">Sample data, when refreshing data resets with database info</h1>
		<div class="container-sm my-2" >
			<table class="shadow table table-hover table-dark rounded">
				<thead>
					<tr>
						<th style="border-right: 1px solid #fff">Company</th>
						<th style="border-right: 1px solid #fff">Job Title</th>
						<th>Job Description</th>	
					</tr>
				</thead>
				
				<tbody id="sample">

				<tbody>
			</table>
		</div>


		<div
			class="buttonContainer position-fixed bottom-0 start-50 translate-middle-x mt-5"
		>
			<a class="readMeButton" href="./readMe.html">Read Me</a>
			<a class="readMeButton" href="./form.php">Add Job</a>
			<a class="readMeButton" href="./view.php">View Jobs</a>
			<a class="readMeButton" href="./dbfCreate.php">Create Sample Data</a>
			<a class="readMeButton active" href="./json.php">JSON data</a>
		</div>
		<script src="json.js"></script>
	</body>
</html>
