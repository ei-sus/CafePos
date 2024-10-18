<?php
include "conn.php"; // Include database connection

// Fetch categories from the database
$categoryResult = $conn->query("SELECT * FROM category");
$categories = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch ingredients from the database
$ingredientResult = $conn->query("SELECT * FROM ingredients");
$ingredients = [];
while ($row = $ingredientResult->fetch_assoc()) {
    $ingredients[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Café POS</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Sidebar styles */
        .sidebar {
            height: 100%;
            width: 250px; /* Default width when expanded */
            background-color: #634832;
            position: fixed;
            top: 60px; /* Adjusted for the top bar */
            left: 0;
            overflow-x: hidden;
            transition: width 0.3s ease;
            padding-top: 20px;
        }

        .sidebar.collapsed {
            width: 60px; /* Narrow bar when collapsed */
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            color: white;
            padding: 10px;
            background-color: #634832;
            border-bottom: 1px solid #85604a;
            transition: 0.3s ease;
        }

        .hamburger-menu {
            font-size: 24px;
            cursor: pointer;
            color: white;
            margin-right: 10px;
        }

        #sidebarTitle {
            font-size: 1.2rem;
            font-weight: bold;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed #sidebarTitle {
            display: none; /* Hide title when sidebar is collapsed */
        }

        .sidebar.collapsed .hamburger-menu {
            margin-right: 0;
            margin-left: 15px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s ease;
        }

        .sidebar.collapsed a {
            display: none;
        }

        .sidebar a:hover {
            background-color: #85604a;
        }

        /* Top bar */
        .topbar {
            height: 60px;
            width: 100%;
            background-color: #4b3621; /* Darker brown */
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            padding: 0 20px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .topbar .cafe-logo {
            display: flex;
            align-items: center;
        }

        .topbar .cafe-logo img {
            height: 40px;
            margin-right: 10px;
        }

        .topbar .user-profile {
            display: flex;
            align-items: center;
            color: white;
            cursor: pointer;
        }

        .user-profile img {
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
        }

        /* Main content container */
        .main {
            margin-left: 250px; /* Default margin when sidebar is expanded */
            margin-top: 60px; /* To avoid overlap with the top bar */
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        /* Adjust margin when the sidebar is collapsed */
        .sidebar.collapsed + .main {
            margin-left: 60px; /* Smaller margin when sidebar is collapsed */
        }

        /* Style for the Add New Product button */
        .add-product-btn {
            background-color: #634832;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            float: right;
            margin-bottom: 20px;
        }

        .add-product-btn:hover {
            background-color: #85604a;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
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

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <!-- Hamburger Menu and Café POS Name -->
            <span class="hamburger-menu" onclick="toggleSidebar()">&#9776;</span>
            <h2 id="sidebarTitle">Café POS</h2>
        </div>
        <br>
        <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
        <a href="products.php" class="active"><i class="fa fa-coffee"></i> Products</a>
        <a href="orders.php"><i class="fa fa-cart-plus"></i> Orders</a>
        <a href="sales.php"><i class="fa fa-bar-chart"></i> Sales</a>
        <a href="users.php"><i class="fa fa-user"></i> Users</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <!-- Display success message if a product was successfully added -->
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success">Product added successfully!</div>
        <?php } ?>

        <!-- Title and Add Product Button container -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Product Management</h2>
            <!-- Add New Product Button -->
            <button type="button" class="btn add-product-btn" data-bs-toggle="modal" data-bs-target="#myModal">
                Add New Product
            </button>
        </div>

        <!-- Modal for Adding New Product -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="productForm" method="POST" action="save_product.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" name="product_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category_id" required>
                                    <?php foreach ($categories as $category) { ?>
                                        <option value="<?php echo $category['category_id']; ?>">
                                            <?php echo $category['category_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="ingredient" class="form-label">Ingredients</label>
                                <select multiple class="form-select" id="ingredient" name="ingredients[]">
                                    <?php foreach ($ingredients as $ingredient) { ?>
                                        <option value="<?php echo $ingredient['ingredient_id']; ?>">
                                            <?php echo $ingredient['ingredient_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="cost_price" class="form-label">Cost Price</label>
                                <input type="number" class="form-control" id="cost_price" name="cost_price" required>
                            </div>

                            <div class="mb-3">
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input type="number" class="form-control" id="selling_price" name="selling_price" required>
                            </div>

                            <div class="mb-3">
                                <label for="formFile" class="form-label">Product Image</label>
                                <input class="form-control" type="file" id="formFile" name="product_image" required>
                            </div>

                            <button type="submit" class="btn btn-success">Save Product</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table for Product List -->
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Ingredients</th>
                    <th scope="col">Image</th>
                    <th scope="col">Cost Price</th>
                    <th scope="col">Selling Price</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic rows from database will be added here -->
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("collapsed");
        }
    </script>
</body>
</html>