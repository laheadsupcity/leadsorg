 // ------- Selectors -------

// ----------- INCLUSION AND EXCLUSION CHECKBOXES ----------
function getIncludeCaseTypeCheckbox(case_type_id) {
  return $(`[data-case-type-row][data-case-type-id=${case_type_id}] [data-include]`);
}

function getExcludeCaseTypeCheckbox(case_type_id) {
  return $(`[data-case-type-row][data-case-type-id=${case_type_id}] [data-exclude]`);
}

function getExcludeCaseStatusCheckbox(case_type_id, case_status_index) {
  return $(`[data-case-type-statuses-row][data-case-type-id=${case_type_id}] [data-case-status-index=${case_status_index}] [data-exclude]`);
}

function getIncludeCaseStatusCheckbox(case_type_id, case_status_index) {
  return $(`[data-case-type-statuses-row][data-case-type-id=${case_type_id}] [data-case-status-index=${case_status_index}] [data-include]`);
}
// ----------- INCLUSION AND EXCLUSION CHECKBOXES ----------

// ----------- DATE RANGES ----------
function getFromDateInputForCaseType(case_type_id) {
  return $(`[data-case-type-${case_type_id}-from-date]`);
}

function getToDateInputForCaseType(case_type_id) {
  return $(`[data-case-type-${case_type_id}-to-date]`);
}

function getFromDateForCaseStatusType(case_type_id, case_status_index) {
  return $(`[data-case-type-${case_type_id}-status-${case_status_index}-from-date]`);
}

function getToDateForCaseStatusType(case_type_id, case_status_index) {
  return $(`[data-case-type-${case_type_id}-status-${case_status_index}-to-date]`);
}
// ----------- DATE RANGES ----------

// ----------- CASE STATUSES ----------
function getCaseStatusesTableRowForCaseType(case_type_id) {
  return $(`[data-case-statuses-for-case-type-${case_type_id}]`);
}
// ----------- CASE STATUSES ----------

// ------- Selectors -------

function gatherCaseStatusTypeFiltersForCaseType(case_type_id, case_type_from_date, case_type_to_date) {
  var case_status_inclusion_filters = [],
      case_status_exclusion_filters = [],
      case_statuses = getCaseStatusesTableRowForCaseType(case_type_id).find('tr[data-case-status-index]');

  $.each(case_statuses, function(index, row) {
    let status_index = $(row).data('case-status-index'),
        include_checkbox = getIncludeCaseStatusCheckbox(case_type_id, status_index),
        exclude_checkbox = getExcludeCaseStatusCheckbox(case_type_id, status_index),
        from_date = getFromDateForCaseStatusType(case_type_id, status_index),
        to_date = getToDateForCaseStatusType(case_type_id, status_index);

    if (include_checkbox.prop('checked')) {
      case_status_inclusion_filters.push({
        'case_status_type': include_checkbox.val() || exclude_checkbox.val(),
        'from_date': from_date.val() ? from_date.val() : case_type_from_date,
        'to_date': to_date.val() ? to_date.val() : case_type_to_date
      });
    } else if (exclude_checkbox.prop('checked')) {
      case_status_exclusion_filters.push({
        'case_status_type': include_checkbox.val() || exclude_checkbox.val()
      });
    }
  });

  return {
    'include': case_status_inclusion_filters,
    'exclude': case_status_exclusion_filters
  }
}

function gatherCaseTypeFilterData() {
  var case_type_inclusion_filters = [],
      case_type_exclusion_filters = [],
      case_type_filter = $('[data-case-type-filter]'),
      case_type_rows = case_type_filter.find('[data-case-type-row][data-case-type-id]');

  $.each(case_type_rows, function(index, row) {
    let case_type_id = $(row).data('case-type-id'),
        from_date = getFromDateInputForCaseType(case_type_id).val(),
        to_date = getToDateInputForCaseType(case_type_id).val(),
        include_checkbox = getIncludeCaseTypeCheckbox(case_type_id),
        exclude_checkbox = getExcludeCaseTypeCheckbox(case_type_id);

    if (include_checkbox.prop('checked')) {
      case_type_inclusion_filters.push({
        'case_type_id': case_type_id,
        'from_date': from_date,
        'to_date': to_date,
        'status_filters': gatherCaseStatusTypeFiltersForCaseType(case_type_id, from_date, to_date)
      });
    } else if (exclude_checkbox.prop('checked')) {
      case_type_exclusion_filters.push({
        'case_type_id': case_type_id
      });
    }
  });

  return {
    'include': case_type_inclusion_filters,
    'exclude': case_type_exclusion_filters
  };
}

