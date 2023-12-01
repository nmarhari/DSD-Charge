<?php

    include './config.php';

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection before proceeding
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>

<!DOCTYPE html>
<html>
    
    <head>

        <title>Vehicles with Charge</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<link rel="stylesheet" href="./css/style.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">

    </head>

    <body>

        <section class="general-header" id="vehicles-header">
            <nav>
                <a href="./index.html#">
                    <i class="fas fa-bolt" id="logo"></i>
                </a>
            
                <div class="navlinks">
                    <ul>
                        <li><a href="./index.html#">Home</a></li>
                        <li><a href="./vehicles.php#">Vehicles</a></li>
                        <li><a href="">Inventory</a></li>
                        <li><a href="">Service</a></li>
                        <li><a href="">Customers</a></li>
                    </ul>
                </div>
            
            </nav>

            <div class="general-header-text">

                <h1>Vehicles</h1>

            </div>

        </section>

        <section class="content">

            <?php 
            
                $sql = "SELECT id, name FROM instructor";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Create table to output results:
                    echo "<table class=\"content-table\"><thead><tr><th>ID</th><th>Name</th></tr></thead>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tbody><tr><td>".$row["id"]."</td><td>".$row["name"]."</td></tr></tbody>";
                    }
                    echo "</table>";
                    echo "There are ". $result->num_rows . " results.";

                } else {
                    echo "No results.";
                }
            
            ?>

        </section>

        <?php
            $conn->close();
        ?>

    </body>

</html>