<?php
  $case_status_types = getCaseStatusTypes();
?>

<table class="table table-borderless border case-type-table-fixed">
  <thead class="text-nowrap border-bottom bg-light">
    <tr>
      <th class="float-left col-1"><i class="ml-1 fas fa-check"></i></th>
      <th class="float-left col-4">Open Case Type</th>
      <th class="float-left col-3">From Date</th>
      <th class="float-left col-3">To Date</th>
      <th class="float-left col-1"><i class="ml-1 far fa-times-circle"></i></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($casetype as $case_type_entry) { ?>
      <tr data-case-type-row data-case-type-id="<?php echo $case_type_entry['case_type_id']; ?>" class="border-bottom">
        <td class="float-left col-1">
          <input type="checkbox" data-include class="form-control">
        </td>
        <td data-case-type-id="<?php echo $case_type_entry['case_type_id']; ?>" class="float-left case-type-name col-4">
          <?php echo $case_type_entry['case_type']; ?>
        </td>
        <td class="float-left col-3">
          <input data-case-type-<?php echo $case_type_entry['case_type_id']; ?>-from-date type="text" class="form-control form-control-sm case-type-datepicker" value="" />
        </td>
        <td class="float-left col-3">
          <input data-case-type-<?php echo $case_type_entry['case_type_id']; ?>-to-date type="text" class="form-control form-control-sm case-type-datepicker" value="" />
        </td>
        <td class="float-left col-1">
          <input type="checkbox" data-exclude class="form-control">
        </td>
      </tr>
      <tr data-case-statuses-for-case-type-<?php echo $case_type_entry['case_type_id']; ?> data-case-type-statuses-row data-case-type-id="<?php echo $case_type_entry['case_type_id']; ?>" class="border-bottom" hidden>
        <td colspan=4 class="w-100">
          <div class="ml-5 mr-5">
            <?php require('includes/case_status_filter.php'); ?>
          </div>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
