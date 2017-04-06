<?php
  require_once('authorize.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Edit Profile Successfully</title>

		<link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head>
	<body>
		<?php include 'adminHeader.php'; ?>

        <div class="container">
            <div class="row text-center">
                <div id="success_msg" class="col-sm-6 col-sm-offset-3">
                    <h2>Success</h2>
                    <img src="images/check-true.jpg">
                    <p class="msg">The profile of <strong><?php echo $_GET['name'] ?></strong> is updated successfully.</p>
                    <a href="admin.php" class="btn btn-success">To Admin Home</a>
                    <?php echo $_GET['query'] ?>
                </div>
	        </div>
        </div>
	</body>
</html>
