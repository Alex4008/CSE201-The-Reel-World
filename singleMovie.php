<?php
	require("db.php");
	session_start();
  $movieManager = new MovieManager($mysqli);
	$commentManager = new CommentManager($mysqli);
	if (isset($_POST['commentText'])) {
		$commentManager -> addComment($_POST['userId'], $_POST['movieId'], $_POST['commentText']);
	}
?>
<!-- Guest user ID is 52 -->
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
		.cButton {
			margin-right: 10px;
			margin-bottom: 5px;
		}

	 img {
		 margin: 10px;
	 }
	</style>
	<script src="sanitize.js"></script>
	<script type="text/javascript">
		$(function() {
			$('#postComment').on('click', function() {
				let userId = $(this).attr('userId');
				let movieId = $(this).attr('movieId');
				let commentText = sanitize($('#commentText').val());
				console.log(userId);
				console.log(movieId);
				console.log(commentText);

				$.ajax({
					url: './singleMovie.php',
					type: 'POST',
					data: {
						movieId: movieId,
						userId: userId,
						commentText: commentText
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
	      <li class="nav-item active">
	        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
	      </li>
				<li class="nav-item">
					<a class="nav-link navTab" href="requests.php">My Requests</a>
				</li>
				<?php
					if ($_SESSION['loggedIn'] && $_SESSION['role'] == 'Admin') {
						$item = '
						<li class="nav-item">
							<a class="nav-link navTab" href="pendingRequests.php">Pending Requests</a>
						</li>';

						print $item;
					}
				?>
	    </ul>
	    <form class="form-inline my-2 my-lg-0">
	      <button class="btn btn-warning" type="button" style="margin:5px; "><a href="addrequest.php" style="text-decoration: none;
				color:black;">Add A Movie</a></button>
	    </form>
			<ul class="navbar-nav">
					<li class="nav-item">
							<a class="nav-link" href="signup.php">SIGN UP</a>
					</li>
					<li class="nav-item">
							<a class="nav-link" data-toggle="modal" data-target="#loginModal" id="loginLink">LOGIN</a>
					</li>
			</ul>
	  </div>
	</nav>


	<div class="container">
	<div  style="padding:15px; margin: 0 auto; background: #f7f5f4;">
		<div class="row">
			<div class="col-lg-11">
			<?php
				$statement = $movieManager->getSingleMovie($_REQUEST['title']); //This runs the function from the db.php file and returns the MySQL statement results.
				//print('<script>console.log("'.json_encode($statement, JSON_HEX_TAG).'");</script>');
			  $result = $statement->get_result(); // Gets the results from the query
				$movieId = null;
			  while ($row = $result->fetch_assoc()) {
			    print '<h1>' . $row['title'] . '</h1>';
			    print '<p>Actors: ' . $row['actors'] . '</p>';
			    print '<p>Description: ' . $row['description'] . '</p>';
					$movieId = $row['movieId'];
			  }
			?>
			</div>
		</div>
	</div>
		<div style="padding:15px; margin: 0 auto; margin-top:50px; background: #f7f5f4;">
			<div class="row">
				<div class="col-lg-11" >
					<h2>Comments</h2>
					<p><small>If you are not logged in, you will not be able to modify or delete your comments.</small></p>
				</div>
			</div>
			<div id="commentSection">
				<div class="addComment">
					<form method="post">
						<div class="card mb-3">
						  <div class="row no-gutters">
						    <div class="col-sm-1">
									<?php
										if (isset($_SESSION['loggedIn'])) {
											echo '<img src="https://api.adorable.io/avatars/200/'.$_SESSION['userName'].'" class="card-img" alt="'.$_SESSION['userName'].'">';
										} else {
											echo '<img src="https://api.adorable.io/avatars/200/guest-user" class="card-img" alt="Guest">';
										}
									?>
						    </div>
						    <div class="col-sm-11">
						      <div class="card-body">
						        <h5 class="card-title"><b>
											<?php
												if (isset($_SESSION['loggedIn'])) {
													if ($_SESSION['displayName'] != "") {
														echo $_SESSION['displayName'];
													} else {
														echo $_SESSION['userName'];
													}
												} else {
													echo 'Guest User';
												}
											?>
										</b></h5>
                                    <?php
                                        //Checks if the edit button is set
                                        if(isset($_POST['editBtn'])) {
                                        //Creates a text area where the usual post area is
                                        echo '<textarea class="form-control card-text" rows="1" id="commentText">'.$_POST[$_POST['editBtn']].'</textarea>';
                                        $commentManager->deleteComment($_POST['editBtn']); //Removes old comment
                                        unset($_POST['editBtn']); //I tried unsetting the button to get it to execute the
                                                                  // else statement but after posting the new comment it 
                                                                  // still prints the text area with the old comment
                                        } else {
                                            echo '<textarea class="form-control card-text" rows="1" id="commentText"></textarea>';
                                        }
                                  ?>
						      </div>
						    </div>
						  </div>
							<div class="row">
								<div class="col">
									<div class="text-right">
										<?php
											if (isset($_SESSION['loggedIn'])) {
												echo '<button type="submit" class="btn btn-danger cButton" id="postComment" movieId="'.$movieId.'" userId="'.$_SESSION['userId'].'">POST</button>';
											} else {
												echo '<button type="submit" class="btn btn-danger cButton" id="postComment" movieId="'.$movieId.'" userId="52">POST</button>';
											}
										?>
										<button type="reset" class="btn btn-secondary cButton">CANCEL</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
                <?php 
                // Deletes comment if delete button is pressed
                if(isset($_POST['deleteBtn'])) {
                $commentManager->deleteComment($_POST['deleteBtn']);
                }
                ?>
				<div class="commentLine ">
					<?php
						$statement = $commentManager -> getCommentsByMovie($movieId); // Gets all comments belonging to this movie
						$result = $statement -> get_result();
						$content = '';
						while ($row = $result->fetch_assoc()) { // Displays all the comments
                            $mycomment = false;
							$content .= '
							<div class="card mb-3">
								<div class="row no-gutters">
									<div class="col-sm-1">
										<img src="https://api.adorable.io/avatars/200/'.$row['userName'].'" class="card-img" alt="'.$row['userName'].'">
									</div>
									<div class="col-sm-11">
										<div class="card-body">
											<h5 class="card-title"><b>';
				            if ($row['displayName'] != "") {
								$content.= $row['displayName'];
                                if(isset($_SESSION['loggedIn'])) {
                                    if($row['userName'] == $_SESSION['userName'] || $_SESSION['role'] == 'Admin')
                                    $mycomment = true; // Checks if the logged in user is an admin or owns the comment
                                }
				            } else {
								$content.= $row['userName'];
                                if(isset($_SESSION['loggedIn'])) {
                                    if($row['userName'] == $_SESSION['userName'] || $_SESSION['role'] == 'Admin')
                                    $mycomment = true;
                                }
                                }
								$content.= '</b></h5>
											<p class="card-text">'.$row['commentText'].'</p>';
                            if($mycomment) {
                                // If the owner is logged in then they can edit and delete comments. If the logged in user
                                // is an admin then they can delete any comment
                                $comment[$row['commentId']] = $row['commentText'];
                                $content.= '<div class="row">
								                <div class="col">
									                 <div class="text-right">
                                                            <form method="post">';
                                                            if ($row['userName'] == $_SESSION['userName']) {
                                                            $content .= '<button class="btn btn-secondary cButton" type="submit" value="'.$row['commentId'].'" name="editBtn">Edit</button>';
                                                            }
                                                            $content .= '<input type="hidden" name="'.$row['commentId'].'" value="'.$row['commentText'].'">
                                                            <button class="btn btn-danger cButton" type="submit" value="'.$row['commentId'].'" name="deleteBtn">Delete</button>
                                                            </form>
                                                     </div>
                                                </div>
							                </div>';
                            }
				             $content.='</div>
									</div>
								</div>
							</div>
							';
						}
						print $content;
					?>
				</div>
			</div>
		</div>
	<?php include 'login.php' ?>
	</div>
</body>
</html>
