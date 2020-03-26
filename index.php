<?php
  require_once("password.php");

  $sql = new mysqli($host, $user, $password, $database);

  if ($sql_.connect_error != "") {
    print $sql->connect_error;
    print "Error Connecting To Database: Did you install the 'password.php' file?"
    die;
  }

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
</head>
<body style="font-family:Alegreya;background-color:#1e272e;">
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  <div class="navbar-header">
    <a class="navbar-brand" href="#">THE REEL WORLD</a>
  </div>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">My Favorite Movies</a>
      </li>

    </ul>
    <form class="form-inline my-2 my-lg-0">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" style="background-color:#c0c3c5;color:#053560;">GO</button>
        </span>
      </div>

      <button class="btn btn-danger" type="button" style="margin:5px;margin-left:10px;">SORT</button>
      <button class="btn btn-danger" type="button" style="margin:5px; ">FILTER</button>
      <button class="btn btn-warning" type="button" style="margin:5px; ">Add A Movie</button>
    </form>
  </div>
</nav>


<div class="container">
  <div class="row">
    <div class="card col-md" style="margin:5px;padding:0px;border-color:#000000;">
      <img src="https://lh3.googleusercontent.com/-8aFq42ot-4ZlaHU6Mynfr8gprsMGeoePcW_1PuaIbFRdj2IKDAPvTA9Lg9Xx2f_Bpj5cRA=s113" width="190px" height="150px" class="card-img-top" alt="Iron Man">
      <div class="card-body">
        <h5 class="card-title">Iron Man</h5>
        <p class="card-text">Genre: <br>Briefing:  <br>Actors:  <br>Rating: _/10</p>
        <a href="#" class="btn btn-primary">IMDB</a>
      </div>
    </div>
    <div class="card col-md" style="margin: 5px;padding:0px;border-color:#000000;">
      <img src="https://lh3.googleusercontent.com/s25pdxsjC8xQeijNJBIktU8ijdm-alISoncJ96K1WYBNkjcXBZFdNdrBRs4Dzonsy-EsxQ=s85" width="150px" height="150px" class="card-img-top" alt="Iron Man">
      <div class="card-body">
        <h5 class="card-title">Inception</h5>
        <p class="card-text">Genre: <br>Briefing:  <br>Actors:  <br>Rating: _/10</p>
        <a href="#" class="btn btn-primary">IMDB</a>
      </div>
    </div>
    <div class="card col-md" style="margin: 5px;padding:0px;border-color:#000000;">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQHwWe3wC0dtk000Qs61X49aosqqaS3YK2HZT629Tej4WzsM8K1" width="150px" height="150px" class="card-img-top" alt="Iron Man">
      <div class="card-body">
        <h5 class="card-title">Pride & Prejudice</h5>
        <p class="card-text">Genre: <br>Briefing:  <br>Actors:  <br>Rating: _/10</p>
        <a href="#" class="btn btn-primary">IMDB</a>
      </div>
    </div>
    <div class="card col-md" style="margin: 5px;padding:0px;border-color:#000000;">
      <img src="https://lh3.googleusercontent.com/D83gtq-Esgz7oL6JHVrKXvdZ-bU4-b7PxBDU0pT5BSC_L-pJ0VPOsA3uPPoTsQ7axsFQgg=s106" width="150px" height="150px" class="card-img-top" alt="Iron Man">
      <div class="card-body">
        <h5 class="card-title">Avengers</h5>
        <p class="card-text">Genre: <br>Briefing:  <br>Actors:  <br>Rating: _/10</p>
        <a href="#" class="btn btn-primary">IMDB</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
