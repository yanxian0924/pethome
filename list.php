<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Search Pets Near You</title>

		<link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head>
	<body>
        <?php
            require_once('appvars.php');
            require_once('connectvars.php');

            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
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

            $zipcode = mysqli_real_escape_string($dbc, trim($_POST['zipcode']));
            $distance = mysqli_real_escape_string($dbc, $_POST['distance']);
            $species = mysqli_real_escape_string($dbc, $_POST['species']);
            $sex = mysqli_real_escape_string($dbc, $_POST['sex']);

            $query_list = 'select pet.id, pet.name, pet.sex, age.name as age, pet.pic';
            $query_where = 'where pet.age=age.id and pet.approved=1 and pet.adopted=0 ';
            if (!empty($species)) {
                $query_where .= 'and pet.species=' . $species . ' ';
            }

            if (!empty($sex)) {
                $query_where .= 'and pet.sex=' . $sex . ' ';
            }

            if (!empty($zipcode)) {
                if (!ctype_digit($zipcode)) {
                    $errZipcode = 'Please enter into only numbers';
                } else {
                    $zipcode_query = 'SELECT lat, lng FROM zipcode WHERE code = ' . $zipcode;
                    $data = mysqli_query($dbc, $zipcode_query);
                    if (!mysqli_num_rows($data)) {
                        $errZipcode = 'Please enter into valid zipcode';
                    }
                    while ($row = mysqli_fetch_assoc($data)) {
                        $query_list .= ', (3959 * acos(cos(radians(' . $row['lat'] . ')) * cos(radians(shelter.lat)) *
                        cos(radians(shelter.lng) - radians(' . $row['lng'] . ')) + sin(radians(' . $row['lat'] . ')) *
                        sin(radians(shelter.lat)))) as distance from pet, shelter, age ' . $query_where .
                        'HAVING distance < ' . $distance . ' ORDER BY distance';
                    }
                }
            } else {
                $query_list .= ' from pet, shelter, age ' . $query_where;
            }
        ?>
		<?php include 'header.php'; ?>
        <!-- Search section begin -->
        <div class="container search-div">
            <div id="errMsg" class="alert alert-danger alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo "<p class='text-danger'>$errZipcode</p>";?>
            </div>
	        <div class="row">
            <div class="panel panel-default">
                <div class="panel-body search-panel">
                    <form class="form-inline" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="form-group search-group">
                            <label class="filter-col" style="margin-right:0;" for="pref-search">ZipCode:</label>
                            <input type="text" name="zipcode" maxlength="5" class="form-control input-sm" id="zipcode" placeholder="Enter your location" value="<?php echo htmlspecialchars($_POST['zipcode']); ?>">
                        </div>
                        <div class="form-group search-group">
                            <label class="filter-col" style="margin-right:0;" for="pref-perpage">Distance:</label>
                            <select id="distance" class="form-control" name="distance">
                                <option value="1" <?=$_POST['distance'] == '1' ? ' selected="selected"' : '';?>>1 mile</option>
                                <option value="3" <?=$_POST['distance'] == '3' ? ' selected="selected"' : '';?>>3 miles</option>
                                <option value="5" <?=$_POST['distance'] == '5' ? ' selected="selected"' : '';?>>5 miles</option>
                                <option value="10" <?=$_POST['distance'] == '10' ? ' selected="selected"' : '';?>>10 miles</option>
                                <option value="15" <?=$_POST['distance'] == '15' ? ' selected="selected"' : '';?>>15 miles</option>
                                <option value="20" <?=$_POST['distance'] == '20' ? ' selected="selected"' : '';?>>20 miles</option>
                                <option value="50" <?=$_POST['distance'] == '50' ? ' selected="selected"' : '';?>>50 miles</option>
                                <option value="100" <?=$_POST['distance'] == '100' ? ' selected="selected"' : '';?>>100 miles</option>
                            </select>
                        </div>
                        <div class="form-group search-group">
                            <label class="filter-col" style="margin-right:0;" for="pref-orderby">Species:</label>
                            <select id="species" class="form-control" name="species">
                                <option value="">-- Select pet type --</option>
                                <?php echo $speciesOpts; ?>
                            </select>
                        </div>
                        <div class="form-group search-group">
                            <label class="filter-col" style="margin-right:0;" for="pref-perpage">Sex:</label>
                            <select id="sex" class="form-control" name="sex">
                                <option value="">-- Select pet sex --</option>
                                <option value="m" <?=$_POST['sex'] == 'm' ? ' selected="selected"' : '';?>>Male</option>
                                <option value="f" <?=$_POST['sex'] == 'f' ? ' selected="selected"' : '';?>>Female</option>
                            </select>
                        </div>
                        <button name="submit" type="submit" class="btn btn-primary filter-col">
                            <span class="glyphicon glyphicon-search"></span> Search
                        </button>
                    </form>
                </div>
            </div>
	        </div>
        </div>
        <!-- Search section end -->

        <!-- Image Grid begin -->
        <div class="pic-div container" style="padding: 0px;">
            <?php
                echo $query_list;
                $data = mysqli_query($dbc, $query_list);
                while ($row = mysqli_fetch_array($data)) {
                    echo '<a href="detail.php?id=' . $row['id'] . '">';
                    echo '<figure>';
                    if (is_file(PH_UPLOADPATH . $row['pic']) && filesize(PH_UPLOADPATH . $row['pic']) > 0) {
                        echo '<img src="' . PH_UPLOADPATH . $row['pic'] . '" alt="Pet Image">';
                    } else {
                        echo '<img src="' . PH_UPLOADPATH . 'unverified.svg' . '" alt="Unverified Image">';
                    }
                    $sex_val = $row['sex'] == 'm' ? 'Male' : 'Female';
                    echo '<figcaption>' . $row['name'] . ' | ' . $sex_val . ' | ' . $row['age'] . '</figcaption></figure></a>';
                }
                mysqli_close($dbc);
            ?>
        </div>
        <!-- Image Grid end -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(function(){
                if ($("#errMsg p").text() == '') {
                    $("#errMsg").hide();
                }
            });
        </script>
        <?php include 'footer.php'; ?>
	</body>
</html>
