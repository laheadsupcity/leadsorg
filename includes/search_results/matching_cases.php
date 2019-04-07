<div class="d-flex flex-column">
  <?php
  $matching_cases_for_property = $matching_cases[$property['parcel_number']];

  foreach ($matching_cases_for_property as $matching_case) { ?>
    <div class="d-flex">
      <div class="text-primary font-weight-bold text-truncate"><?php echo $matching_case['type']; ?></div>
      <div class="ml-1 font-weight-light">(<?php echo $matching_case['case_number']; ?>)</div>
    </div>
  <?php } ?>
</div>