<?php
	// Load passwords
	require_once('password.php');

	// Connect to DB
	$mysqli = mysqli_connect($host, $user, $password, $database);
	if (mysqli_connect_errno($mysqli)) {
		// Throw error if failed
    	echo "Failed to connect to MySQL: " . mysqli_connect_error();
    	die;
	}

	// DB Functions (Add any new functions below this line)

	/*
	Fair warning for anyone who edits this file.
	Global variables in PhP are WACK. You need to use the following syntax to access them:
		$GLOBALS['varName'];

	Just trying to save you some time that I wasted, Goodluck!
	- Alex
	*/

	function getAllMovies() {
		$statement = $GLOBALS['mysqli']->prepare("SELECT * FROM Movies m ORDER BY m.title;"); //Defining the query
		$statement->bind_result($movieId, $requestId, $title, $description, $keywords, $imdbLink, $image, $imageAddress, $rating, $isDeleted); // Binding the variables
		$statement->execute(); // Executing the query
		return $statement; // Return the results from the query
	}
?>