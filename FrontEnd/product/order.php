<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>My Orders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" media="screen" href="../assets/css/styles.css" />
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/My_Order.css">
</head>

<?php
session_start();
require "../../BackEnd/DataBase/DBCLass.php";

use DbClass\Table;

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $user_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit();
}

$totalAmount = 0;
if (isset($_GET['start']) && isset($_GET['end']) && !empty($_GET['start']) && !empty($_GET['end'])) {
    $start = $_GET['start'];
    $end = $_GET['end'];
    $orders = new Table('orders');
    $cond = "rooms.id=orders.room_id And user_id= '$user_id' And orders.order_date BETWEEN '$start' AND '$end'";
    $userOrder =$orders->SelectInnerJoinTable("rooms",["room_number"],["*"],$cond);
} else {
    $orders = new Table('orders');
    $userOrder =$orders->SelectInnerJoinTable("rooms",["room_number"],["*"],"rooms.id=orders.room_id And user_id= '$user_id'");
}
?>


<body>
    <!--cafe name-->
    <div id="Home" class="mainhome jumbotron jumbotron-fluid bg-cover d-flex align-items-center">
        <!-- Navigation bar -->
        <nav id="navbar" class="navbar navbar-expand-lg navbar-dark" style="background-color:transparent;">
            <div class="container-fluid">
                <div class="row  align-items-center">
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
                    <div class="col-auto ms-auto">
                        <div class="input-group d-none d-lg-flex">
                            <form class="input-group d-none d-lg-flex" action="productinfo.php" method="GET">
                                <input type="text" id="productNameInputt" name="search" class="form-control" placeholder="Search for products...">
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
                                    <a class="nav-link text-light" href="index.php#Home" style="width:100%;">Home</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-light" href="menu.php" style="width:100%;">Menu</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-light" href="index.php#Latestorder" style="width:100%;">Latest Order</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link text-light" href="index.php#productSection" style="width:100%;">Order now</a>
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
                <li><a href="index.php#Latestorder">Latest Order</a></li>
                <li><a href="index.php#productSection">Order now</a></li>
                <li><a href="order.php">My 0rders</a></li>
                <li><a onclick="logout();" href="login.php">Log out</a></li>
            </ul>

            <button id="navClose" class="btn btn-outline-light mb-2 ml-2">Close</button>
        </div>


        <div class="container">
            <h1 class="display-4 my-5" style="font-style: italic; font-size: 10em; color: rgba(237, 243, 246, 0.753);">Orders</h1>
            <p class="lead text-light">Indulge Your Senses, Order with Ease: Your Café Delights Await!</p>
        </div>
    </div>


    <!--ordertable-->
    <main class="my-orders my-5">
        <section class="main-padding">
            <div class="container py-5">
                <div class="card shadow p-3">
                    <div class="card-body">
                        <form action="" method="GET" id="searchForm">
                            <input type="hidden" name="userId" value="<?= $user_id ?>" />
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="from-group">
                                        <label for="start">Date from:</label>
                                        <input type="date" class="form-control start" name="start" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="end">Date to:</label>
                                        <input type="date" class="form-control end" name="end" />
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-2">
                                    <button id="search" type="submit" class="btn btn-primary mx-2 text-light">Search</button>
                                    <a href="order.php" class="btn btn-danger mt-2">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

<section class="main-padding">
     <div class="container">
         <div class="user-orders">
              <table class="table table-hover shadow">
                 <thead class="thead-light">
                      <tr>
                          <th scope="col">Order Date</th>
                          <th scope="col">Status</th>
                          <th scope="col">Amount</th>
                          <th scope="col">Action</th>
                     </tr>
                 </thead>
                    <tbody id="orderTableBody">
                         <?php if ($userOrder->rowCount()) {
                         foreach ($userOrder as $order) { ?>
                         <tr class="order">
                            <td>
                                <span><?= $order['order_date'] ?></span>
                                <i class="fa fa-plus-square mx-5"></i>
                            </td>

                             <td class="Processing">
                                    <?php
                                    if ($order['status'] == 'Processing') { ?>
                                        <i class="btn btn-warning"></i>
                                        <?= $order['status'] ?>
                                    <?php } else { ?>
                                        <?= $order['status'] ?>
                                    <?php } ?>
                                </td>

                                <td>
                                        <span>
                                            <?php
                                            echo $order['total_price'];
                                            ?>
                                        </span> $
                                </td>
                                <td>
                          <?php
                            if ($order["status"] == 'Processing') {
                                ?>
                                <a href='cancel_order.php?order_id=<?= $order['id'] ?>' class='cancel btn btn-danger'>Cancel</a>
                             <?php } ?>
                           </td>
                            </tr>

                     <tr class="cart-item details-hidden">
                         <?php
                             $order_id = $order['id'];
                             $orderItems = $orders->UserOrders($user_id, "orders.id = $order_id");
                            foreach ($orderItems as $orderItem) { ?>
                              <td>
                         <div class="cart-item-details">
                             <div class="cart-item-info d-flex justify-content-start align-items-center">
                                  <div class="card shadow position-relative mb-3" style="width: 15rem;">
                                         <img class="card-img-top" src="../../BackEnd/uploads/<?= $orderItem['picture'] ?>" alt="Product Name">
                                          <div class="card-body text-center">
                                             <h5 class="card-title">
                                              <?= $orderItem['name'] ?>
                                            </h5>
                                           <p class="card-text">
                                           <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                 <?= $orderItem['price'] ?>$</span><br>
                                                     Quantity: <?= $orderItem['quantity'] ?><br>
                                                     Total: <?= $orderItem['price'] * $orderItem['quantity'] ?> $
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?php }; ?>
                            <?php } else { ?>
                               <div class="alert alert-warning" role="alert">No orders found in this date ....</div>
                            <?php }; ?>
                        </tbody>
                    </table>
                    <div class="total-price">
                        <h3 class="text-light">Total (Tax 10%)</h3>
                        <h4 class="text-light"><span id="totalAmount" class="text-light"><?= $totalAmount ?></span> $</h4>
                    </div>
                </div>
            </div>
        </section>

        </div>

    </main>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="js/My_Order.js"></script>
    <script src="js/scriptnavimg.js"></script>


</body>


</html>
