<?php
	require 'db.php';
	session_start();

    $movieManager = new MovieManager($mysqli);

    // Printing POST for debugging
//    echo("<p style='color:white;'>POST=</p>");
//    foreach ($_POST as $element) {
//        echo("<p style='color:white;'>");
//         print_r($element);
//        echo("</p>");
//    }
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

		.align-right {
			position: abosolute;
			right: 5px;
		}

		a:link, a:visited {
			text-decoration: none;
			color:black;
		}
	</style>
	<script src="script.js"></script>
</head>
<body style="font-family:Alegreya;background-color:#1e272e;">
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="navbar-header">
    <a class="navbar-brand" href="index.php">THE REEL WORLD</a>
  </div>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
      </li>
			<li class="nav-item">
        <a class="nav-link" href="requests.php">My Requests</a>
      </li>
    </ul>
		<div class="navbar-nav ml-auto w-60 order-3">
			<form class="form-inline my-2 my-lg-0 navbar-nav ml-auto" method="post">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Search...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit" style="background-color:#c0c3c5;color:#053560;">GO</button>
					</span>
				</div>
				<div>
				<div class="btn-group" style="margin:5px;margin-left:10px;">
				  <button type="button" class="btn btn-danger" id="sortButton" name="sortButton" value="SORT">SORT</button>
				  <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu bg-secondary text-white" style="color:white;" id="classSortOptions">
                    <div class="font-weight-bold" style="margin-left:10px;">Sort</div>
                    <form class="px-4 py-3 rounded" id="sortOptions" method="post">
                        
                    <div class="form-group" style="margin-left:10px;">
                     <input type="checkbox" class="form-check-input" id="By title" value="By title" name="sortTitle">  
                    <label for="By title">By title</label>
                        </div>
                    <div class="form-group" style="margin-left:10px;">
                      <input type="checkbox" class="form-check-input" id="By rating" value="By rating" name="sortRating">
                        <label for="By rating">By rating</label>
                        </div>
                        <button type="submit" class="btn btn-danger align-right" style="margin:10px; float:right; margin-bottom: 5px;">OK</button>
                      </form>
				  </ul>
				</div>

				<div class="btn-group">
						<button type="button" class="btn btn-danger">FILTER</button>
						<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="sr-only">Toggle Dropdown</span>
						</button>
						<div class="dropdown-menu bg-secondary text-light">
								<div class="font-weight-bold" style="margin-left:10px;">By Genre</div>
									<form class="px-4 py-3 rounded" id="filterOptions" method="post">
											<!-- Populates options based on the Genres table -->
	<?php
	// get all genres for filter
	$statement = $movieManager->getAllGenres(); //This runs the function from the db.php file and returns the MySQL statement results.

	$result = $statement->get_result(); // Gets the results from the query
	$i = 0;

	// Loop goes through all of the results from the query
	while($row = $result->fetch_assoc()) {
			if( $row['isDeleted'] == false) { // Do not show if its been deleted

					// Build list
					print '<div class="form-group" style="margin-left:10px;">';
					print '<input type="checkbox" class="form-check-input" id="' . $row['description'] . '" value="' . $row['description'] . '" name="genre[' . $i . ']">';
					print '<label for="' . $row['description'] . '">' . $row['description'] . '</label>';
					print '</div>';
					$i++;
			}
	}
	?>
							<button type="submit" class="btn btn-danger align-right" style="margin:10px; float:right; margin-bottom: 5px;">OK</button>
						</form>
					</div>
					</div>
				<button class="btn btn-warning" type="button" style="margin:5px; "><a href="addrequest.php">Add A Movie</a></button>
			</form>
			</div>
			<ul class="navbar-nav">
					<li class="nav-item">
							<a class="nav-link" href="#"><span class="glyphicon glyphicon-user"></span>SIGN UP</a>
					</li>
					<li class="nav-item">
							<a class="nav-link" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-log-in"></span>LOGIN</a>
					</li>
			</ul>
	  </div>
  </div>
</nav>
<?php include 'login.php' ?>
<?php
print '<div class="container">'; // Open container
print '<div class="row">'; // Open first row
$count = 0; //Variable used to determine if we should switch to a new row
$selectedGenre = null;

function getPostGenres() {
    $arr = null;
    if(isset($_POST['genre'])) {
        $arr = $_POST['genre'];

    }
    return($arr);
}

//function getSorted($result) {
//    $resultArr = array();
//    while($row = $result->fetch_assoc()) {
////        if($isDeleted == false) {
//            $arr = array($row["movieId"], $row["title"], $row["imageAddress"], $row["genre"], $row["description"], $row["actors"], $row["rating"], $row["imdbLink"]);
//            array_push($resultArr, $arr);  
////        }
//    }
//    return $result;
//}
$selectedGenre = getPostGenres();

if (!isset($_POST['genre']) && !isset($_POST['movieIds']) && !isset($_POST['sortTitle']) && !isset($_POST['sortRating']))
    $statement = $movieManager->getAllMovies(); //This runs the function from the db.php file and returns the MySQL statement results.
else if (isset($_POST['genre'])) {
    $statement = $movieManager->getCheckedGenres($selectedGenre);
}
else if (isset($_POST['sortTitle'])) {
    $statement = $movieManager->getAllMovies();
}
else if (isset($_POST['sortRating'])) {
    $statement = $movieManager->getAllMoviesByRating();
}

$result = $statement->get_result(); // Gets the results from the query
//$result = getSorted($result);
// Loop goes through all of the results from the query
while($row = $result->fetch_assoc()) {
	if( $isDeleted == false) { // Do not show if its been deleted

		if($count == 4) {
			$count = 0;
			print '</div>'; // end row
			print '<div class="row">'; //start new row
		}

		// Build card
		print '<div class="card col-md movie" movieId="'.$row['movieId'].'" style="margin:5px;padding:0px;border-color:white;">';
		print '<a href= "singleMovie.php?title=' . $row['title'] .'">';
		print '<img src="' . $row['imageAddress'] . '" width="190px" height="150px" class="card-img-top" alt="' . $row['title'] . '">';
		print '</a>';

		print '<div class="card-body">';
		print '<h5 class="card-title">' . $row['title'] . '</h5>';
		print '<p class="card-text"> Genre: ' . $row['genre'] . ' <br>Description: ' . $row['description'] . ' <br>Actors: ' . $row['actors'] . ' <br>Rating: ' . $row['rating'] . ' /10</p>';
		print '<a href="' . $row['imdbLink'] . '" class="btn btn-primary">IMDB</a>';
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
