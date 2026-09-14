<?php

include "../includes/db.php";

if (isset($_GET["id"])) {

    $id = intval($_GET["id"]);

    $stmt = $conn->prepare(
        "SELECT image FROM paintings WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        $image = "../uploads/" . $row["image"];

        if (file_exists($image)) {
            unlink($image);
        }
    }


    $stmt = $conn->prepare(
        "DELETE FROM paintings WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    $stmt->execute();
}

header("Location: dashboard.php");

exit;

?>