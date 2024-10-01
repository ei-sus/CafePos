<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
  margin-left: 180px; /* Same as the width of the sidenav */
  padding: 0px 10px;
}

@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
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
  width: 180px;
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
    <a href="index.php"><i class="fa fa-home"></i>   Dashboard</a>
    <a href="inventory.php"><i class="fa fa-qrcode"></i>   Inventory</a>
    <a href="products.php"><i class="fa fa-coffee"></i>   Products</a>
    <a href="order.php"><i class="fa fa-cart-plus"></i>   Order</a>
    <a href="sales.php"><i class="fa fa-bar-chart"></i>   Sales</a>
  </div>

<div class="main">
  <h2>Sales Management</h2>


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
          <label for="product" class="form-label">Category</label>
          <select class="form-select" aria-label="Default select example">
            <option value="1">Category 1</option>
            <option value="2">Category 2</option>
            <option value="3">Categpry 3</option>
          </select>
          <label for="price" class="form-label">Selling Price</label>
          <input type="number" class="form-control" id="price">
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
        <th scope="col">Date</th>
        <th scope="col">Products</th>
        <th scope="col">Sales</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>1</td>
        <td>Date</td>
        <td>Products</td>
        <th scope="col">Sales</th>
        <td>
           <button type="button" class="btn btn-warning">View Receipt</button>
           <button type="button" class="btn btn-primary">Print</button>
           <button type="button" class="btn btn-danger">Delete</button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
     
</body>
</html> 
