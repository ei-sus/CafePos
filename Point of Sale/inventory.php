<?php
include "conn.php";

// onvert quantities to kg or L
function convertToKgOrL($quantity, $unit) {
    $conversion = [
        'kg' => 1,
        'g' => 0.001,
        'L' => 1,
        'mL' => 0.001,
    ];
    return isset($conversion[$unit]) ? (float)$quantity * $conversion[$unit] : (float)$quantity;
}

// save or update ingredient
function saveOrUpdateIngredient($conn, $item, $quantity, $unit, $price, $table, $itemColumn, $quantityColumn, $priceColumn, $unitColumn) {
    $stmt = $conn->prepare("SELECT $quantityColumn, $priceColumn FROM $table WHERE $itemColumn = ?");
    $stmt->bind_param("s", $item);
    $stmt->execute();
    $stmt->bind_result($existingQuantity, $existingPrice);
    $stmt->fetch();
    $stmt->close();

    // convert quantity to kg or L
    $quantity = convertToKgOrL($quantity, $unit);
    $unit = ($unit === 'kg' || $unit === 'g') ? 'kg' : 'L';

    if ($existingQuantity !== null) {
        // if ittem exists, update the quantity and price
        $newQuantity = $existingQuantity + $quantity;
        $newPrice = $existingPrice + $price;

        $stmt = $conn->prepare("UPDATE $table SET $quantityColumn = ?, $priceColumn = ? WHERE $itemColumn = ?");
        $stmt->bind_param("dds", $newQuantity, $newPrice, $item);
    } else {
        // ttem does not exist, insert a new one
        if ($unitColumn) {
            $stmt = $conn->prepare("INSERT INTO $table ($itemColumn, $quantityColumn, $unitColumn, $priceColumn) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssd", $item, number_format($quantity, 2, '.', ''), $unit, $price);
        } else {
            $stmt = $conn->prepare("INSERT INTO $table ($itemColumn, $quantityColumn, $priceColumn) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $item, number_format($quantity, 2, '.', ''), $price);
        }
    }

    $stmt->execute();
    $stmt->close();
}

// save ingredient
if (isset($_POST['save_drinks'])) {
    saveOrUpdateIngredient($conn, $_POST['item'], $_POST['quantity'], $_POST['unit'], $_POST['total_price'], 'drinks_ingredients', 'item', 'quantity', 'total_price', 'unit');
    header("Location: inventory.php");
    exit();
}

if (isset($_POST['save_pastries'])) {
    saveOrUpdateIngredient($conn, $_POST['item'], $_POST['quantity'], null, $_POST['total_price'], 'pastries_ingredients', 'item', 'quantity', 'total_price', null);
    header("Location: inventory.php");
    exit();
}

// update ingredient
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $unit = isset($_POST['unit']) ? $_POST['unit'] : null; 
    $type = $_POST['type'];

    // convert quantity to kg or L if applicable
    if ($unit) {
        $quantity = convertToKgOrL($quantity, $unit);
        $unit = ($unit === 'kg' || $unit === 'g') ? 'kg' : 'L';
    }

    if ($type === 'drinks') {
        $stmt = $conn->prepare("UPDATE drinks_ingredients SET item = ?, quantity= ?, unit = ?, total_price = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $item, number_format($quantity, 2, '.', ''), $unit, $total_price, $id);
    } else {
        $stmt = $conn->prepare("UPDATE pastries_ingredients SET item = ?, quantity = ?, total_price = ? WHERE id = ?");
        $stmt->bind_param("sssi", $item, number_format($quantity, 2, '.', ''), $total_price, $id);
    }

    $stmt->execute();
    $stmt->close();

    header("Location: inventory.php");
    exit();
}

// delete ingredient
if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];
    $table = ($type === 'drinks') ? 'drinks_ingredients' : 'pastries_ingredients';

    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: inventory.php");
    exit();
}

