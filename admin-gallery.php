<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin-login.php");
    exit();
}

// Define the gallery data file
$galleryFile = "gallery-data.json";

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "add") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    
    // Handle file upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "img/gallery/";
        
        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Generate unique filename
        $filename = uniqid() . "-" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Load existing gallery data
            $galleryData = [];
            if (file_exists($galleryFile)) {
                $galleryData = json_decode(file_get_contents($galleryFile), true);
            }
            
            // Add new image data
            $galleryData[] = [
                "id" => uniqid(),
                "title" => $title,
                "description" => $description,
                "image" => $targetFile
            ];
            
            // Save updated gallery data
            file_put_contents($galleryFile, json_encode($galleryData));
            
            $successMessage = "Image added successfully!";
        } else {
            $errorMessage = "Failed to upload image.";
        }
    } else {
        $errorMessage = "Please select an image to upload.";
    }
}

// Handle image deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "delete") {
    $imageId = $_POST["image_id"];
    
    // Load existing gallery data
    if (file_exists($galleryFile)) {
        $galleryData = json_decode(file_get_contents($galleryFile), true);
        
        // Find and remove the image
        foreach ($galleryData as $key => $image) {
            if ($image["id"] == $imageId) {
                // Delete the image file
                if (file_exists($image["image"])) {
                    unlink($image["image"]);
                }
                
                // Remove from array
                unset($galleryData[$key]);
                break;
            }
        }
        
        // Reindex array and save
        $galleryData = array_values($galleryData);
        file_put_contents($galleryFile, json_encode($galleryData));
        
        $successMessage = "Image deleted successfully!";
    }
}

// Load gallery data for display
$galleryData = [];
if (file_exists($galleryFile)) {
    $galleryData = json_decode(file_get_contents($galleryFile), true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Gallery - Maa Rajpati Devi Educational Trust</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Arial, sans-serif;
            padding-bottom: 50px;
        }
        .admin-header {
            background: linear-gradient(135deg, #6a11cb, #759bde);
            color: white;
            padding: 20px 0;
            margin-bottom: 40px;
        }
        .image-preview {
            width: 100%;
            height: 200px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-position: center;
        }
        .gallery-item {
            margin-bottom: 30px;
        }
        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Gallery Management</h1>
                <div>
                    <a href="news.html" class="btn btn-outline-light me-2">View Website</a>
                    <a href="logout.php" class="btn btn-light">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Add New Image</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required onchange="previewImage(this)">
                            </div>
                            
                            <div class="mb-3">
                                <div class="image-preview" id="imagePreview">
                                    <span>Image Preview</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Add to Gallery</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Manage Gallery Images</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if (empty($galleryData)): ?>
                                <div class="col-12 text-center py-5">
                                    <p>No images in the gallery yet.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($galleryData as $image): ?>
                                    <div class="col-md-6 gallery-item">
                                        <div class="card">
                                            <img src="<?php echo $image['image']; ?>" alt="<?php echo $image['title']; ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $image['title']; ?></h5>
                                                <p class="card-text"><?php echo $image['description']; ?></p>
                                                <form method="post">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.style.backgroundImage = `url(${e.target.result})`;
                    preview.innerHTML = '';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.backgroundImage = '';
                preview.innerHTML = '<span>Image Preview</span>';
            }
        }
    </script>
</body>
</html>