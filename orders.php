<?php
include "conn.php"; // Include database connection

// Fetch products from the database
$productResult = $conn->query("SELECT * FROM products");
$products = [];
while ($row = $productResult->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Café POS</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Orders page style */
        .product-card {
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            margin: 10px;
            transition: transform 0.3s;
            cursor: pointer;
        }

        .product-card:hover {
            transform: scale(1.05); /* Zoom in on hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            width: 100%;
            height: 150px;
            border-radius: 10px 10px 0 0;
        }

        .product-card h5 {
            margin: 10px 0;
        }

        .order-summary {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .order-summary h4 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Top Bar (same as dashboard) -->
    <div class="topbar">
        <div class="cafe-logo">
            <img src="logo.png" alt="Cafe Logo">
            <span>Café POS</span>
        </div>
        <div class="user-profile" onclick="window.location.href='profile.php'">
            <span>John Doe</span>
            <img src="profile.png" alt="User Profile">
        </div>
    </div>

    <!-- Sidebar (with smaller text and icons) -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="hamburger-menu" onclick="toggleSidebar()">&#9776;</span>
            <h2 id="sidebarTitle">Café POS</h2>
        </div>
        <br>
        <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
        <a href="products.php"><i class="fa fa-coffee"></i> Products</a>
        <a href="orders.php" class="active"><i class="fa fa-cart-plus"></i> Orders</a>
        <a href="sales.php"><i class="fa fa-bar-chart"></i> Sales</a>
        <a href="users.php"><i class="fa fa-user"></i> Users</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h2>Take an Order</h2>

        <!-- Product Grid -->
        <div class="d-flex flex-wrap">
            <?php foreach ($products as $product) { ?>
                <div class="product-card" onclick="addToOrder('<?php echo $product['product_name']; ?>', <?php echo $product['selling_price']; ?>)">
                    <img src="uploads/<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>">
                    <h5><?php echo $product['product_name']; ?></h5>
                    <p>$<?php echo number_format($product['selling_price'], 2); ?></p>
                </div>
            <?php } ?>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h4>Order Summary</h4>
            <div id="orderList"></div>
            <h5>Total: $<span id="totalAmount">0.00</span></h5>
            <button class="btn btn-success">Submit Order</button>
        </div>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript for Order Functionality -->
    <script>
        let totalAmount = 0;

        function addToOrder(productName, productPrice) {
            // Add product to order list
            const orderList = document.getElementById('orderList');
            const listItem = document.createElement('div');
            listItem.textContent = productName + ' - $' + productPrice.toFixed(2);
            orderList.appendChild(listItem);

            // Update total
            totalAmount += productPrice;
            document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
        }
    </script>
</body>
</html>