<?php
include "conn.php";

// Add new ingredient
if (isset($_POST['save'])) {
    $type = $_POST['type'];

    if ($type === 'drinks') {
        $item_d = $_POST['item_d'];
        $quantity_d = $_POST['quantity_d'];
        $unit_d = $_POST['unit_d'];
        $price_d = $_POST['price_d'];

        $stmt = $conn->prepare("INSERT INTO drinks_ingredients (item_d, quantity_d, unit_d, price_d) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $item_d, $quantity_d, $unit_d, $price_d);
        $stmt->execute();
        $stmt->close();
    }
    elseif ($type === 'pastries') {
        $item_p = $_POST['item_p'];
        $quantity_p = $_POST['quantity_p'];
        $price_p = $_POST['price_p'];

        $stmt = $conn->prepare("INSERT INTO pastries_ingredients (item_p, quantity_p, price_p) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $item_p, $quantity_p, $price_p);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: inventory.php");
    exit();
}

// Delete ingredient 
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    if ($type === 'drinks') {
        $stmt = $conn->prepare("DELETE FROM drinks_ingredients WHERE id = ?");
    } elseif ($type === 'pastries') {
        $stmt = $conn->prepare("DELETE FROM pastries_ingredients WHERE id = ?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: inventory.php");
    exit();
}

// Update ingredient logic
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'] ?? null;
    $price = $_POST['price'];
    $type = $_POST['type'];

    if ($type === 'drinks') {
        $stmt = $conn->prepare("UPDATE drinks_ingredients SET item_d = ?, quantity_d = ?, unit_d = ?, price_d = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $item, $quantity, $unit, $price, $id);
    } elseif ($type === 'pastries') {
        $stmt = $conn->prepare("UPDATE pastries_ingredients SET item_p = ?, quantity_p = ?, price_p = ? WHERE id = ?");
        $stmt->bind_param("sssi", $item, $quantity, $price, $id);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $stmt->close();
    }

    header("Location: inventory.php");
    exit();
}

// Fetch data for drinks
$drinks_sql = "SELECT * FROM drinks_ingredients";
$drinks_result = $conn->query($drinks_sql);

// Fetch data for pastries
$pastries_sql = "SELECT * FROM pastries_ingredients";
$pastries_result = $conn->query($pastries_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
html {
	scroll-behavior: smooth;
}

body {
  font-family: "Lato", sans-serif;
}

.sidebar {
  height: 100%;
  width: 160px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #4b3021;
  overflow-x: hidden;
  padding-top: 16px;
}

.sidebar a {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 15px;
  color: #dfdcdc;
  display: block;
}

.sidebar a:hover {
  font-size: 20px;
  color: #f1f1f1;
}

.clicked {
  color: #f1f1f1;
}

.main {
  margin-top:50px;
  margin-left: 180px;
  padding: 0px 10px;
}

.button {
  border: none;
  color: white;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  padding-top: 2px;
  transition-duration: 0.4s;
  cursor: pointer;
  width: 260px;
  height: 30px;
}

.button1 {
  background-color: #4b3021;
  color: white;
}

.button1:hover {
  background-color: white; 
  color: black; 
  border: 2px solid #4b3021;
}
</style>
</head>
<body>

<div class="sidebar">
  <br>
  <a href="index.php"><i class="fa fa-home"></i> Dashboard</a>
  <a href="inventory.php"><i class="fa fa-qrcode"></i> Inventory</a>
  <a href="products.php"><i class="fa fa-coffee"></i> Products</a>
  <a href="order.php"><i class="fa fa-cart-plus"></i> Order</a>
  <a href="sales.php"><i class="fa fa-bar-chart"></i> Sales</a>
</div>

<div class="main">
  <h2>Inventory Management</h2>

  <!-- Add New Ingredient for Drinks -->
  <button type="button" class="button button1" data-bs-toggle="modal" data-bs-target="#addDrinkModal">Add New Ingredient for Drinks</button>
  
  <div class="modal" id="addDrinkModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Ingredient</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post">
              <input type="hidden" name="type" value="drinks">
              <label for="item_d" class="form-label">Item</label>
              <input type="text" class="form-control" id="item_d" name="item_d" required>
              <label for="quantity_d" class="form-label">Quantity</label>
              <input type="text" class="form-control" id="quantity_d" name="quantity_d" required>
              <label for="unit_d" class="form-label">Unit of Measurement</label>
              <select class="form-select" id="unit_d" name="unit_d" required>
                  <option selected disabled>Select unit</option>
                  <option value="kg">kg</option>
                  <option value="L">L</option>
              </select>
              <label for="price_d" class="form-label">Price</label>
              <input type="text" class="form-control" id="price_d" name="price_d" required>
              <div class="modal-footer">
                <button type="submit" name="save" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Drinks Ingredients Table -->
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Item</th>
        <th>Quantity</th>
        <th>Unit of Measurement</th>
        <th>Price</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row = $drinks_result->fetch_assoc()) { ?>
      <tr>
        <th><?php echo $row['id']; ?></th>
        <td><?php echo $row['item_d']; ?></td>
        <td><?php echo $row['quantity_d']; ?></td>
        <td><?php echo $row['unit_d']; ?></td>
        <td><?php echo $row['price_d']; ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDrinkModal_<?php echo $row['id']; ?>">Edit</button>
          <a href="inventory.php?id=<?php echo $row['id']; ?>&type=drinks" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this
        this?')">Delete</a>
        </td>
      </tr>

      <!-- Edit Drink Modal -->
      <div class="modal" id="editDrinkModal_<?php echo $row['id']; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Ingredient</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <form method="post">
                <input type="hidden" name="type" value="drinks">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="item_d" class="form-label">Item</label>
                <input type="text" class="form-control" name="item" value="<?php echo $row['item_d']; ?>" required>
                <label for="quantity_d" class="form-label">Quantity</label>
                <input type="text" class="form-control" name="quantity" value="<?php echo $row['quantity_d']; ?>" required>
                <label for="unit_d" class="form-label">Unit of Measurement</label>
                <select class="form-select" name="unit" required>
                  <option value="kg" <?php if ($row['unit_d'] == 'kg') echo 'selected'; ?>>kg</option>
                  <option value="L" <?php if ($row['unit_d'] == 'L') echo 'selected'; ?>>L</option>
                </select>
                <label for="price_d" class="form-label">Price</label>
                <input type="text" class="form-control" name="price" value="<?php echo $row['price_d']; ?>" required>
                <div class="modal-footer">
                  <button type="submit" name="update" class="btn btn-primary">Update</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    <?php } ?>
    </tbody>
  </table>

  <!-- Add New Ingredient for Pastries -->
  <button type="button" class="button button1" data-bs-toggle="modal" data-bs-target="#addPastryModal">Add New Ingredient for Pastries</button>
  
  <div class="modal" id="addPastryModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Ingredient</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post">
              <input type="hidden" name="type" value="pastries">
              <label for="item_p" class="form-label">Item</label>
              <input type="text" class="form-control" id="item_p" name="item_p" required>
              <label for="quantity_p" class="form-label">Quantity</label>
              <input type="text" class="form-control" id="quantity_p" name="quantity_p" required>
              <label for="price_p" class="form-label">Price</label>
              <input type="text" class="form-control" id="price_p" name="price_p" required>
              <div class="modal-footer">
                <button type="submit" name="save" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Pastries Ingredients Table -->
  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Item</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row = $pastries_result->fetch_assoc()) { ?>
      <tr>
        <th><?php echo $row['id']; ?></th>
        <td><?php echo $row['item_p']; ?></td>
        <td><?php echo $row['quantity_p']; ?></td>
        <td><?php echo $row['price_p']; ?></td>
        <td>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPastryModal_<?php echo $row['id']; ?>">Edit</button>
          <a href="inventory.php?id=<?php echo $row['id']; ?>&type=pastries" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
        </td>
      </tr>

      <!-- Edit Pastry Modal -->
      <div class="modal" id="editPastryModal_<?php echo $row['id']; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Ingredient</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <form method="post">
                <input type="hidden" name="type" value="pastries">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="item_p" class="form-label">Item</label>
                <input type="text" class="form-control" name="item" value="<?php echo $row['item_p']; ?>" required>
                <label for="quantity_p" class="form-label">Quantity</label>
                <input type="text" class="form-control" name="quantity" value="<?php echo $row['quantity_p']; ?>" required>
                <label for="price_p" class="form-label">Price</label>
                <input type="text" class="form-control" name="price" value="<?php echo $row['price_p']; ?>" required>
                <div class="modal-footer">
                  <button type="submit" name="update" class="btn btn-primary">Update</button>
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    <?php } ?>
    </tbody>
  </table>
</div>

</body>
</html>
