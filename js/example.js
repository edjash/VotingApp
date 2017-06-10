jQuery(document).ready(function() {

  //Initialize registration form and setup the submit handler.
  $('#register_form').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {
      return false;
    }
    e.preventDefault();
    $('#register_form').validator('destroy');
    show_loading('#register');

    $.ajax({
      url: $(this).attr('action'),
      type: $(this).attr('method'),
      dataType: 'json',
      data: $(this).serialize(),
      success: function(data) {
        hide_loading('#register');

        if (!data.success) {
          for (field in data.errors) {
            var error = data.errors[field];
            var html = '<ul class="list-unstyled"><li>' + error + '</li></ul>';
            $('input[name="' + field + '"]').next().html(html);
            $('input[name="' + field + '"]').parent().addClass('has-error has-danger');
          }
        } else {
          $('.signin-btn, .register-btn').hide();
          location.reload();
        }
      }
    });
    return false;
  });

  //Initialise login form and submit handler
  $('#login_form').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {
      return false;
    }
    e.preventDefault();
    $('#login_form').validator('destroy');
    show_loading('#login');

    $.ajax({
      url: $(this).attr('action'),
      type: $(this).attr('method'),
      dataType: 'json',
      data: $(this).serialize(),
      success: function(data) {
        hide_loading('#login');
        if (!data.user.vote) {
          hide_loading('#login');
          $('#login').modal('hide');
          $('#vote').modal('show');
        } else {
          location.reload();
        }
      },
      error: function(data) {
        hide_loading('#login');
        $('#login_form .error').show();

      }
    });
    return false;
  });
  $('#login_email').focus(function() {
    $('#login_form .error').hide();
  })
  $('#login_password').focus(function() {
    $('#login_form .error').hide();
  })

  //Initialise voting form and submit handler
  $('#vote_form').validator().on('submit', function(e) {
    if (e.isDefaultPrevented()) {
      return false;
    }
    e.preventDefault();
    $('#vote_form').validator('destroy');
    show_loading('#vote');

    $.ajax({
      url: $(this).attr('action'),
      type: $(this).attr('method'),
      dataType: 'json',
      data: $(this).serialize(),
      success: function(data) {
        location.reload();

      }
    });
    return false;
  });

  //prepare the special combos for constituencies and candidates.
  var list = $("#constituencies");
  $.each(constituencies, function(index, item) {
    list.append(new Option(item.name, index));
  });
  list.selectpicker('val', '');

  //Populate the candidates list when the constituencies list changes.
  list.change(function() {
    var id = list.val();
    var candidates = constituencies[id].candidates;
    var clist = $('#candidates');
    clist.html('');
    clist.append(new Option('None (I Abstain)', 'abstain'));
    $.each(candidates, function(index, item) {
      clist.append(new Option(item.name + ' (' + item.party + ')', index));
    });
    clist.selectpicker('refresh');
    clist.selectpicker('val', 'abstain');
  });

  //If the user is logged in but has not voted yet, show the vote modal.
  if ($('.vote-btn').length) {
    $('#vote').modal('show');
  }
});

/**
 * Display an animated loading image on element.
 */
function show_loading(element) {
  $(element + ' .modal-body').css('opacity', '0.5');
  $(element + ' .modal-content').prepend('<span class="loading glyphicon glyphicon-refresh gly-ani" style="font-size:40px;"></span>');

}

/**
 * Remove animated loading image from element.
 */
function hide_loading(element) {
  $(element + ' .modal-body').css('opacity', '1');
  $(element + ' .modal-content .loading').remove();
}
