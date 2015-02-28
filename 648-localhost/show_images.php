<!DOCTYPE html>
<html>
    <head>
        <!--<script type="text/javascript" src="js/scripts.js" ></script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/tab.js"></script>
        <script>
            var main = function () {
                $('#small').click(function (event) {
                    event.preventDefault();
                    $(this).tab('show');
                });

                $('#medium').click(function (event) {
                    event.preventDefault();
                    $(this).tab('show');
                });

                $('#large').click(function (event) {
                    event.preventDefault();
                    $(this).tab('show');
                });
            };

            $(document).ready(main);
        </script>
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
                <!--Navigation Tabs-->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#small" aria-controls="small" role="tab" data-toggle="tab">Small</a></li>
                    <li role="presentation"><a href="#medium" aria-controls="medium" role="tab" data-toggle="tab">Medium</a></li>
                    <li role="presentation"><a href="#large" aria-controls="large" role="tab" data-toggle="tab">Large</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="small">
                        <?php
                        print "<img src=\"" . $small['path'] . "\" />";
                        ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="medium">
                        <?php
                        print "<img src=\"" . $medium['path'] . "\" />";
                        ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="large">
                        <?php
                        print "<img src=\"" . $large['path'] . "\" />";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>