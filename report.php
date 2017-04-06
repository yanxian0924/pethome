<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Report Animals</title>
        <link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <?php
            require_once('appvars.php');
            require_once('connectvars.php');

            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            $query = "SELECT * FROM age";
            $data = mysqli_query($dbc, $query);
            $ageOpts = '';
            while ($row = mysqli_fetch_array($data)) {
                if ($_POST['age'] == $row['id']) {
                    $ageOpts .= '<option value=' . $row['id'] . ' selected>' . $row['name'] . '</option>';
                } else {
                    $ageOpts .= '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            }

            $query = "SELECT * FROM species";
            $data = mysqli_query($dbc, $query);
            $speciesOpts = '';
            while ($row = mysqli_fetch_array($data)) {
                if ($_POST['species'] == $row['id']) {
                    $speciesOpts .= '<option value=' . $row['id'] . ' selected>' . $row['name'] . '</option>';
                } else {
                    $speciesOpts .= '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            }

            $query = "SELECT * FROM shelter";
            $data = mysqli_query($dbc, $query);
            $shelterOpts = '';
            while ($row = mysqli_fetch_array($data)) {
                if ($_POST['shelter'] == $row['id']) {
                    $shelterOpts .= '<option value=' . $row['id'] . ' selected>' . $row['name'] . '</option>';
                } else {
                    $shelterOpts .= '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            }

            if (isset($_POST['submit'])) {
                $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
                $species = mysqli_real_escape_string($dbc, $_POST['species']);
                $sex = mysqli_real_escape_string($dbc, $_POST['sex']);
                $age = mysqli_real_escape_string($dbc, $_POST['age']);
                $breed = mysqli_real_escape_string($dbc, trim($_POST['breed']));
                $size = mysqli_real_escape_string($dbc, trim($_POST['size']));
                $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
                $shelter = mysqli_real_escape_string($dbc, $_POST['shelter']);
                $pic = mysqli_real_escape_string($dbc, trim($_FILES['pic']['name']));
                $pic_type = $_FILES['pic']['type'];
                $pic_size = $_FILES['pic']['size'];
                if (!empty($name) && !empty($species) && !empty($shelter) && !empty($pic)) {
                    if ((($pic_type == 'image/gif') || ($pic_type == 'image/jpeg')
                            || ($pic_type == 'image/pjpeg') || ($pic_type == 'image/png'))
                            && ($pic_size > 0) && ($pic_size <= PH_MAXFILESIZE)) {
                        if ($_FILES['pic']['error'] == 0) {
                            // Move the file to the target upload folder
                            $target = PH_UPLOADPATH . $pic;
                            if (move_uploaded_file($_FILES['pic']['tmp_name'], $target)) {
                                // Write the data to the database
                                $query = "INSERT INTO pet (name, sex, age, size, breed, species, description, pic, shelter) VALUES ('$name', '$sex', '$age', '$size', '$breed', '$species', '$description', '$pic', '$shelter')";
                                mysqli_query($dbc, $query);
                                mysqli_close($dbc);
                                header('Location: success_report.php?name=' . $name);
                                exit;
                            } else {
                                $errPicSize = '<p class="error">Sorry, there was a problem uploading picture of animal.</p>';
                            }
                        }
                    } else {
                        $errPicSize = '<p class="error">The picture of pet must be a GIF, JPEG, or PNG image file no greater than ' . (PH_MAXFILESIZE / 1024) . ' KB in size.</p>';
                    }

                    // Try to delete the temporary screen shot image file
                    @unlink($_FILES['pic']['tmp_name']);
                } else {
                    if (empty($name)) {
                        $errName = 'Please enter his/her name';
                    }
                    if (empty($species)) {
                        $errSpecies = 'Please select his/her species';
                    }
                    if (empty($shelter)) {
                        $errShelter = 'Please select a shelter';
                    }
                    if (empty($pic)) {
                        $errPic = 'Please upload his/her picture';
                    }
                }
            }

            mysqli_close($dbc);
        ?>
        <?php include 'headerNoNav.php'; ?>
        <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <h1 class="page-header text-center">Report &nbsp;Animal</h1>
                <form enctype="multipart/form-data" class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <?php echo $errPicSize; ?>
                        </div>
                    </div>
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo PH_MAXFILESIZE; ?>" />
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name<span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Give him/her a lovely name" value="<?php echo htmlspecialchars($_POST['name']); ?>">
                            <?php echo "<p class='text-danger'>$errName</p>";?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="species" class="col-sm-2 control-label">Species<span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-control" id="species" name="species">
                                <option value="">-- Select pet type --</option>
                                <?php echo $speciesOpts; ?>
                            </select>
                            <?php echo "<p class='text-danger'>$errSpecies</p>";?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sex" class="col-sm-2 control-label">Sex</label>
                        <div class="col-sm-10">
                            <label class="radio-inline"><input type="radio" name="sex" value="m" checked="checked">Male</label>
                            <label class="radio-inline"><input type="radio" name="sex" value="f">Female</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="age" class="col-sm-2 control-label">Age</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="age" name="age">
                                <option value="">-- Select his/her age --</option>
                                <?php echo $ageOpts; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="breed" class="col-sm-2 control-label">Breed</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="breed" name="breed" placeholder="e.g. Mini mutts" value="<?php echo htmlspecialchars($_POST['breed']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="size" class="col-sm-2 control-label">Size</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="size" name="size" placeholder="e.g. Small 25 lbs or less" value="<?php echo htmlspecialchars($_POST['size']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="4" name="description"><?php echo htmlspecialchars($_POST['description']);?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="shelter" class="col-sm-2 control-label">Location<span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <select class="form-control" id="shelter" name="shelter">
                                <option value="">-- Select a shelter --</option>
                                <?php echo $shelterOpts; ?>
                            </select>
                            <?php echo "<p class='text-danger'>$errShelter</p>";?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pic" class="col-sm-2 control-label">Picture<span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <input id="pic" type="file" class="well well-sm" style="width:100%;" name="pic">
                            <?php echo "<p class='text-danger'>$errPic</p>";?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="text-align: center; margin-bottom: 45px;">
                            <input id="submit" name="submit" type="submit" value="Report" class="btn btn-primary" style="margin-right: 50px;">
                            <input id="reset" name="reset" type="reset" value="Reset" class="btn btn-default">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <?php include 'footer.php'; ?>
    </body>
</html>
