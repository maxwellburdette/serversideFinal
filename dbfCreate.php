<!-- 
    File: updateDbfCreate.php (Web page to create new database and tables. Populate table data and display it. Uses only prepared statements, except for adding procedure to DB)
    Server Side Development / Project: Term Project
    Maxwell Burdette / burdettm@csp.edu
    04/26/2021
 -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <nav>
            <h1 class="home py-4" style="font-size: 1.8em"><a href="./index.html">JobFinder</a></h1>
		</nav>
        <h1 style="color: #fff">Here is the sample data you created</h1>
      
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

            //DROP table if it already exists
            $sql = "DROP DATABASE IF EXISTS " . DATABASE_NAME;
            runQuery($sql, "Deleted previous DB", false);

            //Function to create new database
            createDatabase();

            //Function to populate same data in tables
            populateTables();

            //Display user table
            $productHead = array("First Name", "Last Name", "Email");
            $tableTitle = "User Table";
            $sql = "SELECT firstName, lastName, email
            FROM users";
            //$result = $conn->query($sql);
            $userTable = array();
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
                    array_push($userTable, array($value1, $value2, $value3));
                }

                // Free results
                $stmt->free_result( );
                // Close the statement
                $stmt->close( );
            }
            displayTable($productHead, $tableTitle, $userTable);

            //Display company tables
            $companyArray = array("Company");
            $companyTitle = "Companies";
            $sql = "SELECT companyName
            FROM company";
            $companyTable = array();
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
                $stmt->bind_result($value1);
                // Fetch the value - returns the next row in the result set
                    
                    
                while($stmt->fetch( )) {
                    array_push($companyTable, array($value1));
                }

                // Free results
                $stmt->free_result( );
                // Close the statement
                $stmt->close( );
            }
            displayTable($companyArray, $companyTitle, $companyTable);
            //Display open jobs table
            $jobsHead = array("Company", "Job Title", "Job Description");
            $jobsTitle = "Job Openings";
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
            displayTable($jobsHead, $jobsTitle, $jobsTable);

        



            //Create our database
            function createDatabase()
            {
                global $conn;
                $sql = "CREATE DATABASE IF NOT EXISTS " . DATABASE_NAME;
                runQuery($sql, "Creating " . DATABASE_NAME, false);

                //Select newly created database
                $conn->select_db(DATABASE_NAME);

                /*
                 * Create tables 
                 */

                //Create Table: Users
                $sql = "CREATE TABLE IF NOT EXISTS users (
                    userID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    firstName VARCHAR(30) NOT NULL,
                    lastName VARCHAR(30) NOT NULL,
                    email VARCHAR(60) NOT NULL,
                    password VARCHAR(255)
                    )";
                runQuery($sql, "Creating users... ", false);

                //Create Table: jobs
                $sql = "CREATE TABLE IF NOT EXISTS openjobs (
                    jobID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    jobTitle VARCHAR(50) NOT NULL,
                    jobDescription VARCHAR(255),
                    companyID INT
                    )";
                runQuery($sql, "Creating jobs...", false);

                //Create Table: company
                $sql = "CREATE TABLE IF NOT EXISTS company (
                    companyID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    companyName VARCHAR(20) NOT NULL
                )";
                runQuery($sql, "Creating companies...", false);

                //Create Procedure Table
                $sql = "CREATE PROCEDURE insertJob(IN job VARCHAR(50), IN description 
                VARCHAR(255), IN id INT) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER INSERT 
                INTO openjobs(jobTitle, jobDescription, companyID) VALUES(job, description, id)";
                //runQuery($sql, "Creating procedure", false);
                $conn->query($sql);
            }

            function populateTables()
            {
                /*
                 * Populate tables
                 */

                 //Populate user table
                $userArray = array(
                    array("Max", "Burdette", "maxbdevelops@gmail.com", 1),
                    array("Bob", "Jones", "bob@bob.com", 1),
                    array("Andrew", "Clement", "andrew@clements.com", 1),
                    array("Rob", "Brown", "rob@gmail.com", 1)
                );
                foreach($userArray as $user)
                {
                    //echo $product[0] . " " . $product[1] . "<br />";
                    $sql = "INSERT INTO users (firstName, lastName, email, password) "
                        . "VALUES ('" . $user[0] . "', '" 
                        . $user[1] . "', '" 
                        . $user[2] . "', '"
                        . $user[3] . "')";
                    runQuery($sql, "Record inserted for: " . $user[1], false);
                }

                //Populate company tables
                $companyArray = array(
                    array("BestBuy"),
                    array("Target"),
                    array("Walmart"),
                );
                foreach($companyArray as $company)
                {
                    //echo "Department: " . $department[0] . ", Manager: " . $department[1] . "<br />";
                    $sql = "INSERT INTO company (companyName) "
                        . "VALUES ('" . $company[0] . "')";
                        
                    runQuery($sql, "Record inserted for: " . $company[1], false);
                }

                //Populate openjobs table
                $jobsArray = array(
                    array("Cashier", "Ring up customers and process payment", 1),
                    array("Cashier", "Ring up customers and process payment", 2),
                    array("Manager", "Manage store", 3)
                );
                foreach($jobsArray as $jobs)
                {
                    //echo "Manufacturer: " . $manufacturer[0] . "<br />";
                    $sql = "INSERT INTO openjobs (jobTitle, jobDescription, companyID) "
                        . " VALUES ('" . $jobs[0] . "', '"
                        . $jobs[1] . "', '"
                        . $jobs[2] . "')";
                    runQuery($sql, "Record inserted for: " . $jobs[0], false);
                }
            }

            function displayTable($tableHead, $title, $table)
            {
                echo '<h2 style="color: #fff">'.$title.'</h2>';
                echo '<div class="container-sm">';
		        echo '<table class="table table-hover table-dark">';
                echo '<thead class="table table-dark">';
		        echo '<tr>';
                foreach($tableHead as $value)
                {
                    echo "<th style='border-left: 1px solid #fff'>".$value."</th>";
                }
                echo '</tr>';
                echo '</thead>';

                foreach($table as $row)
                {
                    echo '<tr class="table table-light">';
                    foreach($row as $col)
                    {
                        echo '<td style="border-left: 1px solid #111">'.$col.'</td>';
                    }
                    echo '</tr>';
                }
           
                echo "</table>";
                echo '<br />';
            }
            
            //Run prepared statements
            function runQuery($sql, $msg, $echoSuccess) {
                global $conn;

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

                    // Free results
                    $stmt->free_result( );

                    // Close the statement
                    $stmt->close( );
                }
            
                
             } // end of runQuery( ) 

        ?>
        <br />
        <br />
        <br />
        <br />
        
        <div
			class="buttonContainer position-fixed bottom-0 start-50 translate-middle-x mt-5"
		>
			<a class="readMeButton" href="./readMe.html">Read Me</a>
			<a class="readMeButton" href="./form.php">Add Job</a>
			<a class="readMeButton" href="./view.php">View Jobs</a>
			<a class="readMeButton active" href="./dbfCreate.php">Create Sample Data</a>
            <a class="readMeButton" href="./json.php">JSON data</a>
            <a class="readMeButton" href="./reflection.html">Reflection</a>
		</div>
    </body>
</html>