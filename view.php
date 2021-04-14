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

			function displayTable($tableHead, $title, $table)
            {
               
		        echo '<table style="color: #cfcfcf">';
		        echo '<tr>';
                foreach($tableHead as $value)
                {
                    echo "<th>".$value."</th>";
                }
				echo '</tr>';
                foreach($table as $row)
                {
                    echo '<tr>';
                    foreach($row as $col)
                    {
                        echo '<td>'.$col.'</td>';
                    }
                    echo '</tr>';
                }
                
                echo "</table>";
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
			

		?>
		

		<h1 class="dispay-5" style="color: #fff">View Data</h1>
		<?PHP
			//Display company tables
            $companyHead = array("Company", "Job Title", "Job Description");
            $companyTitle = "Job Openings";
            $sql = "SELECT c.companyName, j.jobTitle, j.jobDescription 
            FROM company c
            JOIN openjobs j
            ON c.companyID = j.companyID
            ";
            $jobsTable = array();
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
                // Bind result variables
                // one variable for each field in the SELECT
                // This is the variable that fetch( ) will use to store the result
                $stmt->bind_result($value1, $value2, $value3);
                // Fetch the value - returns the next row in the result set
                    
                    
                while($stmt->fetch( )) {
                    array_push($jobsTable, array($value1, $value2, $value3));
                }

                // Free results
                $stmt->free_result( );
                // Close the statement
                $stmt->close( );
            }
            //Display tables of data
            displayTable($companyHead, $companyTitle, $jobsTable);	
		?>
		<script
			src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
			crossorigin="anonymous"
		></script>
	</body>
</html>
