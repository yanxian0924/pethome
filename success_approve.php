<?php
  require_once('authorize.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>To Adopt</title>

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
            $query = 'update pet set approved=1 where id=' . $petId;
            mysqli_query($dbc, $query);
            mysqli_close($dbc);
        ?>
		<?php include 'adminHeader.php'; ?>

        <div class="container">
            <div class="row text-center">
                <div id="success_msg" class="col-sm-6 col-sm-offset-3">
                    <h2>Success</h2>
                    <img src="images/check-true.jpg">
                    <p class="msg">The report of <strong><?php echo $_GET['name'] ?></strong> is approved successfully.</p>
                    <a href="admin.php" class="btn btn-success">Back to List</a>
                </div>
	        </div>
        </div>
	</body>
</html>
