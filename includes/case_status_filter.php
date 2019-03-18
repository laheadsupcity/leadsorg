<table class="table table-sm table-borderless case-status-type-table">
  <thead>
    <tr class="border-bottom">
      <th scope="col"><i class="ml-1 fas fa-check"></i></th>
      <th scope="col">Case Status Type</th>
      <th scope="col">From Date</th>
      <th scope="col">To Date</th>
      <th scope="col"><i class="ml-1 far fa-times-circle"></i></th>
    </tr>
  </thead>
  <tbody>
    <tr class="bg-light border-bottom" data-case-type-id="<?php echo $case_type_entry['case_type_id']; ?>" data-case-status-index="case_closed_date">
      <td class="align-middle">
        <input type="checkbox" data-include class="form-control" value="case_closed_date">
      </td>
      <td class="align-middle">
        Case Closed Date
      </td>
      <td class="align-middle">
        <input
          data-case-type-<?php echo $case_type_entry['case_type_id']; ?>-status-case_closed_date-from-date
          type="text"
          style="width: 80px"
          class="case-status-datepicker form-control form-control-sm"
          value=""
        />
      </td>
      <td class="align-middle">
        <input
          data-case-type-<?php echo $case_type_entry['case_type_id']; ?>-status-case_closed_date-to-date
          type="text"
          style="width: 80px"
          class="case-status-datepicker form-control form-control-sm"
          value=""
        />
      </td>
      <td class="align-middle">
        <input type="checkbox" data-exclude class="form-control" value="case_closed_date">
      </td>
    </tr>
    <?php foreach ($case_status_types[$case_type_entry['case_type_id']] as $index => $case_status_type) { ?>
      <tr data-case-type-id="<?php echo $case_type_entry['case_type_id']; ?>" data-case-status-index="<?php echo $index; ?>" class="border-bottom">
        <td class="align-middle">
          <input type="checkbox" data-include class="form-control" value="<?php echo $case_status_type; ?>">
        </td>
        <td class="align-middle">
          <div data-status-type>
            <?php echo $case_status_type; ?>
          </div>
        </td>
        <td class="align-middle">
          <input
            data-case-type-<?php echo $case_type_entry['case_type_id']; ?>-status-<?php echo $index; ?>-from-date
            data-case-status-index="<?php echo $index; ?>"
            type="text"
            style="width: 80px"
            class="case-status-datepicker form-control form-control-sm"
            value=""
          />
        </td>
        <td class="align-middle">
          <input
            data-case-type-<?php echo $case_type_entry['case_type_id']; ?>-status-<?php echo $index; ?>-to-date
            data-case-status-index="<?php echo $index; ?>"
            type="text"
            style="width: 80px"
            class="case-status-datepicker form-control form-control-sm"
            value=""
          />
        </td>
        <td class="align-middle">
          <input type="checkbox" value="<?php echo $case_status_type; ?>" data-exclude class="form-control">
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
