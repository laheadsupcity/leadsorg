function navigateToPage(page) {
  var url = new URL(window.location.href);
  url.searchParams.set('page', page);

  window.location.href = url.toString();
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

  window.location.href = url.toString();
}

function resizePropertyList() {
  var window_height = $(window).height();

  $('.property-list').height(window_height - 350);
}

$(document).ready(function() {

  var id = $('.property-list-group').data('id');

  setupSortableColumns(id);

  $('.pagination a').click(function(event) {
    handlePagination(event);
  });

  $('#num_rec_per_page').change(function(event) {
    handlePageSizeChange(event);
  });

  resizePropertyList();

  $(window).resize(function(event) {
    resizePropertyList();
  });

  $('.main-content').width($('.properties-scroll').width() + 13);

});
