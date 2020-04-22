<?php
  require_once 'db.php';
  $userManager = new UserManager($mysqli);
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
  <!-- Add icon library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Add text font -->
  <link href='https://fonts.googleapis.com/css?family=Alegreya' rel='stylesheet'>
  <script src="multiselect-plugin/dist/js/BsMultiSelect.js"></script>
  <script src="sanitize.js"></script>
	<style>
    form {
      margin:0 auto;
      margin-top:20px;
      padding:20px;
    }

    form > h3 {
      text-align: center;
      color:#8c1f2b;
    }
	</style>
  <script src="sanitize.js"></script>
	<script>
    $(function() {
      $('#userNameExists').hide();

      $('#newUserName').on('keyup change blur', function() {
        let newUserName = sanitize($(this).val().trim());
        if (newUserName !== '') {
          $.ajax({
            url: './processData.php',
            type: 'POST',
            data: {
              userNameToCheck: newUserName
            },
            success: function (data) {
              if (data != 0) {
                $('#userNameExists').show();
                $('#signupButton').attr('disabled', true);
              } else {
                $('#userNameExists').hide();
                $('#signupButton').removeAttr('disabled');
              }
            }
          });
        } else {
          $('#userNameExists').hide();
          $('#signupButton').attr('disabled', true);
        }
      });

      $('#signupForm').on('submit', function(event) {
        event.preventDefault();
        let newUserName = sanitize($('#newUserName').val());
        let newPassword = sanitize($('#newPassword').val());
        let displayName = sanitize($('#displayName').val());

        $.ajax({
          url: './processData.php',
          type: 'POST',
          data: {
            newUserName: newUserName,
            newPassword: newPassword,
            displayName: displayName
          },
          success: function(data) {
            if (data == 1) {
              $('#status').css("color", "#116327");
              $('#status').text('Account created successfully!');
            } else {
              $('#status').css("color", "#8f1711");
              $('#status').text('There is an error. Please try again later!');
            }
            $('#signupStatus').modal();
            $('#signupForm')[0].reset();
          }
        });
      });

      $('#showPassword').on('click', function(event) {
        event.preventDefault();
        let password = $('#newPassword');
        if (password.attr("type") == "password") {
          password.attr("type", "text");
        } else {
          password.attr("type", "password");
        }
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
    		    <a class="nav-link navTab" href="index.php">Home<span class="sr-only">(current)</span></a>
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
        <div class="text-right">
          <ul class="navbar-nav">
  						<li class="nav-item">
  								<a class="nav-link" href="#"><span class="glyphicon glyphicon-user"></span>SIGN UP</a>
  						</li>
  						<li class="nav-item">
  								<a class="nav-link" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-log-in"></span>LOGIN</a>
  						</li>
  				</ul>
        </div>
		  </div>
		</nav>

    <div class="container">
      <form class="bg-light text-dark w-75 rounded" method="post" id="signupForm">
        <h3>SIGN UP!</h3>
        <div class ="row">
          <div class="form-group col-sm-12">
            <label for="displayName">Full Name</label>
            <input type="text" class="form-control" id="displayName" name="displayName" placeholder="Enter your full name" required>
          </div>
        </div>
        <div class ="row">
          <div class="form-group col-sm-12">
            <label for="newUserName">Username</label>
            <input type="text" class="form-control" id="newUserName" name="newUserName" placeholder="Enter a unique user name" required>
            <small style="color:red" id="userNameExists">Username already exists.</small>
          </div>
        </div>
        <div class ="row">
          <div class="form-group col-sm-12">
            <label for="newPassword">Password</label>
            <div class="input-group">
              <input type="password" class="form-control" data-toggle="password" id="newPassword" name="newPassword" placeholder="Enter your password" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <a href="#"><i id="showPassword" class="fa fa-eye"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 text-right">
            <button type="submit" class="btn btn-danger" id="signupButton" name="button">Sign Up</button>
            <button type="reset" class="btn btn-secondary" name="button" style="margin-left: 10px;">Cancel</button>
          </div>

        </div>
      </form>
    </div>
    <?php include 'login.php' ?>
    <div class="modal fade" id="signupStatus" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <p id="status"></p>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
        </div>

      </div>
    </div>
	</body>
</html>
