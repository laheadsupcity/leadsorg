const SORT_TYPE_ASC = "ASC";
const SORT_TYPE_DESC = "DESC";
const SORT_TYPE_NEITHER = "NEITHER";

const SORT_COLUMN_UNITS = "num_units";
const SORT_COLUMN_OWNER_NAME = "owner_name";
const SORT_COLUMN_BEDS = "num_beds";
const SORT_COLUMN_BUILDING_AREA = "building_area";
const SORT_COLUMN_LOT_AREA_SQFT = "lot_area_sqft";
const SORT_COLUMN_YEAR_BUILT = "year_built";
const SORT_COLUMN_SALE_DATE = "sale_date";
const SORT_COLUMN_RELATED_PROPERTIES = "related_properties";
const SORT_COLUMN_FAVORITES_FOLDERS = "favorites_folders";

const sortSettings = {};

function getOrderedSortSettings() {
  let sorted = Object.values(sortSettings).sort(function(sortColumnA, sortColumnB) {
    return sortColumnA.order < sortColumnB.order ? -1 : 1;
  });

  return sorted.map(function(column) {
    return column.column;
  });
}

function initDefaultSortSettings() {
  let order = 0;
  changeSortSetting(SORT_COLUMN_UNITS, SORT_TYPE_DESC, order++);
  changeSortSetting(SORT_COLUMN_BUILDING_AREA, SORT_TYPE_DESC, order++);
  changeSortSetting(SORT_COLUMN_LOT_AREA_SQFT, SORT_TYPE_DESC, order++);
  changeSortSetting(SORT_COLUMN_YEAR_BUILT, SORT_TYPE_DESC, order++);
  changeSortSetting(SORT_COLUMN_SALE_DATE, SORT_TYPE_DESC, order++);
  changeSortSetting(SORT_COLUMN_RELATED_PROPERTIES, SORT_TYPE_DESC, order++);
  changeSortSetting(SORT_COLUMN_FAVORITES_FOLDERS, SORT_TYPE_ASC, order++);
  changeSortSetting(SORT_COLUMN_BEDS, SORT_TYPE_DESC, order++);
  changeSortSetting(SORT_COLUMN_OWNER_NAME, SORT_TYPE_ASC, order++);
}

function changeSortSetting(column, direction, order) {
  sortSettings[column] = {
    column: column,
    direction: direction,
    order: order
  };
}

function handleSortToggle(event, column) {
  var target = $(event.currentTarget);

  toggleSortDirection(target, column);
}

function toggleSortDirection(column_header, column, direction = null, reorder_sort_columns = true) {
  if (direction) {
    switch (direction) {
      case SORT_TYPE_ASC:
        column_header.removeClass('sortable-column-desc').addClass('sortable-column-asc');
        break;
      case SORT_TYPE_DESC:
        column_header.removeClass('sortable-column-asc').addClass('sortable-column-desc');
        break;
    }
  } else if (column_header.hasClass('sortable-column-asc')) {
    direction = SORT_TYPE_NEITHER;
    column_header.removeClass('sortable-column-asc');
  } else if (column_header.hasClass('sortable-column-desc')) {
    direction = SORT_TYPE_ASC;
    column_header.removeClass('sortable-column-desc').addClass('sortable-column-asc');
  } else {
    direction = SORT_TYPE_DESC;
    column_header.removeClass('sortable-column-asc').addClass('sortable-column-desc');
  }

  // change direction
  sortSettings[column].direction = direction;

  if (reorder_sort_columns && direction != SORT_TYPE_NEITHER) {
    // make this sort filter the most important
    let previous_order = sortSettings[column].order;
    Object.keys(sortSettings).forEach(function(setting) {
      if (sortSettings[setting].column == column) {
        sortSettings[column].order = 0;
      } else if (sortSettings[setting].order < previous_order) {
        sortSettings[setting].order = sortSettings[setting].order + 1;
      }
    });
  }

  sortProperties();
}

