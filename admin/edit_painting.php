<?php

include "../includes/db.php";

$id = intval($_GET["id"] ?? 0);

$stmt = $conn->prepare(
    "SELECT * FROM paintings WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$painting = $result->fetch_assoc();

if (!$painting) {
    die("Painting not found.");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST["title"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    $newImage = $painting["image"];


    // If new image selected

    if (!empty($_FILES["image"]["name"])) {

        $imageName = $_FILES["image"]["name"];
        $tmpName = $_FILES["image"]["tmp_name"];

        $extension = strtolower(
            pathinfo($imageName, PATHINFO_EXTENSION)
        );

        $allowed = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];

        if (!in_array($extension, $allowed)) {

            die("Invalid image format.");

        }


        // Delete old image

        $oldImage =
            "../uploads/" . $painting["image"];

        if (file_exists($oldImage)) {
            unlink($oldImage);
        }


        $newImage =
            time() . "_" . basename($imageName);

        move_uploaded_file(
            $tmpName,
            "../uploads/" . $newImage
        );
    }


    $stmt = $conn->prepare(
        "UPDATE paintings
        SET title=?,
            category=?,
            description=?,
            price=?,
            image=?
        WHERE id=?"
    );

    $stmt->bind_param(
        "sssdsi",
        $title,
        $category,
        $description,
        $price,
        $newImage,
        $id
    );

    $stmt->execute();

    header("Location: dashboard.php");

    exit;
}

?>


<!DOCTYPE html>

<html>

<head>

<title>Edit Painting</title>

<style>

body {
    font-family: Arial;
    background: #f5f1ec;
    padding: 40px;
}

.container {
    max-width: 650px;
    margin: auto;
    background: white;
    padding: 35px;
}

h1 {
    color: #704527;
}

label {
    display: block;
    margin-top: 18px;
    margin-bottom: 7px;
}

input,
textarea,
select {
    width: 100%;
    padding: 12px;
}

textarea {
    height: 120px;
}

button {
    margin-top: 25px;
    padding: 13px 25px;
    background: #704527;
    color: white;
    border: none;
}

.current-image {
    width: 180px;
    height: 180px;
    object-fit: cover;
    margin-top: 10px;
}

</style>

</head>

<body>

<div class="container">

<h1>Edit Painting</h1>

<form method="POST" enctype="multipart/form-data">

<label>Painting Name</label>

<input
    type="text"
    name="title"
    value="<?php echo htmlspecialchars($painting['title']); ?>"
    required
>


<label>Category</label>

<select name="category">

<option value="Traditional Art"
<?php if ($painting['category'] == "Traditional Art") echo "selected"; ?>>
    Traditional Art
</option>

<option value="Modern Art"
<?php if ($painting['category'] == "Modern Art") echo "selected"; ?>>
    Modern Art
</option>

<option value="Portrait"
<?php if ($painting['category'] == "Portrait") echo "selected"; ?>>
    Portrait
</option>

<option value="Nature"
<?php if ($painting['category'] == "Nature") echo "selected"; ?>>
    Nature
</option>

<option value="Spiritual"
<?php if ($painting['category'] == "Spiritual") echo "selected"; ?>>
    Spiritual
</option>

<option value="Abstract"
<?php if ($painting['category'] == "Abstract") echo "selected"; ?>>
    Abstract
</option>

<option value="Sculpture"
<?php if ($painting['category'] == "Sculpture") echo "selected"; ?>>
    Sculpture
</option>

<option value="Sketches & Drawings"
<?php if ($painting['category'] == "Sketches & Drawings") echo "selected"; ?>>
    Sketches & Drawings
</option>

<option value="Canvas Art"
<?php if ($painting['category'] == "Canvas Art") echo "selected"; ?>>
    Canvas Art
</option>

<option value="Watercolor"
<?php if ($painting['category'] == "Watercolor") echo "selected"; ?>>
    Watercolor
</option>

<option value="Oil Painting"
<?php if ($painting['category'] == "Oil Painting") echo "selected"; ?>>
    Oil Painting
</option>

<option value="Acrylic Painting"
<?php if ($painting['category'] == "Acrylic Painting") echo "selected"; ?>>
    Acrylic Painting
</option>

<option value="Indian Art"
<?php if ($painting['category'] == "Indian Art") echo "selected"; ?>>
    Indian Art
</option>

<option value="Folk Art"
<?php if ($painting['category'] == "Folk Art") echo "selected"; ?>>
    Folk Art
</option>

<option value="Other"
<?php if ($painting['category'] == "Other") echo "selected"; ?>>
    Other
</option>

</select>


<label>Description</label>

<textarea name="description"><?php
echo htmlspecialchars($painting['description']);
?></textarea>


<label>Price</label>

<input
    type="number"
    name="price"
    step="0.01"
    value="<?php echo $painting['price']; ?>"
>


<label>Current Image</label>

<br>

<img
    class="current-image"
    src="../uploads/<?php
    echo htmlspecialchars($painting['image']);
    ?>"
>


<label>Replace Image</label>

<input
    type="file"
    name="image"
    accept=".jpg,.jpeg,.png,.webp"
>


<button type="submit">
    Update Painting
</button>

</form>

</div>

</body>

</html>