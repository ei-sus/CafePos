<?php
include "conn.php";

// convert quantities to kg or L
function convertToKgOrL($quantity, $unit) {
    switch ($unit) {
        case 'kg':
            return (float)$quantity; 
        case 'g':
            return (float)$quantity / 1000; 
        case 'L':
            return (float)$quantity; 
        case 'mL':
            return (float)$quantity / 1000; 
        default:
            return (float)$quantity; 
    }
}


// Add new ingredient for drinks and pastries
if (isset($_POST['save'])) {
  // Check for drinks ingredient
  if (isset($_POST['item_d'])) {
      $item = $_POST['item_d'];
      $quantity = $_POST['quantity_d'];
      $unit = $_POST['unit_d'];
      $price = $_POST['price_d'];

      // Convert quantity to kg or L based on unit
      if (in_array($unit, ['kg', 'g'])) {
          $quantity = convertToKgOrL($quantity, $unit);
          $unit = 'kg'; 
      } elseif (in_array($unit, ['L', 'mL'])) {
          $quantity = convertToKgOrL($quantity, $unit);
          $unit = 'L'; 
      }

      // Check if item already exists
      $stmt = $conn->prepare("SELECT quantity_d FROM drinks_ingredients WHERE item_d = ?");
      $stmt->bind_param("s", $item);
      $stmt->execute();
      $stmt->bind_result($existingQuantity);
      $stmt->fetch();
      $stmt->close();

      if ($existingQuantity !== null) {
          // Item exists, update the quantity
          $newQuantity = $existingQuantity + $quantity;

          $stmt = $conn->prepare("UPDATE drinks_ingredients SET quantity_d = ?, price_d = ? WHERE item_d = ?");
          $stmt->bind_param("sss", number_format($newQuantity, 2, '.', ''), $price, $item);
      } else {
          // Item does not exist, insert a new one
          $stmt = $conn->prepare("INSERT INTO drinks_ingredients (item_d, quantity_d, unit_d, price_d) VALUES (?, ?, ?, ?)");
          $stmt->bind_param("ssss", $item, number_format($quantity, 2, '.', ''), $unit, $price);
      }
      $stmt->execute();
      $stmt->close();
  }

  // Check for pastries ingredient
  if (isset($_POST['item_p'])) {
      $item_p = $_POST['item_p'];
      $quantity_p = $_POST['quantity_p'];
      $price_p = $_POST['price_p'];

      // Check if item already exists
      $stmt = $conn->prepare("SELECT quantity_p FROM pastries_ingredients WHERE item_p = ?");
      $stmt->bind_param("s", $item_p);
      $stmt->execute();
      $stmt->bind_result($existingQuantityP);
      $stmt->fetch();
      $stmt->close();

      if ($existingQuantityP !== null) {
          // Item exists, update the quantity
          $newQuantityP = $existingQuantityP + $quantity_p;

          $stmt = $conn->prepare("UPDATE pastries_ingredients SET quantity_p = ?, price_p = ? WHERE item_p = ?");
          $stmt->bind_param("sss", $newQuantityP, $price_p, $item_p);
      } else {
          // Item does not exist, insert a new one
          $stmt = $conn->prepare("INSERT INTO pastries_ingredients (item_p, quantity_p, price_p) VALUES (?, ?, ?)");
          $stmt->bind_param("sss", $item_p, $quantity_p, $price_p);
      }
      $stmt->execute();
      $stmt->close();
  }

  // Redirect to inventory
  header("Location: inventory.php");
  exit();
}


