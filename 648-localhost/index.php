<!DOCTYPE html>
<html>
    <head>
        <?php include_once './header.php'; ?>
    </head>
    <body>
        <div class="container">
            <h3 class="text-center">A. Rodriguez file upload page</h3>
            <br>
            <div class="col-lg-6 col-lg-offset-3">
                <form class="form-horizontal" role="form" action="file_upload.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="file_description">Description:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="file_description" id="file_description" placeholder="File Description"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="file_upload">File:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="file" name="file_upload" id="file_upload"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" name="submit" class="btn btn-default" >Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