// fetch ingredients
$drinks_result = $conn->query("SELECT * FROM drinks_ingredients");
$pastries_result = $conn->query("SELECT * FROM pastries_ingredients");
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    <!-- drinks -->
    <h2>Drinks Ingredients</h2>
    <button type="button" class="button button1" data-bs-toggle="modal" data-bs-target="#myModal">
        Add New Stock/Ingredient
    </button>

    <!-- add ingredient for drinks  -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Stock/Ingredient</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <label for="item" class="form-label">Item</label>
                        <input type="text" class="form-select" id="item" name="item" required>

                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" required>

                        <label for="unit" class="form-label">Unit of Measurement</label>
                        <select class="form-select" id="unit" name="unit" required>
                            <option selected disabled>Select unit</option>
                            <option value="kg">kg</option>
                            <option value="g">g</option>
                            <option value="L">L</option>
                            <option value="mL">mL</option>
                        </select>

                        <label for="total_price" class="form-label">Total Price</label>
                        <input type="text" class="form-control" id="total_price" name="total_price" required>

                        <div class="modal-footer">
                            <button type="submit" name="save_drinks" class="btn btn-primary">Save</button>
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
                <th scope="col">Total Price</th>
                <th scope="col">Price Per Unit</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- fetch data from drinks_ingredients -->
            <?php while ($row = $drinks_result->fetch_assoc()) { ?>
                <tr>
                    <th><?php echo $row['id']; ?></th>
                    <td><?php echo $row['item']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['unit']; ?></td>
                    <td><?php echo $row['total_price']; ?></td>
                    <td><?php echo $row['price_per_unit']; ?></td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo $row['id']; ?>">Edit</button>

                        <!-- edit drinks -->
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
                                            <label for="item" class="form-label">Item</label>
                                            <input type="text" class="form-control" name="item" value="<?php echo $row['item']; ?>" required>
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="text" class="form-control" name="quantity" value="<?php echo $row['quantity']; ?>" required>
                                            <label for="unit" class="form-label">Unit of Measurement</label>
                                            <select class="form-select" name="unit" required>
                                                <option value="kg" <?php if ($row['unit'] == 'kg') echo 'selected'; ?>>kg</option>
                                                <option value="g" <?php if ($row['unit'] == 'g') echo 'selected'; ?>>g</option>
                                                <option value="L" <?php if ($row['unit'] == 'L') echo 'selected'; ?>>L</option>
                                                <option value="mL" <?php if ($row['unit'] == 'mL') echo 'selected'; ?>>mL</option>
                                            </select>
                                            <label for="total_price" class="form-label">Total Price</label>
                                            <input type="text" class="form-control" name="total_price" value="<?php echo $row['total_price']; ?>" required>
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

    <!-- pastries -->
    <h2>Pastries Ingredients</h2>
    <button type="button" class="button button1" data-bs-toggle="modal" data-bs-target="#pastryModal">
        Add New Stock/Ingredient
    </button>

    <!-- add ingredient for pastry -->
    <div class="modal" id="pastryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Stock/Ingredient</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <label for="item" class="form-label">Item</label>
                        <input type="text" class="form-control"  id="item" name="item" required>

                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="text" class="form-control" id="quantity" name="quantity" required>

                        <label for="total_price" class="form-label">Total Price</label>
                        <input type="text" class="form-control" id="total_price" name="total_price" required>

                        <div class="modal-footer">
                            <button type="submit" name="save_pastries" class="btn btn-primary">Save</button>
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
                <th scope="col">Total Price</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- fetch data from pastries_ingredients -->
            <?php while ($row = $pastries_result->fetch_assoc()) { ?>
                <tr>
                    <th><?php echo $row['id']; ?></th>
                    <td><?php echo $row['item']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['total_price']; ?></td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPastryModal_<?php echo $row['id']; ?>">Edit</button>

                        <!-- edit for pastry -->
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
                                            <input type="text" class="form-control" id="item" name="item" value="<?php echo $row['item']; ?>" required>

                                            <label for="quantity" class="form-label">Quantity</label>
                                            <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo $row['quantity']; ?>" required>

                                            <label for="total_price" class="form-label">Total Price</label>
                                            <input type="text" class="form-control" id="total_price" name="total_price" value="<?php echo $row['total_price']; ?>" required>

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
