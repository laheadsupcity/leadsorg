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

const defaultSortSettings = [
  {
    column: SORT_COLUMN_UNITS,
    direction: SORT_TYPE_DESC,
    order: 0
  },
  {
    column: SORT_COLUMN_BUILDING_AREA,
    direction: SORT_TYPE_DESC,
    order: 1
  },
  {
    column: SORT_COLUMN_LOT_AREA_SQFT,
    direction: SORT_TYPE_NEITHER,
    order: 2
  },
  {
    column: SORT_COLUMN_YEAR_BUILT,
    direction: SORT_TYPE_NEITHER,
    order: 3
  },
  {
    column: SORT_COLUMN_SALE_DATE,
    direction: SORT_TYPE_NEITHER,
    order: 4
  },
  {
    column: SORT_COLUMN_RELATED_PROPERTIES,
    direction: SORT_TYPE_NEITHER,
    order: 5
  },
  {
    column: SORT_COLUMN_FAVORITES_FOLDERS,
    direction: SORT_TYPE_NEITHER,
    order: 6
  },
  {
    column: SORT_COLUMN_BEDS,
    direction: SORT_TYPE_NEITHER,
    order: 7
  },
  {
    column: SORT_COLUMN_OWNER_NAME,
    direction: SORT_TYPE_NEITHER,
    order: 8
  }
];

const sortSettings = {};


function getAllSortSettings() {
  return defaultSortSettings.map(function(setting) {return setting.column;});
}
function initDefaultSortSettings(id) {
  sortSettings[id] = {};

  defaultSortSettings.forEach(function(setting) {
    changeSortSetting(
      id,
      setting.column,
      setting.direction,
      setting.order
    );
  });
}

function changeSortSetting(id, column, direction, order) {
  // these are abbreviated because get requests have a limit on url length
  sortSettings[id][column] = {
    col: column,
    dir: direction,
    ord: order
  };
}

function handleSortToggle(id, event, column) {
  var target = $(event.currentTarget);

  toggleSortDirection(id, target, column, null, true, true);
}

function toggleSortDirection(
  id,
  column_header,
  column,
  direction = null,
  reorder_sort_columns = true,
  trigger_reload = false
) {
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
  sortSettings[id][column].dir = direction;

  if (reorder_sort_columns && direction != SORT_TYPE_NEITHER) {
    // make this sort filter the most important
    let previous_order = sortSettings[id][column].ord;
    Object.keys(sortSettings[id]).forEach(function(setting) {
      if (sortSettings[id][setting].col == column) {
        sortSettings[id][column].ord = 0;
      } else if (sortSettings[id][setting].ord < previous_order) {
        sortSettings[id][setting].ord = sortSettings[id][setting].ord + 1;
      }
    });
  }

  if (trigger_reload) {
    fetchProperties(window.location.search);
  }
}

function setupSortableColumns(id) {

  getAllSortSettings().forEach(function(column) {
    var direction = sortSettings[id][column].dir;
    var column_header = $('[data-id=' + id + "]").find('[data-sortable-column="' + column + '"]');

    column_header.addClass('sortable-column');

    column_header.append(
      "<span class=\"sorting-arrows\"><i class=\"fas fa-sort-up sorting-arrows-asc\"></i><i class=\"fas fa-sort-down sorting-arrows-desc\"></i></span>"
    );

    toggleSortDirection(id, column_header, column, direction, false);

    column_header.click(function(event) {
      handleSortToggle(id, event, column);
    });
  });
}
