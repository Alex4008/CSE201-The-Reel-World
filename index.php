<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

	require_once('password.php');
	$mysqli = mysqli_connect($host, $user, $password, $database);

	if (mysqli_connect_errno($mysqli)) {
    	echo "Failed to connect to MySQL: " . mysqli_connect_error();
    	die;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>The Reel World</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="fav.ico">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <link href='https://fonts.googleapis.com/css?family=Alegreya' rel='stylesheet'>
	<style>
		.left-align {
			position: abosolute;
			left: 5px;
		}
	</style>
</head>
<body style="font-family:Alegreya;background-color:#1e272e;">
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="navbar-header">
    <a class="navbar-brand" href="#">THE REEL WORLD</a>
  </div>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <!--
      <li class="nav-item">
        <a class="nav-link" href="#">My Favorite Movies</a>
      </li>
  -->
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" style="background-color:#c0c3c5;color:#053560;">GO</button>
        </span>
      </div>

      <button class="btn btn-danger" type="button" style="margin:5px;margin-left:10px;">SORT</button>
			<div class="btn-group">
			  <button type="button" class="btn btn-danger">FILTER</button>
			  <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <div class="dropdown-menu bg-secondary text-light">
					<div class="font-weight-bold" style="margin-left:10px;">By Genre</div>
					<form class="px-4 py-3 rounded" id="filterOptions">
						<!-- add php here to populate options based on the Genres table -->
						<div class="form-group" style="margin-left:10px;">
								<input type="checkbox" class="form-check-input" id="romance" value="romance">
                <label for="romance">Romance </label>
            </div>
						<div class="form-group" style="margin-left:10px;">
								<input type="checkbox" class="form-check-input" id="action" value="action">
                <label for="romance">Action </label>
            </div>
				    <button type="submit" class="btn btn-danger align-right" style="margin:10px; float:right; margin-bottom: 5px;">OK</button>
				  </form>
			  </div>
			</div>
      <button class="btn btn-warning" type="button" style="margin:5px; ">Add A Movie</button>
    </form>
  </div>
</nav>

<?php
print '<div class="container">'; // Open container
print '<div class="row">'; // Open first row
$count = 0; //Variable used to determine if we should switch to a new row

$statement = $mysqli->prepare("SELECT * FROM Movies m ORDER BY m.title;"); //Defining the query

$statement->bind_result($movieId, $requestId, $title, $description, $keywords, $imdbLink, $image, $imageAddress, $rating, $isDeleted); // Binding the variables

$statement->execute(); // Executing the query

// Loop goes through all of the results from the query
while($statement->fetch()) {
	if( $isDeleted == false) { // Do not show if its been deleted
		
		if($count == 4) {
			$count = 0;
			print '</div>'; // end row
			print '<div class="row">'; //start new row
		}

		// Build card 
		print '<div class="card col-md" style="margin:5px;padding:0px;border-color:white;">';
		print '<img src="' . $imageAddress . '" width="190px" height="150px" class="card-img-top" alt="' . $title . '">';
		print '<div class="card-body">';
		print '<h5 class="card-title">' . $title . '</h5>';
		print '<p class="card-text"> Genre: ' . $keywords . ' <br>Description: ' . $description . ' <br>Actors: ' . '*shrug emoji*' . ' <br>Rating: ' . $rating . ' /10</p>';
		print '<a href="' . $imdbLink . '" class="btn btn-primary">IMDB</a>';
		print '</div>';
		print '</div>';
		$count++;
	}
}
print '</div>'; // Close the ending row
print '</div>'; // Close the container

?>

</body>
</html>
