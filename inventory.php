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
                        <li><a href="./inventory.php#">Inventory</a></li>
                        <li><a href="./sales.php#">Sales</a></li>
                        <li><a href="./service.php#">Service</a></li>
                        <li><a href="./customers.php#">Customers</a></li>
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

        <div class="inventory-entry">
                <h2>New Inventory Entry Form:</h2>

                <form action="inventory.php" method="get">
                    <p>Enter a New Vehicle Inventory ID:</p> <input type="text" size="9" minlength="9" name="Inventory_ID">
                    <p>Enter Vehicle VIN:</p> <input type="text" size="17" minlength="17" name="VIN">
                    <p>Is the Vehicle Reserved?</p>
                    <input type="radio" name="reserved" value="Yes">Yes</input>
                    <input type="radio" name="reserved" value="No" checked>No</input>
                    <p>Enter Vehicle Price:</p> <input type="text" size="10" name="vehprice">
                    <br>
                    <input type="hidden" name="form_submitted" value="1">
                    <input type="submit" value="Submit">
                </form>
            </div>

            <?php 
            
                if (isset($_GET["form_submitted"])) {
                    if (!empty($_GET["Inventory_ID"]) && !empty($_GET["VIN"]) && !empty($_GET["reserved"]) && !empty($_GET["vehprice"])) {
                        $Inventory_ID = $_GET["Inventory_ID"];
                        $VIN = $_GET["VIN"];
                        $reserved = $_GET["reserved"];
                        $price = $_GET["vehprice"];

                        $sql = $conn->prepare("INSERT INTO inventory (Inventory_ID, VIN, reserved, price) VALUES(?,?,?,?)");
                        $sql->bind_param("sssd", $Inventory_ID, $VIN, $reserved, $price);
                        $sql->execute();
                        echo $sql->error;
                        $sql->close();
                    } else {
                        echo "Please fill in all of the vehicle's information.";
                    }
                }

            ?>

            <div class="inventory-operations">
                <h1><a href="./inventory.php">View All Inventory</a></h1>
                <h1><a href="./inventory.php?query=forsale">View Vehicles Not Reserved</a></h1>
                <h1><a href="./inventory.php?query=new">View New Vehicles Only</a></h1>
                <h1><a href="./inventory.php?query=used">View Used Vehicles Only</a></h1>
                <div class="inventory-price">
                    <form action="inventory.php" method="get">
                        <h1>View Vehicles By Desired Price Limit:</h1> <input type="text" size="10" name="price">
                        <input type="submit" value="Go">
                    </form>
                </div>
                
            </div>


            <h2>Search results:</h2>
            <table class="content-table" id="myTable"> 

                <hr>

                <thead>
                    <tr>
                        <th>Inventory ID</th>
                        <th>Year</th>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Color</th>
                        <th>Mileage</th>
                        <th>Intake Date</th>
                        <th>Reserved</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                        $query = $_GET["query"];

                        if (empty($_GET["query"])) { // catch all
                            $sqlstatement = $conn->prepare("SELECT Inventory_ID, inventory.VIN, year, make, model, color, mileage, intake_date, reserved, price FROM inventory NATURAL JOIN vehicle");
                        } else if ($query == "forsale") {
                            $sqlstatement = $conn->prepare("SELECT Inventory_ID, inventory.VIN, year, make, model, color, mileage, intake_date, reserved, price FROM inventory NATURAL JOIN vehicle WHERE reserved = \"No\"");
                        } else if ($query == "used") {
                            $sqlstatement = $conn->prepare("SELECT Inventory_ID, inventory.VIN, year, make, model, color, mileage, intake_date, reserved, price FROM inventory NATURAL JOIN vehicle WHERE isnew = \"No\"");
                        } else if ($query == "new") {
                            $sqlstatement = $conn->prepare("SELECT Inventory_ID, inventory.VIN, year, make, model, color, mileage, intake_date, reserved, price FROM inventory NATURAL JOIN vehicle WHERE isnew = \"Yes\"");
                        } 
                        

                        if (!empty($_GET["price"])) {
                            $price = $_GET["price"];
                            $sqlstatement = $conn->prepare("SELECT Inventory_ID, inventory.VIN, year, make, model, color, mileage, intake_date, reserved, price FROM inventory NATURAL JOIN vehicle WHERE reserved = \"No\" AND price < ?");
                            $sqlstatement->bind_param("d", $price);
                        }

                    
                        $sqlstatement->execute();
                        $result = $sqlstatement->get_result();
        
                        $sqlstatement->close();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td><a href=\"./inventory.php?VININFO=".$row["VIN"]."\">".$row["Inventory_ID"]."</td>
                                <td>".$row["year"]."</td>
                                <td>".$row["make"]."</td>
                                <td>".$row["model"]."</td>
                                <td>".$row["color"]."</td>
                                <td>".$row["mileage"]."</td>
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
            
                if (!empty($_GET["VININFO"])) {
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
                            $VIN = $_GET["VININFO"];

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