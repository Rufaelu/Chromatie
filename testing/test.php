<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form  method="POST" enctype="multipart/form-data">
    <label for="file">Choose a file:</label>
    <input type="file" name="file" id="file" required>
    <button type="submit">Upload</button>
</form>

</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Directory where the uploaded file will be saved
    $targetDir = __DIR__ . "/pics/"; // Change this to your project directory

    // Get the file name and temporary file path
    $fileName = basename($_FILES["file"]["name"]);
    $tempFilePath = $_FILES["file"]["tmp_name"];

    // Generate the target file path
    $targetFilePath = $targetDir . $fileName;

    // Move the file from the temporary location to the target directory
    if (move_uploaded_file($tempFilePath, $targetFilePath)) {
        echo "File has been uploaded successfully.";

        // Save the file path into a variable
        $filePath = $targetFilePath;

        // Optional: Save the file path in the database or do other processing
        echo "File path saved: " . $filePath;
    } else {
        echo "File upload failed.";
    }
}
?>

