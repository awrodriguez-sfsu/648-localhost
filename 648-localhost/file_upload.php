<?php

include_once './db_connect.php';

//File Meta Data needed
$server_upload_directory = "uploads/"; // upload dir
$uploaded_name = strtolower(pathinfo($_FILES['file_upload']['name'], PATHINFO_BASENAME));
$uploaded_extension = strtolower(pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION)); // file extension
$server_file = $server_upload_directory . pathinfo(tempnam($server_upload_directory, "large_"), PATHINFO_BASENAME) . "." . $uploaded_extension; // unique file name
//Status of upload
$upload_status = 1;

if (isset($_POST["submit"])) {

    // Check if file is one of three accepted image formats
    if ($uploaded_extension != "gif" && $uploaded_extension != "jpg" && $uploaded_extension != "jpeg" && $uploaded_extension != "png") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_status = 0;
    }

    $description = $_POST["file_description"];
    $check = getimagesize($_FILES["file_upload"]["tmp_name"]);

    if ($check === false) {
        // uploaded file is NOT an image
        $upload_status = 0;
    }
} else {
    $upload_status = 0;
}

// Store new image entry in DB
try {
    // Connect to DB
    $connection = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL statement to insert new entry into image table
    $sql_insert = "INSERT INTO images (path, name, size, description)"
            . "VALUES ('$server_file', '$uploaded_name', 'large', '$description')";

    // Prepare and execute INSERT statement
    $statement = $connection->prepare($sql_insert);
    $statement->execute();
    $id = $connection->lastInsertId();

    // Prepare and execute UPDATE statement
    $sql_update = "UPDATE images SET parent_id='$id' WHERE id='$id'";
    $statement = $connection->prepare($sql_update);
    $statement->execute();
    $connection = null;
} catch (PDOException $exc) {
    //There was an error
    print $exc->getMessage();
    print "<br>";
    $upload_status = 0;
}

// Check if $upload_status is set to 0 by an error
if ($upload_status === 0) {
    //File was not uploaded redirect to error page
    header("Location: upload_error.php");
// if everything is ok, try to upload file
} else {
    // Move file from temp directory to $server_directory
    move_uploaded_file($_FILES["file_upload"]["tmp_name"], $server_file);
    // Remove the temp file that was created with tempnam()
    unlink($server_upload_directory . pathinfo($server_file, PATHINFO_FILENAME));
}

// Scale images by percentage
$med_scale_percentage = 0.75;
$small_scale_percentage = 0.40;

$med_width = $check[0] * $med_scale_percentage;
$med_height = $check[1] * $med_scale_percentage;
$small_width = $check[0] * $small_scale_percentage;
$small_height = $check[1] * $small_scale_percentage;

// Create image resource identifier
$image_med_identifier = imagecreatetruecolor($med_width, $med_height);
$image_small_identifier = imagecreatetruecolor($small_width, $small_height);

// Call appropriate function depending on image type
switch ($uploaded_extension) {
    case 'jpeg':
    case 'jpg':
        $image_small = imagecreatefromjpeg($server_file);
        $image_med = imagecreatefromjpeg($server_file);
        break;
    case 'png':
        $image_small = imagecreatefrompng($server_file);
        $image_med = imagecreatefrompng($server_file);
        break;
    case 'gif':
        $image_small = imagecreatefromgif($server_file);
        $image_med = imagecreatefromgif($server_file);
        break;
    default:
        $image_small = false;
        $image_med = false;
        break;
}

// Actually scale images down; one small, one medium
if ($image_small !== false && $image_med !== false) {
    imagecopyresampled($image_small_identifier, $image_small, 0, 0, 0, 0, $small_width, $small_height, $check[0], $check[1]);
    imagecopyresampled($image_med_identifier, $image_med, 0, 0, 0, 0, $med_width, $med_height, $check[0], $check[1]);

    $small_path = $server_upload_directory . "small_" . substr(pathinfo($server_file, PATHINFO_FILENAME), 6, 12) . "." . $uploaded_extension; // unique file name
    $med_path = $server_upload_directory . "medium_" . substr(pathinfo($server_file, PATHINFO_FILENAME), 6, 12) . "." . $uploaded_extension; // unique file name
}

// Call appropriate function depending on image type
switch ($uploaded_extension) {
    case 'jpeg':
    case 'jpg':
        imagejpeg($image_small_identifier, $small_path);
        imagejpeg($image_med_identifier, $med_path);
        break;
    case 'png':
        imagepng($image_small_identifier, $small_path);
        imagepng($image_med_identifier, $med_path);
        break;
    case 'gif':
        imagegif($image_small_identifier, $small_path);
        imagegif($image_med_identifier, $med_path);
        break;
    default:
        break;
}

try {
    // Connect to DB
    $connection = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL statement to insert new entry into image table
    $sql_insert_small = "INSERT INTO images (parent_id, path, name, size, description)"
            . "VALUES ('$id', '$small_path', '$uploaded_name', 'small', '$description')";
    $sql_insert_med = "INSERT INTO images (parent_id, path, name, size, description)"
            . "VALUES ('$id', '$med_path', '$uploaded_name', 'medium', '$description')";

    // Prepare and execute INSERT small image statement
    $statement = $connection->prepare($sql_insert_small);
    $statement->execute();

    // Prepare and execute INSERT medium image statement
    $statement = $connection->prepare($sql_insert_med);
    $statement->execute();

    $connection = null;
} catch (PDOException $exc) {
    //There was an error
    print $exc->getMessage();
    print "<br>";
}

session_start();
$_SESSION['image_id'] = $id;
header("Location: show_images.php");