// Update ingredient
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $unit = $_POST['unit']; 
    $type = $_POST['type'];

    // Convert quantity to kg or L based on unit
    if (in_array($unit, ['kg', 'g'])) {
        $quantity = convertToKgOrL($quantity, $unit);
        $unit = 'kg'; // Set unit to kg for storage
    } elseif (in_array($unit, ['L', 'mL'])) {
        $quantity = convertToKgOrL($quantity, $unit);
        $unit = 'L'; // Set unit to L for storage
    }

    if ($type === 'drinks') {
        $stmt = $conn->prepare("UPDATE drinks_ingredients SET item_d = ?, quantity_d = ?, unit_d = ?, price_d = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $item, number_format($quantity, 2, '.', ''), $unit, $price, $id); // Format the quantity
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

// Delete ingredient
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];

    if ($type === 'drinks') {
        $stmt = $conn->prepare("DELETE FROM drinks_ingredients WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($type === 'pastries') {
        $stmt = $conn->prepare("DELETE FROM pastries_ingredients WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: inventory.php");
    exit();
}

// Fetch drinks_ingredients
$drinks_sql = "SELECT * FROM drinks_ingredients";
$drinks_result = $conn->query($drinks_sql);

// Fetch pastries_ingredients
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

        .main {
            margin-top: 50px;
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

<div class="sidebar"><br>
    <a href="index.php"><i class="fa fa-home"></i> Dashboard</a>
    <a href="inventory.php"><i class="fa fa-qrcode"></i> Inventory</a>
    <a href="products.php"><i class="fa fa-coffee"></i> Products</a>
    <a href="order.php"><i class="fa fa-cart-plus"></i> Order</a>
    <a href="sales.php"><i class="fa fa-bar-chart"></i> Sales</a>
</div>

<div class="main">
    <h2>Drinks Ingredients</h2>
    <button type="button" class="button button1" data-bs-toggle="modal" data-bs-target="#myModal">
        Add New Stock/Ingredient
    </button>

    <!-- Add ingredient for drinks -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Stock/Ingredient</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                    <label for="item_d" class="form-label">Item</label>
                    <input type="text" class="form-select"  id="item_d" name="item_d"  required>
                    
                        <label for="quantity_d" class="form-label">Quantity</label>
                        <input type="text" class="form-control" id="quantity_d" name="quantity_d" required>

                        <label for="unit_d" class="form-label">Unit of Measurement</label>
                        <select class="form-select" id="unit_d" name="unit_d" required>
                            <option selected disabled>Select unit</option>
                            <option value="kg">kg</option>
                            <option value="g">g</option>
                            <option value="L">L</option>
                            <option value="mL">mL</option>
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

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Item</th>
            <th scope="col">Quantity</th>
            <th scope="col">Unit of Measurement</th>
            <th scope="col">Price Per Quantity</th>
            <th scope="col">Action</th>
        </thead>
        <tbody>
        <!-- Fetch data from drinks_ingredients -->
        <?php while ($row = $drinks_result->fetch_assoc()) { ?>
            <tr>
                <th><?php echo $row['id']; ?></th>
                <td><?php echo $row['item_d']; ?></td>
                <td><?php echo $row['quantity_d']; ?></td>
                <td><?php echo $row['unit_d']; ?></td>
                <td><?php echo $row['price_d']; ?></td>
                <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo $row['id']; ?>">Edit</button>
          
          <!-- Edit Modal for drinks-->
          <div class="modal" id="editModal_<?php echo $row['id']; ?>">
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
                              <option value="g" <?php if ($row['unit_d'] == 'g') echo 'selected'; ?>>g</option>
                              <option value="L" <?php if ($row['unit_d'] == 'L') echo 'selected'; ?>>L</option>
                              <option value="mL" <?php if ($row['unit_d'] == 'mL') echo 'selected'; ?>>mL</option>
                              <select class="form-select" id="unit_d" name="unit_d" required>
                        </select>
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

                    <a href="inventory.php?id=<?php echo $row['id']; ?>&type=drinks" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <!-- Pastries -->
    <h2>Pastries Ingredients</h2>
    <button type="button" class="button button1" data-bs-toggle="modal" data-bs-target="#pastryModal">
        Add New Stock/Ingredient
    </button>

    <!-- PAdd ingredient for pastry -->
    <div class="modal" id="pastryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Stock/Ingredient</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <label for="item_p" class="form-label">Item</label>
                        <input class="form-select" list="options" id="item_p" name="item_p" placeholder="Type or select an option" required>
                        <datalist id="options">
                            <?php foreach ($existing_items as $existing_item) { ?>
                                <option value="<?php echo htmlspecialchars($existing_item); ?>"><?php echo htmlspecialchars($existing_item); ?></option>
                              <?php } ?>
                        </datalist>

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

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Item</th>
            <th scope="col">Quantity</th>
            <th scope="col">Price Per Quantity</th>
            <th scope="col">Action</th>
        </thead>
        <tbody>

        <!-- Fetch data from pastries_ingredients -->
        <?php while ($row = $pastries_result->fetch_assoc()) { ?>
            <tr>
                <th><?php echo $row['id']; ?></th>
                <td><?php echo $row['item_p']; ?></td>
                <td><?php echo $row['quantity_p']; ?></td>
                <td><?php echo $row['price_p']; ?></td>
                <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPastryModal_<?php echo $row['id']; ?>">Edit</button>

                    <!-- Edit Modal for pastry -->
                    <div class="modal" id="editPastryModal_<?php echo $row['id']; ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Ingredient</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="type" value="pastries">

                                        <label for="item" class="form-label">Item</label>
                                        <input type="text" class="form-control" id="item" name="item" value="<?php echo $row['item_p']; ?>" required>

                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo $row['quantity_p']; ?>" required>

                                        <label for="price" class="form-label">Price</label>
                                        <input type="text" class="form-control" id="price" name="price" value="<?php echo $row['price_p']; ?>" required>

                                        <div class="modal-footer">
                                            <button type="submit" name="update" class="btn btn-success">Update</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="inventory.php?id=<?php echo $row['id']; ?>&type=pastries" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
