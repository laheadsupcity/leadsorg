let results_id;

function getParcelNumber() {
  var url = new URL(window.location.href);

  return url.searchParams.get('related_apns_for_parcel_number');
}

function resizePropertyList() {
  var window_height = $(window).height();

  $('.property-list').height(window_height - 350);
}

function fetchProperties() {

  var params = {
    user_id: getUserID(),
    related_apns_for_parcel_number: getParcelNumber()
  };

  $.post(
    'fetch_properties_results.php',
    params,
    function(data) {
      data = JSON.parse(data);

      $('[data-total-records]').html(data.total_records);
      $('[data-properties-list=related_properties]').html(data.properties_list_markup);
      $('[data-results-and-actions]').prop('hidden', false)
      $('.main-content').width($('.properties-scroll').width() + 13);
      setupEditableFields();
      resizePropertyList();
    }
  );
}

$(document).ready(function() {
  results_id = $('[data-results-id]').data('results-id');

  setupSortableColumns(results_id);

  fetchProperties();
});
