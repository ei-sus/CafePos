<?php
include "conn.php"; // Include the database connection

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $ingredients = isset($_POST['ingredients']) ? $_POST['ingredients'] : []; // Ingredients array
    $cost_price = $_POST['cost_price'];
    $selling_price = $_POST['selling_price'];
    
    // Handle image upload
    $target_dir = "uploads/"; // Directory to store images
    $image_name = basename($_FILES["product_image"]["name"]);
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is an actual image or fake
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
        exit;
    }

    // Move uploaded file to the uploads directory
    if (!move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        echo "Sorry, there was an error uploading your file.";
        exit;
    }

    // Insert the product into the products table
    $stmt = $conn->prepare("INSERT INTO products (product_name, category_id, product_image, cost_price, selling_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisdd", $product_name, $category_id, $image_name, $cost_price, $selling_price);
    
    if ($stmt->execute()) {
        // Get the last inserted product ID
        $product_id = $stmt->insert_id;

        // Insert product-ingredients relationships into product_ingredients table
        if (!empty($ingredients)) {
            foreach ($ingredients as $ingredient_id) {
                $stmt_ingredient = $conn->prepare("INSERT INTO product_ingredients (product_id, ingredient_id) VALUES (?, ?)");
                $stmt_ingredient->bind_param("ii", $product_id, $ingredient_id);
                $stmt_ingredient->execute();
            }
        }

        // Redirect to products page after success
        header("Location: products.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid Request.";
}

$conn->close();
?>