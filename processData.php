<?php
  require 'db.php';
  $movieManager = new MovieManager($mysqli);
  $requestManager = new RequestManager($mysqli);
  $userManager = new UserManager($mysqli);
  $genreManager = new GenreManager($mysqli);
  $actorManager = new ActorManager($mysqli);
  $commentManager = new CommentManager($mysqli);
  session_start();
?>

<?php
  //When user is logged in and requestData is sent, save the request
  if (isset($_SESSION['loggedIn']) && isset($_POST['data'])) {
    $requestManager -> saveRequest($_SESSION['userId'], "Request made by ".$_SESSION['userName'], $_POST['data']);
    echo('"data is received"');
  }

  // Checks the availability of a username
  if (isset($_POST['userNameToCheck'])) {
    $userManager-> checkUsername($_POST['userNameToCheck']);
  }

  // Adds/Insert a user into the DB
  if (isset($_POST['newUserName']) && isset($_POST['newPassword']) && isset($_POST['displayName'])) {
    $userManager->signup($_POST['newUserName'], $_POST['newPassword'], $_POST['displayName']);
  }

  // Call the log in function to log a user in
  if (isset($_POST['userName']) && isset($_POST['password'])) {
    //  sanitize input if have time
    $result = $userManager -> login($_POST['userName'], $_POST['password']) -> get_result();
    $count = 0;
    while($row = $result->fetch_assoc()) {
      // Sets the $_SESSION parameters to use across pages
      $_SESSION['userId'] = $row['userId'];
      $_SESSION['userName'] = $row['userName'];
      $_SESSION['displayName'] = $row['displayName'];
      $_SESSION['role'] = $row['roleName'];
      $count += 1;
    }

    if ($count === 1) {
      $_SESSION['loggedIn'] = true;
      print(1);
    } else {
      print('No such user found. Please try again.');
    }
  }

  // Call the approve request function
  if (isset($_POST['method']) && $_POST['method'] === 'approveRequest') {
    $comment = $_POST['comment'];
    $requestId = $_POST['requestId'];
    // Gets request info
    $request = $requestManager->getRequestById($requestId);
    $data = json_decode($request['description']);

    // Adds new genres and new actors as needed
    foreach ($data->newGenreDescription as $d) {
      $genreManager->addNewGenre($d);
    }
    foreach ($data->newActors as $actor) {
      $actorManager->addNewActor($requestId, $actor->actorName, $actor->actorLink);
    }
    // Adds movie to the DB
    if (isset($data-> movieRating)) {
      $movieManager->addMovie($requestId, $data->movieTitle, $data->movieDescription, $data->imdbLink, $data->imageLink, $data->movieRating);
    } else {
      $movieManager->addMovie($requestId, $data->movieTitle, $data->movieDescription, $data->imdbLink, $data->imageLink, 5);
    }

    // Links genres/actors with the movie
    foreach ($data->genreToDisplay as $key => $value) {
      $genreManager->linkGenreMovieByName($value, $data->movieTitle);
    }
    foreach ($data->actorToDisplay as $key => $value) {
      $actorManager->linkActorMovieByName($value, $data->movieTitle);
    }
    // Add the requestComment to the request
    $data->requestComment = $comment;
    $requestManager->updateRequest($requestId, json_encode($data), 'Approved'); //Sets status of the request to Approved
    echo json_encode($data);
  }

  // Call to decline the request
  if (isset($_POST['method']) && $_POST['method'] === 'declineRequest') {
    $comment = $_POST['comment'];
    $requestId = $_POST['requestId'];

    // Gets request info
    $request = $requestManager->getRequestById($requestId);
    $data = json_decode($request['description']);
    $data->requestComment = $comment; // Add comments admin made to the request
    $requestManager->updateRequest($requestId, json_encode($data), 'Declined'); // Set request status to Declined
    echo json_encode($data);
  }

  if (isset($_POST['commentText'])) {
		$commentManager -> addComment($_POST['userId'], $_POST['movieId'], $_POST['commentText']);
	}
?>
