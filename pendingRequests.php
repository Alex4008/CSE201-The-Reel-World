<?php
	require_once 'db.php';
	$requestManager = new RequestManager($mysqli);
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
	<script src="sanitize.js"></script>
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
	<script>
		$(function() {
			// Approve/Decline button is clicked -> Send POST request and info to processData
			$('.approveButton').on('click', function() {
				let requestId = $(this).attr('requestId');
				let comment = sanitize($('#requestComment' + requestId).val());

				$.ajax({
					url: './processData.php',
					type: 'POST',
					data: {
						method: 'approveRequest',
						comment: comment,
						requestId: requestId
					},
					success: function(data) {
						location.reload();
					}
				});
			});

			$('.declineButton').on('click', function() {
				let requestId = $(this).attr('requestId');
				let comment = sanitize($('#requestComment' + requestId).val());

				$.ajax({
					url: './processData.php',
					type: 'POST',
					data: {
						method: 'declineRequest',
						comment: comment,
						requestId: requestId
					},
					success: function(data) {
						location.reload();
					}
				});
			});
		});

	</script>
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
				  <li class="nav-item ">
				    <a class="nav-link navTab" href="index.php">Home<span class="sr-only">(current)</span></a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link navTab" href="requests.php">My Requests</a>
				  </li>
				  <?php
				    if ($_SESSION['loggedIn'] && $_SESSION['role'] == 'Admin') {
				      $item = '
				      <li class="nav-item active">
				        <a class="nav-link navTab" href="pendingRequests.php">Pending Requests</a>
				      </li>';

				      print $item;
				    }
				  ?>
				</ul>
				<div class="text-right">
					<button class="btn btn-warning" type="button" style="margin:5px; "><a href="addrequest.php" style="text-decoration: none;
					color:black;">Add A Movie</a></button>
				</div>
				<ul class="navbar-nav">
						<li class="nav-item">
								<a class="nav-link" href="./signup.php"><span class="glyphicon glyphicon-user"></span>SIGN UP</a>
						</li>
						<li class="nav-item">
								<a class="nav-link" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-log-in"></span>LOGIN</a>
						</li>
				</ul>
		  </div>
		</nav>

		<div class=".container">
			<?php
				if (!isset($_SESSION['loggedIn'])) {
					print '<div id="message">Please <a href="" data-toggle="modal" data-target="#loginModal">log in</a> first.</div>';
				} elseif ($_SESSION['role'] !== 'Admin') {
					print '<div id="message">This page is for admin only.</div>';
				} else {
					$content = '';
					$statement = $requestManager -> getRequests('*'); //Gets all the requests that is not approved/declined
					$result = $statement -> get_result();

					while ($row = $result -> fetch_assoc()) { // Loops through all requests and populate the page
						// print '<script>console.log(\''.json_encode($row).'\')</script>';
						$requestDescription = $row['description'];
						// print '<script>console.log(\''.$requestDescription.'\')</script>';
						if ($requestDescription != "") {
							$info = json_decode($requestDescription, true);
							// print '<script>console.log(\''.json_encode($info).'\')</script>';
							$content = $content.'
							<div class="card mb-3 requestCard">
								<div class="row no-gutters">
									<div class="col-md-2">
										<img src="'.$info['imageLink'].'" class="card-img" alt="'.$info['movieTitle'].'">
									</div>
									<div class="col-md-10">
										<div class="card-body">
											<h5 class="card-title"><b>'.$info['movieTitle'].'</b></h5>
											<p class="card-text">Genres: '.implode(", ", $info['genreToDisplay']).'</p>
											<p class="card-text">Actors: '.implode(", ", $info['actorToDisplay']).'</p>
											<p class="card-text">Description: '.$info['movieDescription'].'</p>
											<p class="card-text">Rating: '.$info['movieRating'].' /10</p>
											<p class="card-text"><small class="text-muted">'.$row['status'].' on '.$row['requestDate'].'</small></p>
											<div>
												<form>
													<div class="row">
														<div class="col-lg-9">
															<div class="form-group">
																<label for="requestComment"><b>Comments</b></label>
																<textarea id="requestComment'.$row['requestId'].'" class="form-control" rows="2" placeholder="Add request comments"></textarea>
															</div>
														</div>
														<div class="col-lg-3 text-right" style="margin-top: 30px;">
															<button type="button" class="btn btn-danger approveButton" requestId="'.$row['requestId'].'">Approve</button>
															<button type="button" class="btn btn-secondary declineButton" style="margin-left:5px;" requestId="'.$row['requestId'].'">Decline</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							';
						}
					}
					print $content;
				}
			?>
			<?php include 'login.php' ?>
		</div>
	</body>
<html>
