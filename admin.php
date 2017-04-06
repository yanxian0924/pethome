<?php
  require_once('authorize.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Admin System For PetHome</title>

		<link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	</head>
	<body>
        <?php
            require_once('appvars.php');
            require_once('connectvars.php');
            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

            $query = 'select pet.id, pet.name, species.name as species, pet.sex, pet.breed, age.name as age, pet.size, pet.approved
                    from pet, age, species
                    where pet.age=age.id and pet.species=species.id and pet.adopted=0;';
            $data = mysqli_query($dbc, $query);
        ?>
		<?php include 'adminHeader.php'; ?>
        <div class="container">
            <h2>Pet List</h2>
            <p>Below is a list of all adoptable pets. Use this page to approve reported pets, update pets information, or set pets adopted as needed.</p>
            <div class="form-group pull-right">
                <input id="search_input" type="text" class="search form-control" placeholder="What you looking for?">
            </div>
            <span class="counter pull-right"></span>
            <table class="table table-hover table-bordered results">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Species</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>Breed</th>
                        <th>Size</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($row = mysqli_fetch_array($data)) {
                            $sex = $row['sex']=='m' ? 'Male' : 'Female';
                            $age = $row['age']=='' ? 'N/A' : $row['age'];
                            $breed = $row['breed']=='' ? 'N/A' : $row['breed'];
                            $size = $row['size']=='' ? 'N/A' : $row['size'];
                            echo '<tr>';
                            echo '<td>' . $row['name'] . '</td>';
                            echo '<td>' . $row['species'] . '</td>';
                            echo '<td>' . $sex . '</td>';
                            echo '<td>' . $age . '</td>';
                            echo '<td>' . $breed . '</td>';
                            echo '<td>' . $size . '</td>';
                            echo '<td><a class="btn btn-success btn-sm" role="button" style="padding: 0px 5px; margin-right: 5px;" href="adopt.php?id=' . $row['id'] . '">Adopt</a>';
                            echo '<a class="btn btn-warning btn-sm" role="button" style="padding: 0px 5px; margin-right: 5px;" href="edit.php?id=' . $row['id'] . '">Edit</a>';
                            if ($row['approved'] == '0') {
                                echo '<a class="btn btn-primary btn-sm" role="button" style="padding: 0px 5px;" href="approve.php?id=' . $row['id'] . '">Approve</a>';
                            }
                            echo '</td></tr>';
                        }

                        mysqli_close($dbc);
                    ?>
                </tbody>
            </table>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(function(){
                $(".search").keyup(function () {
                    var searchTerm = $(".search").val();
                    var listItem = $('.results tbody').children('tr');
                    var searchSplit = searchTerm.replace(/ /g, "'):containsi('");

                    $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
                            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                        }
                    });

                    $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
                        $(this).attr('visible','false');
                    });

                    $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
                        $(this).attr('visible','true');
                    });

                    var jobCount = $('.results tbody tr[visible="true"]').length;
                    $('.counter').text(jobCount + ' item');

                    if(jobCount == '0') {
                        $('.no-result').show();
                    } else {
                        $('.no-result').hide();
                    }
                });
            });
        </script>
		<?php include 'footer.php'; ?>
	</body>
</html>
