<?php
  require_once 'db.php';
?>

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<form class="bg-light text-dark rounded" method="post">
							<label for="userName">Username</label>
              <!-- some pattern checking may be needed  -->
							<input type="text" class="form-control" id="userName" name="userName" placeholder="Enter username" required>
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
							<p>Don't have an account? Sign up <a href='#'>here</a>.</p>
							<div class="text-right"><button type="submit" class="btn btn-danger">Login</button></div>
				</form>
				<?php
					if (isset($_POST['userName']) && isset($_POST['password'])) {
            //  sanitize input if have time
						$result = login($_POST['userName'], $_POST['password']) -> get_result();
						$count = 0;
						while($row = $result->fetch_assoc()) {
							$_SESSION['userId'] = $row['userId'];
							$_SESSION['userName'] = $row['userName'];
							$_SESSION['displayName'] = $row['displayName'];
              $_SESSION['role'] = $row['roleName'];
              // print('<script>console.log("'.$_SESSION['role'].'")</script>');
							$count += 1;
						}

						if ($count === 1) {
							$_SESSION['loggedIn'] = true;
							// header("Location: /index.php "); //Go back to index after loggedIn
						} else {
							print('<div class="errorMessage">No such user found. Please try again.</div>');
						}
					}
				?>
      </div>
    </div>
  </div>
</div>
