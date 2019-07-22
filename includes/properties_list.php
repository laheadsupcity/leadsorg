<?php if (!empty($properties) && $properties_only) { ?>
  <?php
    $favorites = new FavoriteProperties();
  ?>

  <div class="property-list">
    <div class="properties-scroll">
      <?php foreach ($properties as $property) { ?>
        <?php include('includes/properties_list_single_property.php'); ?>
      <?php } ?>
    </div>
  </div>
<?php } else if (!empty($properties)) { ?>
  <?php
    $favorites = new FavoriteProperties();
  ?>

  <div
    data-id="<?php echo($id); ?>"
    class="border property-list-group mb-4">
    <div class="property-item-header border-bottom bg-light font-weight-bold d-flex justify-content-between align-items-end">
      <?php if ($show_favorites_flag) { ?>
        <div class="mr-3">
          <i class="far fa-flag"></i>
        </div>
      <?php } ?>
      <div class="sm-property-info-column">
        <div>
          Select<br /> All
        </div>
        <input type="checkbox" data-check-all name="all" <?php echo($select_all ? "checked" : ""); ?>>
      </div>
      <?php if (!$read_only_fields) { ?>
        <div class="mr-2 edit-related text-center">
          Edit All<br />Related
        </div>
      <?php } ?>
      <div class="d-flex flex-fill align-items-end">
        <?php if ($include_related_properties) { ?>
          <div data-sortable-column="related_properties" class="sm-property-info-column related-properties property-info-column sortable-column text-center">
            Related
          </div>
        <?php } ?>
        <div data-sortable-column="favorites_folders" class="xlg-property-info-column property-info-column">
          Favorite Folders
        </div>
        <div class="lg-property-info-column property-info-column">
          Parcel #
        </div>
        <div class="xlg-property-info-column property-info-column">
          Address
        </div>
        <div data-sortable-column="owner_name" class="xlg-property-info-column property-info-column">
          Owner
        </div>
        <div class="xlg-property-info-column property-info-column">
          Owner Address
        </div>
        <div class="phone-number text-truncate property-info-column">
          Phone 1
        </div>
        <div class="phone-number text-truncate property-info-column">
          Phone 2
        </div>
        <div class="email-address text-truncate property-info-column">
          Email 1
        </div>
        <div class="email-address text-truncate property-info-column">
          Email 2
        </div>
        <div data-sortable-column="num_units" class="sm-property-info-column property-info-column d-flex align-items-end num-units">
          Units
        </div>
        <div data-sortable-column="num_beds" class="sm-property-info-column property-info-column d-flex align-items-end num-beds">
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
        <?php if ($show_matching_cases) { ?>
          <div class="matching-cases property-info-column">Matching cases</div>
        <?php } ?>
        <div class="xlg-property-info-column property-info-column">
          Notes
        </div>
        <div class="xlg-property-info-column property-info-column">
          Private Notes
        </div>
      </div>
      <div class="actions-header ml-2">
      </div>
    </div>
    <div class="property-list">
      <div class="properties-scroll">
        <?php foreach ($properties as $property) { ?>
          <?php include('includes/properties_list_single_property.php'); ?>
        <?php } ?>
      </div>
    </div>
  </div>

  <?php
    if ($show_pagination) {
      require('includes/search_results/pagination.php');
    }
  ?>

  <?php include('includes/confirm_edit_contact_info_modal.php'); ?>

  <?php include('includes/confirm_edit_notes_modal.php'); ?>


<?php } ?>
