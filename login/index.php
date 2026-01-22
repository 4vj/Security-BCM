<?php
$error_message = '';

// Check if authentication failed (You need to implement this logic)
//if (/* Add your condition to check authentication failure */) {
//    $error_message = 'Invalid username or password. Please try again.';
//}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication with AD</title>
    <!-- Bootstrap CSS -->
    <style>
        body {
            text-align: center;
            padding: 50px;
        }
        form {
            margin: 0 auto;
            max-width: 500px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input {
            padding: 10px;
            font-size: 20px;
            margin-bottom: 20px;
        }
        h1 {
            margin-bottom: 30px;
        }
        .error-message {
            color: red;
            margin-bottom: 20px;
            font-size: 20px;
        }
    </style>
</head>
<body>
<h1>Authentication with AD</h1>
<?php if (isset($_SERVER['HTTP_REFERER']) && basename($_SERVER['HTTP_REFERER']) === "login") : ?>
    <div class="error-message"><?="Invalid username or password. Please try again."?></div>
<?php endif; ?>
<form method="POST" action="/dologin.php">
    <div class="form-group">
        <label for="httpd_username">Username:</label>
        <input type="text" class="form-control" id="httpd_username" name="httpd_username" placeholder="Enter your username">
    </div>
    <div class="form-group">
        <label for="httpd_password">Password:</label>
        <input type="password" class="form-control" id="httpd_password" name="httpd_password" placeholder="Enter your password">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
</body>
</html>
