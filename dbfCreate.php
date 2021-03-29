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
        <h1>Sun Run Create Test Page</h1>
        <?PHP

   
            // Set up connection constants
            define("SERVER_NAME","localhost");
            define("DBF_USER_NAME", "root");
            define("DBF_PASSWORD", "mysql");
            define("DATABASE_NAME", "sunRun");
            // Using default username and password for AMPPS  

            // Create connection object
            $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
            // Start with a new database to start primary keys at 1
            $sql = "DROP DATABASE " . DATABASE_NAME;
            runQuery($sql, "DROP " . DATABASE_NAME, true);
            
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            createDatabase();

            /***************************************************
            * Populate Tables Using Sample Data
            * This data will later be collected using a form.
             ***************************************************/
            // Populate Table:runner
            // $sql = "INSERT INTO runner (fName, lName, gender, phone) "
            //     . "VALUES('Johnny', 'Hayes', 'male', '1234567890')";
            //runQuery($sql, "New record", false);
            $runnerArray = array(
                array("Johnny", "Hayes", "male", "1234567890"),
                array("Robert", "Fowler", "male","2234567890"),
                array("James", "Clark", "male","3234567890"),
                array("Marie-Louise", "Ledru", 'female',"4234567890")
                );
             
            foreach($runnerArray as $runner) {   
                echo $runner[0] . " " . $runner[1] . "<br />";
                $sql = "INSERT INTO runner (fName, lName, gender, phone) "
                    . "VALUES ('" . $runner[0] . "', '" 
                    . $runner[1] . "', '" 
                    . $runner[2] . "', '"
                    . $runner[3] . "')";
                runQuery($sql, "Record inserted for: " . $runner[1], false);
             }

            // Populate Table:race
            $raceArray = array(
                array("10K", 46),
                array("5K", 46),
                array("Marathon", 85),
                array("Half Marathon", 75)
            );

            foreach($raceArray as $race) {
                $sql = "INSERT INTO race (id_race, raceName, entranceFee) "
                    . "VALUES (NULL, '" . $race[0] . "', '" 
                    . $race[1] . "')";
                    
                //echo "\$sql string is: " . $sql . "<br />";
                runQuery($sql, "New record insert $race[1]", false);
            }

            // Populate Table:sponsor
            $sponsorArray = array(
                array("Nike",  2),
                array("Western Hospital", 3),
                array("House of Heroes", 4)
                );
                
            foreach($sponsorArray as $sponsor) {
                $sql = "INSERT INTO sponsor (id_sponsor, sponsorName, id_runner) "
                    . "VALUES (NULL, '" . $sponsor[0] . "', '" 
                    . $sponsor[1] . "')";
                    
                //echo "\$sql string is: " . $sql . "<br />";
                runQuery($sql, "New record insert $sponsor[0]", false);
            }

            // Add a sponsor that is not yet sponsoring a runner.
            $sql = "INSERT INTO sponsor (id_sponsor, sponsorName, id_runner) "
                . "VALUES (NULL, 'Wells Fargo Bank', NULL)";
            runQuery($sql, "New record insert Wells Fargo", false);

            // Populate Table:runner_race
            // Determine id_runner for Robert Fowler
            $sql = "SELECT id_runner FROM runner WHERE fName='Robert' AND lName='Fowler'";
            $result = $conn->query($sql);
            $record = $result->fetch_assoc();
            //echo '$record: <pre>';
            // print_r($record);
            // echo '</pre>';
            $thisRunner = $record['id_runner'];
            //echo '$thisRunner: '. $thisRunner . '<br />';

            // Determine id_race for Half Marathon
            $sql = "SELECT id_race FROM race WHERE raceName='Half Marathon'";
            $result = $conn->query($sql);
            $record = $result->fetch_assoc();
            $thisRace = $record['id_race'];
            //echo '$thisRace: ' . $thisRace . '<br />';
            // Add each sample runner to the Marathon and Half Marathon
            foreach($runnerArray as $runner) {
                //echo "<strong>Adding $runner[0] $runner[1]</strong><br />";
                buildRunnerRace($runner[0], $runner[1], "Marathon");
                buildRunnerRace($runner[0], $runner[1], "Half Marathon");
            }


            // Check to make sure runner hasn't already registered for this race
            $sql = "SELECT id_race FROM runner_race WHERE id_race = " . $thisRace;
            if ($result = $conn->query($sql)) {
                //determine number of rows result set 
                $row_count = $result->num_rows;
                if($row_count > 0) {
                      echo "Runner " . $thisRunner
                      . " has already registered for race " 
                      . $thisRace . "<br />";
                } 
                else { // Not a duplicate
                    $sql = "INSERT INTO runner_race (id_runner, id_race, bibNumber, paid) 
                        VALUES (" . $thisRunner . ", " . $thisRace . ", 1234, true)";
                    runQuery($sql, "Insert " . $thisRunner . " and " . $thisRace, true);
                } // end of if($row_count)
            } // end if($result)

            // Add in extra runners who aren't registered for a race yet.
            $sql = "INSERT INTO runner (id_runner, fName, lName, gender,phone) "
            . "VALUES (NULL, 'John', 'Watson', 'male', '5071237899')";
            runQuery($sql, "New record insert John Watson", false);

            $sql = "INSERT INTO runner (id_runner, fName, lName, gender,phone) "
                . "VALUES (NULL, 'Sally', 'Johnson', 'female', '8121237800')";
            runQuery($sql, "New record insert Sally Johnson", false);

            $sql = "INSERT INTO runner (id_runner, fName, lName, gender,phone) "
                . "VALUES (NULL, 'Paula', 'Radcliff', 'female', '8029881123')";
            runQuery($sql, "New record insert Sally Johnson", false);


            /***************************************************
             * Display the tables
            ***************************************************/
            // Table:runner

            // Table:race

            // Table:sponsor
 
            // Close the database
            $conn->close();


            /*************************************************************
             * buildRunnerRace( ) - Register runner for specific races
             *                      using sample data.
             * Sets up a table with two foreign keys 
             * connecting Table:runner to Table:race
             * Parameters:  $fName - runner's first name
             *              $lName - runner's last name
             *              $thisRace - register this runner to this race
             **************************************************************/
            function createDatabase()
            {
                global $conn;
                // Create database if it doesn't exist
                $sql = "CREATE DATABASE IF NOT EXISTS " . DATABASE_NAME;
                runQuery($sql, "Creating " . DATABASE_NAME, false);

                // Select the database
                $conn->select_db(DATABASE_NAME);

                /*******************************
                * Create the tables
                *******************************/
                // Create Table:runner
                $sql = "CREATE TABLE IF NOT EXISTS runner (
                    id_runner INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    fName     VARCHAR(25) NOT NULL,
                    lName     VARCHAR(25) NOT NULL,
                    gender    VARCHAR(10),
                    phone     VARCHAR(10)
                    )";
                runQuery($sql, "Creating runner ", false);

                // Create Table:race
                $sql = "CREATE TABLE IF NOT EXISTS race (
                    id_race     INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    raceName        VARCHAR(25) NOT NULL,
                    entranceFee SMALLINT
                    )";
                runQuery($sql, "Table:race", false);

                // Create Table:runner_race if it doesn't exist
                // One racer can run multiple races
                $sql = "CREATE TABLE IF NOT EXISTS runner_race (
                    id_runner INT(6),
                    id_race   INT(6),
                    bibNumber INT(6),
                    paid      BOOLEAN
                    )"; 
                runQuery($sql, "Table:runner_race", false);

                // Create Table:sponsor
                $sql = "CREATE TABLE IF NOT EXISTS sponsor (
                    id_sponsor      INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
                    sponsorName     VARCHAR(50) NOT NULL,
                    id_runner       INT(6)
                    )";
                runQuery($sql, "Table:sponsor", false);
            }

            function buildRunnerRace($fName, $lName, $thisRace) {
                global $conn;
   
                // Populate Table:runner_race
                // Determine id_runner
                $sql = "SELECT id_runner FROM runner 
                        WHERE fName='" . $fName 
                        . "' AND lName='" . $lName . "'";
                $result = $conn->query($sql);
                $record = $result->fetch_assoc();
                $runnerID = $record['id_runner'];
                //echo '$thisRunner: ' . $thisRunner;

                // Determine id_race
                $sql = "SELECT id_race FROM race WHERE raceName='" . $thisRace . "'";
                $result = $conn->query($sql);
                $record = $result->fetch_assoc();
                $raceID = $record['id_race'];
                //echo ' -- $raceID: ' . $raceID . '<br />';

                // Check to make sure runner hasn't already registered for this race
                $sql = "SELECT id_race FROM runner_race 
                WHERE id_race = " . $raceID 
                . " AND id_runner = " . $runnerID;
                $result = $conn->query($sql);

                /* determine number of rows result set */
                $row_count = $result->num_rows;
                if($row_count > 0) {
                    echo "Runner " . $thisRunner
                    . " has already registered for race " 
                    . $thisRace . "<br />";
                } 
                else { // Not a duplicate
                    $sql = "INSERT INTO runner_race (id_runner, id_race, bibNumber, paid) 
                         VALUES (" . $runnerID . ", " . $raceID . ", 1234, true)";
                    runQuery($sql, "Insert " . $runnerID . " and " . $thisRace, false);
                } // end if($result)

            } // end of buildRunnerRace( )


            /********************************************
             * displayResult( ) - Execute a query and display the result
             *    Parameters:  $rs - result set to display as 2D array
            *                 $sql - SQL string used to display an error msg
            ********************************************/
            function displayResult($result, $sql) {

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