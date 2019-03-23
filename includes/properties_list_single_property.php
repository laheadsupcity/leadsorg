<div
  data-parcel-number="<?php echo $property['parcel_number']; ?>"
  data-num-units="<?php echo $property['number_of_units']; ?>"
  data-building-area="<?php echo $property['building_area']; ?>"
  data-lot-area-sqft="<?php echo $property['lot_area_sqft']; ?>"
  data-year-built="<?php echo $property['year_built']; ?>"
  data-sale-date="<?php echo $property['sales_date']; ?>"
  class="property-item border-bottom d-flex justify-content-between align-items-center">
  <div class="mr-2">
    <input type="checkbox" data-property-checkbox value="<?php echo $property['parcel_number']; ?>" />
  </div>
  <div class="d-flex flex-fill align-items-center">
    <div class="xlg-property-info-column ml-3">
      <div class="parcel-number border-bottom mb-2 pb-1">
        Parcel # <span class="font-weight-light parcel-number-sort-by-indicator sort-by-indicator"><?php echo $property['parcel_number']; ?></span>
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
    <div class="xlg-property-info-column">
      <div class="owner-name">
        <?php echo $property['owner_name2']; ?>
      </div>
      <div class="font-weight-light">
        <div class="">
          Phone: <?php echo $property['phone1']; ?>
        </div>
        <div class="">
          Email: <?php echo $property['email1']; ?>
        </div>
      </div>
    </div>
    <div class="sm-property-info-column">
      <div class="num-units-sort-by-indicator sort-by-indicator"><span class="font-weight-bold"><?php echo $property['number_of_units']; ?></span> units</div>
    </div>
    <div class="sm-property-info-column">
      <div class="font-weight-bold"><?php echo $property['bedrooms']; ?></div> beds
    </div>
    <div class="sm-property-info-column">
      <span class="font-weight-bold"><?php echo $property['bathrooms']; ?></span> baths
    </div>
    <div class="lg-property-info-column">
      Sold on
      <span class="sale-date-sort-by-indicator sort-by-indicator font-weight-bold">
        <?php
          if ($property['sales_date']!='0000-00-00') {
            echo date('m/d/Y',
            strtotime($property['sales_date']));
          } else {
            echo "";
          } ?>
      </span>
    </div>
    <div class="md-property-info-column">
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
    <div class="md-property-info-column">
      <div>Built in <span class="year-built-sort-by-indicator sort-by-indicator font-weight-bold"><?php echo $property['year_built']; ?></span></div>
    </div>
    <div class="md-property-info-column">
      <span class="building-area-sort-by-indicator sort-by-indicator font-weight-bold"><?php echo number_format(intval($property['building_area']), 0, ",", ","); ?> </span>sqft.
    </div>
    <div class="md-property-info-column">
      <span class="lot-area-sqft-sort-by-indicator sort-by-indicator font-weight-bold"><?php echo number_format(intval($property['lot_area_sqft']), 0, ",", ","); ?> </span>sqft.
    </div>
    <div class="xlg-property-info-column ml-2">
      <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo $property['notes']; ?></textarea>
    </div>
  </div>
  <div class="ml-2">
    <div>
      <a target="_blank" class="br-1 pr-1 mr-1" href="lead_property_detail.php?apn=<?php echo $property['parcel_number']; ?>&matching_cases=<?php echo implode(",", $matching_cases[$property['parcel_number']]);?>"><i class="text-secondary fas fa-chevron-circle-right"></i></a>
    </div>
    <div>
      <a href="lead_update_customtask.php?editid=<?php echo $property['id']; ?>" target="_blank"><i class="text-secondary fas fa-edit"></i></a>
    </div>
  </div>
</div>
