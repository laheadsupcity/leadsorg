<?php
  $owner_address = $property['full_mail_address'];
  $related_properties_for_owner_count = $related_properties_counts[$owner_address] - 1;
  $has_related_properties = $related_properties_for_owner_count > 0;

  if ($show_matching_cases) {
    $matching_cases_for_property = $matching_cases[$property['parcel_number']];

    $matching_cases_for_property_string = implode(', ',
      array_map(
        function($matching_case) {
          return sprintf(
            "%s (%s)",
            $matching_case['type'],
            $matching_case['case_number']
          );
        },
        $matching_cases_for_property
      )
    );

    $matching_case_ids_search_param = implode(
      ',',
      array_map(
        create_function('$case_data', 'return $case_data["pcid"];'),
        $matching_cases_for_property
      )
    );
  }
?>

<div
  data-parcel_number="<?php echo $property['parcel_number']; ?>"
  data-num_units="<?php echo $property['number_of_units']; ?>"
  data-building_area="<?php echo $property['building_area']; ?>"
  data-lot_area_sqft="<?php echo $property['lot_area_sqft']; ?>"
  data-year_built="<?php echo $property['year_built']; ?>"
  data-sale_date="<?php echo $property['sales_date']; ?>"
  data-owner_name="<?php echo $property['owner_name2']; ?>"
  data-related_properties="<?php echo $related_properties_for_owner_count; ?>"
  data-matching-cases-string="<?php echo $matching_cases_for_property_string; ?>"
  class="property-item border-bottom w-100 d-flex justify-content-between align-items-center">

  <div class="d-flex flex-fill">
    <?php if ($show_favorites_flag) { ?>
      <div class="mr-3" style="width: 15px;">
        <?php if($property['has_unseen_updates']) { ?>
          <i class="fas fa-flag text-danger"></i>
        <?php } ?>
      </div>
    <?php } ?>
    <div class="mr-4">
      <input type="checkbox" data-property-checkbox value="<?php echo $property['parcel_number']; ?>" />
    </div>
    <div class="mr-2 edit-related text-center">
      <input type="checkbox" data-edit-related-checkbox name="all" value="<?php echo $property['parcel_number']; ?>" checked>
    </div>
    <div class="sm-property-info-column related-properties property-info-column sortable-column text-center font-weight-bold">
      <?php if ($has_related_properties) {
        echo "<span class='text-primary'>" . $related_properties_for_owner_count . "</span>";
      } else {
        echo "<span class=\"font-italic font-weight-light\">none</span>";
      }?>
    </div>

    <div class="lg-property-info-column property-info-column">
      <span class="font-weight-light"><?php echo $property['parcel_number']; ?></span>
    </div>
    <div class="xlg-property-info-column property-info-column">
      <div class="">
        <div>
          <?php echo $property['street_number']; ?> <?php echo $property['street_name'] ?>
        </div>
        <div>
          <?php echo $property['site_address_city_state']; ?>, <?php echo $property['site_address_zip']; ?>
        </div>
      </div>
    </div>

    <div class="xlg-property-info-column property-info-column">
      <?php echo $property['owner_name2']; ?>
    </div>

    <div class="phone-number owner-column property-info-column">
      <div data-field="phone1" class="editable-field text-truncate"><?php echo $property['phone1']; ?></div>
    </div>

    <div class="phone-number owner-column property-info-column">
      <div data-field="phone2" class="editable-field text-truncate"><?php echo $property['phone2']; ?></div>
    </div>

    <div class="email-address owner-column property-info-column">
      <div data-field="email1" class="editable-field text-truncate"><?php echo $property['email1']; ?></div>
    </div>

    <div class="email-address owner-column property-info-column">
      <div data-field="email2" class="editable-field text-truncate"><?php echo $property['email2']; ?></div>
    </div>

    <div class="sm-property-info-column property-info-column sortable-column num-units">
      <div><span class="font-weight-bold"><?php echo $property['number_of_units']; ?></span><br /> units</div>
    </div>
    <div class="sm-property-info-column property-info-column text-center">
      <div class="font-weight-bold"><?php echo $property['bedrooms']; ?></div> beds
    </div>
    <div class="sm-property-info-column property-info-column text-center">
      <span class="font-weight-bold"><?php echo $property['bathrooms']; ?></span> baths
    </div>
    <div class="lg-property-info-column property-info-column sortable-column text-center">
      Sold on
      <span class="font-weight-bold">
        <?php
          if ($property['sales_date']!='0000-00-00') {
            echo date(
              'm/d/Y',
              strtotime($property['sales_date'])
            );
          } else {
            echo "";
          } ?>
      </span>
    </div>
    <div class="md-property-info-column property-info-column text-center">
      <span class="detail text-success">
        <?php
          if (!empty($property['sales_price'])) {
            $sales_price = "$" . number_format(intval($property['sales_price']), 0, ",", ",");
          }
        ?>
        <?php if(!isset($sales_price)) { ?>
          <span class="font-italic font-weight-light text-muted">unknown</span>
        <?php } else { ?>
          <span class="text-success"><?php echo $sales_price; ?></span>
        <?php } ?>
      </span>
    </div>
    <div class="sm-property-info-column property-info-column sortable-column year-built text-center">
      <div><span class="font-weight-bold"><?php echo $property['year_built']; ?></span></div>
    </div>
    <div class="sm-property-info-column property-info-column sortable-column building-size text-center">
      <span class="font-weight-bold"><?php echo number_format(intval($property['building_area']), 0, ",", ","); ?><br /> </span>sqft.<br />building
    </div>
    <div class="sm-property-info-column property-info-column sortable-column lot-size text-center">
      <span class="font-weight-bold"><?php echo number_format(intval($property['lot_area_sqft']), 0, ",", ","); ?><br /> </span>sqft.<br />lot
    </div>

    <?php if ($show_matching_cases) { ?>
      <div class="matching-cases property-info-column">
        <?php include('includes/search_results/matching_cases.php'); ?>
      </div>
    <?php } ?>

    <div class="xlg-property-info-column property-info-column notes-column editable-field font-weight-light">
      <?php echo $property['notes']; ?>
    </div>
  </div>
  <div class="ml-3 mr-1">
    <div>
      <a target="_blank" class="br-1 pr-1 mr-1" href="lead_property_detail.php?apn=<?php echo $property['parcel_number']; ?>&matching_cases=<?php echo $matching_case_ids_search_param;?>"><i class="text-secondary fas fa-chevron-circle-right"></i></a>
    </div>
    <div>
      <a href="lead_update_customtask.php?editid=<?php echo $property['id']; ?>" target="_blank"><i class="text-secondary fas fa-edit"></i></a>
    </div>
  </div>
</div>
