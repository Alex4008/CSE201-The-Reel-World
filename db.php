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

    // A function that gets all the movies from the database that match a string
    // $keyword is the string used to search the database
    public function getAllMoviesByKeyword($keyword) {
          $statement = $this->mysqli->prepare("SELECT m.title, m.movieId, m.requestId, m.description, m.keywords, m.imdbLink, m.image, m. imageAddress, m.rating, m.isDeleted, GROUP_CONCAT(g.description) genre, (SELECT GROUP_CONCAT(a.actorName) FROM Actors a JOIN ActorMovie am ON am.actorId = a.actorId WHERE am.movieId = m.movieId) AS actors
			FROM Movies m
			JOIN GenreMovie gm ON gm.movieId = m.movieId
			JOIN Genres g ON g.genreId = gm.genreId
            WHERE m.title LIKE '%$keyword%'
			GROUP BY m.title
			ORDER BY m.title;"); //Defining the query
		$statement->bind_result($movieId, $requestId, $title, $description, $keywords, $imdbLink, $image, $imageAddress, $rating, $isDeleted, $genre, $actors); // Binding the variablesX()
		$statement->execute(); // Executing the query
		return $statement; // Return the results from the query
    }
    // A function that gets all the movies from the database and orders them by their rating
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

	// Gets all genres in the DB
    public function getAllGenres() {
        $statement = $this->mysqli->prepare("SELECT DISTINCT genreId, description FROM Genres;");
        //Defining the query
		$statement->bind_result($genreId, $description); // Binding the variables
		$statement->execute(); // Executing the query
		return $statement; // Return the results from the query
    }

    public function getAllActors() {
        $statement = $this->mysqli->prepare("SELECT DISTINCT actorId, actorName FROM Actors WHERE isDeleted = false;");
        //Defining the query
		$statement->bind_result($actorId, $actorName); // Binding the variables
		$statement->execute(); // Executing the query
		return $statement; // Return the results from the query
    }

		// Gets all checked genres from filter form
    public function getCheckedGenres($genreList) {

        $sql = "SELECT m.title, m.description, m.keywords, m.imdbLink, m.image, m.imageAddress, m.rating, m.isDeleted, GROUP_CONCAT(g.description) genre, (SELECT GROUP_CONCAT(a.actorName) FROM Actors a JOIN ActorMovie am ON am.actorId = a.actorId WHERE am.movieId = m.movieId) AS actors , g.isDeleted gDeleted
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

		//Gets information of a movie by title
    public function getSingleMovie($movieTitle) {
		$statement = $this->mysqli->prepare("SELECT m.title, m.movieId, m.requestId, m.description, m.keywords, m.imdbLink, m.image, m.imageAddress, m.rating, m.isDeleted, GROUP_CONCAT(g.description) genre, (SELECT GROUP_CONCAT(a.actorName) FROM Actors a JOIN ActorMovie am ON am.actorId = a.actorId WHERE am.movieId = m.movieId) AS actors
			FROM Movies m
			JOIN GenreMovie gm ON gm.movieId = m.movieId
			JOIN Genres g ON g.genreId = gm.genreId
			WHERE m.title = ?
			GROUP BY m.title
			ORDER BY m.title;"); //Defining the query

			$param = str_replace('\'', '&#x27;', $movieTitle);
			$statement->bind_param("s", $param);

			$statement->bind_result($movieId, $requestId, $title, $description, $keywords, $imdbLink, $image, $imageAddress, $rating, $isDeleted, $genre, $actors); // Binding the variablesX()
			$statement->execute();
			return $statement;

	}

	// Adds/Inserts a movie into the DB
	public function addMovie($requestId, $title, $description, $imdbLink, $imageAddress, $rating) {
		$sql = "INSERT INTO Movies(requestId, title, description, imdbLink, imageAddress, rating)
		VALUES (".$requestId.", '".$title."', '".$description."', '".$imdbLink."', '".$imageAddress."', ".$rating.");";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo "New movie added successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}
}

final class UserManager
{
    private $mysqli;

    public function __construct($mysqli_conn)
    {
        $this->mysqli = $mysqli_conn;
    }

		//Checks login info and return user's info
    public function login($userName, $password) {

        $sql = "SELECT u.userId, u.userName, u.displayName, r.roleName
        FROM Users u
        JOIN Roles r ON u.roleId = r.roleId
        WHERE (u.userName = '".$userName."') AND (u.password = '".$password."') AND (u.isDeleted = false);";

        if (!($statement = $this->mysqli->prepare($sql))) {
            echo "prepare fail" . $mysqli->error;
        }
        //Defining the query
				$statement->bind_result($userId, $userName, $displayName, $roleName); // Binding the variables
				$statement->execute(); // Executing the query
				return $statement; // Return the results from the query
    }

		// Allows user to sign up
		public function signup($userName, $password, $displayName) {
				$sql = "INSERT INTO Users(userName, password, displayName)
			 	VALUES ('".$userName."', '".$password."', '".$displayName."');";

			 if ($this->mysqli->query($sql) === TRUE) {
					 echo "1";
			 } else {
					 echo "Error: " . $sql . "<br>" . $this->mysqli->error;
			 }
		}

		//Checks to ensure that the userName is not used yet (i.e. available)
		public function checkUsername($userName) {
            $returnVal = false;
            
			$sql = "SELECT userId FROM Users WHERE userName = '".$userName."';";
			if (!($statement = $this->mysqli->prepare($sql))) {
					echo "prepare fail" . $mysqli->error;
			}

			$statement -> bind_result($userId);
			$statement -> execute();
			$result = $statement->get_result();
			echo $result-> num_rows;
            
            if ($result->num_rows == 0)
                $returnVal = true;
            return $returnVal;
		}
}

final class RequestManager
{
	private $mysqli;

	public function __construct($mysqli_conn)
	{
			$this->mysqli = $mysqli_conn;
	}

	/**
	Save/Insert a new request into the DB
	*/
	public function saveRequest($userId, $requestName, $description) {
		$sql = "INSERT INTO Requests(userId, requestName, description)
		VALUES (".$userId.", '".$requestName."', '".$description."');";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo "New record created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}

	/**
	Gets all requests belonging to a user.
	If the userId is '*', all requests will be chosen
	*/
	public function getRequests($userId) {
		if ($userId === '*') {
			$sql = "SELECT r.requestId, r.requestDate, r.requestName, r.description, (SELECT statusDescription FROM Status s WHERE s.statusId=r.statusId) AS status FROM Requests r WHERE (SELECT statusDescription FROM Status s WHERE s.statusId=r.statusId) NOT IN ('Approved', 'Declined') ORDER BY r.requestDate DESC ;";
		} else {
			$sql = "SELECT r.requestId, r.requestDate, r.requestName, r.description, (SELECT statusDescription FROM Status s WHERE s.statusId=r.statusId) AS status FROM Requests r WHERE r.userId=".$userId.";";
		}

		if (!($statement = $this -> mysqli -> prepare($sql))) {
			echo "prepare fail" . $this -> mysqli->error;
			return $this -> mysqli->error;
		} else {
			$statement -> bind_result($requestId, $requestDate, $requestName, $description, $status);
			$statement -> execute();
			return $statement;
		}
	}

	/**
	Gets a request specified by requestId
	*/
	public function getRequestById($requestId) {
		$sql = "SELECT r.requestId, r.requestDate, r.requestName, r.description, (SELECT statusDescription FROM Status s WHERE s.statusId=r.statusId) AS status FROM Requests r WHERE r.requestId=$requestId";

		if (!($statement = $this -> mysqli -> prepare($sql))) {
			echo "prepare fail" . $this -> mysqli->error;
			return $this -> mysqli->error;
		} else {
			$statement -> bind_result($requestId, $requestDate, $requestName, $description, $status);
			$statement -> execute();
			$row = $statement->get_result()->fetch_assoc();
			return $row;
		}
	}

	/**
	Updates a request with new description (including requestComment),
	status and current date
	*/
	public function updateRequest($requestId, $requestDescription, $statusDescription) {
		$sql = "UPDATE Requests r
						SET r.description = '".$requestDescription."',
								r.statusId = (SELECT statusId FROM Status WHERE statusDescription='".$statusDescription."'),
								r.requestDate = '".date('Y-m-d H:i:s')."'
						WHERE requestId = ".$requestId.";";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo $requestId." status changed";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}

	/**
	Deletes (Cancels) a specified request
	*/
	public function deleteRequest($requestId) {
		$sql = "DELETE FROM Requests WHERE requestId = ".$requestId.";";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo $requestId." deleted";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}
}

final class CommentManager
{
	private $mysqli;

	public function __construct($mysqli_conn) {
		$this -> mysqli = $mysqli_conn;
	}

	/**
	Returns the comments belonging to a certain movie
	*/
	public function getCommentsByMovie ($movieId) {
		$sql = "SELECT c.commentId, u.userName, u.displayName, c.commentText
		FROM Comments c
		JOIN Users u ON c.userId = u.userId
		WHERE c.movieId = ".$movieId."
		GROUP BY c.commentId
		ORDER BY c.commentId;";

		// if (!()) {
		// 	echo "prepare fail" . $mysqli->error;
		// }

		$statement = $this -> mysqli -> prepare($sql);
		$statement -> bind_result($commentId, $userName, $displayName, $commentText);
		$statement -> execute();
		return $statement;
	}

	/**
	Adds comments for the specified movie
	*/
	public function addComment($userId, $movieId, $commentText) {
		$sql = "INSERT INTO Comments(userId, movieId, commentText)
		VALUES (".$userId.", ".$movieId.", '".$commentText."');";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo "New comment added successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}
    
    /**
	Deletes comments for the specified movie
	*/
	public function deleteComment($commentId) {
		$sql = "DELETE FROM Comments WHERE commentId=".$commentId."";
		if ($this->mysqli->query($sql) === TRUE) {
            echo $commentid;
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}
}

final class ActorManager
{
	private $mysqli;

	public function __construct($mysqli_conn)
	{
			$this->mysqli = $mysqli_conn;
	}

	/**
	Adds/Inserts a new actor into the DB
	*/
	public function addNewActor($requestId, $actorName, $actorLink) {
		$sql = "INSERT INTO Actors(requestId, actorName, actorLink)
		VALUES (".$requestId.", '".$actorName."', '".$actorLink."');";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo "New actor added successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}

	/**
	Adds a new actor movie record into the DB
	Links an actor with a movie
	*/
	public function linkActorMovieById($actorId, $movieId) {
		$sql = "INSERT INTO ActorMovie(actorId, movieId)
		VALUES (".$actorId.", ".$movieId.");";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo "New actor-movie link added successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}

	/**
	Adds a new actor movie record into the DB by names
	Links an actor with a movie
	*/
	public function linkActorMovieByName($actorName, $movieName) {
		$actorId = -1;
		$movieId = -1;
		$sql = "SELECT a.actorId FROM Actors a WHERE a.actorName='".$actorName."';";
		if (!($statement = $this -> mysqli -> prepare($sql))) {
			echo "prepare fail" . $this -> mysqli->error;
			return $this -> mysqli->error;
		} else {
			$statement -> bind_result($actorId);
			$statement -> execute();
			$row = $statement->get_result()->fetch_assoc();
			$actorId = $row['actorId'];
		}
		$sql = "SELECT m.movieId FROM Movies m WHERE m.title='".$movieName."';";
		if (!($statement = $this -> mysqli -> prepare($sql))) {
			echo "prepare fail" . $this -> mysqli->error;
			return $this -> mysqli->error;
		} else {
			$statement -> bind_result($movieId);
			$statement -> execute();
			$row = $statement->get_result()->fetch_assoc();
			$movieId = $row['movieId'];
		}

		if ($actorId !== -1 && $movieId !== -1) {
			$this->linkActorMovieById($actorId, $movieId);
		} else {
			echo 'Actor or movie not found';
		}
 	}
}

final class GenreManager
{
	private $mysqli;

	public function __construct($mysqli_conn)
	{
			$this->mysqli = $mysqli_conn;
	}

/**
Adds a new genre to the DB
*/
	public function addNewGenre($description) {
		$sql = "INSERT INTO Genres(description)
		VALUES ('".$description."');";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo "New genre created successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}

	/**
	Adds a new genre movie record to the DB
	Links a movie with its genre
	*/
	public function linkGenreMovieById($genreId, $movieId) {
		$sql = "INSERT INTO GenreMovie(genreId, movieId)
		VALUES (".$genreId.", ".$movieId.");";

		if ($this->mysqli->query($sql) === TRUE) {
		    echo "New genre-movie link added successfully";
		} else {
		    echo "Error: " . $sql . "<br>" . $this->mysqli->error;
		}
	}

	/**
	Adds a new genre movie record to the DB
	Links a movie with its genre by name
	*/
	public function linkGenreMovieByName($genreName, $movieName) {
		$genreId = -1;
		$movieId = -1;
		$sql = "SELECT g.genreId FROM Genres g WHERE g.description='".$genreName."';";
		if (!($statement = $this -> mysqli -> prepare($sql))) {
			echo "prepare fail" . $this -> mysqli->error;
			return $this -> mysqli->error;
		} else {
			$statement -> bind_result($genreId);
			$statement -> execute();
			$row = $statement->get_result()->fetch_assoc();
			$genreId = $row['genreId'];
		}
		$sql = "SELECT m.movieId FROM Movies m WHERE m.title='".$movieName."';";
		if (!($statement = $this -> mysqli -> prepare($sql))) {
			echo "prepare fail" . $this -> mysqli->error;
			return $this -> mysqli->error;
		} else {
			$statement -> bind_result($movieId);
			$statement -> execute();
			$row = $statement->get_result()->fetch_assoc();
			$movieId = $row['movieId'];
		}

		if ($genreId !== -1 && $movieId !== -1) {
			$this->linkGenreMovieById($genreId, $movieId);
		} else {
			echo 'Genre or movie not found';
		}
	}
}
?>
