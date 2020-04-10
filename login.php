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

    .errorMessage {
      padding:20px;
      margin: 0 auto;
      color:red;
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
		  </div>
		</nav>

  		<div class=".container ">
        <div class="col-lg-4 holder">
          <form class="bg-light text-dark rounded" method="post">
                <label for="userName">Username</label>
                <input type="text" class="form-control" id="userName" name="userName" aria-describedby="usernameHelp" placeholder="Enter username" required>
                <small id="usernameHelp" class="form-text text-muted">Please enter your username.</small>
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <p>Don't have an account? Sign up <a href='#'>here</a>.</p>
                <div class="text-right"><button type="submit" class="btn btn-danger">Login</button></div>
          </form>
          <?php
            if (isset($_POST['userName']) && isset($_POST['password'])) {
              $result = login($_POST['userName'], $_POST['password']) -> get_result();
              $count = 0;
              while($row = $result->fetch_assoc()) {
                $_SESSION['userId'] = $row['userId'];
                $_SESSION['userName'] = $row['userName'];
                $_SESSION['displayName'] = $row['displayName'];
                $count += 1;
              }

              if ($count === 1) {
                $_SESSION['loggedIn'] = true;
                header("Location: /index.php "); //Go back to index after loggedIn
              } else {
                print('<div class="errorMessage">No such user found. Please try again.</div>');
              }
            }
          ?>
  		</div>
    </div>
	</body>
<html>
