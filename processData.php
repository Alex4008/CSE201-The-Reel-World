<?php
  require 'db.php';
  $requestManager = new RequestManager($mysqli);
  $userManager = new UserManager($mysqli);
  session_start();
?>

<?php
  if (isset($_SESSION['loggedIn']) && isset($_POST['data'])) {
    $requestManager -> saveRequest($_SESSION['userId'], "Request made by ".$_SESSION['userName'], $_POST['data']);
    echo('"data is received"');
  }

  if (isset($_POST['userNameToCheck'])) {
    $userManager-> checkUsername($_POST['userNameToCheck']);
  }

  if (isset($_POST['newUserName']) && isset($_POST['newPassword']) && isset($_POST['displayName'])) {
    $userManager->signup($_POST['newUserName'], $_POST['newPassword'], $_POST['displayName']);
  }

  if (isset($_POST['userName']) && isset($_POST['password'])) {
    //  sanitize input if have time
    $result = $userManager -> login($_POST['userName'], $_POST['password']) -> get_result();
    $count = 0;
    while($row = $result->fetch_assoc()) {
      $_SESSION['userId'] = $row['userId'];
      $_SESSION['userName'] = $row['userName'];
      $_SESSION['displayName'] = $row['displayName'];
      $_SESSION['role'] = $row['roleName'];
      $count += 1;
    }

    if ($count === 1) {
      $_SESSION['loggedIn'] = true;
      print(1);
    } else {
      print('No such user found. Please try again.');
    }
  }
?>
