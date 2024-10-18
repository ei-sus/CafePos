<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Café POS Login</title>
    <style>
        /* Flexbox centering for the entire page */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #dbc1ac;
        }

        /* Styling for the form container */
        .form-container {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        /* Center text inside form */
        .form-container h1, .form-container p {
            text-align: center;
        }

        /* Form fields styling */
        .form-container input[type="text"], .form-container input[type="password"] {
            width: 95%; /* Add some padding from the edges of the container */
            padding: 10px;
            margin: 8px 0 20px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Center the login button */
        .button-container {
            text-align: center;
        }

        /* Make the button rectangular with rounded corners */
        .button1 {
            width: 95%; /* Adjust button width to match input fields and avoid hitting edges */
            border-radius: 20px; /* Rounded corners */
            padding: 10px 0; /* Adjust padding for better appearance */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1 style="color: #4b3021; font-size: 36px;">Welcome to Café POS</h1>
        <p style="color: #dfdcdc; font-size: 18px;">Please login to continue</p>

        <form action="login_handler.php" method="POST">
            <label for="username" style="color: #4b3021;">Username</label><br>
            <input type="text" id="username" name="username" placeholder="Enter your username" required><br>

            <label for="password" style="color: #4b3021;">Password</label><br>
            <input type="password" id="password" name="password" placeholder="Enter your password" required><br>

            <!-- Center the button within a container -->
            <div class="button-container">
                <button class="button button1" type="submit">Login</button>
            </div>
        </form>

        <p style="text-align: center; color: #4b3021; margin-top: 20px;">Don't have an account? 
            <a href="register.php" style="color: #4b3021; text-decoration: underline;">Register here</a>
        </p>
    </div>
</body>
</html>