<?php
  require 'db.php';
  $requestManager = new RequestManager($mysqli);
  session_start();
?>

<?php
  if (isset($_SESSION['loggedIn']) && isset($_POST['data'])) {
    $requestManager -> saveRequest($_SESSION['userId'], "Request made by ".$_SESSION['userName'], $_POST['data']);
    echo('"data is received"');
  }
?>
