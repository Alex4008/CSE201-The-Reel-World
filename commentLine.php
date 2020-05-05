<?php
	require("db.php");
	session_start();
  $movieManager = new MovieManager($mysqli);
	$commentManager = new CommentManager($mysqli);
?>

<?php
  $movieId = $_SESSION['singleMovieId'];
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
