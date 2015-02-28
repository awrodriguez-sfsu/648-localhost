<!DOCTYPE html>
<html>
    <head>
        <?php
        include_once './header.php';
        session_start();
        $error = $_SESSION['error_code'];
        $error_codes = array(
            '-1' => 'Image uploaded was not the correct format. Only JPG, JPEG, GIF, and PNG formats are accepted.',
            '-2' => 'Uploaded file was not an image.',
            '-3' => 'Form was not submitted correctly.',
            '-4' => 'There was an error connecting to the database.',
            '-5' => 'There was an error resizing the image.',
        );
        $message = $error_codes[$error];
        ?>
    </head>
    <body>
        <div class="container">
            <h3 class="text-center"><?php print $message; ?></h3>
            <p class="text-center">Please try again</p>
        </div>
    </body>
</html>

