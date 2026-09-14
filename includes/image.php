<?php

function getPaintingImage($filename)
{
    $filename = trim($filename);

    if ($filename === '') {
        return 'images/no-image.jpg';
    }

    $filenameOnly = basename($filename);

    $filePath = __DIR__ . '/../uploads/' . $filenameOnly;

    if (file_exists($filePath) && is_file($filePath)) {

        return 'uploads/' . rawurlencode($filenameOnly);

    }

    return 'images/no-image.jpg';
}

?>