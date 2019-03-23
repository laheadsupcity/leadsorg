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
      <div class="xlg-property-info-column ml-3">
        Address & APN
      </div>
      <div class="xlg-property-info-column">
        Owner Information
      </div>
      <div class="sm-property-info-column">
        Units
      </div>
      <div class="sm-property-info-column">
        Beds
      </div>
      <div class="sm-property-info-column">
        Baths
      </div>
      <div class="lg-property-info-column">
        <div>Sold</div>
        <div>Date</div>
      </div>
      <div class="md-property-info-column">
        Sale $
      </div>
      <div class="md-property-info-column">
        <div>Year</div>
        <div>Built</div>
      </div>
      <div class="md-property-info-column">
        Building
        Size
      </div>
      <div class="md-property-info-column">
        <div>Lot</div>
        <div>Size</div>
      </div>
      <div class="xlg-property-info-column ml-2"></div>
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
