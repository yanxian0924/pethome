<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Report Animal Successful</title>

		<link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head>
	<body>
		<?php include 'headerNoNav.php'; ?>

        <div class="container">
            <div class="row text-center">
                <div id="success_msg" class="col-sm-6 col-sm-offset-3">
                    <h2>Success</h2>
                    <img src="images/check-true.jpg">
                    <p class="msg">Thanks for reporting <strong><?php echo $_GET['name'] ?></strong> to PetHome! It will be reviewed and added to the list as soon as possible.</p>
                    <a href="list.php" class="btn btn-success">Back to Homepage</a>
                    <a href="report.php" class="btn btn-success">Report Again</a>
                </div>
	        </div>
        </div>
	</body>
</html>
