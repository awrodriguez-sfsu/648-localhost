<!DOCTYPE html>
<html>
    <head>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/tab.js"></script>
        <?php
        include_once './db_connect.php';
        include_once './header.php';

        session_start();
        $id = $_SESSION['image_id'];

        try {
            $connection = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_get_images = "SELECT * FROM images WHERE parent_id='$id'";
            $statement = $connection->prepare($sql_get_images);
            $statement->execute();

            $results = $statement->fetchAll();

            foreach ($results as $entry) {
                switch ($entry['size']) {
                    case 'small':
                        $small = $entry;
                        break;
                    case 'medium':
                        $medium = $entry;
                        break;
                    case 'large':
                        $large = $entry;
                        break;
                }
            }

            $connection = null;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        ?>
    </head>
    <body>
        <div class="container">
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#small" aria-controls="small" role="tab" data-toggle="tab">Small</a></li>
                    <li role="presentation"><a href="#medium" aria-controls="medium" role="tab" data-toggle="tab">Medium</a></li>
                    <li role="presentation"><a href="#large" aria-controls="large" role="tab" data-toggle="tab">Large</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="small">
                        <?php
                        print "<img src=\"" . $small['path'] . "\"/>";
                        print "<br>";
                        print "Name: " . $small['name'];
                        print "<br>";
                        print "Description: " . $small['description'];
                        print "<br>";
                        ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="medium">
                        <?php
                        print "<img src=\"" . $medium['path'] . "\"/>";
                        print "<br>";
                        print "Name: " . $medium['name'];
                        print "<br>";
                        print "Description: " . $mediums['description'];
                        print "<br>";
                        ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="large">
                        <?php
                        print "<img src=\"" . $large['path'] . "\"/>";
                        print "<br>";
                        print "Name: " . $large['name'];
                        print "<br>";
                        print "Description: " . $large['description'];
                        print "<br>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>