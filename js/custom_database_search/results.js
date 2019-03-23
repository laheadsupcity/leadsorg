function indicateSortByField(indicatorPrefix) {
  if (false) {
    $('.sort-by-indicator').removeClass('sort-by-field');
    $('.' + indicatorPrefix + '-sort-by-indicator').addClass('sort-by-field');
  }
}

function sortProperties(dataAttributeToSortBy, isAscending, isDate = false) {
  $('.property-item').sort(function(propertyA, propertyB) {
    var sortPropA = $(propertyA).data(dataAttributeToSortBy);
    var sortPropB = $(propertyB).data(dataAttributeToSortBy);

    var sortPropAInt = parseInt(sortPropA, 10);
    var sortPropBInt = parseInt(sortPropB, 10);

    if (isDate) {
      return isAscending ? new Date(sortPropA) - new Date(sortPropB) :  new Date(sortPropB) - new Date(sortPropA);
    } else if (!isNaN(sortPropAInt) && !isNaN(sortPropBInt)) {
      sortPropA = sortPropAInt;
      sortPropB = sortPropBInt;
    }

    return (sortPropA > sortPropB) ? (isAscending ? 1 : -1) : (sortPropA < sortPropB) ? (isAscending ? -1 : 1) : 0;
  }).appendTo('.property-list');
}

function handleSortOrderChange(sort_order) {
  switch (sort_order) {
    case "parcel_number":
      sortProperties('parcel-number', true);
      indicateSortByField('parcel-number');
      break;
    case "num_units_asc":
      sortProperties('num-units', true);
      indicateSortByField('num-units');
      break;
    case "num_units_desc":
      sortProperties('num-units', false);
      indicateSortByField('num-units');
      break;
    case "building_area_asc":
      sortProperties('building-area', true);
      indicateSortByField('building-area');
      break;
    case "building_area_desc":
      sortProperties('building-area', false);
      indicateSortByField('building-area');
      break;
    case "lot_area_sqft_asc":
      sortProperties('lot-area-sqft', true);
      indicateSortByField('lot-area-sqft');
      break;
    case "lot_area_sqft_desc":
      sortProperties('lot-area-sqft', false);
      indicateSortByField('lot-area-sqft');
      break;
    case "year_built_asc":
      sortProperties('year-built', true);
      indicateSortByField('year-built');
      break;
    case "year_built_desc":
      sortProperties('year-built', false);
      indicateSortByField('year-built');
      break;
    case "sale_date_asc":
      sortProperties('sale-date', true, true);
      indicateSortByField('sale-date');
      break;
    case "sale_date_desc":
      sortProperties('sale-date', false, true);
      indicateSortByField('sale-date');
      break;
  }
}

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

$(document).ready(function() {

  handleSortOrderChange($('#sort_order').val());

  $('.results-pagination a').click(function(event) {
    handlePagination(event);
  })

  $('#num_rec_per_page').change(function(event) {
    handlePageSizeChange(event);
  })

  $('#sort_order').change(function(event) {
    handleSortOrderChange($(event.target).val());
  });

});
