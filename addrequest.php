<?php
  require 'db.php';
  session_start();
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
  <script src="multiselect-plugin/dist/js/BsMultiSelect.js"></script>

	<style>
		.holder {
      margin:0 auto;
      padding:20px;
		}

    form {
      padding:20px;
    }

    button {
      margin: 10px 0 0 0;
    }

    #message {
			text-align: center;
			margin:0 auto;
			margin-top:20px;
			color: white;
		}

    form > h3 {
      text-align: center;
      color:#8c1f2b;
    }
	</style>
	<script src="addrequest.js"></script>
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
		  </div>
		</nav>

    <div class=".container ">
      <?php
				if (!isset($_SESSION['loggedIn'])) {
					print '<div id="message">Please <a href="" data-toggle="modal" data-target="#loginModal">log in</a> first.</div>';
				} else {
					$content ='<div class="col-lg-6 holder">';
          $content = $content.'
          <form class="bg-light text-dark rounded" method="post">
                <h3>Movie Request Form</h3>
                <div class="form-group">
                  <label for="movieTitle">Title</label>
                  <input type="text" class="form-control" id="movieTitle" name="movieTitle" placeholder="Enter movie title" required>
                </div>
                <div class="form-group">
                  <label for="movieDescription">Description</label>
                  <textarea class="form-control" id="movieDescription" name="movieDescription" rows="3" placeholder="Enter movie description"></textarea>
                </div>
                <div class="form-group">
                  <label for="movieGenres">Genres</label>
                  <div class="genres">
                    <select name="genres[]" id="movieGenres" class="form-control"  multiple="multiple" style="display: none;" placeholder="Select movie genres" required>';
          //Gets all genres and populate
          $statement = getAllGenres(); //This runs the function from the db.php file and returns the MySQL statement results.

        	$result = $statement->get_result(); // Gets the results from the query
        	$i = 0;

        	// Loop goes through all of the results from the query
        	while($row = $result->fetch_assoc()) {
            if ($row['isDeleted'] == false) {
              if ($i <= 1) {
                $content = $content.'<option selected value="'.$row[genreId].'">'.$row[description].'</option>';
              } else {
                $content = $content.'<option value="'.$row[genreId].'">'.$row[description].'</option>';
              }
            }
            $i = $i + 1;
          }
          $content = $content.'</select>
                  </div>
                  <p style="margin-top:7px;">Your movie genres not found?
                    <a href="#" class="btn btn-danger btn-sm">
                      Add new genres
                    </a>
                  </p>
                  <div id="addGenreField"></div>
                </div>

                <div class="form-group">
                  <label for="movieActors">Actors</label>
                  <div class="actors">
                    <select name="actors[]" id="movieActors" class="form-control"  multiple="multiple" style="display: none;" placeholder="Select movie genres" required>';
          //Gets all genres and populate
          $statement = getAllActors(); //This runs the function from the db.php file and returns the MySQL statement results.

        	$result = $statement->get_result(); // Gets the results from the query
        	$i = 0;

        	// Loop goes through all of the results from the query
        	while($row = $result->fetch_assoc()) {
            if ($row['isDeleted'] == false) {
              if ($i <= 1) {
                $content = $content.'<option selected value="'.$row[actorId].'">'.$row[actorName].'</option>';
              } else {
                $content = $content.'<option value="'.$row[actorId].'">'.$row[actorName].'</option>';
              }
            }
            $i = $i + 1;
          }
          $content = $content.'</select>
                  </div>
                  <p style="margin-top: 7px;">Your actors not found?
                    <a href="#" class="btn btn-danger btn-sm">
                      Add new actors
                    </a>
                  </p>
                  <div id="addActorField"></div>
                </div>

                <div class="form-group">
                  <label for="imdbLink">IMDb Link</label>
                  <input class="form-control" id="imdbLink" name="imdbLink" type="url" placeholder="IMDb link to this movie" required/>
                </div>
                <div class="form-group">
                  <label for="imageLink">Display Image</label>
                  <input class="form-control" id="imageLink" name="imageLink" type="url" placeholder="Link to the image you want to display for this movie" required/>
                </div>
                <div class="text-right">
                  <button type="button" class="btn btn-warning" id="saveButton">Save</button>
                  <button type="button" class="btn btn-danger" id="submitButton">Submit</button>
                  <button type="reset" class="btn btn-secondary">Cancel</button>
                </div>
          </form>';
          $content = $content.'</div>';
          print $content;
				}
			?>
      <?php include 'login.php' ?>
    </div>
    <script src="multiselect.min.js"></script>
  </body>
</html>
