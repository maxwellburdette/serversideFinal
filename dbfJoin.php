
<!-- 
    File: dbfJoin.php (Web page to display JOINS using database tables created by dbfCreate.php)
    Server Side Development / Project: Crud
    Maxwell Burdette / burdettm@csp.edu
    03/27/2021
 -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="dbf.css">
    </head>
    <body>
        <?PHP
            //Set up connection contstants
            //Default aamps properties
            define("SERVER_NAME","localhost");
            define("DBF_USER_NAME", "root");
            define("DBF_PASSWORD", "mysql");
            define("DATABASE_NAME", "storedatabase");

            //Create connection object
            $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
            //Check connection
            if($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            //Select database
            $conn->select_db(DATABASE_NAME);

            //JOIN
            echo "<h2>JOIN</h2>";
            $sql = "SELECT product.productName, product.color, product.price, product.quantity, 
                product.productPage, department.departmentName, manufacturer.manufactureName 
                FROM department 
                JOIN product 
                ON product.departmentID = department.departmentID 
                JOIN manufacturer 
                ON product.manufacturerID = manufacturer.manufacturerID";
            echo "<pre>".$sql."</pre>";
            $result = $conn->query($sql);
            displayResult($result, $sql);
            echo "<br />";

            //LEFT OUTER JOIN
            echo "<h2>LEFT OUTER JOIN</h2>";
            $sql = "SELECT  m.manufactureName, m.manufactureWebsite, d.departmentName, d.departmentID
                FROM manufacturer m 
                LEFT JOIN department d 
                ON d.departmentID = m.departmentID";
            echo "<pre>".$sql."</pre>";
            $result = $conn->query($sql);
            displayResult($result, $sql);
            echo "<br />";

            //RIGHT OUTER JOIN TABLE
            echo "<h2>RIGHT OUTER JOIN</h2>";
            $sql = "SELECT p.productName, p.color, p.price, p.quantity, p.productPage, m.manufactureName
                FROM product p
                RIGHT JOIN manufacturer m
                ON m.departmentID = p.departmentID";
            echo "<pre>".$sql."</pre>";
            $result = $conn->query($sql);
            displayResult($result, $sql);
            echo "<br />";


            //Displays query results
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
       
            } 

        ?>
        <h2>Difference between inner and outer joins</h2>
        <p>An inner join on tables the unmatched or NULL rows are excluded from the output. With outer joins,
            you don't exclude the unmatched rows. In our case with a retail store, if there is a new product
            that comes out and it does not have its own department yet, then that information should not be displayed
            if it is not yet available. Also if there is a manufacturer that reserves a space in the store, but does not 
            yet have a product ready for release, then we can show a manufacturer name but have the other information reguarding
            the product blank, this is done through an outer join. 
        </p>
    </body>
</html>