function setupSortableColumns() {

  initDefaultSortSettings();

  getOrderedSortSettings().forEach(function(column) {
    var direction = sortSettings[column].direction;
    var column_header = $('[data-sortable-column="' + column + '"]');

    column_header.addClass('sortable-column');

    column_header.append(
      "<span class=\"sorting-arrows\"><i class=\"fas fa-sort-up sorting-arrows-asc\"></i><i class=\"fas fa-sort-down sorting-arrows-desc\"></i></span>"
    );

    toggleSortDirection(column_header, column, direction, false);

    column_header.click(function(event) {
      handleSortToggle(event, column);
    });
  });
}

function sortProperties() {
  $('.property-item').sort(function(propertyA, propertyB) {
    let ordered_sort_settings = getOrderedSortSettings();

    for (var index in ordered_sort_settings) {
      var setting = sortSettings[ordered_sort_settings[index]];

      let sortPropA = $(propertyA).data(setting.column);
      let sortPropB = $(propertyB).data(setting.column);
      let sortDirection = setting.direction;

      if (sortDirection == SORT_TYPE_NEITHER) {
        continue;
      }

      switch (setting.column) {
        case SORT_COLUMN_UNITS:
        case SORT_COLUMN_BEDS:
        case SORT_COLUMN_BUILDING_AREA:
        case SORT_COLUMN_LOT_AREA_SQFT:
        case SORT_COLUMN_YEAR_BUILT:
        case SORT_COLUMN_RELATED_PROPERTIES:
          sortPropA = parseInt(sortPropA, 10);
          sortPropB = parseInt(sortPropB, 10);

          if (sortPropA == sortPropB) {
            continue;
          } else if (sortPropA > sortPropB) {
            return sortDirection == SORT_TYPE_ASC ? 1 : -1;
          } else if (sortPropA < sortPropB) {
            return sortDirection == SORT_TYPE_ASC ? -1 : 1;
          }
          break;
        case SORT_COLUMN_OWNER_NAME:
          sortPropA = sortPropA.trim().toLowerCase();
          sortPropB = sortPropB.trim().toLowerCase();

          if (sortPropA == sortPropB) {
            continue;
          } else if (sortPropA > sortPropB) {
            return sortDirection == SORT_TYPE_ASC ? 1 : -1;
          } else if (sortPropA < sortPropB) {
            return sortDirection == SORT_TYPE_ASC ? -1 : 1;
          }
          break;
        case SORT_COLUMN_SALE_DATE:
          sortPropA = new Date(sortPropA);
          sortPropB = new Date(sortPropB);

          if (sortPropA == sortPropB) {
            continue;
          } else {
            return sortDirection == SORT_TYPE_ASC ? sortPropA - sortPropB : sortPropB - sortPropA;
          }
          break;
        case SORT_COLUMN_FAVORITES_FOLDERS:
          sortPropA = sortPropA == "" ? [] : sortPropA.split(',').sort();
          sortPropB = sortPropB == "" ? [] : sortPropB.split(',').sort();

          if (sortPropA.length == 1 && sortPropB.length == 1) {
            sortPropA = sortPropA[0];
            sortPropB = sortPropB[0];

            if (sortPropA > sortPropB) {
              return sortDirection == SORT_TYPE_ASC ? 1 : -1;
            } else if (sortPropA < sortPropB) {
              return sortDirection == SORT_TYPE_ASC ? -1 : 1;
            } else {
              continue;
            }
          } else if (sortPropA == sortPropB) {
            continue;
          } else if (sortPropA.length == 0) {
            return 1;
          } else if (sortPropB.length == 0) {
            return -1;
          } else if (sortPropA.length > sortPropB.length) {
            return sortDirection == SORT_TYPE_ASC ? 1 : -1;
          } else {
            return sortDirection == SORT_TYPE_ASC ? -1 : 1;
          }
          break;
      }
    }

    return 0;
  }).appendTo('.properties-scroll');
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

function resizePropertyList() {
  var window_height = $(window).height();

  $('.property-list').height(window_height - 350);
}

$(document).ready(function() {

  setupSortableColumns();

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
