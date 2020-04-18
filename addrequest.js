$(function(){
  var maxGenreLine = 10;
  var countGenreLine = 1;
  var maxActorLine = 20;
  var countActorLine = 1;

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

  $('#submitButton').on('click', function(e) {

    if ($('#requestForm')[0].checkValidity()) {
      e.preventDefault();
      var data = {
        movieTitle: $('#movieTitle').val(),
        movieDescription: $('#movieDescription').val(),
        genres: [],
        newGenreDescription: [],
        genreToDisplay: [],
        actors: [],
        newActors: [],
        actorToDisplay: [],

        imdbLink: $('#imdbLink').val(),
        imageLink: $('#imageLink').val()
      }

      $('#movieGenres option:selected').each(function() {
        data.genreToDisplay.push($(this).text());
        data.genres.push($(this).val());
      });

      $('.newGenreDescription').each( function() {
        if ($(this).val() !== "") {
          data.newGenreDescription.push($(this).val());
          data.genreToDisplay.push($(this).val());
        }
      });

      $('#movieActors option:selected').each(function() {
        data.actorToDisplay.push($(this).text());
        data.actors.push($(this).val());
      });

      $('.lineActor').each( function() {
        if ($(this).find('.newActorName').val() !== "") {
          let actor = {
            actorName:  $(this).find('.newActorName').val(),
            actorLink: $(this).find('.newActorLink').val()
          }
          data.newActors.push(actor);
          data.actorToDisplay.push($(this).find('.newActorName').val());
        }
      });
      // console.log(JSON.stringify(data));
      $.ajax({
        url: '/saveRequest.php',
        type: 'POST',
        data: {
          data: 'this is sample data'
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