function handleFormSubmit(form) {
  var form_object = {
    num_units_min: null,
    num_units_max: null,
    is_owner_occupied: null,
    zip_codes: [],
    cities: [],
    zoning: [],
    tax_exemption_codes: [],
    num_bedrooms_min: null,
    num_bedrooms_max: null,
    num_baths_min: null,
    num_baths_max: null,
    year_built_min: null,
    year_built_max: null,
    lot_area_sq_ft_min: null,
    lot_area_sq_ft_max: null,
    sales_price_min: null,
    sales_price_max: null,
    sales_date_from: null,
    sales_date_to: null,
    num_stories_min: null,
    num_stories_max: null,
    cost_per_sq_ft_min: null,
    cost_per_sq_ft_max: null,
    case_types: {},
    is_open_cases_exclusively: true,
    filter_on_notes: false,
    notes_content_to_match: null
  };

  $.each(form.serializeArray(), function(_, key_value) {
    if (!form_object.hasOwnProperty(key_value.name)) {
      // no op
    } else if (Array.isArray(form_object[key_value.name])) {
      form_object[key_value.name].push(key_value.value);
    } else {
      form_object[key_value.name] = key_value.value || null;
    }
  });

  form_object['case_types'] = gatherCaseTypeFilterData();

  window.location = "lead_get_property.php?" + $.param(form_object);
}

function toggleExcludeForCaseTypeDisabled(case_type_id, is_disabled) {
  var exclude_checkbox = getExcludeCaseTypeCheckbox(case_type_id);
  exclude_checkbox.attr('disabled', is_disabled);
}

function toggleIncludeForCaseTypeDisabled(case_type_id, is_disabled) {
  var include_checkbox = getIncludeCaseTypeCheckbox(case_type_id);
  include_checkbox.attr('disabled', is_disabled);
}

function toggleIncludeForCaseStatusTypeDisabled(case_type_id, case_status_index, is_disabled) {
  var include_checkbox = getIncludeCaseStatusCheckbox(case_type_id, case_status_index);
  include_checkbox.attr('disabled', is_disabled);
}

function toggleCaseTypeStatusesVisibility(case_type_id, show) {
  var case_type_row = $(`[data-case-type-row][data-case-type-id=${case_type_id}]`),
      statuses = getCaseStatusesTableRowForCaseType(case_type_id);

  if (show) {
    case_type_row.removeClass('border-bottom');
  } else {
    case_type_row.addClass('border-bottom');
  }

  statuses.attr('hidden', !show);
}

function resetDateRangeForCaseType(case_type_id) {
  var from_date_param = getFromDateInputForCaseType(case_type_id),
      to_date_param = getToDateInputForCaseType(case_type_id);

  from_date_param.val('');
  to_date_param.val('');
}

function disableCaseTypeDateRange(case_type_id, is_disabled) {
  var from_date_param = getFromDateInputForCaseType(case_type_id),
      to_date_param = getToDateInputForCaseType(case_type_id);

  from_date_param.attr('disabled', is_disabled);
  to_date_param.attr('disabled', is_disabled);
  if (is_disabled) {
    resetDateRangeForCaseType(case_type_id);
  }
}

function resetDateRangeForCaseStatusType(case_type_id, case_status_index) {
  var from_date_param = getFromDateForCaseStatusType(case_type_id, case_status_index),
      to_date_param = getToDateForCaseStatusType(case_type_id, case_status_index);

  from_date_param.val('');
  to_date_param.val('');
}

function disableCaseStatusTypeDateRange(case_type_id, case_status_index, is_disabled) {
  var from_date_param = getFromDateForCaseStatusType(case_type_id, case_status_index),
      to_date_param = getToDateForCaseStatusType(case_type_id, case_status_index);

  from_date_param.attr('disabled', is_disabled);
  to_date_param.attr('disabled', is_disabled);
  if (is_disabled) {
    resetDateRangeForCaseStatusType(case_type_id, case_status_index);
  }
}

function toggleCaseStatusTypeNameDisabled(case_type_id, case_status_index, is_disabled) {
  var case_status_name = $(`tr[data-case-type-id=${case_type_id}][data-case-status-index=${case_status_index}]`);

  if (is_disabled) {
    case_status_name.addClass('disabled');
  } else {
    case_status_name.removeClass('disabled');
  }
}

function toggleCaseTypeNameDisabled(case_type_index, is_disabled) {
  var case_type_name = $(`.case-type-name[data-case-type-id=${case_type_index}]`);

  if (is_disabled) {
    case_type_name.addClass('disabled');
  } else {
    case_type_name.removeClass('disabled');
  }
}

// ------- Toggle include and exclude -------

function includeCaseStatusTypeInFilterToggle(case_type_id, case_status_index, is_include) {
  var exclude_checkbox = getExcludeCaseStatusCheckbox(case_type_id, case_status_index);

  if (is_include) {
    exclude_checkbox.attr('checked', false);
    exclude_checkbox.attr('disabled', true);
  } else {
    resetDateRangeForCaseStatusType(case_type_id, case_status_index);
    exclude_checkbox.attr('disabled', false);
  }
}

