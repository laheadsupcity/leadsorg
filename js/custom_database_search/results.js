function navigateToPage(page) {
  var url = new URL(window.location.href);
  url.searchParams.set('page', page);

  setupPagination(page);

  fetchProperties(url.search);
}

function handlePagination(event) {
  event.preventDefault();

  var target = $(event.currentTarget),
      is_previous = target.data('previous-page'),
      is_next = target.data('next-page'),
      current_page = target.data('current-page'),
      page = target.data('page');

  if (target.data().hasOwnProperty('previousPage')) {
    page = current_page - 1;
  } else if (target.data().hasOwnProperty('nextPage')) {
    page = current_page + 1;
  }

  navigateToPage(page);
}

function handlePageSizeChange(event) {
  var page_size = $(event.currentTarget).val();
  var url = new URL(window.location.href);

  url.searchParams.set('num_rec_per_page', page_size);
  url.searchParams.set('page', 1);

  fetchProperties(url.search);
}

function resizePropertyList() {
  var window_height = $(window).height();

  $('.property-list').height(window_height - 350);
}

function fetchProperties(search_parameters, properties_only = true) {
  let searchParams = new URLSearchParams(search_parameters),
      entries = Object.fromEntries(searchParams.entries());

  entries.user_id = $('[data-user-id]').data('user-id');

  entries.properties_only = properties_only;

  $('.main-content').width($(window).width());
  if (properties_only) {
    $('.property-list').html($('[data-loading]').html());
  } else {
    $('[data-loading]').addClass('d-flex').removeClass('d-none');
    $('[data-results-and-actions]').addClass('d-none');
  }

  $.get(
    'fetch_properties_results.php',
    entries,
    function(data) {
      data = JSON.parse(data);

      if (properties_only) {
        $('.property-list').replaceWith(data.properties_list_markup);
      } else {
        $('[data-properties-list]').html(data.properties_list_markup);
        $('[data-loading]').removeClass('d-flex').addClass('d-none');
        $('[data-results-and-actions]').removeClass('d-none');
      }

      resizePropertyList();
      $('.main-content').width($('.properties-scroll').width() + 13);

      var id = $('.property-list-group').data('id');

      if (!properties_only) {
        setupSortableColumns(id);
      }

      setupEditableContactInfoFields();

      setupEditableNotes();

      $('[data-total-records]').html(data.total_records);

      $('.pagination a').click(function(event) {
        handlePagination(event);
      });
    }
  );
}

function setupPagination(current_page) {
  $('.page-item').removeClass('active');
  $('.page-' + current_page).addClass('active');
}

$(document).ready(function() {

  fetchProperties(window.location.search, false);

  $('#num_rec_per_page').change(function(event) {
    handlePageSizeChange(event);
  });

  $(window).resize(function(event) {
    resizePropertyList();
  });

});
