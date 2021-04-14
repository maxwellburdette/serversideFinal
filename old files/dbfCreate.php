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
            $result = $conn->query($sql);
            displayTable($productHead, $tableTitle, $result);

            //Display company tables
            $manufacturerArray = array("Company");
            $manufacturerTitle = "Companies";
            $sql = "SELECT companyName
            FROM company";
            $result = $conn->query($sql);
            displayTable($manufacturerArray, $manufacturerTitle, $result);
            
            //Display open jobs table
            $departmentHead = array("Company", "Job Title", "Job Description");
            $departmentTitle = "Job Openings";
            $sql = "SELECT c.companyName, j.jobTitle, j.jobDescription 
            FROM company c
            JOIN openjobs j
            ON c.companyID = j.companyID
            ";
            $result = $conn->query($sql);
            //Display tables of data
            displayTable($departmentHead, $departmentTitle, $result);

        



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

            function displayTable($tableHead, $title, $result)
            {
                echo "<h2>".$title."</h2>";
		        echo '<table>';
		        echo '<tr>';
                foreach($tableHead as $value)
                {
                    echo "<th>".$value."</th>";
                }
                while($row = $result->fetch_assoc()) {
                    //print_r($row);
                    //echo "<br />";
                    echo "<tr>\n";
                    // print data
                    foreach($row as $key=>$value) {
                    echo "<td>" . $value . "</td>\n";
                    }
                    echo "</tr>\n";
                }
                echo '</tr>';
                echo "</table>";
                echo '<br />';
            }

            function runQuery($sql, $msg, $echoSuccess) {
                global $conn;
         
                // run the query
                if ($conn->query($sql) === TRUE) {
                   if($echoSuccess) {
                      echo $msg . "<br />";
                   }
                 } 
                 else {
                     echo "<strong>Error when: " . $msg . "</strong> using SQL: " . $sql . "<br />" . $conn->error;
                 }           
             } // end of runQuery( ) 

        ?>
    </body>
</html>