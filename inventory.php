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

        <title>Inventory at Charge</title>

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

        <section class="general-header" id="inventory-header">
            <nav>
                <a href="./index.html#">
                    <i class="fas fa-bolt" id="logo"></i>
                </a>
            
                <div class="navlinks">
                    <ul class="nav-class" id="nav-id">
                        <li><a href="./index.html#">Home</a></li>
                        <li><a href="./vehicles.php#">Vehicles</a></li>
                        <li><a href="./inventory.php">Inventory</a></li>
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

                <h1>Inventory</h1>

            </div>

        </section>

        <section class="content">

            <div class="inventory operations">
                <h1><a href="./inventory.php">View All Inventory</a></h1>
                <h1><a href="./inventory.php?query=forsale">View Vehicles Not Reserved</a><h1>
                <div class="inventory-price">
                    <form action="inventory.php" method="get">
                    <p>View Vehicles By Desired Price Limit:</p> <input type="text" size="10" name="price">
                    <input type="submit" value="Go">
                    </form>
                </div>
                
            </div>

            <h1>Search results:</h1>
            <table class="content-table" id="myTable"> 

               

                <hr>

                <thead>
                    <tr>
                        <th>Inventory ID</th>
                        <th>VIN</th>
                        <th>Intake Date</th>
                        <th>Reserved</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                        $query = $_GET["query"];

                        if ($query == "forsale") {
                            $sqlstatement = $conn->prepare("SELECT Inventory_ID, VIN, intake_date, reserved, price from inventory WHERE reserved = \"No\"");
                        }  else { // catch all
                            $sqlstatement = $conn->prepare("SELECT Inventory_ID, VIN, intake_date, reserved, price from inventory");
                        }

                        if (!empty($_GET["price"])) {
                            $price = $_GET["price"];
                            $sqlstatement = $conn->prepare("SELECT Inventory_ID, VIN, intake_date, reserved, price from inventory WHERE reserved = \"No\" AND price < ?");
                            $sqlstatement->bind_param("d", $price);
                        }

                    
                        $sqlstatement->execute();
                        $result = $sqlstatement->get_result();
        
                        $sqlstatement->close();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>".$row["Inventory_ID"]."</td>
                                <td><a href=\"./inventory.php?VIN=".$row["VIN"]."\">".$row["VIN"]."</td>
                                <td>".$row["intake_date"]."</td>
                                <td>".$row["reserved"]."</td>
                                <td>".$row["price"]."</td>
                                </tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>

            <?php 
            
                if (!empty($_GET["VIN"])) {
                    echo "
                        <h1>Selected Vehicle Info</h1>
                        <div class=\"vehicle-info\">
                            <table class=\"content-table\" id=\"myTable\"> 

                    

                            <hr>
            
                            <thead>
                                <tr>
                                    <th>VIN</th>
                                    <th>Year</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Color</th>
                                    <th>Mileage</th>
                                    <th>New</th>
                                    <th>Custom</th>
                                </tr>
                            </thead>
                            <tbody>";
                            
                            $vinfo = $conn->prepare("SELECT VIN, year, make, model, color, mileage, isnew, custom from vehicle WHERE VIN = ?");
                            $VIN = $_GET["VIN"];

                            $vinfo->bind_param("s", $VIN);

                            $vinfo->execute();
                            $resultvinfo = $vinfo->get_result();
            
                            $vinfo->close();
    
                            if ($resultvinfo->num_rows > 0) {
                                while ($row = $resultvinfo->fetch_assoc()) {
                                    echo "<tr>
                                    <td>".$row["VIN"]."</td>
                                    <td>".$row["year"]."</td>
                                    <td>".$row["make"]."</td>
                                    <td>".$row["model"]."</td>
                                    <td>".$row["color"]."</td>
                                    <td>".$row["mileage"]."</td>
                                    <td>".$row["isnew"]."</td>
                                    <td>".$row["custom"]."</td>
                                    </tr>";
                                }
                            }
                    echo "</tbody></table>";

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