function includeCaseTypeInFilterToggle(case_type_id, toggle_direction) {
  if (toggle_direction) {
    getCaseStatusesTableRowForCaseType(case_type_id).trigger('statusesExpanded');
  }

  toggleCaseTypeStatusesVisibility(case_type_id, toggle_direction);
  toggleExcludeForCaseTypeDisabled(case_type_id, toggle_direction);

  if (!toggle_direction) {
    resetDateRangeForCaseType(case_type_id, toggle_direction);
  }
}

function excludeCaseTypeInFilterToggle(case_type_index, is_exclude) {
    toggleIncludeForCaseTypeDisabled(case_type_index, is_exclude);
    disableCaseTypeDateRange(case_type_index, is_exclude);
    toggleCaseTypeNameDisabled(case_type_index, is_exclude);
}

function excludeCaseStatusTypeInFilterToggle(case_type_id, case_status_index, is_exclude) {
  toggleIncludeForCaseStatusTypeDisabled(case_type_id, case_status_index, is_exclude);
  toggleCaseStatusTypeNameDisabled(case_type_id, case_status_index, is_exclude);
  disableCaseStatusTypeDateRange(case_type_id, case_status_index, is_exclude);
}

// ------- Toggle include and exclude -------

// ------- Event Handlers -------

function handleDateRangeChange(event) {
  var target = $(event.target),
      row = target.closest('tr'),
      include_checkbox = row.find('[data-include]');

  include_checkbox.prop('checked', true);
  if (row.data('case-type-id')) {
    includeCaseTypeInFilterToggle(row.data('case-type-id'), true);
  } else if (row.data('case-status-index')) {
    includeCaseStatusTypeInFilterToggle(row.data('case-status-index'), true);
  }
}

function handleIncludeCaseTypeChange(event) {
  var target = $(event.target),
      case_type_index = target.closest('tr').data('case-type-id'),
      is_checked = target.prop('checked');

  includeCaseTypeInFilterToggle(case_type_index, is_checked);
}

function handleExcludeCaseTypeChange(event) {
  var target = $(event.target),
      case_type_id = target.closest('tr').data('case-type-id'),
      is_checked = target.prop('checked');

  excludeCaseTypeInFilterToggle(case_type_id, is_checked);
}

function handleIncludeCaseStatusTypeChange(event) {
  var target = $(event.target),
      row = target.closest('tr'),
      case_type_id = row.data('case-type-id')
      case_status_index = row.data('case-status-index'),
      is_checked = target.prop('checked');

  includeCaseStatusTypeInFilterToggle(case_type_id, case_status_index, is_checked);
}

function handleExcludeCaseStatusTypeChange(event) {
  var target = $(event.target),
      row = target.closest('tr'),
      case_type_id = row.data('case-type-id'),
      case_status_index = row.data('case-status-index'),
      is_checked = target.prop('checked');

  excludeCaseStatusTypeInFilterToggle(case_type_id, case_status_index, is_checked);
}

// ------- Event Handlers -------

function setupCaseStatusDatePickers(case_type_id) {
  var datepickers = $(`[data-case-type-statuses-row][data-case-type-id=${case_type_id}] .case-status-datepicker`);

  datepickers.datetimepicker({
    format: 'm/d/Y',
    timepicker: false,
    scrollInput: false
  });
}

$(document).ready(function() {

  $('.case-type-datepicker').datetimepicker({
    format: 'm/d/Y',
    timepicker: false,
    scrollInput: false
  });

  $('.case-type-datepicker, .case-status-datepicker').change(function(event) {
    handleDateRangeChange(event);
  });

  // setup datepickers for statuses only if case type is selected
  // this is to avoid a performance issue
  $('[data-case-type-statuses-row]').one('statusesExpanded', function(event) {
    var case_type_id = $(event.target).closest('tr').data('case-type-id');
    setupCaseStatusDatePickers(case_type_id);
  });

  $('[data-case-type-row] [data-include]').change(function(event) {
    handleIncludeCaseTypeChange(event);
  });

  $('[data-case-type-row] [data-exclude]').change(function(event) {
    handleExcludeCaseTypeChange(event);
  });

  $('[data-case-type-statuses-row] [data-include]').change(function(event) {
    handleIncludeCaseStatusTypeChange(event);
  });

  $('[data-case-type-statuses-row] [data-exclude]').change(function(event) {
    handleExcludeCaseStatusTypeChange(event);
  });

  $('[data-action="perform-search"]').on('submit', function(event) {
    event.preventDefault();
    var form = $(event.target);
    handleFormSubmit(form);
  });

});
