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
  color: #b8b3b3;
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
  <h2>Dashboard</h2>
  <div class="container">
      <div class="row">
          <div class="col-md-3">
              <div class="card text-center" style="width: 14rem;">
                  <img src="img\inventory.png" class="card-img-top" alt="">
                  <div class="card-body">
                      <h5 class="card-title">Total Categories</h5>
                      <a href="category.html" class="button button1">View</a>
                  </div>
              </div>
          </div>
          <div class="col-md-3">
              <div class="card text-center" style="width: 14rem;">
                  <img src="img\product.png" class="card-img-top" alt="">
                  <div class="card-body">
                      <h5 class="card-title">Total Products</h5>
                      <a href="products.html" class="button button1">View</a>
                  </div>
              </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center" style="width: 14rem;">
                <img src="img\order.png" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <a href="order.html" class="button button1">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
          <div class="card text-center" style="width: 14rem;">
              <img src="img\sales.png" class="card-img-top" alt="">
              <div class="card-body">
                  <h5 class="card-title">Total Sales</h5>
                  <a href="sales.html" class="button button1">View</a>
              </div>
          </div>
      </div>
      </div>
  </div>
</div>


     
</body>
</html> 
