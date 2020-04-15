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
      $('#addGenreField').append('<div class="row lineGenre"><div class="form-group col-lg-11"><input type="text" name="newGenreDescription[]" class="form-control" placeholder="New genre\'s name" /></div><div class="form-group col-lg-1"><button type="button" class="btn btn-dark removeLineGenre"><i class="fa fa-minus"></i></button></div></div>');
    }
  });

  $('#addGenreField').on('click', '.removeLineGenre', function() {
    countGenreLine--;
    $(this).closest('.lineGenre').remove();
  });

  $('#addNewActor').on('click', function() {
    $('#addActorField').toggle();
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
      $('#addActorField').append('<div class="row lineActor"><div class="form-group col-lg-5"><input type="text" name="newActorName[]" class="form-control" placeholder="New actor\'s name" /></div><div class="col-lg-6"><input type="text" name="newActorLink[]" class="form-control" placeholder="New actor\'s link" /></div><div class="form-group col-lg-1"><button type="button" class="btn btn-dark removeLineActor"><i class="fa fa-minus"></i></button></div></div>');
    }
  });

  $('#addActorField').on('click', '.removeLineActor', function() {
    countActorLine--;
    $(this).closest('.lineActor').remove();
  });


});
