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
?>
