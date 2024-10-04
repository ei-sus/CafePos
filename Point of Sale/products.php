<?php
include "conn.php";

// Fetch ingredients
$drinks_result = $conn->query("SELECT * FROM drinks_ingredients");
$pastries_result = $conn->query("SELECT * FROM pastries_ingredients");

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
        <form>
          <label for="product" class="form-label">Product Name</label>
          <input type="text" class="form-control" id="product">
          <label for="category" class="form-label">Category</label>
          <select class="form-select" aria-label="Default select example" id="category">
            <option value="1">Hot Drink</option>
            <option value="2">Cold Drink</option>
            <option value="3">Pastry</option>
          </select>
          <div id="ingredientContainer" class="form-row" style="margin-left:-5px; margin-right:-5px; margin-top:5px">
            <div class="col"><input type="text" class="form-control" placeholder="Ingredient" required></div>
            <div class="col"><input type="number" class="form-control" placeholder="Quantity" required></div>
            <div class="col">
                <select class="form-control" required>
                    <option value="" disabled selected>Select a unit</option>
                    <option value="g">g</option>
                    <option value="mL">mL</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn btn-primary mt-3" id="addIngredient">Add Ingredient</button>
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
          <input class="form-control" type="file" id="formFile">
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
    document.getElementById('addIngredient').addEventListener('click', () => {
        ingredientContainer.insertAdjacentHTML('beforeend', `
              <div id="ingredientContainer" class="form-row"style="margin-left:0px; margin-right:0px; margin-top:5px">
                <div class="col"><input type="text" class="form-control" placeholder="Ingredient" required></div>
                <div class="col"><input type="number" class="form-control" placeholder="Quantity" required></div>
                <div class="col">
                    <select class="form-control" required>
                        <option value="" disabled selected>Select a unit</option>
                        <option value="g">g</option>
                        <option value="mL">mL</option>
                    </select>
                </div>
              </div>
            `);
    });

    document.getElementById('ingredientForm').addEventListener('submit', e => {
        e.preventDefault();
        alert('Form submitted!');
    });
</script>

</body>
</html> 
