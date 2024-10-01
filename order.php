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

.button{
  border: none;
  color: white;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  padding-top: 2px;
  transition-duration: 0.4s;
  cursor: pointer;
  width: 120px;
  height: 30px;
}

.button {
  background-color: white;
  color: #4b3021
}

.button1:hover{
  background-color: #4b3021;
  color: white;
  border: 2px solid #4b3021;
}

.container {
            max-width: 385px;
            height: 30px;
            margin-left:50px;
            margin-top:50px; 
            padding: 2px;
            background-color: #4B2C12;
            color: #ffffff;
            text-align: center;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: visible;    
        }
    
.container2 {
    float: right;
    max-width: 500px;
    height: 30px;
    margin-right:200px;
    margin-top:-300px; 
    padding: 2px;
    color: #4B2C12;
    text-align: left;
    font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;    
}

.overflow {
	overflow: auto;
	background-color: #4B2C12;
	margin:auto;
	width: 400px;
	height: 300px;
	margin-top: 1px;
	padding-top: 10px;
    margin-left: 50px;
    overflow-x: hidden;
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
    <h2>Order Management</h2>
    <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Product Name</th>
            <th scope="col">Price</th>
            <th scope="col">Quantity</th>
            <th scope="col">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Product 1</td>
            <td>Price</td>
            <td><input type="number" class="form-control" id="quantity" style="width: 100px"></td>
            <td>Total</td>
          </tr>
        </tbody>
      </table>
      <div class="container">
        <a href=#hot class="button button1">Hot Drinks</a>
        <a href=#cold class="button button1">Cold Drinks</a>
        <a href=#pasties class="button button1">Pastries</a>
      </div>
        <div class="overflow">
        <div id="hot" class="row align-items-start" style="margin-left: 10px; margin-right:-330px;">
            <div class="col-sm-2">
              <img src="6.png" width="100px">
            </div>
            <div class="col-sm-2">
                <img src="7.png" width="100px">
            </div>
            <div class="col-sm-2">
                <img src="8.png" width="100px">            
            </div>
        </div>
        <div id="cold" class="row align-items-start" style="margin-left: 10px; margin-right:-330px;">
            <div class="col-sm-2">
              <img src="9.png" width="100px">
            </div>
            <div class="col-sm-2">
                <img src="10.png" width="100px">
            </div>
            <div class="col-sm-2">
                <img src="11.png" width="100px">            
            </div>
        </div>
        <div id="pastries" class="row align-items-start" style="margin-left: 10px; margin-right:-330px;">
            <div class="col-sm-2">
              <img src="12.png" width="100px">
            </div>
            <div class="col-sm-2">
                <img src="13.png" width="100px">
            </div>
            <div class="col-sm-2">
                <img src="14.png" width="100px">            
            </div>
        </div>
    </div>
    <div class="container2">
        <form>
            <label for="amount" class="form-label">Total Amount</label>
            <input type="number" class="form-control" id="amount">
            <label for="pay" class="form-label">Pay</label>
            <input type="number" class="form-control" id="pay">
            <label for="change" class="form-label">Change</label>
            <input type="number" class="form-control" id="change">
            <button type="button" class="btn btn-primary">Show Receipt</button>
          </form>
    </div>
</div>
</body>
</html> 
