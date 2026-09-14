```php
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../includes/db.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = isset($_POST["price"]) && $_POST["price"] !== ""
        ? (float)$_POST["price"]
        : 0;

    // ---------------------------------------------
    // CHECK REQUIRED FIELDS
    // ---------------------------------------------

    if ($title === "") {

        $message = "Please enter painting title.";
        $messageType = "error";

    } elseif ($category === "") {

        $message = "Please select/enter a category.";
        $messageType = "error";

    } elseif (!isset($_FILES["image"])) {

        $message = "Image field was not received.";
        $messageType = "error";

    } elseif ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

        $errorCode = $_FILES["image"]["error"];

        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE   => "Image is larger than the server upload limit.",
            UPLOAD_ERR_FORM_SIZE  => "Image is larger than the form upload limit.",
            UPLOAD_ERR_PARTIAL    => "Image was only partially uploaded.",
            UPLOAD_ERR_NO_FILE    => "Please select an image.",
            UPLOAD_ERR_NO_TMP_DIR => "Temporary upload folder is missing.",
            UPLOAD_ERR_CANT_WRITE => "Server cannot write the uploaded file.",
            UPLOAD_ERR_EXTENSION  => "A PHP extension stopped the upload."
        ];

        $message = $uploadErrors[$errorCode] ?? "Unknown upload error.";
        $messageType = "error";

    } else {

        $file = $_FILES["image"];

        $tmpName = $file["tmp_name"];
        $fileSize = $file["size"];

        // ---------------------------------------------
        // MAX SIZE = 10 MB
        // ---------------------------------------------

        if ($fileSize > 10 * 1024 * 1024) {

            $message = "Image must be less than 10 MB.";
            $messageType = "error";

        } else {

            // ---------------------------------------------
            // CHECK IMAGE
            // ---------------------------------------------

            $imageInfo = @getimagesize($tmpName);

            if ($imageInfo === false) {

                $message = "The uploaded file is not a valid image.";
                $messageType = "error";

            } else {

                $imageType = $imageInfo[2];

                // JPG, PNG, WEBP, GIF
                $allowedTypes = [
                    IMAGETYPE_JPEG,
                    IMAGETYPE_PNG,
                    IMAGETYPE_WEBP,
                    IMAGETYPE_GIF
                ];

                if (!in_array($imageType, $allowedTypes, true)) {

                    $message = "Only JPG, JPEG, PNG, WEBP and GIF images are allowed.";
                    $messageType = "error";

                } else {

                    // ---------------------------------------------
                    // UPLOAD DIRECTORY
                    // ---------------------------------------------

                    $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "uploads";

                    if (!is_dir($uploadDir)) {

                        if (!mkdir($uploadDir, 0755, true)) {

                            $message = "Could not create uploads folder.";
                            $messageType = "error";

                        }

                    }

                    if ($messageType !== "error") {

                        // ---------------------------------------------
                        // CREATE UNIQUE FILE NAME
                        // ---------------------------------------------

                        switch ($imageType) {

                            case IMAGETYPE_JPEG:
                                $extension = "jpg";
                                break;

                            case IMAGETYPE_PNG:
                                $extension = "png";
                                break;

                            case IMAGETYPE_WEBP:
                                $extension = "webp";
                                break;

                            case IMAGETYPE_GIF:
                                $extension = "gif";
                                break;

                            default:
                                $extension = "jpg";
                        }

                        $newName =
                            "painting_" .
                            date("Ymd_His") .
                            "_" .
                            bin2hex(random_bytes(5)) .
                            "." .
                            $extension;

                        $destination =
                            $uploadDir .
                            DIRECTORY_SEPARATOR .
                            $newName;


                        // ---------------------------------------------
                        // MOVE IMAGE
                        // ---------------------------------------------

                        if (!move_uploaded_file($tmpName, $destination)) {

                            $message =
                                "Image upload failed. Check the uploads folder.";

                            $messageType = "error";

                        } else {

                            // ---------------------------------------------
                            // SAVE DATABASE
                            // ---------------------------------------------

                            $sql = "
                                INSERT INTO paintings
                                (title, category, description, price, image)
                                VALUES (?, ?, ?, ?, ?)
                            ";

                            $stmt = $conn->prepare($sql);

                            if (!$stmt) {

                                if (file_exists($destination)) {
                                    unlink($destination);
                                }

                                $message =
                                    "Database error: " . $conn->error;

                                $messageType = "error";

                            } else {

                                $stmt->bind_param(
                                    "sssds",
                                    $title,
                                    $category,
                                    $description,
                                    $price,
                                    $newName
                                );

                                if ($stmt->execute()) {

                                    header("Location: dashboard.php?success=1");
                                    exit;

                                } else {

                                    if (file_exists($destination)) {
                                        unlink($destination);
                                    }

                                    $message =
                                        "Could not save painting: " .
                                        $stmt->error;

                                    $messageType = "error";
                                }

                                $stmt->close();
                            }
                        }
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Painting | Athmananda Art Gallery</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f5f1eb;
    color: #33251c;
}

.container {
    width: 90%;
    max-width: 750px;
    margin: 50px auto;
}

.card {
    background: white;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 10px 35px rgba(0,0,0,.08);
}

h1 {
    margin-top: 0;
    font-family: Georgia, serif;
    color: #704527;
}

.subtitle {
    color: #777;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    font-size: 14px;
}

input,
textarea,
select {
    width: 100%;
    padding: 13px;
    border: 1px solid #d8d0c8;
    border-radius: 6px;
    font-size: 14px;
}

textarea {
    min-height: 130px;
    resize: vertical;
}

input:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: #704527;
}

.file-info {
    margin-top: 7px;
    font-size: 12px;
    color: #777;
}

button {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 6px;
    background: #704527;
    color: white;
    font-size: 15px;
    cursor: pointer;
}

button:hover {
    background: #4e301e;
}

.message {
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.error {
    background: #ffe6e6;
    color: #a40000;
}

.success {
    background: #e6f8e9;
    color: #176b28;
}

.back {
    display: inline-block;
    margin-bottom: 20px;
    color: #704527;
    text-decoration: none;
}

.preview {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    display: none;
    margin-top: 15px;
    background: #f5f1eb;
    border-radius: 6px;
}

</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="back">
    ← Back to Dashboard
</a>

<div class="card">

<h1>
    Add New Painting
</h1>

<p class="subtitle">
    Upload artwork to Athmananda Art Gallery.
</p>


<?php if ($message !== ""): ?>

<div class="message <?php echo $messageType; ?>">

    <?php echo htmlspecialchars($message); ?>

</div>

<?php endif; ?>


<form
    method="POST"
    enctype="multipart/form-data"
>


<div class="form-group">

<label>
    Painting Title
</label>

<input
    type="text"
    name="title"
    placeholder="Example: Lord Ganesha"
    value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
    required
>

</div>


<div class="form-group">

<label>
    Category
</label>

<select name="category" required>

<option value="">
    Select Category
</option>

<option value="">All Categories</option>

<option value="Traditional Art">
    Traditional Art
</option>

<option value="Modern Art">
    Modern Art
</option>

<option value="Portrait">
    Portrait
</option>

<option value="Nature">
    Nature
</option>

<option value="Spiritual">
    Spiritual
</option>

<option value="Abstract">
    Abstract
</option>

<option value="Sculpture">
    Sculpture
</option>

<option value="Sketches & Drawings">
    Sketches & Drawings
</option>

<option value="Canvas Art">
    Canvas Art
</option>

<option value="Watercolor">
    Watercolor
</option>

<option value="Oil Painting">
    Oil Painting
</option>

<option value="Acrylic Painting">
    Acrylic Painting
</option>

<option value="Indian Art">
    Indian Art
</option>

<option value="Folk Art">
    Folk Art
</option>

<option value="Other">
    Other
</option>
</select>

</div>


<div class="form-group">

<label>
    Description
</label>

<textarea
    name="description"
    placeholder="Write something about this artwork..."
><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

</div>


<div class="form-group">

<label>
    Price (₹)
</label>

<input
    type="number"
    name="price"
    min="0"
    step="0.01"
    placeholder="Example: 5000"
    value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>"
>

</div>


<div class="form-group">

<label>
    Painting Image
</label>

<input
    type="file"
    name="image"
    id="image"
    accept="image/jpeg,image/png,image/webp,image/gif"
    required
>

<div class="file-info">

    Supported: JPG, JPEG, PNG, WEBP, GIF

    <br>

    Maximum size: 10 MB

</div>


<img
    id="preview"
    class="preview"
    alt="Image Preview"
>

</div>


<button type="submit">

    Add Painting

</button>


</form>

</div>

</div>


<script>

document
.getElementById("image")
.addEventListener("change", function(event) {

    const file = event.target.files[0];

    const preview = document.getElementById("preview");

    if (!file) {

        preview.style.display = "none";

        return;
    }

    if (!file.type.startsWith("image/")) {

        alert("Please select an image file.");

        event.target.value = "";

        preview.style.display = "none";

        return;
    }

    preview.src = URL.createObjectURL(file);

    preview.style.display = "block";

});

</script>

</body>

</html>
```
