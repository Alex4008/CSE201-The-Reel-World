<?php
	require("db.php");
  $movieManager = new MovieManager($mysqli);
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
    <a class="navbar-brand" href="index.php">THE REEL WORLD</a>
  </div>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
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

<div style="padding:15px; margin-left: 15%; margin-right: 15%; background: #f7f5f4;">
<?php
	$statement = $movieManager->getSingleMovie($_REQUEST['title']); //This runs the function from the db.php file and returns the MySQL statement results.

  $result = $statement->get_result(); // Gets the results from the query

  while ($row = $result->fetch_assoc()) {
    print '<h1>' . $row['title'] . '</h1>';
    print '<p>Actors: ' . $row['actors'] . '</p>';
    print '<p>Description: ' . $row['description'] . '</p>';
  }
?>
</div>


</body>
</html>
