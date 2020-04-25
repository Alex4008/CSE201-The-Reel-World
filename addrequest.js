$(function(){
  var maxGenreLine = 10;
  var countGenreLine = 1;
  var maxActorLine = 20;
  var countActorLine = 1;

  // Responsive features of the form
  $("select").bsMultiSelect();
  $('#addGenreField').hide();
  $('#addActorField').hide();

  $('#addNewGenre').on('click', function() {
    $('#addGenreField').toggle();

    if ($('#addGenreField').is(':hidden')) {
      $('.newGenreDescription').prop('required',false);
    } else {
      $('.newGenreDescription').prop('required',true);
    }

    if ($(this).html() !== 'Cancel') {
      $(this).html('Cancel');
      $(this).removeClass('btn-danger');
      $(this).addClass('btn-secondary');
    } else {
      $(this).html('Add new genres');
      $(this).removeClass('btn-secondary');
      $(this).addClass('btn-danger');
    }
  });

  $('#addLineGenre').on('click', function() {
    if (countGenreLine < maxGenreLine) {
      countGenreLine++;
      $('#addGenreField').append('<div class="row lineGenre"><div class="form-group col-lg-11"><input type="text" name="newGenreDescription[]" class="form-control newGenreDescription" placeholder="New genre\'s name" /></div><div class="form-group col-lg-1"><button type="button" class="btn btn-dark removeLineGenre"><i class="fa fa-minus"></i></button></div></div>');
    }
    if ($('#addGenreField').is(':hidden')) {
      $('.newGenreDescription').prop('required',false);
    } else {
      $('.newGenreDescription').prop('required',true);
    }
  });

  $('#addGenreField').on('click', '.removeLineGenre', function() {
    countGenreLine--;
    $(this).closest('.lineGenre').remove();
  });

  $('#addNewActor').on('click', function() {
    $('#addActorField').toggle();
    if ($('#addActorField').is(':hidden')) {
      $('.newActorName').prop('required',false);
      $('.newActorLink').prop('required',false);

    } else {
      $('.newActorName').prop('required',true);
      $('.newActorLink').prop('required',true);
    }
    if ($(this).html() !== 'Cancel') {
      $(this).html('Cancel');
      $(this).removeClass('btn-danger');
      $(this).addClass('btn-secondary');
    } else {
      $(this).html('Add new actors');
      $(this).removeClass('btn-secondary');
      $(this).addClass('btn-danger');
    }
  });

  $('#addLineActor').on('click', function() {
    if (countActorLine < maxActorLine) {
      countActorLine++;
      $('#addActorField').append('<div class="row lineActor"><div class="form-group col-lg-5"><input type="text" name="newActorName[]" class="form-control newActorName" placeholder="New actor\'s name" /></div><div class="col-lg-6"><input type="text" name="newActorLink[]" class="form-control newActorLink" placeholder="New actor\'s link" /></div><div class="form-group col-lg-1"><button type="button" class="btn btn-dark removeLineActor"><i class="fa fa-minus"></i></button></div></div>');
    }
    if ($('#addActorField').is(':hidden')) {
      $('.newActorName').prop('required',false);
      $('.newActorLink').prop('required',false);

    } else {
      $('.newActorName').prop('required',true);
      $('.newActorLink').prop('required',true);
    }
  });

  $('#addActorField').on('click', '.removeLineActor', function() {
    countActorLine--;
    $(this).closest('.lineActor').remove();
  });

  // When the request form is submitted
  $('#submitButton').on('click', function(e) {
    // Checks if the form is valid and collect form fields
    if ($('#requestForm')[0].checkValidity()) {
      e.preventDefault();
      var data = {
        movieTitle: sanitize($('#movieTitle').val()),
        movieDescription: sanitize($('#movieDescription').val()),
        genres: [],
        newGenreDescription: [],
        genreToDisplay: [],
        movieRating: sanitize($('#movieRating').val()),
        actors: [],
        newActors: [],
        actorToDisplay: [],
        imdbLink: $('#imdbLink').val(),
        imageLink: $('#imageLink').val()
      }

      $('#movieGenres option:selected').each(function() {
        data.genreToDisplay.push(sanitize($(this).text()));
        data.genres.push($(this).val());
      });

      $('.newGenreDescription').each( function() {
        if ($(this).val() !== "") {
          let gVal = sanitize($(this).val());
          data.newGenreDescription.push(gVal);
          data.genreToDisplay.push(gVal);
        }
      });

      $('#movieActors option:selected').each(function() {
        data.actorToDisplay.push(sanitize($(this).text()));
        data.actors.push($(this).val());
      });

      $('.lineActor').each( function() {
        if ($(this).find('.newActorName').val() !== "") {
          let actor = {
            actorName:  sanitize($(this).find('.newActorName').val()),
            actorLink: sanitize($(this).find('.newActorLink').val())
          }
          data.newActors.push(actor);
          data.actorToDisplay.push(sanitize($(this).find('.newActorName').val()));
        }
      });
      // Sends a POST request to the DB and process the request
      $.ajax({
        url: './processData.php',
        type: 'POST',
        data: {
          data: JSON.stringify(data)
        },
        success: function(data) {
          console.log(data);
          $('#status').css({"padding":"10px","margin":"20px", "color": "#11702d"});
          $('#status').html('Your request was successfully submitted');
          $('#requestForm')[0].reset();
        }
      });
    }
  });
});
