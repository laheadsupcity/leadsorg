var is_initial_load = true;

var current_page = null;
var current_page_size = null;
var cases_results = null;
var start_time = null;
var end_time = null;

function initPage() {
  current_page = 1;
}

function initPageSize() {
  current_page_size = 10;
}

function startTimer() {
  start_time = new Date();
}

function endTimer() {
  end_time = new Date();

  console.log(Math.round((end_time - start_time) / 1000) + " seconds elapsed");
}

var start_time = null;
var end_time = null;

function startTimer() {
  start_time = new Date();
}

function endTimer() {
  end_time = new Date();

  console.log(Math.round((end_time - start_time) / 1000) + " seconds elapsed");
}

function navigateToPage(page) {
  current_page = page;

  $('.page-item').removeClass('active');
  $('.page-' + page).addClass('active');

  fetchProperties(window.location.search);
}

function handlePageSizeChange(event) {
  var page_size = $(event.currentTarget).val();
  current_page_size = page_size;

  // when page size changes we must go back to first page
  initPage();

  fetchProperties(window.location.search);
}

function handlePagination(event) {
  var target = $(event.currentTarget),
      is_previous = target.data('previous-page'),
      is_next = target.data('next-page'),
      next_page = target.data('next-page'),
      page = target.data('page');

  if (target.data().hasOwnProperty('previousPage')) {
    page = next_page - 1;
  } else if (target.data().hasOwnProperty('nextPage')) {
    page = next_page + 1;
  }

  navigateToPage(page);
}

function resizePropertyList() {
  var window_height = $(window).height();

  $('.property-list').height(window_height - 350);
}

function fetchProperties(search_parameters) {
  startTimer();

  let original_scrollX = window.scrollX;
  let entries;

  if (cases_results == null) {
    let searchParams = new URLSearchParams(search_parameters);
    entries = Object.fromEntries(searchParams.entries());
  } else {
    entries = {
      case_pcids_to_search: Object.values(cases_results).join(",")
    };
  }

  entries.page = current_page;

  entries.page_size = current_page_size;

  entries.user_id = $('[data-user-id]').data('user-id');

  entries.sortSettings = $.param(sortSettings.custom_database_search_results);

  if (is_initial_load) {
    $('[data-loading]').addClass('d-flex').removeClass('d-none');
    $('[data-results-and-actions]').addClass('d-none');
  } else {
    $('[data-properties-list]').html("");
  }

  $.post(
    'fetch_properties_results.php',
    entries,
    function(data) {
      data = JSON.parse(data);

      cases_results = data.cases_results;

      $('[data-properties-list]').html(data.properties_list_markup);
      $('.pagination').replaceWith(data.pagination_markup);

      if (is_initial_load) {
        $('[data-loading]').removeClass('d-flex').addClass('d-none');
        $('[data-results-and-actions]').removeClass('d-none');
      }

      resizePropertyList();
      if (is_initial_load) {
        $('.main-content').width($('.properties-scroll').width() + 13);
      }

      var results_id = $('.property-list-group').data('results-id');

      if (data.total_records == 0) {
        // no op
      } else if (is_initial_load) {
        setupSortableColumns(results_id);
      }

      setupEditableFields();

      $('[data-total-records]').html(data.total_records);

      window.scrollTo(original_scrollX, 0);

      is_initial_load = false;
      endTimer();
    }
  );
}

$(document).ready(function() {

  initDefaultSortSettings('custom_database_search_results');

  initPage();

  initPageSize();

  fetchProperties(window.location.search, false);

  $(document).on('click', '.pagination a', function(event) {
    handlePagination(event);
  });

  $('#page_size').change(function(event) {
    handlePageSizeChange(event);
  });

  $(window).resize(function(event) {
    resizePropertyList();
  });

});
