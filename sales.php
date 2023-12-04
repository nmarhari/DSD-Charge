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

        <title>Sales at Charge</title>

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

        <section class="general-header" id="sales-header">
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

                <h1>Sales</h1>

            </div>

        </section>

        <section class="content">

            <div class="sales-operations">
                <h1><a href="./sales.php#">View All Sales</a></h1>
                
            </div>

            <div class="sales-entry">
                <h1>Register a New Sale:</h1>

                <form action="sales.php" method="get">
                    <p>Enter a new Sale ID:</p> <input type="text" size="10" minlength="9" name="Sale_ID">
                    <p>Enter Agreed Price:</p> <input type="text" size="10" name="price">
                    <p>Enter Vehicle's VIN:</p> <input type="text" size="17" minlength="17" name="VIN">
                    <p>Enter Customer's ID:</p> <input type="text" size="10" minlength="9" name="Customer_ID">
                    <br>
                    <input type="hidden" name="form_submitted" value="1">
                    <input type="submit" value="Submit">
                </form>
            </div>
            
            <?php 
            
                if (isset($_GET["form_submitted"])) {
                    if (!empty($_GET["Sale_ID"]) && !empty($_GET["price"]) && !empty($_GET["VIN"]) && !empty($_GET["Customer_ID"])) {
                        $Sale_ID = $_GET["Sale_ID"];
                        $price = $_GET["price"];
                        $VIN = $_GET["VIN"];
                        $Customer_ID = $_GET["Customer_ID"];

                        $sql = $conn->prepare("INSERT INTO sales (SALE_ID, sale_price, VIN, CUSTOMER_ID) VALUES (?,?,?,?)"); 
                        $sql->bind_param("ssss", $Sale_ID, $price, $VIN, $Customer_ID);
                        $sql->execute();
                        echo $sql->error;
                        $sql->close();

                        $sqlVIN = $conn->prepare("DELETE FROM inventory WHERE VIN = ?");
                        $sqlVIN->bind_param("s", $VIN);
                        $sqlVIN->execute();
                        echo $sqlVIN->error;
                        $sqlVIN->close();
                        
                    } else {
                        echo "Please fill in all of the customer's information.";
                    }
                }

            ?>

            <h2>Sales History:</h2>
            <table class="content-table" id="myTable"> 

            <hr>

            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Agreed Price</th>
                    <th>Sale Date</th>
                    <th>VIN</th>
                    <th>Customer ID</th>
                </tr>
            </thead>
            <tbody>

                <?php 

                    $sqlstatement = $conn->prepare("SELECT SALE_ID, sale_price, sale_date, VIN, CUSTOMER_ID FROM sales");
                    
                    $sqlstatement->execute();
                    $result = $sqlstatement->get_result();
        
                    $sqlstatement->close();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                            <td>".$row["SALE_ID"]."</td>
                            <td>".$row["sale_price"]."</td>
                            <td>".$row["sale_date"]."</td>
                            <td><a href=\"./sales.php?VININFO=".$row["VIN"]."\">".$row["VIN"]."</td>
                            <td><a href=\"./sales.php?CUSTOMER_INFO=".$row["CUSTOMER_ID"]."\">".$row["CUSTOMER_ID"]."</td>
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

            <?php 
            
                if (!empty($_GET["CUSTOMER_INFO"])) {
                    echo "
                        <h1>Selected Customer's Info</h1>
                        <div class=\"customer-info\">
                            <table class=\"content-table\" id=\"myTable\"> 

                    

                            <hr>
            
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone Number(s)</th>
                                    <th>Phone Type</th>
                                </tr>
                            </thead>
                            <tbody>";
                            
                            $cinfo = $conn->prepare("SELECT customer.CUSTOMER_ID, first_name, last_name, email, phone_number, type FROM customer NATURAL JOIN customer_phone WHERE CUSTOMER_ID = ?");
                            
                            $cID = $_GET["CUSTOMER_INFO"];
                            
                            $cinfo->bind_param("s", $cID);

                            $cinfo->execute();
                            $result = $cinfo->get_result();
                
                            $cinfo->close();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                    <td>".$row["CUSTOMER_ID"]."</td>
                                    <td>".$row["first_name"]."</td>
                                    <td>".$row["last_name"]."</td>
                                    <td>".$row["email"]."</td>
                                    <td>".$row["phone_number"]."</td>
                                    <td>".$row["type"]."</td>
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