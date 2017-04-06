<?php
  require_once('authorize.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Animal Adopt</title>

		<link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head>
	<body>
        <?php
            require_once('appvars.php');
            require_once('connectvars.php');

            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            $petId = $_GET['id'];
            $query = 'select pet.name as pName, species.name as species,
                        pet.sex, pet.breed, age.name as age, pet.size, pet.description, pet.pic,
                        shelter.name as sName
                    from pet, shelter, age, species
                    where pet.shelter=shelter.id and pet.age=age.id and pet.species=species.id
                        and pet.id=' . $petId;
            $data = mysqli_query($dbc, $query);
            $row = mysqli_fetch_assoc($data);
            mysqli_close($dbc);
            $to_adopt_url = "success_adopt.php?id=$petId&name=" . $row['pName'];
        ?>
		<?php include 'adminHeader.php'; ?>

        <div class="container">
            <h1 class="page-header"><?php echo $row['pName']?>'s Profile</h1>
            <div class="row">
                <!-- left column -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="text-center">
                        <?php
                            if (is_file(PH_UPLOADPATH . $row['pic']) && filesize(PH_UPLOADPATH . $row['pic']) > 0) {
                                echo '<img class="avatar img-circle img-thumbnail" src="' . PH_UPLOADPATH . $row['pic'] . '" alt="Pet Image">';
                            } else {
                                echo '<img class="avatar img-circle img-thumbnail" src="' . PH_UPLOADPATH . 'unverified.svg' . '" alt="Unverified Image">';
                            }
                        ?>
                    </div>
                </div>

                <div class="col-md-8 col-sm-6 col-xs-12 personal-info">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Species:</label>
                            <div class="detail-val col-lg-8"><?php echo $row['species']?></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Breed:</label>
                            <div class="detail-val col-lg-8"><?php echo $row['breed']=='' ? 'No Record' : $row['breed']?></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Sex:</label>
                            <div class="detail-val col-lg-8"><?php echo $row['sex'] == 'm' ? 'Male' : 'Female'?></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Age:</label>
                            <div class="detail-val col-lg-8"><?php echo $row['age']=='' ? 'No Record' : $row['age']?></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Size:</label>
                            <div class="detail-val col-lg-8"><?php echo $row['size']=='' ? 'No Record' : $row['size']?></div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Description:</label>
                            <div class="detail-val col-lg-8"><?php echo $row['description']=='' ? 'No Record' : $row['description']?></div>
                        </div>
						<div class="form-group">
                            <label class="col-lg-3 control-label">Location:</label>
                            <div class="detail-val col-lg-8"><?php echo $row['sName']=='' ? 'No Record' : $row['sName']?></div>
                        </div>
                        <div class="form-group" style="margin-bottom: 45px;">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-8">
                                <a href="<?php echo $to_adopt_url ?>" class="btn btn-primary" role="button">Adopt</a>
                                <span>&nbsp;</span>
                                <a href="admin.php" class="btn btn-default" role="button">Cancel</a>
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
