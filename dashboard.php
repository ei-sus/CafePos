<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Café POS</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js for graphs -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: Arial, sans-serif;
        }

        /* Layout styles */
        body {
            display: flex;
            min-height: 100vh;
            background-color: #ece0d1; /* Updated palette background color */
            overflow-x: hidden;
        }

        header {
            background-color: #38220f; /* Darker top bar */
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            height: 60px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        header .logo {
            display: flex;
            align-items: center;
        }

        header .logo img {
            height: 40px;
            margin-right: 10px;
        }

        header .user-profile img {
            height: 40px;
            border-radius: 50%;
        }

        header .hamburger {
            font-size: 24px;
            cursor: pointer;
        }

        /* Sidebar styles */
        aside {
            background-color: #634832; /* Lighter sidebar color */
            color: #dfdcdc;
            width: 160px; /* Reduced width */
            height: 100%;
            padding-top: 80px;
            transition: transform 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 900;
            transform: translateX(-100%); /* Initially hidden */
        }

        aside.visible {
            transform: translateX(0); /* Show when triggered */
        }

        aside a {
            display: flex;
            align-items: center;
            padding: 15px;
            color: #dfdcdc;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        aside a i {
            font-size: 16px;
            margin-right: 8px;
        }

        aside a:hover {
            background-color: #38220f;
        }

        /* Main content styles */
        main {
            flex-grow: 1;
            margin-left: 0;
            padding: 80px 20px 20px 20px;
            transition: margin-left 0.3s ease;
            background-color: #f1f1f1;
        }

        /* Dashboard sections */
        section.stats {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        section.stats article {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(33.33% - 20px);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        section.charts {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
        }

        .chart-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 48%;
            text-align: center;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }

        /* Set specific size for doughnut chart canvas */
        #orderBreakdownChart {
            width: 250px;
            height: 250px;
        }

        .chart-container h2 {
            margin-bottom: 10px;
        }

        /* Centering the welcome message and role */
        .dashboard-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .dashboard-header p {
            font-size: 1.25rem;
            color: #555;
        }

        /* Style for the clickable user profile */
        .user-profile a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }

        .user-profile a:hover {
            text-decoration: underline;
        }

        .user-profile img {
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
        }
    </style>
</head>
<body>

    <!-- Header with Logo and Profile -->
    <header>
        <div class="logo">
            <span class="hamburger" onclick="toggleSidebar()">&#9776;</span> <!-- Hamburger Menu -->
            <img src="logo.png" alt="Cafe Logo">
            <h1>Café POS</h1>
        </div>
        <div class="user-profile">
            <a href="profile.php">
                <span>John Doe</span>
                <img src="profile.png" alt="User Profile">
            </a>
        </div>
    </header>

    <!-- Sidebar with icons -->
    <aside id="sidebar">
        <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
        <a href="products.php"><i class="fa fa-coffee"></i> Products</a>
        <a href="orders.php"><i class="fa fa-cart-plus"></i> Orders</a>
        <a href="sales.php"><i class="fa fa-chart-bar"></i> Sales</a>
        <a href="users.php"><i class="fa fa-user"></i> Users</a>
    </aside>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Centered Welcome Message and Role -->
        <div class="dashboard-header">
            <h1>Welcome to the Café POS Dashboard</h1>
            <p>Your current role: Manager</p>
        </div>

        <!-- Statistics Section -->
        <section class="stats">
            <article>
                <h2>Total Sales</h2>
                <p>$10,000</p>
            </article>
            <article>
                <h2>Total Orders</h2>
                <p>120 Orders</p>
            </article>
            <article>
                <h2>Best-selling Product</h2>
                <p>Latte</p>
            </article>
        </section>

        <!-- Charts Section -->
        <section class="charts">
            <div class="chart-container">
                <h2>Order Breakdown</h2>
                <canvas id="orderBreakdownChart"></canvas>
            </div>

            <div class="chart-container">
                <h2>Sales Over Time</h2>
                <canvas id="salesChart"></canvas>
            </div>
        </section>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("visible");
        }

        // Doughnut Chart
        const orderCtx = document.getElementById('orderBreakdownChart').getContext('2d');
        const orderBreakdownChart = new Chart(orderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Dine-in', 'Takeaway', 'Delivery'],
                datasets: [{
                    data: [50, 30, 20],
                    backgroundColor: ['#634832', '#dbc1ac', '#967259']
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: true,
                cutout: '70%'
            }
        });

        // Line Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                datasets: [{
                    label: 'Sales in $',
                    data: [1000, 1250, 900, 1500, 2000, 1750, 2300],
                    borderColor: '#634832',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    x: {
                        ticks: {
                            padding: 10,
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>