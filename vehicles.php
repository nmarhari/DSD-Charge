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

                <h1>Vehicles</h1>

            </div>

        </section>

        <section class="content">

            <div class="vehicle-entry">
                <h1>New Vehicle Entry Form:</h1>

                <form action="vehicles.php" method="get">
                    <p>Enter Vehicle VIN:</p> <input type="text" size="17" minlength="17" name="VIN">
                    <p>Enter Vehicle Year:</p> <input type="text" size="4" name="year">
                    <p>Enter Vehicle Make:</p> <input type="text" size="20" name="make">
                    <p>Enter Vehicle Model:</p> <input type="text" size="20" name="model">
                    <p>Enter Vehicle Color:</p> <input type="text" size="20" name="color">
                    <p>Enter Vehicle Mileage:</p> <input type="text" size="9" name="mileage">
                    <p>Is the Vehicle New?</p>
                    <input type="radio" name="isnew" value="Yes">Yes</input>
                    <input type="radio" name="isnew" value="No" checked>No</input>
                    <p>Is the Vehicle a Custom Vehicle?</p>
                    <input type="radio" name="custom" value="Yes">Yes</input>
                    <input type="radio" name="custom" value="No" checked>No</input>
                    <br>
                    <input type="hidden" name="form_submitted" value="1">
                    <input type="submit" value="Submit">
                    
            </div>

            <?php 
            
                if (isset($_GET["form_submitted"])) {
                    if (!empty($_GET["VIN"]) && !empty($_GET["year"]) && !empty($_GET["make"]) && !empty($_GET["model"]) && !empty($_GET["color"]) && !empty($_GET["mileage"]) && !empty($_GET["isnew"]) && !empty($_GET["custom"])) {
                        $VIN = $_GET["VIN"];
                        $year = $_GET["year"];
                        $make = $_GET["make"];
                        $model = $_GET["model"];
                        $color = $_GET["color"];
                        $mileage = $_GET["mileage"];
                        $isnew = $_GET["isnew"];
                        $custom = $_GET["custom"];

                        $sql = $conn->prepare("INSERT INTO vehicle values(?,?,?,?,?,?,?,?)");
                        $sql->bind_param("sisssdss", $VIN, $year, $make, $model, $color, $mileage, $isnew, $custom);
                        $sql->execute();
                        echo $sql->error;
                        $sql->close();
                    } else {
                        echo "Please fill in all of the vehicle's information.";
                    }
                }

            ?>


            <h2>Current Vehicles Registered at Charge</h2>
            <table class="content-table" id="myTable"> 

               

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
                <tbody>
                    <?php 
                    
                        $sqlstatement = $conn->prepare("SELECT VIN, year, make, model, color, mileage, isnew, custom from vehicle");
                    
                        $sqlstatement->execute();
                        $result = $sqlstatement->get_result();
        
                        $sqlstatement->close();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
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
                    ?>
                </tbody>
            </table>

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