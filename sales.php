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

                <h1>Sales</h1>

            </div>

        </section>

        <section class="content">


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