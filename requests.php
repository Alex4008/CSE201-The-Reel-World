<?php
	require_once 'db.php';
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
		#message {
			text-align: center;
			margin:0 auto;
			margin-top:20px;
			color: white;
		}

		.requestCard {
			margin: 20px;
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
		        <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
		      </li>
					<li class="nav-item">
		        <a class="nav-link" href="requests.php">My Requests</a>
		      </li>
		    </ul>
		  </div>
		</nav>

		<div class=".container">
			<?php
				if (!isset($_SESSION['loggedIn'])) {
					print '<div id="message">Please <a href="" data-toggle="modal" data-target="#loginModal">log in</a> first.</div>';
				} else {
					$requestManager = new RequestManager($mysqli);
					$content = '';
					$statement = $requestManager -> getRequests($_SESSION['userId']);
					$result = $statement -> get_result();

					while ($row = $result -> fetch_assoc()) {
						$requestDescription = $row['description'];
						$info = json_decode($requestDescription, true);

						$content = $content.'
						<div class="card mb-3 requestCard">
							<div class="row no-gutters">
								<div class="col-md-2">
									<img src="..." class="card-img" alt="...">
								</div>
								<div class="col-md-10">
									<div class="card-body">
										<h5 class="card-title">'.$info -> movieTitle.'</h5>
										<p class="card-text">'.$row['description'].'</p>
										<p class="card-text"><small class="text-muted">'.$row['status'].' on '.$row['requestDate'].'</small></p>
										<div class="text-right">
											<button class="btn btn-secondary">Cancel</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						';
					}
					// $content = $content.'
					// <div class="card mb-3 requestCard">
					// 	<div class="row no-gutters">
					// 		<div class="col-md-2">
					// 			<img src="..." class="card-img" alt="...">
					// 		</div>
					// 		<div class="col-md-10">
					// 			<div class="card-body">
					// 				<h5 class="card-title">Card title</h5>
					// 				<p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
					// 				<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
					// 				<div class="text-right">
					// 					<button class="btn btn-secondary">Cancel</button>
					// 				</div>
					// 			</div>
					// 		</div>
					// 	</div>
					// </div>
					// ';

					print $content;
				}
			?>
			<?php include 'login.php' ?>
		</div>
	</body>
<html>
