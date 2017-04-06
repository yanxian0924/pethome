<?php
  require_once('authorize.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Animal Profile Edit</title>

		<link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head>
	<body>
        <?php
            require_once('appvars.php');
            require_once('connectvars.php');

            $petId = $_GET['id'];
            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            $species = '';
            $age = '';
            $shelter = '';
			$pid = '';
			$name = '';
			$sex = '';
			$breed = '';
			$size = '';
			$description = '';
			$pic = '';
            if (isset($_POST['submit'])) {
                $pid = mysqli_real_escape_string($dbc, trim($_POST['pid']));
                $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
                $species = mysqli_real_escape_string($dbc, $_POST['species']);
                $sex = mysqli_real_escape_string($dbc, $_POST['sex']);
                $age = mysqli_real_escape_string($dbc, $_POST['age']);
                $breed = mysqli_real_escape_string($dbc, trim($_POST['breed']));
                $size = mysqli_real_escape_string($dbc, trim($_POST['size']));
                $description = mysqli_real_escape_string($dbc, trim($_POST['description']));
                $shelter = mysqli_real_escape_string($dbc, $_POST['shelter']);
                $old_pic = mysqli_real_escape_string($dbc, trim($_POST['old_pic']));
				$pic = $old_pic;
                $new_pic = mysqli_real_escape_string($dbc, trim($_FILES['new_pic']['name']));
                $new_pic_type = $_FILES['new_pic']['type'];
                $new_pic_size = $_FILES['new_pic']['size'];
                $error = false;
                if (!empty($name) && !empty($species) && !empty($shelter) && !empty($new_pic)) {
                    if ((($new_pic_type == 'image/gif') || ($new_pic_type == 'image/jpeg')
                            || ($new_pic_type == 'image/pjpeg') || ($new_pic_type == 'image/png'))
                            && ($new_pic_size > 0) && ($new_pic_size <= PH_MAXFILESIZE)) {
                        if ($_FILES['new_pic']['error'] == 0) {
                            $target = PH_UPLOADPATH . $new_pic;
                            if (move_uploaded_file($_FILES['new_pic']['tmp_name'], $target)) {
                                // The new picture file move was successful, now make sure any old picture is deleted
                                if (($old_pic != "unverified.svg") && ($old_pic != $new_pic)) {
                                    @unlink(PH_UPLOADPATH . $old_pic);
                                }
                            } else {
                                // The new picture file move failed, so delete the temporary file and set the error flag
                                @unlink($_FILES['new_pic']['tmp_name']);
                                $error = true;
                                $errPicSize = '<p class="error">Sorry, there was a problem uploading your picture.</p>';
                            }
                        }
                    } else {
                        @unlink($_FILES['new_pic']['tmp_name']);
                        $error = true;
                        $errPicSize = '<p class="error">The picture of pet must be a GIF, JPEG, or PNG image file no greater than ' . (PH_MAXFILESIZE / 1024) . ' KB in size.</p>';
                    }
                }

                if (!$error) {
                    $pic_update = empty($new_pic) ? $old_pic : $new_pic;
                    $query = "update pet set name='$name', sex='$sex', age='$age', size='$size',
                                breed='$breed', species='$species', description='$description',
                                pic='$pic_update', shelter='$shelter' where id='$pid'";
                    mysqli_query($dbc, $query);
                    mysqli_close($dbc);
                    header("Location: success_edit.php?name=$name");
                    exit;
                }
            } else {
                $query = 'select id, name, species, sex, breed, age, size, description, pic, shelter
                        from pet where pet.id=' . $petId;
                $data = mysqli_query($dbc, $query);
                $info = mysqli_fetch_assoc($data);
				$pid = $info['id'];
				$name = $info['name'];
				$sex = $info['sex'];
				$breed = $info['breed'];
				$size = $info['size'];
				$description = $info['description'];
				$pic = $info['pic'];
                $species = $info['species'];
                $age = $info['age'];
                $shelter = $info['shelter'];
            }

            $query = "SELECT * FROM age";
            $data = mysqli_query($dbc, $query);
            $ageOpts = '';
            while ($row = mysqli_fetch_array($data)) {
                if ($age == $row['id']) {
                    $ageOpts .= '<option value=' . $row['id'] . ' selected>' . $row['name'] . '</option>';
                } else {
                    $ageOpts .= '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            }

            $query = "SELECT * FROM species";
            $data = mysqli_query($dbc, $query);
            $speciesOpts = '';
            while ($row = mysqli_fetch_array($data)) {
                if ($species == $row['id']) {
                    $speciesOpts .= '<option value=' . $row['id'] . ' selected>' . $row['name'] . '</option>';
                } else {
                    $speciesOpts .= '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            }

            $query = "SELECT * FROM shelter";
            $data = mysqli_query($dbc, $query);
            $shelterOpts = '';
            while ($row = mysqli_fetch_array($data)) {
                if ($shelter == $row['id']) {
                    $shelterOpts .= '<option value=' . $row['id'] . ' selected>' . $row['name'] . '</option>';
                } else {
                    $shelterOpts .= '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
                }
            }

            mysqli_close($dbc);
        ?>
		<?php include 'adminHeader.php'; ?>

        <div class="container">
            <h1 class="page-header">Edit Profile</h1>
            <div class="row">
                <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="old_pic" value="<?php if($pic != 'unverified.svg') echo $pic ?>" />
                    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo PH_MAXFILESIZE; ?>" />
                    <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
                    <!-- left column -->
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="text-center">
                            <?php
                                if (is_file(PH_UPLOADPATH . $pic) && filesize(PH_UPLOADPATH . $pic) > 0) {
                                    echo '<img class="avatar img-circle img-thumbnail" src="' . PH_UPLOADPATH . $pic . '" alt="Pet Image">';
                                } else {
                                    echo '<img class="avatar img-circle img-thumbnail" src="' . PH_UPLOADPATH . 'unverified.svg' . '" alt="Unverified Image">';
                                }
                            ?>
                            <h6>Upload a different picture here</h6>
                            <input id="new_pic" name="new_pic" type="file" class="text-center center-block well well-sm">
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-6 col-xs-12 personal-info">
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <?php echo $errPicSize; ?>
                            </div>
                        </div>
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo PH_MAXFILESIZE; ?>" />
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Name:</label>
                            <div class="col-lg-8">
                                <input id="name" name="name" class="form-control" style="width: inherit;" value="<?php echo $name?>" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Species:</label>
                            <div class="col-lg-8">
                                <select class="form-control" style="width: inherit;" id="species" name="species">
                                    <option value="">-- Select pet type --</option>
                                    <?php echo $speciesOpts; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Breed:</label>
                            <div class="col-lg-8">
                                <input id="breed" name="breed" class="form-control" style="width: inherit;" value="<?php echo $breed?>" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Sex:</label>
                            <div class="col-lg-8">
                                <label class="radio-inline"><input type="radio" name="sex" value="m" <?php echo $sex == 'm' ? 'checked' : ''?>>Male</label>
                                <label class="radio-inline"><input type="radio" name="sex" value="f" <?php echo $sex == 'f' ? 'checked' : ''?>>Female</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Age:</label>
                            <div class="col-lg-8">
                                <select class="form-control" style="width: inherit;" id="age" name="age">
                                    <option value="">-- Select his/her age --</option>
                                    <?php echo $ageOpts; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Size:</label>
                            <div class="col-lg-8">
                                <input id="size" name="size" class="form-control" style="width: inherit;" value="<?php echo $size?>" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Description:</label>
                            <div class="col-lg-8">
                                <textarea id="description" class="form-control" style="width: inherit;" rows="4" name="description"><?php echo $description?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Location:</label>
                            <div class="col-lg-8">
                                <select class="form-control" style="width: inherit;" id="shelter" name="shelter">
                                    <option value="">-- Select a shelter --</option>
                                    <?php echo $shelterOpts; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 45px;">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-8">
                                <input id="submit" name="submit" type="submit" value="Save Profile" class="btn btn-primary">
                                <span>&nbsp;</span>
                                <a href="admin.php" class="btn btn-default" role="button">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <?php include 'footer.php'; ?>
	</body>
</html>
