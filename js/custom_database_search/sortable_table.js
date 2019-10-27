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

const ARROW_MARKUP = "<span class=\"sorting-arrows\"><i class=\"fas fa-sort-up sorting-arrows-asc\"></i><i class=\"fas fa-sort-down sorting-arrows-desc\"></i></span>";

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

function toggleSortDirection(
  results_id,
  results_callback,
  column_header,
  column_name
) {
  switch(sortSettings[results_id][column_name].direction) {
    case SORT_TYPE_ASC:
      new_direction = SORT_TYPE_NEITHER;
      break;
    case SORT_TYPE_DESC:
      new_direction = SORT_TYPE_ASC;
      break;
    case SORT_TYPE_NEITHER:
      new_direction = SORT_TYPE_DESC;
      break;
  }

  setupDirectionArrows(column_header, new_direction);

  sortSettings[results_id][column_name].direction = new_direction;

  if (new_direction != SORT_TYPE_NEITHER) {
    // make this sort filter the most important
    let previous_order = sortSettings[results_id][column_name].order;
    Object.keys(sortSettings[results_id]).forEach(function(setting) {
      if (sortSettings[results_id][setting].column == column_name) {
        sortSettings[results_id][column_name].order = 0;
      } else if (sortSettings[results_id][setting].order < previous_order) {
        sortSettings[results_id][setting].order = sortSettings[results_id][setting].order + 1;
      }
    });
  }

  results_callback();
}

function setupSortableColumns(results_id, results_callback) {
  sortSettings[results_id] = {};

  defaultSortSettings.forEach(function(setting) {
    sortSettings[results_id][setting.column] = {
      column: setting.column,
      direction: setting.direction,
      order: setting.order
    };
  });

  getAllSortSettings().forEach(function(column_name) {
    var column_header = getColumnHeaderForColumnName(results_id, column_name);

    column_header
      .addClass('sortable-column')
      .append(ARROW_MARKUP)
      .on('click', function(event) {
        handleSortToggle(results_id, results_callback, event, column_name);
      });

    setupDirectionArrows(column_header, sortSettings[results_id][column_name].direction);
  });
}

/// HANDLERS

function handleSortToggle(results_id, results_callback, event, column) {
  var target = $(event.currentTarget);

  toggleSortDirection(results_id, results_callback, target, column);
}

/// HANDLERS

/// UTILITIES ///

function setupDirectionArrows(column_header, direction) {
  switch (direction) {
    case SORT_TYPE_ASC:
      column_header
        .removeClass('sortable-column-desc')
        .addClass('sortable-column-asc');
      break;
    case SORT_TYPE_DESC:
      column_header
        .removeClass('sortable-column-asc')
        .addClass('sortable-column-desc');
      break;
    case SORT_TYPE_NEITHER:
      column_header
        .removeClass('sortable-column-asc')
        .removeClass('sortable-column-desc');
  }
}

function getAllSortSettings() {
  return defaultSortSettings.map(function(setting) {return setting.column;});
}

function getColumnHeaderForColumnName(results_id, column_name) {
  return $('[data-results-id=' + results_id + "]").find('[data-sortable-column="' + column_name + '"]');
}

/// UTILITIES ///
