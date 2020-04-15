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

final class MovieManager
{
    private $mysqli;
    
    public function __construct($mysqli_conn)
    {
        $this->mysqli = $mysqli_conn;
    }
    
    public function getAllMovies() {
		$statement = $this->mysqli->prepare("SELECT m.title, m.movieId, m.requestId, m.description, m.keywords, m.imdbLink, m.image, m. imageAddress, m.rating, m.isDeleted, GROUP_CONCAT(g.description) genre, (SELECT GROUP_CONCAT(a.actorName) FROM Actors a JOIN ActorMovie am ON am.actorId = a.actorId WHERE am.movieId = m.movieId) AS actors 
			FROM Movies m 
			JOIN GenreMovie gm ON gm.movieId = m.movieId 
			JOIN Genres g ON g.genreId = gm.genreId 
			GROUP BY m.title 
			ORDER BY m.title;"); //Defining the query
		$statement->bind_result($movieId, $requestId, $title, $description, $keywords, $imdbLink, $image, $imageAddress, $rating, $isDeleted, $genre, $actors); // Binding the variablesX()
		$statement->execute(); // Executing the query
		return $statement; // Return the results from the query
	}
    public function getAllMoviesByRating() {
		$statement = $this->mysqli->prepare("SELECT m.title, m.movieId, m.requestId, m.description, m.keywords, m.imdbLink, m.image, m. imageAddress, m.rating, m.isDeleted, GROUP_CONCAT(g.description) genre, (SELECT GROUP_CONCAT(a.actorName) FROM Actors a JOIN ActorMovie am ON am.actorId = a.actorId WHERE am.movieId = m.movieId) AS actors 
			FROM Movies m 
			JOIN GenreMovie gm ON gm.movieId = m.movieId 
			JOIN Genres g ON g.genreId = gm.genreId 
			GROUP BY m.title 
			ORDER BY m.rating DESC;"); //Defining the query
		$statement->bind_result($movieId, $requestId, $title, $description, $keywords, $imdbLink, $image, $imageAddress, $rating, $isDeleted, $genre, $actors); // Binding the variablesX()
		$statement->execute(); // Executing the query
		return $statement; // Return the results from the query
	}
    
    public function getAllGenres() {
        $statement = $this->mysqli->prepare("SELECT DISTINCT description FROM Genres;"); 
        //Defining the query
		$statement->bind_result($description); // Binding the variables
		$statement->execute(); // Executing the query
		return $statement; // Return the results from the query
    }

    public function getCheckedGenres($genreList) {
        
        $sql = "SELECT MAX(m.title), m.description, m.keywords, m.imdbLink, m.image, m.imageAddress, m.rating, m.isDeleted, GROUP_CONCAT(g.description) genre, (SELECT GROUP_CONCAT(a.actorName) FROM Actors a JOIN ActorMovie am ON am.actorId = a.actorId WHERE am.movieId = m.movieId) AS actors , g.isDeleted gDeleted 
        FROM Movies m
        JOIN GenreMovie gm ON m.movieId = gm.movieId 
        JOIN Genres g ON g.genreId = gm.genreId
        WHERE g.description IN ('" . implode("','", $genreList) . "')
        GROUP BY m.title;";
        
        if (!($statement = $this->mysqli->prepare($sql))) {
            echo "prepare fail" . $mysqli->error;
        }
        //Defining the query
		$statement->bind_result($title, $description, $keywords, $imdbLink, $image, $imageAddress, $rating, $isDeleted, $genre, $actors, $gDeleted ); // Binding the variables
		$statement->execute(); // Executing the query
		return $statement; // Return the results from the query
    }

    public function getSingleMovie($movieTitle) {
		$statement = $this->mysqli->prepare("SELECT m.title, m.movieId, m.requestId, m.description, m.keywords, m.imdbLink, m.image, m.imageAddress, m.rating, m.isDeleted, GROUP_CONCAT(g.description) genre, (SELECT GROUP_CONCAT(a.actorName) FROM Actors a JOIN ActorMovie am ON am.actorId = a.actorId WHERE am.movieId = m.movieId) AS actors 
			FROM Movies m
			JOIN GenreMovie gm ON gm.movieId = m.movieId 
			JOIN Genres g ON g.genreId = gm.genreId 
			WHERE m.title = ?
			GROUP BY m.title 
			ORDER BY m.title;"); //Defining the query
		$statement->bind_param("s", $movieTitle);
		$statement->bind_result($movieId, $requestId, $title, $description, $keywords, $imdbLink, $image, $imageAddress, $rating, $isDeleted, $genre, $actors); // Binding the variablesX()
		$statement->execute();
		return $statement;
    }
}

?>
