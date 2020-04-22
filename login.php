<?php
  require_once 'db.php';
?>
<script src="sanitize.js"></script>
<script type="text/javascript">
  $(function() {
    $('#loginForm').on('submit', function(event) {
      event.preventDefault();
      let userName = sanitize($('#userName').val());
      let password = sanitize($('#password').val());

      $.ajax({
        url: './processData.php',
        type: 'POST',
        data: {
          userName: userName,
          password: password
        },
        success: function(data) {
          if (data == 1) {
            location.reload();
          } else {
            $('#errorMessage').text(data);
          }

        }
      });
    });
  });
</script>

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
				<form class="bg-light text-dark rounded" method="post" id="loginForm">
							<label for="userName">Username</label>
              <!-- some pattern checking may be needed  -->
							<input type="text" class="form-control" id="userName" name="userName" placeholder="Enter username" required>
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
							<p>Don't have an account? Sign up <a href='signup.php'>here</a>.</p>
              <small style="color:red;" id="errorMessage"></small>
							<div class="text-right"><button type="submit" class="btn btn-danger">Login</button></div>
				</form>
      </div>
    </div>
  </div>
</div>
