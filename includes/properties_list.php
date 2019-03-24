<div
  class="border property-list-group mb-4">
  <div class="property-item-header border-bottom bg-light font-weight-bold d-flex justify-content-between align-items-end">
    <?php
    if ($show_favorites_flag) { ?>
      <i class="far fa-flag"></i>
    <?php } ?>
    <div class="mr-2">
      <input type="checkbox" id="checkAll" name="all">
    </div>
    <div class="d-flex flex-fill align-items-end">
      <div class="xlg-property-info-column property-info-column">
        Address & APN
      </div>
      <div class="owner-column property-info-column">
        Owner Information
      </div>
      <div data-sortable-column="num_units" class="sm-property-info-column property-info-column d-flex align-items-end num-units">
        Units
      </div>
      <div class="sm-property-info-column property-info-column text-center">
        Beds
      </div>
      <div class="sm-property-info-column property-info-column text-center">
        Baths
      </div>
      <div data-sortable-column="sale_date" class="lg-property-info-column property-info-column d-flex justify-content-center align-items-end sold-date">
        Sold<br /> Date
      </div>
      <div class="md-property-info-column property-info-column text-center">
        Sale<br /> Price
      </div>
      <div data-sortable-column="year_built" class="sm-property-info-column property-info-column d-flex justify-content-center align-items-end year-built">
        Year<br /> Built
      </div>
      <div data-sortable-column="building_area" class="sm-property-info-column property-info-column d-flex justify-content-center align-items-end building-size">
        Bldg<br /> Size
      </div>
      <div data-sortable-column="lot_area_sqft" class="sm-property-info-column property-info-column d-flex justify-content-center align-items-end lot-size">
        Lot<br /> Size
      </div>
      <div class="xlg-property-info-column property-info-column"></div>
    </div>
    <div class="actions-header ml-2">
    </div>
  </div>
  <div class="property-list">
    <?php
    foreach ($properties as $property) { ?>
      <?php include('includes/properties_list_single_property.php'); ?>
    <?php }  ?>
  </div>
</div>

<?php include('includes/confirm_edit_contact_info_modal.php'); ?>
