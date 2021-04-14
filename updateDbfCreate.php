<!-- 
    File: dbfCreate.php (Web page to create new database and tables. Populate table data and display it)
    Server Side Development / Project: Term Project
    Maxwell Burdette / burdettm@csp.edu
    04/11/2021
 -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <nav>
			<h1>JobFinder</h1>
		</nav>
        <h1>Here is the sample data you created</h1>
        <h3>
			<a href="index.html">Back</a>
		</h3>
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
                echo "<h2>".$title."</h2>";
		        echo '<table>';
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
    </body>
</html>