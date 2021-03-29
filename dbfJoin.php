
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <h1>Sun Run Join Testing</h1>
    <?PHP
        /* sunRunJoin.php - Experiment with SQL JOINS
             Registration data for the Sun Run Marathon
        Written by Student Name
        Written  Current date
        Revised: ??? 
        */
   
        // Set up connection constants
        // Using default username and password for AMPPS  
        define("SERVER_NAME","localhost");
        define("DBF_USER_NAME", "root");
        define("DBF_PASSWORD", "mysql");
        define("DATABASE_NAME", "sunRun");

        // Create connection object
        $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        // Select the database
        $conn->select_db(DATABASE_NAME);

        // Display Table:runner
        echo "All Fields FROM runner<br />";
        echo "(Some runners don't have a sponsor.)<br />";
        $sql = "SELECT * FROM runner";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />";
        
        // Display specific field names using aliases
        echo "fName and lName FROM runner<br />";
        echo "Using aliases<br />";
        // FROM runner";
        $sql = "SELECT fName AS 'First Name', lName AS 'Last Name' FROM runner";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />";

        // Display Table:sponsor
        echo "All Fields FROM sponsor<br />";
        echo "(Not every sponsor has a runner.)<br />";
        $sql = "SELECT * FROM sponsor";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />"; 

        // JOIN (INNER JOIN by default)
        echo "JOIN<br />";
        echo "(A JOIN is an INNER JOIN by default)<br />";
        $sql = "SELECT * FROM runner 
        JOIN sponsor
        ON runner.id_runner = sponsor.id_runner";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />";

        // LEFT INNER JOIN
        // Inner join shows what is common between tables.
        // Consequently, a LEFT INNER and RIGHT INNER
        // both give the same result.
        echo "LEFT INNER JOIN<br />";
        echo "(Same as JOIN)<br />";
        $sql = "SELECT * FROM runner 
        JOIN sponsor
        ON runner.id_runner = sponsor.id_runner";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />"; 

        // RIGHT INNER JOIN
        echo "RIGHT INNER JOIN<br />";
        echo "(Same as JOIN)<br />";
        $sql = "SELECT * FROM runner 
        JOIN sponsor
        ON runner.id_runner = sponsor.id_runner";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />";

        // INNER JOIN THREE TABLES
        echo "INNER JOIN 3 TABLES<br />";
        echo "INNER JOIN from runner, runner_race, and race<br />";
        $sql = "SELECT run.fName, run.lName, race.raceName 
        FROM runner run
        JOIN runner_race rr
        ON run.id_runner = rr.id_runner
        JOIN race
        ON rr.id_race = race.id_race
        ORDER BY run.lName";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />"; 

        // LEFT OUTER JOIN
        echo "LEFT OUTER JOIN (Left table: runner)<br />";
        $sql = "SELECT r.id_runner, r.fName, r.lName, r.gender, r.phone, s.id_sponsor, s.sponsorName 
        FROM runner r
        LEFT JOIN sponsor s
        ON r.id_runner = s.id_runner";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />";

        // RIGHT OUTER JOIN
        echo "<h2>RIGHT OUTER JOIN (Right table: sponsor)</h2><br />";
        $sql = "SELECT r.id_runner, r.fName, r.lName, r.gender, r.phone, s.id_sponsor, s.sponsorName 
        FROM runner r
        RIGHT JOIN sponsor s
        ON r.id_runner = s.id_runner";
        $result = $conn->query($sql);
        displayResult($result, $sql);
        echo "<br />";
        // Close the database
        $conn->close();


        /********************************************
        * displayResult( ) - Execute a query and display the result
        * Parameters: $rs  - Result set to display as 2D array
        *             $sql - SQL string used to display an error msg
        ********************************************/
        function displayResult($result, $sql) {
            if ($result->num_rows > 0) {
                echo "<table border='1'>\n";
                // print headings (field names)
                $heading = $result->fetch_assoc( );
                echo "<tr>\n";
                // Print field names as table headings
                foreach($heading as $key=>$value){
                    echo "<th>" . $key . "</th>\n";
                }
                echo "</tr>";
                // Print the values for the first row
                echo "<tr>";
                foreach($heading as $key=>$value){
                    echo "<td>" . $value . "</td>\n";
                }
                // Output the rest of the records
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
                echo "</table>\n";
            }
            // No results
            else {
               echo "<strong>zero results using SQL: </strong>" . $sql;
            }
   
        } // end of displayResult( )

        /********************************************
        * runQuery( ) - Execute a query and display message
         *    Parameters:  $sql         -  SQL String to be executed.
         *                 $msg         -  Text of message to display on success or error
         *     ___$msg___ successful.    Error when: __$msg_____ using SQL: ___$sql____.
         *                 $echoSuccess - boolean True=Display message on success
         ********************************************/
        function runQuery($sql, $msg, $echoSuccess) {
           global $conn;
    
           // run the query
           if ($conn->query($sql) === TRUE) {
              if($echoSuccess) {
                 echo $msg . " successful.<br />";
              }
            } 
            else {
                echo "<strong>Error when: " . $msg . "</strong> using SQL: " . $sql . "<br />" . $conn->error;
            }           
        } // end of runQuery( ) 
    ?>
</body>
</html>