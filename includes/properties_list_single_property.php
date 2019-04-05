<?php
  $owner_address = $property['full_mail_address'] . ' ' . $property['mail_address_zip'];
  $related_properties_for_owner_count = $related_properties_counts[$owner_address] - 1;
  $has_related_properties = $related_properties_for_owner_count > 0;
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
  class="property-item border-bottom w-100 d-flex justify-content-between align-items-center">
  <div class="mr-4">
    <input type="checkbox" data-property-checkbox value="<?php echo $property['parcel_number']; ?>" />
  </div>
  <div class="mr-2 edit-related text-center">
    <input type="checkbox" data-edit-related-checkbox name="all" value="<?php echo $property['parcel_number']; ?>" checked>
  </div>

  <div class="d-flex flex-fill">
    <div class="sm-property-info-column related-properties property-info-column sortable-column text-center d-flex align-items-center justify-content-center font-weight-bold">
      <?php if ($has_related_properties) {
        echo "<span class='text-primary'>" . $related_properties_for_owner_count . "</span>";
      } else {
        echo "<span class=\"font-italic font-weight-light\">none</span>";
      }?>
    </div>

    <div class="xlg-property-info-column property-info-column">
      <div class="parcel-number border-bottom mb-2 pb-1">
        Parcel # <span class="font-weight-light"><?php echo $property['parcel_number']; ?></span>
      </div>
      <div class="">
        <div>
          <?php echo $property['street_number']; ?> <?php echo $property['street_name'] ?>
        </div>
        <div>
          <?php echo $property['site_address_city_state']; ?>, <?php echo $property['site_address_zip']; ?>
        </div>
      </div>
    </div>

    <div class="owner-column property-info-column">
      <div class="owner-name color-primary font-weight-bold text-truncate">
        <?php echo $property['owner_name2']; ?>
      </div>
      <div class="d-flex flex-column font-weight-light">
          <div class="d-flex mb-1">
            <div class="owner-contact-label">Phone 1:</div>
            <div data-field="phone1" class="editable-field ml-1"><?php echo $property['phone1']; ?></div>
          </div>
          <div class="d-flex mb-1">
            <div class="owner-contact-label">Phone 2:</div>
            <div data-field="phone2" class="editable-field ml-1"><?php echo $property['phone2']; ?></div>
          </div>
          <div class="d-flex mb-1">
            <div class="owner-contact-label">Email 1:</div>
            <div data-field="email1" class="editable-field ml-1"><?php echo $property['email1']; ?></div>
          </div>
          <div class="d-flex">
            <div class="owner-contact-label">Email 2:</div>
            <div data-field="email2" class="editable-field ml-1"><?php echo $property['email2']; ?></div>
          </div>
      </div>
    </div>

    <div class="d-flex align-items-center">
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
      <div class="xlg-property-info-column property-info-column notes-column editable-field font-weight-light">
        <?php echo $property['notes']; ?>
      </div>
    </div>
  </div>
  <div class="ml-3 mr-1">
    <div>
      <a target="_blank" class="br-1 pr-1 mr-1" href="lead_property_detail.php?apn=<?php echo $property['parcel_number']; ?>&matching_cases=<?php echo implode(",", $matching_cases[$property['parcel_number']]);?>"><i class="text-secondary fas fa-chevron-circle-right"></i></a>
    </div>
    <div>
      <a href="lead_update_customtask.php?editid=<?php echo $property['id']; ?>" target="_blank"><i class="text-secondary fas fa-edit"></i></a>
    </div>
  </div>
</div>
