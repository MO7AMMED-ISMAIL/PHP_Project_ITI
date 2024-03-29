<?php

session_start();


if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $user_id = $_SESSION['user_id'];
} else {

    header('Location: login.php');
    exit();
}

if (isset($_GET['logout'])) {

    session_unset();

    header('Location: login.php');
    exit();
}


require "../../BackEnd/DataBase/DBCLass.php";

use DbClass\Table;

$orderTable = new Table('orders');

//latest order for the user
$latestOrderQuery = $orderTable->Select(['*'], "user_id = $user_id AND status = 'Done' ORDER BY order_date DESC LIMIT 1");
$latestOrder = $latestOrderQuery->fetch(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafeteria App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>

<body>
    <!--cafe name-->
    <div id="Home" class="mainhome jumbotron jumbotron-fluid bg-cover d-flex align-items-center">
        <!-- Navigation bar -->
        <nav id="navbar" class="navbar navbar-expand-lg navbar-dark" style="background-color:transparent;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <!-- User image and name -->
                    <div class="col-auto">
                        <div class="d-flex align-items-center">

                            <!-- user image -->
                            <?php
                            $userTable = new Table('users');
                            $userDataQuery = $userTable->Select(['profile_picture', 'username'], 'id = ' . $user_id);
                            $userData = $userDataQuery->fetch(PDO::FETCH_ASSOC);

                            if ($userData && isset($userData['profile_picture'])) {
                                echo '<img id="userimg" src="../../BackEnd/uploads/' . $userData['profile_picture'] . '" alt="User Image" class="img-fluid rounded-circle mr-2">';
                            }
                            ?>
                            <!-- username -->
                            <?php
                            if ($userData && isset($userData['username'])) {
                                echo '<p class="text-white mb-0">' . $userData['username'] . '</p>';
                            }
                            ?>
                        </div>
                    </div>


                    <!-- Search input and button -->
                    <div class="col-auto ml-auto">
                        <div class="input-group d-none d-lg-flex">
                            <form class="input-group d-none d-lg-flex" action="productinfo.php" method="GET">
                                <input type="text" id="productNameInput" name="search" class="form-control" placeholder="Search for products...">
                                <div class="input-group-append">
                                    <button id="searchButton" class="lince btn btn-primary" type="submit">Search</button>
                                </div>
                            </form>

                        </div>
                    </div>


                    <!-- Nav icon  -->
                    <div class="col-auto">
                        <button id="navToggle" class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <ul class="navbar-nav" style="margin-top:3%;">
                                <li class="nav-item">
                                    <a class="nav-link text-light" href="#Home" style="width:100%;">Home</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-light" href="menu.php" style="width:100%;">Menu</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-light" href="#Latestorder" style="width:100%;">Latest Order</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-light" href="#productSection" style="width:100%;">Order now</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-light" href="order.php" style="width:100%;">My 0rders</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-light" href="index.php?logout=1" style="width:100%;">Log out</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Nav drawer -->
        <div id="sideNav" class="nav-drawer d-lg-none">
            <ul class="mt-4">
                <li>
                    <!-- Search input and button -->
                    <form class="input-group" action="productinfo.php" method="GET">
                        <input type="text" id="productNameInput" name="search" class="form-control" placeholder="Search for products...">
                        <div class="input-group-append">
                            <button id="searchButton" class="lince btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </li>


                <li><a href="#Home">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="#Latestorder">Latest Order</a></li>
                <li><a href="#productSection">Order now</a></li>
                <li><a href="order.php">My 0rders</a></li>
                <li><a onclick="logout();" href="login.php">Log out</a></li>
            </ul>

            <button id="navClose" class="btn btn-outline-light mb-2 ml-2">Close</button>
        </div>

        <div class="container">
            <h1 class="display-4 my-5" style="font-style: italic; font-size: 10.7em; color: rgba(237, 243, 246, 0.753);">Cafeto</h1>
            <div id="slogann">
                <p id="sloganText" class="lead" style="font-style: italic;color: rgba(237, 243, 246, 0.753); font-size: 1.5em;">Discover Delight, Taste the Moment: Your Café, Your Culinary Journey!</p>
            </div>
        </div>
    </div>


    <!-- Latest Order Section -->
    <?php if ($latestOrder) : ?>
        <div id="Latestorder" class="container mt-4 my-5" style="padding-top: 3%;">
            <div class="row my-5">
                <h1 class="col-12 my-5 text-center text-light" style="padding: 1.5%; background-color: rgba(71, 44, 8, 0.816);">Your Latest orders</h1>
            </div>
            <div class="row">
                <div class="card-deck" style="width: 100%;">
                    <?php
                    // order items for the latest order
                    $orderItemsTable = new Table('order_items');
                    $orderItemsQuery = $orderItemsTable->Select(['product_id', 'product_price'], 'order_id = ' . $latestOrder['id']);
                    $orderItems = $orderItemsQuery->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($orderItems as $item) {
                        $productTable = new Table('products');
                        $productQuery = $productTable->Select(['name', 'picture', 'price'], 'id = ' . $item['product_id']);
                        $product = $productQuery->fetch(PDO::FETCH_ASSOC);

                        // Display product
                        if ($product) : ?>
                            <div class="col-md-3 col-8 offset-2 offset-md-0 my-5 my-md-0 text-center">
                                <div class="card mb-3" style="max-width: 250px; position: relative;">
                                    <img src="../../BackEnd/uploads/<?php echo $product['picture']; ?>" class="card-img-top" alt="Product Image">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                        <span class="badge bg-success text-light" style="position: absolute; top: 0; right: 0;">Price: $<?php echo $product['price']; ?></span>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endif;
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <!-- Product section -->
    <div id="productSection" class="container mt-4 text-center" style="padding:0%; padding-top:9%;">
        <div class="row">
            <h1 class="col-12 text-center text-light" style="padding: 1.5%; background-color: rgba(71, 44, 8, 0.816);">Order Now</h1>
        </div>
        <div class="row">
            <!-- Product Table -->
            <div class="col-md-8 col-10 my-md-0 my-5 mx-md-0 " style="padding:0%;">
                <div id="productContainer" class="my-5">
      <?php

$productTable = new Table('products');

$productQuery = $productTable->Select(['*'], 'status = "Available"');
$products = $productQuery->fetchAll(PDO::FETCH_ASSOC);

if (!empty($products)) {
    $itemsPerPage = 9; // 3 items for row, 3 rows
    $numPages = ceil(count($products) / $itemsPerPage);

    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $startIdx = ($currentPage - 1) * $itemsPerPage;
    $endIdx = $startIdx + $itemsPerPage;

    echo '<div class="row">';
    for ($i = $startIdx; $i < $endIdx && $i < count($products); $i++) {
        $product = $products[$i];
        echo '<div class="col-md-4 col-12 my-5">';
        echo '<div class="card">';
        echo '<input type="hidden"  name="product_id" class="product-id" value="' . $product['id'] . '">';
        echo '<img src="../../BackEnd/uploads/' . $product['picture'] . '" class="card-img-top" alt="Product Image">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $product['name'] . '</h5>';
        echo '<p class="card-text">Price:$ ' . $product['price'] . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        if (($i + 1) % 3 == 0) {
            echo '</div><div class="row">';
        }
    }

    echo '</div>';
} else {
    echo "<h1 class='text-danger'>Sorry, no products found yet😔</h1>";
}
?>

     </div>

               <div class="text-center mt-3">
                    <button class="btn btn-primary " id="prevPage">Back</button>
                    <button class="btn btn-primary ml-2" id="nextPage">Next</button>
                </div>

            </div>

            <!-- Order form -->
            <div class="col-md-4 my-5 my-md-0 col-10 offset-md-0 offset-3" style=" padding-top:4%;">

                <form id="orderForm" class="order-form formbtn" action="addOrder.php" method="post">
                    <div class="form-group">
                        <label for="selectedProducts">Selected Products</label>
                        <div id="selectedProducts"></div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="room">Room</label>
                        <select class="form-control" id="room" name="room">
                            <?php
                            try {
                                $table = new Table('rooms');
                                $roomNumbersQuery = $table->Select(['id', 'room_number']);
                                $roomNumbers = $roomNumbersQuery->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($roomNumbers as $room) {
                                    echo "<option  value='" . $room['id'] . "'>" . $room['room_number'] . "</option>";
                                }
                            } catch (Exception $e) {
                                echo "<option value=''>Error fetching rooms</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="totalPrice">Total Price</label>
                        <input type="text" class="form-control" id="totalPrice" name="totalPrice" readonly>
                    </div>

                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <div class="form-group">
                        <div class="d-flex flex-column">
                            <button type="submit" class=" order btn btn-primary mb-2 text-light " id="orderButton" disabled>Order</button>
                            <button type="button" class="btn btn-danger text-light " id="removeAllProducts">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>



 <!--footer-->

<div class="container my-5 footer-container">
    <div class="row">
        <h1 class="col-12 text-center" style="font-style:italic;margin-top:15%; color:rgba(43, 31, 6, 0.973);">
            Crafting Memories, One Cup at a Time
        </h1>
    </div>
    <div class="row my-5"> 
        <div class="col-3">
            <div class="image-container">
                <img src="images/instagram2.jpg" alt="Instagram Image">
            </div>
        </div>
        
        <div class="col-3">
            <div class="image-container">
                <img src="images/instagram3.jpg" alt="Instagram Image">
            </div>
        </div>

        <div class="col-3">
            <div class="image-container">
                <img src="images/instagram4.jpg" alt="Instagram Image">
            </div>
        </div>

        <div class="col-3">
            <div class="image-container">
                <img src="images/instagram6.jpg" alt="Instagram Image">
            </div>
        </div>

    </div>
</div>
  


            <!-- About Section -->
            <div class=" container my-5" style="padding-top:5%;">
                <div class="about row">
                    <div class="col-md-4 col-5 text-center ">
                        <h5 class="text-light" style="font-size:1.8em;">Help & Information</h5>
                        <ul class="list-unstyled">
                            <li class="my-5"><a class="text-light" href="#">About Us</a></li>
                            <li class="my-5"><a class="text-light" href="#">Privacy Policy</a></li>
                            <li class="my-5"><a class="text-light" href="#">Terms & Conditions</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-3 text-center">
                        <h5 class="text-light" style="font-size:1.8em;">About Us</h5>
                        <ul class="list-unstyled">
                            <li class="my-5"><a class="text-light" href="#">Terms & Conditions</a></li>
                            <li class="my-5"><a class="text-light" href="#">Contact</a></li>
                            <li class="my-5"><a class="text-light" href="#">Home Page</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-3 text-center">
                        <h5 class="text-light" style="font-size:1.8em;">Categories</h5>
                        <ul class="list-unstyled">
                            <li class="my-5"><a class="text-light" href="#">Privacy Policy</a></li>
                            <li class="my-5"><a class="text-light" href="#">Home Page</a></li>
                            <li class="my-5"><a class="text-light" href="#">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


            <script>
                //display product at tpage
                document.addEventListener('DOMContentLoaded', function() {
                    const productContainer = document.getElementById('productContainer');
                    const prevButton = document.getElementById('prevPage');
                    const nextButton = document.getElementById('nextPage');

                    const itemsPerPage = 9; //products per page
                    let currentPage = <?php echo $currentPage; ?>;
                    let numPages = <?php echo $numPages; ?>;
                    let products = <?php echo json_encode($products); ?>;

                    function displayProducts(page) {
                        const startIdx = (page - 1) * itemsPerPage;
                        const endIdx = Math.min(startIdx + itemsPerPage, products.length);

                        let html = '<div class="row">';
                        for (let i = startIdx; i < endIdx; i++) {
                            const product = products[i];
                            html += '<div class="col-md-4">';
                            html += '<div class="card">';
                            html += '<input type="hidden" name="product_id" class="product-id" value="' + product.id + '">';
                            html += '<img src="../../BackEnd/uploads/' + product.picture + '" class="card-img-top" alt="Product Image">';
                            html += '<div class="card-body">';
                            html += '<h5 class="card-title">' + product.name + '</h5>';
                            html += '<p id="price" class="card-text">Price$: ' + product.price + '</p>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                        }
                        html += '</div>';
                        productContainer.innerHTML = html;
                        //previous and next button
                        if (currentPage === 1) {
                            prevButton.disabled = true;
                        } else {
                            prevButton.disabled = false;
                        }

                        if (currentPage === numPages) {
                            nextButton.disabled = true;
                        } else {
                            nextButton.disabled = false;
                        }
                    }

                    prevButton.addEventListener('click', function() {
                        if (currentPage > 1) {
                            currentPage--;
                            displayProducts(currentPage);
                        }
                    });

                    nextButton.addEventListener('click', function() {
                        if (currentPage < numPages) {
                            currentPage++;
                            displayProducts(currentPage);
                        }
                    });

                    displayProducts(currentPage);
                });


            </script>

            <script src="js/scriptnavimg.js"></script>

            <script src="js/script.js"></script>
        </div>
    </div>

</body>

</html>