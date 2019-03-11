<table class="table table-borderless table-striped fixed-head-table border">
  <thead class="border-bottom">
    <tr>
      <?php
      $favorites_enabled = false;
      if ($favorites_enabled) { ?>
        <th class="flag-col">
          <i class="far fa-flag"></i>
        </th>
      <?php } ?>
      <th class="checkbox-col">
        <input type="checkbox" id="checkAll" name="all">
      </th>
      <th class="apn-col">Parcel #</th>
      <th class="address-col">Address</th>
      <th class="owner-col">Owner Name</th>
      <th class="units-col">Units</th>
      <th class="beds-col">Beds</th>
      <th class="baths-col">Baths</th>
      <th class="lot-sqft-col">Lot SQFT</th>
      <th class="year-built-col">Year Built</th>
      <th class="sale-date-col">Sale Date</th>
      <th class="sale-price-col">Sale Price</th>
      <th class="options-col"></th>
    </tr>
  </thead>

  <tbody style="height: 500px;">
    <?php
      foreach ($properties as $property) { ?>
        <tr class="border-bottom">
          <?php
          $favorites_enabled = false;
          if ($favorites_enabled) { ?>
            <td class="flag-col">
              <?php if($property['has_unseen_updates']) { ?>
                <i class="fas fa-flag text-danger"></i>
              <?php } ?>
            </td>
          <?php } ?>
          <td class="checkbox-col"><input type="checkbox" data-property-checkbox value="<?php echo $property['parcel_number']; ?>" /></td>
          <td class="apn-col"><?php echo $property['parcel_number']; ?></td>
          <td class="address-col"><?php echo $property['street_number'].' '.$property['street_name'].'<br/> '.$property['site_address_city_state'].',
 '.$property['site_address_zip']; ?></td>
          <td class="owner-col"><?php echo $property['owner_name2']; ?></td>
          <td class="units-col"><?php echo $property['number_of_units']; ?></td>
          <td class="beds-col"><?php echo $property['bedrooms']; ?></td>
          <td class="baths-col"><?php echo $property['bathrooms']; ?></td>
          <td class="lot-sqft-col"><?php echo $property['lot_area_sqft']; ?></td>
          <td class="year-built-col"><?php echo $property['year_built']; ?></td>
          <td class="sale-date-col">
            <?php
              if ($property['sales_date']!='0000-00-00') {
                echo date('m/d/Y',
                strtotime($property['sales_date']));
              } else {
                echo "";
              } ?>
          </td>
          <td class="sale-price-col"><?php echo $property['sales_price']; ?></td>
          <td class="options-col">
            <div>
              <a target="_blank" class="br-1 pr-1 mr-1" href="lead_property_detail.php?apn=<?php echo $property['parcel_number']; ?>"><i class="text-secondary fas fa-chevron-circle-right"></i></a>
            </div>
            <div>
              <a href="lead_update_customtask.php?editid=<?php echo $property['id']; ?>" target="_blank"><i class="text-secondary fas fa-edit"></i></a>
            </div>
          </td>
        </tr>
      <?php } ?>
    </tbody>
</table>
