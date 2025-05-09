<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Maa Rajpati Devi Educational Trust</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header img {
            height: 60px;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: #6a11cb;
            border-color: #6a11cb;
        }
        .btn-primary:hover {
            background: #5a0cb9;
            border-color: #5a0cb9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <img src="logo.jpg" alt="School Logo">
                <h2>Admin Login</h2>
            </div>
            
            <?php
            // Create necessary directories and files
            $galleryDir = dirname(__FILE__) . '/img/gallery';
            if (!file_exists($galleryDir)) {
                mkdir($galleryDir, 0777, true);
            }
            
            $galleryFile = dirname(__FILE__) . '/gallery-data.json';
            if (!file_exists($galleryFile)) {
                file_put_contents($galleryFile, json_encode([]));
            }
            
            session_start();
            
            // Check if form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = $_POST["username"];
                $password = $_POST["password"];
                
                // Simple authentication - in production, use proper authentication
                if ($username === "admin" && $password === "school123") {
                    $_SESSION["admin_logged_in"] = true;
                    
                    // Create a simple test file to verify PHP is working
                    $testFile = dirname(__FILE__) . '/login-test.txt';
                    file_put_contents($testFile, "Login successful at " . date('Y-m-d H:i:s'));
                    
                    header("Location: admin-gallery.php");
                    exit();
                } else {
                    echo '<div class="alert alert-danger">Invalid username or password</div>';
                }
            }
            ?>
            
            <form method="post" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            
            <div class="mt-3 text-center">
                <a href="news.html">Back to News & Events</a>
            </div>
            
            <!-- PHP Info for testing -->
            <div class="mt-4 text-center">
                <p>PHP Version: <?php echo phpversion(); ?></p>
                <p>Server Software: <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
            </div>
        </div>
    </div>
</body>
</html>