<?php
include "conn.php";

// Fetch ingredients and their prices
$drinks_result = $conn->query("SELECT * FROM drinks_ingredients");
$ingredients = [];
while ($row = $drinks_result->fetch_assoc()) {
    $ingredients[] = [
        'item' => $row['item'],
        'id' => $row['id'], // Include ingredient ID
        'price_per_g_ml' => $row['price_per_g_ml']
    ]; 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve product data
    $productName = $_POST['product'];
    $category = $_POST['category'];
    $costPrice = $_POST['cost_price'];
    $sellingPrice = $_POST['selling_price'];
    $image = $_FILES['formFile']['name']; // Handle file upload if needed

    // Move uploaded file to the desired directory
    move_uploaded_file($_FILES['formFile']['tmp_name'], "uploads/" . $image); // Ensure 'uploads' directory exists

    // Insert product into products table
    $stmt = $conn->prepare("INSERT INTO products (product, category, image, cost_price, selling_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssidd", $productName, $category, $image, $costPrice, $sellingPrice);
    
    if ($stmt->execute()) {
        $productId = $stmt->insert_id; // Get the last inserted product ID

        // Insert ingredients into products_ingredients table
        if (isset($_POST['ingredients'])) {
            $ingredientsData = $_POST['ingredients'];

            foreach ($ingredientsData as $ingredient) {
                $ingredientName = $ingredient['ingredient'];
                $quantity = $ingredient['quantity'];
                $unit = $ingredient['unit'];

                // Retrieve ingredient_id
                foreach ($ingredients as $ing) {
                    if ($ing['item'] === $ingredientName) {
                        $ingredientId = $ing['id'];

                        // Insert into products_ingredients table
                        $ingredientInsertStmt = $conn->prepare("INSERT INTO products_ingredients (product_id, ingredient_id, ingredient, quantity, unit) VALUES (?, ?, ?, ?, ?)");
                        $ingredientInsertStmt->bind_param("iisss", $productId, $ingredientId, $ingredientName, $quantity, $unit);
                        $ingredientInsertStmt->execute();
                        $ingredientInsertStmt->close();
                        break;
                    }
                }
            }
        }

        // Return a success response
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting product']);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
?>



<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="sidebar"><br>
    <a href="index.php"><i class="fa fa-home"></i>   Dashboard</a>
    <a href="inventory.php"><i class="fa fa-qrcode"></i>   Inventory</a>
    <a href="products.php"><i class="fa fa-coffee"></i>   Products</a>
    <a href="order.php"><i class="fa fa-cart-plus"></i>   Order</a>
    <a href="sales.php"><i class="fa fa-bar-chart"></i>   Sales</a>
  </div>

<div class="main">
  <h2>Product Management</h2>
  
<button type="button" class="button button1" data-bs-toggle="modal" data-bs-target="#myModal">
  Add New Product
</button>

<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Add New Product</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
      <form id="productForm" method="POST" action="save_product.php">
        <label for="product" class="form-label">Product Name</label>
          <input type="text" class="form-control" id="product">
          <label for="category" class="form-label">Category & Ingredients</label>
          <select class="form-select" aria-label="Default select example" id="category">
            <option value="1">Hot Drink</option>
            <option value="2">Cold Drink</option>
            <option value="3">Pastry</option>
          </select>

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

        <div class="mb-3">
            <label for="formFile" class="form-label">Product Image</label>
            <input class="form-control" type="file" id="formFile" name="formFile" required>
        </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Product Name</th>
        <th scope="col">Category</th>
        <th scope="col">Ingredients</th>
        <th scope="col">Image</th>
        <th scope="col">Cost Price</th>
        <th scope="col">Selling Price</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Product 1</td>
        <td>Category 1</td>
        <td>Ingredient</td>
        <td>Img</td>
        <td>Cost Price</td>
        <td>Selling Price</td>
        <td>
          <button type="button" class="btn btn-primary">Edit</button>
          <button type="button" class="btn btn-danger">Delete</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<script>
    const ingredientContainer = document.getElementById('ingredientContainer');
    let totalCostPrice = 0;
    let ingredientCounter = 1; // Counter for ingredient fields

    document.getElementById('addIngredient').addEventListener('click', () => {
        // Get the current input values for the last ingredient added
        const ingredientNameSelect = document.querySelector('.ingredient-name:last-of-type');
        const ingredientQuantityInput = document.querySelector('.ingredient-quantity:last-of-type');
        const ingredientUnitSelect = document.querySelector('.ingredient-unit:last-of-type');

        // Check if an ingredient is selected and a quantity is provided
        if (ingredientNameSelect.value && ingredientQuantityInput.value && ingredientUnitSelect.value) {
            // Increment the counter for the next ingredient fields
            ingredientCounter++;

            // Add new ingredient row with incrementing names
            ingredientContainer.insertAdjacentHTML('beforeend', `
                <div class="form-row mt-2">
                    <div class="col">
                        <select class="form-select ingredient-name" name="ingredient${ingredientCounter}" required>
                            <option value="" disabled selected>Select an ingredient</option>
                            <?php foreach ($ingredients as $ingredient): ?>
                                <option data-price="<?php echo $ingredient['price_per_g_ml']; ?>" value="<?php echo htmlspecialchars($ingredient['item']); ?>"><?php echo htmlspecialchars($ingredient['item']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col"><input type="number" class="form-control ingredient-quantity" name="quantity${ingredientCounter}" placeholder="Quantity" required></div>
                    <div class="col">
                        <select class="form-control ingredient-unit" name="unit${ingredientCounter}" required>
                            <option value="" disabled selected>Select a unit</option>
                            <option value="g">g</option>
                            <option value="mL">mL</option>
                        </select>
                    </div>
                </div>
            `);

            // Calculate total cost price based on all ingredients
            totalCostPrice = 0; // Reset total cost price
            const ingredientNames = document.querySelectorAll('.ingredient-name');
            const ingredientQuantities = document.querySelectorAll('.ingredient-quantity');

            ingredientNames.forEach((select, index) => {
                const quantity = parseFloat(ingredientQuantities[index].value);
                if (select.value && quantity) {
                    const selectedOption = select.options[select.selectedIndex];
                    const ingredientPrice = parseFloat(selectedOption.getAttribute('data-price'));
                    totalCostPrice += ingredientPrice * quantity;
                }
            });

            // Update the total cost price field
            document.getElementById('cost_price').value = totalCostPrice.toFixed(2);
        } else {
            alert('Please fill in all fields before adding an ingredient.');
        }
    });
    document.querySelector('.btn-primary[data-bs-dismiss="modal"]').addEventListener('click', () => {
    const formData = new FormData(document.getElementById('productForm'));

    // Add ingredients to FormData
    const ingredientNames = document.querySelectorAll('.ingredient-name');
    const ingredientQuantities = document.querySelectorAll('.ingredient-quantity');
    const ingredientUnits = document.querySelectorAll('.ingredient-unit');

    ingredientNames.forEach((select, index) => {
        if (select.value && ingredientQuantities[index].value && ingredientUnits[index].value) {
            formData.append(`ingredients[${index}][ingredient]`, select.value);
            formData.append(`ingredients[${index}][quantity]`, ingredientQuantities[index].value);
            formData.append(`ingredients[${index}][unit]`, ingredientUnits[index].value);
        }
    });

    // Submit the form data
    fetch('save_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Handle success or error
        if (data.success) {
            alert('Product saved successfully!');
            // Optionally, you can refresh the page or update the table
        } else {
            alert('Error saving product: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

document.querySelector('.btn-primary[data-bs-dismiss="modal"]').addEventListener('click', (event) => {
    event.preventDefault(); // Prevent modal dismissal

    const formData = new FormData(document.getElementById('productForm'));

    // Add ingredients to FormData
    const ingredientNames = document.querySelectorAll('.ingredient-name');
    const ingredientQuantities = document.querySelectorAll('.ingredient-quantity');
    const ingredientUnits = document.querySelectorAll('.ingredient-unit');

    ingredientNames.forEach((select, index) => {
        if (select.value && ingredientQuantities[index].value && ingredientUnits[index].value) {
            formData.append(`ingredients[${index}][ingredient]`, select.value);
            formData.append(`ingredients[${index}][quantity]`, ingredientQuantities[index].value);
            formData.append(`ingredients[${index}][unit]`, ingredientUnits[index].value);
        }
    });

    // Submit the form data
    fetch('save_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Handle success or error
        if (data.success) {
            alert('Product saved successfully!');
            // Optionally close the modal or refresh the table
            $('#myModal').modal('hide'); // Close modal on success
        } else {
            alert('Error saving product: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>


</body>
</html>
