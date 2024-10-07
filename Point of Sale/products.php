<?php
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if product data is set
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';

    // Handle file upload
    if (isset($_FILES['formFile']) && $_FILES['formFile']['error'] == 0) {
        $imageTempPath = $_FILES['formFile']['tmp_name'];
        $imageContent = file_get_contents($imageTempPath); // Read the image file
        $imageContent = base64_encode($imageContent); // Encode image to base64 for storage

        // Insert product into products table
        $stmt = $conn->prepare("INSERT INTO products (product_name, category, product_image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $product_name, $category, $imageContent);
        $stmt->execute(); // Execute the statement
        $stmt->close();

        // Return success message as JSON
        echo json_encode(['success' => true, 'message' => 'Product saved successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error uploading image: ' . $_FILES['formFile']['error']]);
    }
    exit; // Prevent further output
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="sidebar"><br>
    <a href="index.php"><i class="fa fa-home"></i> Dashboard</a>
    <a href="inventory.php"><i class="fa fa-qrcode"></i> Inventory</a>
    <a href="products.php"><i class="fa fa-coffee"></i> Products</a>
    <a href="order.php"><i class="fa fa-cart-plus"></i> Order</a>
    <a href="sales.php"><i class="fa fa-bar-chart"></i> Sales</a>
  </div>

  <div class="main">
    <h2>Product Management</h2>
    <button type="button" class="button button1" data-bs-toggle="modal" data-bs-target="#productModal">
      Add New Product
    </button>

    <!-- Modal for Basic Product Info -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="productModalLabel">Add New Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="basicProductForm" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="Hot Drink">Hot Drink</option>
                                <option value="Cold Drink">Cold Drink</option>
                                <option value="Pastry">Pastry</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Product Image</label>
                            <input class="form-control" type="file" id="formFile" name="formFile" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="saveProductBtn">Save Product</button>
                            <button type="button" class="btn btn-danger" id="close" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                    
                    <!-- Ingredient Form (Initially Hidden) -->
                    <form id="ingredientForm" method="POST" enctype="multipart/form-data" style="display: none;">
                        <!-- Ingredient Section -->
                        <div id="ingredientContainer" class="form-row" style="margin-left:-5px; margin-right:-5px; margin-top:5px">
                            <div class="col">
                                <select class="form-select ingredient-name" required>
                                    <option value="" disabled selected>Ingredient</option>
                                    <?php foreach ($ingredients as $ingredient): ?>
                                        <option data-price="<?php echo $ingredient['price_per_g_ml']; ?>" value="<?php echo htmlspecialchars($ingredient['item']); ?>"><?php echo htmlspecialchars($ingredient['item']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col"><input type="number" class="form-control ingredient-quantity" placeholder="Quantity" required></div>
                            <div class="col">
                                <select class="form-control ingredient-unit" required>
                                    <option value="" disabled selected>Unit</option>
                                    <option value="g">g</option>
                                    <option value="mL">mL</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-3" id="addIngredient">Add Ingredient</button>
                        
                        <!-- Cost and Selling Prices -->
                        <div class="form-row mt-2">
                            <div class="col">
                                <label for="cost_price" class="form-label">Cost Price</label>
                                <input type="number" class="form-control" id="cost_price" readonly>
                            </div>
                            <div class="col">
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input type="number" class="form-control" id="selling_price">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Display any success/error messages -->
    <div id="message" class="mt-3"></div>
</div>

<script>
document.getElementById('saveProductBtn').addEventListener('click', function() {
    const form = document.getElementById('basicProductForm');
    if (form.checkValidity()) {
        const formData = new FormData(form); // Create FormData object

        // Use AJAX to submit the form
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('saveProductBtn').style.display = 'none';
                document.getElementById('close').style.display = 'none';

                const ingredientLabel = document.createElement('h5');
                ingredientLabel.textContent = 'Ingredients';
                ingredientLabel.classList.add('mt-3'); // Add margin top for spacing
                document.querySelector('.modal-body').insertBefore(ingredientLabel, document.getElementById('ingredientForm'));

                // Show ingredient form after saving product
                document.getElementById('ingredientForm').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('message').innerHTML = '<div class="alert alert-danger">An error occurred while saving the product.</div>';
        });
    } else {
        form.reportValidity();
    }
});


</script>

</body>
</html>
