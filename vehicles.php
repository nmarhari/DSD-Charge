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
        
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
        <script src="./js/showNav.js" crossorigin="anonymous"></script>

    </head>

    <body>

        <section class="general-header" id="vehicles-header">
            <nav>
                <a href="./index.html#">
                    <i class="fas fa-bolt" id="logo"></i>
                </a>
            
                <div class="navlinks">
                    <ul class="nav-class" id="nav-id">
                        <li><a href="./index.html#">Home</a></li>
                        <li><a href="./vehicles.php#">Vehicles</a></li>
                        <li><a href="">Inventory</a></li>
                        <li><a href="">Sales</a></li>
                        <li><a href="">Service</a></li>
                        <li><a href="">Customers</a></li>
                        <li class="icon">
                            <a href="javascript:void(0);" onclick="showNav()">
                                <i class="fa fa-bars"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            
            </nav>

            <div class="general-header-text">

                <h1>Vehicles</h1>

            </div>

        </section>

        <section class="content">

            <!--<div class="refine-text">
                <a href=""><p>Refine search results</p></a>
            </div>
            

            <form class="refine-search" action="vehicles.php" method="get">
                
                <p>Sort by: </p>
                <div class="dropdown">
                    <select name="sort">
                        <option value=""> </option>
                        <option value="mileage">Mileage - Lowest First</option>
                        <option value="mileage-desc">Mileage - Highest First</option>
                    </select>
                </div>

                <p>Filters: </p>
                Make: <input type="text" size="20" name="make">
                Model: <input type="text" size="20" name="model">
                Color: <input type="text" size="20" name="color">
                <p>Vehicle Properties: </p>
                <div class="dropdown">
                    <select name="filters">
                        <option value=""> </option>
                        <option value="isnew">New Vehicles Only</option>
                        <option value="custom">Custom Vehicles Only</option>
                    </select>
                </div>


                <input type="submit" value="Submit"></input>
            
            </form> -->

            <?php 
            
                
                $sqlstatement = $conn->prepare("SELECT VIN, year, make, model, color, mileage, isnew, custom from vehicle");
                
                $sqlstatement->execute();
                $result = $sqlstatement->get_result();

                $sqlstatement->close();

                if ($result->num_rows > 0) {
                    // Create table to output results:
                    echo "<table class=\"content-table\" id=\"myTable\"><thead><tr><th>VIN</th><th>Make</th><th>Model</th><th>Color</th><th>Mileage</th><th>IsNew</th><th>Custom</th></tr></thead>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tbody><tr>
                        <td>".$row["VIN"]."</td>
                        <td>".$row["make"]."</td>
                        <td>".$row["model"]."</td>
                        <td>".$row["color"]."</td>
                        <td>".$row["mileage"]."</td>
                        <td>".$row["isnew"]."</td>
                        <td>".$row["custom"]."</td>
                        </tr></tbody>";
                    }
                    echo "</table>";
                    //echo "There are ". $result->num_rows . " results.";

                } else {
                    echo "No results.";

                    echo "<a href=\"vehicles.php\">Click here to reset the table.";
                }
            
            ?>

        </section>

        <?php
            $conn->close();
        ?>

        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
            });
        </script>


    </body>

</html>