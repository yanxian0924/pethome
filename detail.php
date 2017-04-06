<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Animal Information</title>

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
                        shelter.name as sName, shelter.email, shelter.phone, shelter.website,
                        shelter.lat, shelter.lng
                    from pet, shelter, age, species
                    where pet.shelter=shelter.id and pet.age=age.id and pet.species=species.id
                        and pet.id=' . $petId . ';';
            $data = mysqli_query($dbc, $query);
            $row = mysqli_fetch_assoc($data);
            mysqli_close($dbc);
        ?>
		<?php include 'header.php'; ?>
        
        <div class="container">
            <h1 class="page-header"><?php echo $row['pName']?>'s Profile</h1>
            <div class="row">
                <!-- left column -->
                <div class="col-md-3 col-sm-12 col-xs-12 personal-info">
                    <div class="text-center">
                        <?php
                            if (is_file(PH_UPLOADPATH . $row['pic']) && filesize(PH_UPLOADPATH . $row['pic']) > 0) {
                                echo '<img class="avatar img-circle img-thumbnail" src="' . PH_UPLOADPATH . $row['pic'] . '" alt="Pet Image">';
                            } else {
                                echo '<img class="avatar img-circle img-thumbnail" src="' . PH_UPLOADPATH . 'unverified.svg' . '" alt="Unverified Image">';
                            }
                        ?>
                        <a href="javascript:history.back()" class="btn btn-primary active" style="margin-top:20px;" role="button">Back To Search</a>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12 personal-info">
                    <form class="form-horizontal" role="form">
                        <input type="hidden" id="lat" value="<?php echo $row['lat'] ?>" />
                        <input type="hidden" id="lng" value="<?php echo $row['lng'] ?>" />
                        <input type="hidden" id="email" value="<?php echo $row['email'] ?>" />
                        <input type="hidden" id="phone" value="<?php echo $row['phone'] ?>" />
                        <input type="hidden" id="website" value="<?php echo $row['website'] ?>" />
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
                    </form>
                </div>

                <div class="col-md-3 col-sm-12 col-xs-12 personal-info">
                    <div id="map" style="width:100%;height:400px;"></div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            function initMap() {
                var point = {lat: parseFloat($("#lat").val()), lng: parseFloat($("#lng").val())};

                var map = new google.maps.Map(document.getElementById('map'), {
                    center: point,
                    zoom: 12
                });

                var marker = new google.maps.Marker({
                    position: point,
                    map: map
                });

                var infoWindow = new google.maps.InfoWindow;
                var email = $("#email").val()=='' ? 'N/A' : $("#email").val();
                var phone = $("#phone").val()=='' ? 'N/A' : $("#phone").val();
                var website = $("#website").val()=='' ? 'N/A' : $("#website").val();
                marker.addListener('click', function() {
                    infoWindow.setContent('<div class = "info"><dl><dt>Email: </dt><dd>' + email + '</dd><dt>Phone: </dt><dd>' + phone + '</dd><dt>Website: </dt><dd><a href="' + website + '" target="_blank">' + website + '</a></dd></dl></div>');
                    infoWindow.open(map, marker);
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABwWfhqptKgbjViclp-gxAQ3mHntGjjEo&callback=initMap"></script>;
        <?php include 'footer.php'; ?>
	</body>
</html>
