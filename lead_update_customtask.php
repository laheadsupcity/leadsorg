<?php
require_once('config.php');
require_once('Property.php');

include('datafunction.php');
$id=isset($_REQUEST['editid']) ? $_REQUEST['editid'] : '';
$property_data=getpropertydata($id);
$zip=getziplist();
$city=getcitylist();
$zoning=getzoninglist();
$exemption=getexemptionlist();

$user_id = $_SESSION['userdetail']['id'];
?>
<!doctype html>
<html lang="en" style="font-size: 14px;">
  <head>
    <meta charset="UTF-8">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>Scraping</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/main_content.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/edit_property.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style>
      .active1{background:#337ab7!important;}
    </style>
  </head>
 <body>
  <div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>
    <div data-user-id="<?php echo $user_id; ?>" class="main-content main-content-fixed-width mx-auto">
      <form id="update-property-form">
        <div class="mt-3 mb-3 d-flex flex-wrap">
          <div class="col-sm-4">
            <h5 class="text-primary font-weight-bold">Owner Information</h5>
            <p><label><span>Parcel Number</span></label><br/> <input type="text" readonly class="edittext" name="apn" id="apn" value="<?php echo  isset($property_data['parcel_number']) ? $property_data['parcel_number'] : '' ?>"></p>
            <p><label><span>Owner Name</span></label><br/> <input type="text" class="edittext" name="ownername" id="ownername" value="<?php echo  isset($property_data['owner_name2']) ? $property_data['owner_name2'] : '' ?>"></p>
            <p><label><span>Owner First Name</span></label><br/> <input type="text" class="edittext" name="ofname" id="ofname" value="<?php echo  isset($property_data['owner1_first_name']) ? $property_data['owner1_first_name'] : '' ?>"></p>
            <p><label><span>Owner Last Name</span></label><br/> <input type="text" class="edittext" name="olname" id="olname" value="<?php echo  isset($property_data['owner1_last_name']) ? $property_data['owner1_last_name'] : '' ?>"></p>
            <p><label><span>Owner Spouse Name</span></label><br/> <input type="text" class="edittext" name="osname" id="osname" value="<?php echo  isset($property_data['owner1_spouse_first_name']) ? $property_data['owner1_spouse_first_name'] : '' ?>"></p>
          </div>

          <div class="col-sm-4">
            <h5 class="text-primary font-weight-bold">Address Information</h5>
            <table style="width:100%;">
              <tr>
                <td><p><label><span>Street Number</span></label><br/> <input type="text" class="edittext" name="streetnumber" id="streetnumber" value="<?php echo  isset($property_data['street_number']) ? $property_data['street_number'] : '' ?>"></p></td>
                <td><p><label><span>Street Name</span></label><br/> <input type="text" class="edittext" name="strname" id="strname" value="<?php echo  isset($property_data['street_name']) ? $property_data['street_name'] : '' ?>"></p></td>
              </tr>
              <tr>
                <td><p><label><span>Site Address Zip</span></label><br/> <input type="text" class="edittext" name="sazip" id="sazip" value="<?php echo  isset($property_data['site_address_zip']) ? $property_data['site_address_zip'] : '' ?>"></p></td>
                <td><p><label><span>Mail Address Zip</span></label><br/> <input type="text" class="edittext" name="mazip" id="mazip" value="<?php echo  isset($property_data['mail_address_zip']) ? $property_data['mail_address_zip'] : '' ?>"></p></td>
              </tr>
              <tr>
                <td colspan="2"><p><label><span>Full Mail Address</span></label><br/> <input type="text" class="edittext1" name="fmaddress" id="fmaddress" value="<?php echo  isset($property_data['full_mail_address']) ? $property_data['full_mail_address'] : '' ?>"></p></td>
              </tr>
              <tr>
                <td colspan="2"><p><label><span>Mail Address City/State</span></label><br/><input type="text" class="edittext1" name="maddcs" id="maddcs" value="<?php echo  isset($property_data['mail_address_city_state']) ? $property_data['mail_address_city_state'] : '' ?>"> </p></td>
              </tr>
              <tr>
                <td><p><label><span>Use Code</span></label><br/> <input type="text" class="edittext" name="uscode" id="uscode" value="<?php echo  isset($property_data['use_code']) ? $property_data['use_code'] : '' ?>"></p></td>
                <td><p><label><span>Use Code Description</span></label><br/> <input type="text" class="uscodedesc" name="uscodedesc" id="uscodedesc" value="<?php echo  isset($property_data['use_code_descrition']) ? $property_data['use_code_descrition'] : '' ?>"></p></td>
              </tr>
            </table>
          </div>

          <div class="col-sm-4">
            <h5 class="text-primary font-weight-bold">Property Information</h5>
            <table style="width:100%;">
            <tr>
            <td><p><label><span>Building Area</span></label><br/> <input type="text" class="edittext" name="buldarea" id="buldarea" value="<?php echo  isset($property_data['building_area']) ? $property_data['building_area'] : '' ?>"></p></td>
            <td><p><label><span>Bedrooms</span></label><br/> <input type="text" class="edittext" name="bedroom" id="bedroom" value="<?php echo  isset($property_data['bedrooms']) ? $property_data['bedrooms'] : '' ?>"></p></td>

            <td><p><label><span>Bathrooms</span></label><br/> <input type="text" class="edittext" name="bathrooms" id="bathrooms" value="<?php echo  isset($property_data['bathrooms']) ? $property_data['bathrooms'] : '' ?>"></p></td>
            </tr>

            <tr>
            <td><p><label><span>Tract</span></label><br/> <input type="text" class="edittext" name="tract" id="sazip" value="<?php echo  isset($property_data['tract']) ? $property_data['tract'] : '' ?>"></p></td>

            <td><p><label><span>Lot Area SQFT</span></label><br/> <input type="text" class="edittext" name="lot_area_sq_ft_mint" id="lot_area_sq_ft_mint" value="<?php echo  isset($property_data['lot_area_sqft']) ? $property_data['lot_area_sqft'] : '' ?>"></p></td>

            <td><p><label><span>Year Built</span></label><br/> <input type="text" class="edittext" name="yearbuilt" id="yearbuilt" value="<?php echo  isset($property_data['year_built']) ? $property_data['year_built'] : '' ?>"></p></td>
            </tr>

            <tr>
            <td><p><label><span>Pool</span></label><br/> <input type="text" class="edittext" name="pool" id="pool" value="<?php echo  isset($property_data['pool']) ? $property_data['pool'] : '' ?>"></p></td>

            <td><p><label><span>Garage Type</span></label><br/> <input type="text" class="edittext" name="garage_type" id="garage_type" value="<?php echo  isset($property_data['garage_type']) ? $property_data['garage_type'] : '' ?>"></p></td>

            <td><p><label><span>Sale Date</span></label><br/> <input type="text" class="edittext" name="sales_date" id="sales_date" value="<?php echo  isset($property_data['sales_date']) ? $property_data['sales_date'] : '' ?>"></p></td>
            </tr>

            <tr>
            <td><p><label><span>Number of Units</span></label><br/> <input type="text" class="edittext" name="nou" id="nou" value="<?php echo  isset($property_data['number_of_units']) ? $property_data['number_of_units'] : '' ?>"></p></td>

            <td><p><label><span>Number of Stories</span></label><br/> <input type="text" class="edittext" name="nos" id="nos" value="<?php echo  isset($property_data['number_of_stories']) ? $property_data['number_of_stories'] : '' ?>"></p></td>

            <td><p><label><span>Zoning</span></label><br/> <input type="text" class="edittext" name="zoning" id="zoning" value="<?php echo  isset($property_data['zoning']) ? $property_data['zoning'] : '' ?>"> </p></td>
            </tr>

            <tr>
            <td><p><label><span>Sales Price</span></label><br/> <input type="text" class="edittext" name="salprice" id="salprice" value="<?php echo  isset($property_data['sales_price']) ? $property_data['sales_price'] : '' ?>"></p></td>

            <td><p><label><span>Cost Per Sq Ft</span></label><br/> <input type="text" class="edittext" name="cpsqft" id="cpsqft" value="<?php echo  isset($property_data['cost_per_sq_ft']) ? $property_data['cost_per_sq_ft'] : '' ?>"></p></td>

            <td><p><label><span>Total Assessed Value</span></label><br/> <input type="text" class="edittext" name="totalav" id="totalav" value="<?php echo  isset($property_data['total_assessed_value']) ? $property_data['total_assessed_value'] : '' ?>"></p></td>
            </tr>
            </table>
          </div>

          <div class="col-sm-12 mb-4">
            <h5 class="text-primary font-weight-bold">Other Information</h5>
            <table style="width:100%;">
              <tr>
                <td><p><label><span>Sales Price Code</span></label><br/> <input type="text" class="edittext" name="spcode" id="spcode" value="<?php echo  isset($property_data['sales_price_code']) ? $property_data['sales_price_code'] : '' ?>"></p></td>
                <td><p><label><span>Tax Exemption Code</span></label><br/> <input type="text" class="edittext" name="texcode" id="texcode" value="<?php echo  isset($property_data['tax_exemption_code']) ? $property_data['tax_exemption_code'] : '' ?>"></p></td>
                <td><p><label><span>Fireplace</span></label><br/> <input type="text" class="edittext" name="fireplace" id="fireplace" value="<?php echo  isset($property_data['fireplace']) ? $property_data['fireplace'] : '' ?>"></p></td>
              </tr>
              <tr>
                <td><p><label><span>Owner Occupied</span></label><br/> <input type="text" class="edittext" name="ownerocupied" id="ownerocupied" value="<?php echo  isset($property_data['owner_occupied']) ? $property_data['owner_occupied'] : '' ?>"></p></td>
                <td><p><label><span>Total Market Value</span></label><br/> <input type="text" class="edittext" name="tmv" id="tmv" value="<?php echo  isset($property_data['total_market_value']) ? $property_data['total_market_value'] : '' ?>"></p></td>
              </tr>
            </table>

            <table style="width:100%;">
              <tr>
                <td style="width: 17%;"><p><label><span>Assessed Improvement Value</span></label><br/> <input type="text"  class="edittext" name="aiv" id="aiv" value="<?php echo  isset($property_data['assessed_improvement_value']) ? $property_data['assessed_improvement_value'] : '' ?>"></p></td>
                <td style="width: 34%;"><p><label><span>Assessed Land Value</span></label><br/> <input type="text" style="width: 46%;" class="edittext" name="alv" id="alv" value="<?php echo  isset($property_data['assessed_land_value']) ? $property_data['assessed_land_value'] : '' ?>"><input type="hidden" name="pid" value="<?php echo  isset($_REQUEST['editid']) ? $_REQUEST['editid'] : '' ?>"/></p></td>
              </tr>
            </table>
          </div>

          <div class="col-sm-4">
            <h5 class="text-primary font-weight-bold">Contact Information</h5>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" value="true" id="editRelated" name="editRelated" checked>
              <label class="form-check-label" for="editRelated">
                Edit contact info for related properties
              </label>
            </div>
            <p><label><span>Phone 1</span></label><br/> <input type="text" class="edittext" name="phone1" id="phone1" maxlength="20" value="<?php echo  isset($property_data['phone1']) ? $property_data['phone1'] : '' ?>"></p>

            <p><label><span>Phone 2</span></label><br/> <input type="text" class="edittext" name="phone2" id="phone2" maxlength="20" value="<?php echo  isset($property_data['phone2']) ? $property_data['phone2'] : '' ?>"></p>

            <p><label><span>Email 1</span></label><br/> <input type="text" class="edittext" name="email1" id="email1" maxlength="255" value="<?php echo  isset($property_data['email1']) ? $property_data['email1'] : '' ?>"></p>

            <p><label><span>Email 2</span></label><br/> <input type="text" class="edittext" name="email2" id="email2" maxlength="255" value="<?php echo  isset($property_data['email2']) ? $property_data['email2'] : '' ?>"></p>
          </div>
          <div class="col-4">
            <h5 class="text-primary font-weight-bold">Notes</h5>
            <?php
              $public_note = Property::getPublicNoteForAPN($property_data['parcel_number']);
            ?>
            <textarea class="form-control" id="notes" name="public_note" rows="12"><?php echo $public_note; ?></textarea>
          </div>
          <div class="col-4">
            <h5 class="text-primary font-weight-bold">Private Notes</h5>
            <?php
              $private_note = Property::getPrivateNoteForAPN($user_id, $property_data['parcel_number']);
            ?>
            <textarea class="form-control" id="notes" name="private_note" rows="12"><?php echo $private_note; ?></textarea>
          </div>
        </div>
        <div class="text-center">
          <button data-action="update_property" class="btn btn-primary mb-5">Update</button>
        </div>
      </form>
    </div>
  </div>

  <?php include('includes/confirm_edit_property_modal.php'); ?>
 </body>
</html>
