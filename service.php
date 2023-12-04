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

        <title>Service at Charge</title>

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

        <section class="general-header" id="service-header">
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

                <h1>Service</h1>

            </div>

        </section>

        <section class="content">

            <div class="service-entry">
                <h1>Service Request Form:</h1>

                <form action="service.php" method="get">
                    <p>Enter A New Service ID:</p> <input type="text" size="17"  name="service_ID">
                    <p>Enter Vehicle VIN:</p> <input type="text" size="17" minlength="17" name="VIN">
                    <p>Enter Service Request:</p> <input type="text" size="20" name="service_type">
                    <p>Enter Service Request Date:</p> <input type="text" size="20" name="service_date">
                    <p>Enter Service Cost:</p> <input type="text" size="20" name="service_cost">                  
                    <p>Comments:</p> <input type="text" size="40" name="Comments" class="commentbox">
                    <br>
                    <input type="hidden" name="form_submitted" value="1">
                    <p><input type="submit" value="Submit"></p>
                </form>
            </div>

            <?php 
                if (isset($_GET["form_submitted"])) {
                    if (!empty($_GET["service_ID"]) && !empty($_GET["VIN"]) && !empty($_GET["service_type"]) && !empty($_GET["service_date"]) && !empty($_GET["service_cost"])) {
                        $service_ID = $_GET["service_ID"];
                        $VIN = $_GET["VIN"];
                        $service_type = $_GET["service_type"];
                        $service_date = $_GET["service_date"];
                        $service_cost = $_GET["service_cost"];
                        $comments = $_GET["comments"];

                        $sql = $conn->prepare("INSERT INTO service (service_ID, VIN, type, service_date, service_cost, comments) VALUES(?,?,?,?,?,?)"); 
                        $sql->bind_param("ssssss", $service_ID, $VIN, $service_type, $service_date, $service_cost, $comments);
                        $sql->execute();
                        echo $sql->error;
                        $sql->close();

                    } else {
                        echo "Please fill in all of the Service information.";
                    }
                }
                        
                        
            ?>
            <h2>All Customers:</h2>
            <table class="content-table" id="serviceTable"> 

                <hr>

                <thead>
                    <tr>
                        <th>Service ID</th>
                        <th>VIN</th>
                        <th>Service Type</th>
                        <th>Service Request Date</th>
                        <th>Service Cost</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 

                        $sqlstatement = $conn->prepare("SELECT customer.CUSTOMER_ID, first_name, last_name, email, phone_number, type FROM customer NATURAL JOIN customer_phone");
            
                        $sqlstatement->execute();
                        $result = $sqlstatement->get_result();

                        $sqlstatement->close();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                <td>".$row["service_ID"]."</td>
                                <td>".$row["VIN"]."</td>
                                <td>".$row["service_type"]."</td>
                                <td>".$row["service_date"]."</td>
                                <td>".$row["service_cost"]."</td>
                                <td>".$row["commentbox"]."</td>
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
                $('#serviceTable').DataTable();
            });
        </script>


    </body>

</html>