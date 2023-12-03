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

        <title>Customers with Charge</title>

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

        <section class="general-header" id="customers-header">
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

                <h1>Customers</h1>

            </div>

        </section>

        <section class="content">

            <div class="customer-entry">
                <h1>New Customer Entry Form:</h1>

                <form action="customers.php" method="get">
                    <p>Enter a new Customer ID:</p> <input type="text" size="10" minlength="9" name="Customer_ID">
                    <p>Enter Customer's First Name:</p> <input type="text" size="20" name="first_name">
                    <p>Enter Customer's Last Name:</p> <input type="text" size="20" name="last_name">
                    <p>Enter Customer's Email:</p> <input type="text" size="40" minlength="5" name="email">
                    <p>Enter Customer's Phone Number:</p> <input type="text" size="15" minlength="5" name="phone_number">
                    <p>Enter Customer's Phone Type:</p> <input type="text" size="10" name="type">
                    <input type="hidden" name="form_submitted" value="1">
                    <input type="submit" value="Submit">
                </form>
            </div>

            <?php 
            
                if (isset($_GET["form_submitted"])) {
                    if (!empty($_GET["Customer_ID"]) && !empty($_GET["first_name"]) && !empty($_GET["last_name"]) && !empty($_GET["email"]) && !empty($_GET["phone_number"]) && !empty($_GET["type"])) {
                        $Customer_ID = $_GET["Customer_ID"];
                        $first_name = $_GET["first_name"];
                        $last_name = $_GET["last_name"];
                        $email = $_GET["email"];
                        $phone_number = $_GET["phone_number"];
                        $type = $_GET["type"];

                        $sql = $conn->prepare("INSERT INTO customer (CUSTOMER_ID, first_name, last_name, email) VALUES(?,?,?,?)"); 
                        $sql->bind_param("ssss", $Customer_ID, $first_name, $last_name, $email);
                        $sql->execute();
                        echo $sql->error;
                        $sql->close();

                        $sqlphone = $conn->prepare("INSERT INTO customer_phone (CUSTOMER_ID, phone_number, type) VALUES (?,?,?)");
                        $sqlphone->bind_param("sss", $Customer_ID, $phone_number, $type);
                        $sqlphone->execute();
                        echo $sqlphone->error;
                        $sqlphone->close();
                        
                    } else {
                        echo "Please fill in all of the customer's information.";
                    }
                }

            ?>

            <h2>All Customers:</h2>
            <table class="content-table" id="myTable"> 

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
            <tbody>

                <?php 

                    $sqlstatement = $conn->prepare("SELECT customer.CUSTOMER_ID, first_name, last_name, email, phone_number, type FROM customer NATURAL JOIN customer_phone");
                    
                    $sqlstatement->execute();
                    $result = $sqlstatement->get_result();
        
                    $sqlstatement->close();

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