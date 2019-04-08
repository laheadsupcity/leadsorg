<?php
  require_once('config.php');
  require_once('Database.php');
  require_once('FavoriteProperties.php');

  $parcel_number = isset($_REQUEST['apn']) ? $_REQUEST['apn'] : '';
  $det = getpropertydetail($parcel_number);
  $totalcount = count($det);
  $cases = getCasesForProperty($parcel_number);
  $property = getpropertyinfo($parcel_number);
  $imgurl = getimglist($parcel_number);
  $property = $property[0];

  $matching_cases = isset($_REQUEST['matching_cases']) ? explode(',', $_REQUEST['matching_cases']) : '';

  function getimgurl($imgurl)
  {
      $url = explode('http://', $imgurl);
      $newurl=isset($url[1]) ? $url[1] : '';
      return $newurl;
  }

  $det = isset($det[0]) ? $det[0] : '';
  function getcasetypeid($pcid, $caseid)
  {
      $db = Database::instance();
      $db->select('property_cases_detail', array('property_case_id' => $pcid,'case_id'=>$caseid), false, false, 'AND', 'id');
      $result=$db->row_array();
      return $result;
  }

  function getpropertydetail($parcel_number)
  {
      $db = Database::instance();
      $db->select('property_detail', array('APN' => $parcel_number), false, false, '', '*');
      $result=$db->result_array();
      return $result;
  }

  function getCasesForProperty($parcel_number)
  {
      $db = Database::instance();
      $db->select('property_cases', array('APN' => $parcel_number), false, 'pcid desc', '', '*');
      $result=$db->result_array();
      return $result;
  }

  function getimglist($parcel_number)
  {
      $db = Database::instance();
      $db->select('property_cases_detail', array('apn' => $parcel_number), false, false, "", 'imageurl');
      $result=$db->result_array();
      return $result;
  }

  function getpropertyinfo($parcel_number)
  {
      $db = Database::instance();
      $db->select('property', array('parcel_number' => $parcel_number), false, false, '', '*');
      $result=$db->result_array();
      return $result;
  }
?>
<!DOCTYPE html>
<html lang="en" style="font-size: 14px;">
<head>
  <title>PROPERTY INFORMATION</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />

    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="js/myscr.js"></script>
    <style>
  .active5{background:#337ab7!important;}
  .caselist tr:nth-child(odd){background-color:#fff}
    .caselist tr:nth-child(even){background-color:#f2f2f2}
  .slideshow-container {
    max-width: 1000px;
    position: relative;
    margin: auto;
  }
  .prev{
    cursor: pointer;
    position: absolute;
    top:99%;
    left:25%;
    width: auto;
    padding: 16px;
    margin-top: -22px;
    color: #337ab7;
    font-weight: bold;
    font-size: 35px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
  }
   .next {
    cursor: pointer;
    position: absolute;
    top:99%;
    right:25%;
    width: auto;
    padding: 16px;
    margin-top: -22px;
    color: #337ab7;
    font-weight: bold;
    font-size: 35px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
  }
  .mySlides{margin-bottom:35px;}
  .prev img, .next img {width:50px;}
  .prev:hover, .next:hover {
    text-decoration:none;
    color:#337ab7;
  }
  .text {
    color: #f2f2f2;
    font-size: 15px;
    padding: 8px 12px;
    position: absolute;
    bottom: 8px;
    width: 100%;
    text-align: center;
  }
  .numbertext {
    color: #f2f2f2;
    font-size: 12px;
    padding: 8px 12px;
    position: absolute;
    top: 0;
  }
  .dot {
     display:none;
    cursor: pointer;
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    transition: background-color 0.6s ease;
  }
  .active1, .dot:hover {
    background-color: #717171;
  }
  </style>
</head>
<body>
<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>

  <?php

    $favorites = new FavoriteProperties();
    $favorites->markPropertyAsSeen(
      $_SESSION['userdetail']['id'],
      $parcel_number
    );

  ?>

  <div class="scr1 d-flex flex-wrap" style="border:1px solid #fff; height:auto;">
    <?php if ($totalcount > 0) { ?>
    <div class="col-sm-4" style="padding:0px 30px 0 0;">
        <div style="border:1px solid #337ab7; margin:10px 0 0;">
          <h4 style="text-transform:initial;">Property Address</h4>
            <table cellspacing="5" style="width:100%; margin:0 auto;">
              <tr>
              <td class="field1">Assessor Parcel Number:</td>
              <td class="field1data"><?php echo $property['parcel_number']; ?></td>
              </tr>
              <tr>
              <td class="field1">Official Address:</td>
              <td class="field1data"><?php echo $det['address']; ?></td>
              </tr>
              <tr>
              <td class="field1">Council District:</td>
              <td class="field1data"><?php echo $det['council_district']; ?></td>
              </tr>
            </table>
        </div>

        <div style="border:1px solid #337ab7; margin:10px 0 0;">
          <h4 style="text-transform:initial;">Property Details </h4>
          <table cellspacing="5" style="width:100%; margin:0 auto;">
              <tr>
              <td class="field1">Total Units:</td>
              <td class="field1data"><?php echo $det['number_of_units']; ?></td>
              </tr>
              <tr>
              <td class="field1">Total Exemption Units:</td>
              <td class="field1data"><?php echo $det['exemption']; ?></td>
              </tr>
              <tr>
              <td class="field1">Bedrooms:</td>
              <td class="field1data spdata"><?php echo $property['bedrooms']; ?></td>
              </tr>
              <tr>
              <td class="field1">Bathrooms</td>
              <td class="field1data spdata"><?php echo $property['bathrooms']; ?></td>
              </tr>
              <td class="field1">Number of Stories:</td>
              <td class="field1data spdata"><?php echo $property['number_of_stories']; ?></td>
              </tr>
              <tr>
              <td class="field1">Lot Area Sqft:</td>
              <td class="field1data spdata"><?php echo $property['lot_area_sqft']; ?></td>
              </tr>
              <tr>
              <td class="field1">Pool:</td>
              <td class="field1data spdata"><?php echo $property['pool']; ?></td>
              </tr>
              <tr>
              <td class="field1">Year Built:</td>
              <td class="field1data"><?php echo isset($property['year_built']) ? $property['year_built']:''; ?></td>
              </tr>
              <tr>
              <td class="field1">Zoning:</td>
              <td class="field1data spdata"><?php echo $property['zoning']; ?></td>
              </tr>
            </table>
        </div>

        <div style="border:1px solid #337ab7; margin:10px 0 0;">
          <h4 style="text-transform:initial;">Other Details</h4>
          <table cellspacing="5" style="width:100%; margin:0 auto;">
              <tr>
              <td class="field1">Use Code:</td>
              <td class="field1data spdata"><?php echo $property['use_code']; ?></td>
              </tr>
              <tr>
              <td class="field1">Use Code Description:</td>
              <td class="field1data spdata"><?php echo $property['use_code_descrition']; ?></td>
              </tr>
              <tr>
              <td class="field1">Building Area:</td>
              <td class="field1data spdata"><?php echo $property['building_area']; ?></td>
              </tr>
              <tr>
              <td class="field1">Code Regional Area:</td>
              <td class="field1data"><?php echo $det['coderegionalaea']; ?></td>
              </tr>
              <tr>
              <td class="field1">Rent Office ID:</td>
              <td class="field1data"><?php echo $det['rentoffice']; ?></td>
              </tr>
              <tr>
              <td class="field1">Rent Registration Number:</td>
              <td class="field1data"><?php echo $det['rent_registration_number']; ?></td>
              </tr>
              <tr>
              <td class="field1">Census Tract:</td>
              <td class="field1data"><?php echo $det['census_tract']; ?></td>
              </tr>
            </table>
        </div>
        <a class="btn btn-primary mt-2 mb-4" href="lead_update_customtask.php?editid=<?php echo $property['id']; ?>" target="_blank">Edit Property</a>
    </div>

    <div class="col-sm-4">
      <div style="border:1px solid #337ab7; margin:10px 0 0;">
        <h4 style="text-transform:initial;">Owners Info</h4>
        <table cellspacing="5" style="width:100%; margin:0 auto;">
          <tr>
            <td class="field1">Owners Name:</td>
            <td class="field1data spdata"><?php echo $property['owner_name2']; ?></td>
          </tr>
          <tr>
            <td class="field1">Phone 1:</td>
            <td class="field1data"><?php echo $property['phone1']; ?></td>
          </tr>
          <tr>
            <td class="field1">Phone 2:</td>
            <td class="field1data spdata"><?php echo $property['phone2']; ?></td>
          </tr>
          <tr>
            <td class="field1">Email 1:</td>
            <td class="field1data"><?php echo $property['email1']; ?></td>
          </tr>
          <tr>
            <td class="field1">Email 2:</td>
            <td class="field1data spdata"><?php echo $property['email2']; ?></td>
          </tr>
        </table>
      </div>

        <div style="border:1px solid #337ab7; margin:10px 0 0;">
          <h4 style="text-transform:initial;">Photos</h4>
            <div class="slideshow-container">
              <?php
              $defaultimage="images/No_Image.jpg";
      if (count($imgurl) > 0) {
          foreach ($imgurl as $key) {
              $cimge= getimgurl($key['imageurl']); ?>
                <div class="mySlides">
                 <img src="<?php echo isset($cimge) ? '//'.$cimge : $defaultimage ?>" style="width:100%;">
                </div>
              <?php
          }
      } else {
          ?>

              <div class="mySlides">
                 <img src="<?php  echo $defaultimage; ?>" style="width:100%;">
                </div>
              <?php
      } ?>
              <a class="prev" onclick="plusSlides(-1)"><img src="images/leftarrow.png"></a>
              <a class="next" onclick="plusSlides(1)"> <img src="images/rightarrow.png"></a>
            </div>

            <div style="text-align:center; display:none;">
              <?php
              foreach ($imgurl as $key) {
                  $cimge= getimgurl($key['imageurl']); ?>
                  <span class="dot" onclick="currentSlide()"></span>
              <?php
              } ?>
            </div>
        </div>
    </div>

    <div class="col-sm-4" style="padding:0px 0px 0 30px;">
        <div style="border:1px solid #337ab7; margin:10px 0 0; min-height:50px;">
          <h4 style="text-transform:initial;">Owners Mailing Address </h4>
          <table cellspacing="5" style="width:100%; margin:0 auto;">
              <tr>
              <td class="field1">Address:</td>
              <td class="field1data spdata">
              <?php echo $property['full_mail_address'];
      echo "<br/>";
      echo $property['mail_address_city_state'];
      echo "<br/>" ;
      echo $property['mail_address_zip']; ?>
              </td>
              </tr>
            </table>
        </div>

        <div style="border:1px solid #337ab7; margin:10px 0 0;">
          <h4 style="text-transform:initial;">Sales History</h4>
          <table cellspacing="5" style="width:100%; margin:0 auto;">
              <tr>
              <td class="field1">Last sale date:</td>
              <td class="field1data spdata"><?php  echo isset($property['sales_date']) ? date('m/d/Y', strtotime($property['sales_date'])): ''; ?></td>
              </tr>
              <tr>
              <td class="field1">Sales Price:</td>
              <td class="field1data spdata"><?php echo $property['sales_price']; ?></td>
              </tr>
              <tr>
              <td class="field1">Cost per Sqft:</td>
              <td class="field1data spdata"><?php echo $property['cost_per_sq_ft']; ?></td>
              </tr>
            </table>
        </div>

        <div style="border:1px solid #337ab7; margin:10px 0 0;">
          <h4 style="text-transform:initial;">Value</h4>
          <table cellspacing="5" style="width:100%; margin:0 auto;">
              <tr>
              <td class="field1">Total Assessed Value:</td>
              <td class="field1data spdata"><?php echo $property['total_assessed_value']; ?></td>
              </tr>
              <tr>
              <td class="field1">Assessed Land Value:</td>
              <td class="field1data spdata"><?php echo $property['assessed_land_value']; ?></td>
              </tr>
              <tr>
              <td class="field1">Assessed improvement Value:</td>
              <td class="field1data spdata"><?php echo $property['assessed_improvement_value']; ?></td>
              </tr>
            </table>
        </div>
                               <?php
           $upstatus=isset($det['update_status']) ? $det['update_status'] : '';
      if ($upstatus >0) {
          ?>
        <div style="border:1px solid #337ab7; margin:10px 0 0;">
        <h4 style="text-transform:initial;">Date</h4>
        <table cellspacing="5" style="width:100%; margin:0 auto;">
            <tr>
          <td class="field1">Recent Update Date:</td>
          <td class="field1data spdata"><?php echo date('m/d/Y H:i A', strtotime($det['date_modified'])); ?></td>
          </tr>
        </table>
        </div>
        <div style="border:1px solid #337ab7; margin:10px 0 0;">
          <h4 style="text-transform:initial;">Notes</h4>
          <div class="field1data spdata" style="width:100%; height: 118px; overflow-y: scroll;">
            <?php echo $property['notes']; ?>
          </div>
        </div>
     <?php } ?>
    </div>
    <div class="w-100">
      <h4>PROPERTY CASE</h4>
      <p style="margin:15px 0; color:#333;">Please click on a Case Number to view&nbsp;"Property Activity Report"</p>
        <div class="d-flex">
          <div class="col-sm-5 caselist" style="padding:0 20px 0 0;">
            <table cellpadding="10" style="width:100%; margin:0 auto; border:1px solid #337ab7; font-size:12px;">
              <tr style="background: #337ab7; color:#fff;">
              <td style='padding:3px 5px; border-right:1px solid #fff;'>Case Type</td>
              <td style='padding:3px 5px; border-right:1px solid #fff;'>Case Number</td>
              <td style='padding:3px 5px;'>Date Closed</td>
              </tr>
              <?php
              foreach ($cases as $row) {
                  $caseid=getcasetypeid($row['pcid'], $row['case_id']);
                  $case_start_date = date_create($row['date_modified']);
              ?>
                <tr style='color:#333; <?php if (in_array($row["pcid"], $matching_cases)) { ?>background-color: #fcf8e3;<?php } ?>'>
                  <td style='border-bottom:1px solid #337ab7; border-right:1px solid #337ab7; padding:3px 5px;'><?php echo $row['case_type']; ?></td>
                  <td style='border-bottom:1px solid #337ab7; border-right:1px solid #337ab7; padding:3px 5px;'>
                    <a href='#' onclick='return opencasedetail(<?php echo $parcel_number; ?>,<?php echo $row['case_id']; ?>,<?php echo $caseid['id']; ?>);'  style='color: DarkBlue;'><?php echo $row['case_id']; ?></a>
                  </td>
                  <td style='padding:3px 5px; border-bottom:1px solid #337ab7;'><?php echo $row['case_date']; ?></td>
                </tr>
              <?php } ?>
            </table>
          </div>

          <div class="col-sm-7 casedata">
            <!-- Insert case data-->
          </div>
        </div>
                         <?php
  } else {
      ?>
<div style="padding: 15px;border:1px solid #337ab7;">
                <center style="color:red;" > This property is not available in this system </center>
</div>
                <?php
  } ?>
    </div>
  </div>

  <script type="text/javascript">
  var slideIndex1 = 0;
  showSlides1();

  function showSlides1() {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    for (i = 0; i < slides.length; i++) {
       slides[i].style.display = "none";
    }
    slideIndex1++;
    if (slideIndex1 > slides.length) {slideIndex1 = 1}
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex1-1].style.display = "block";
    dots[slideIndex1-1].className += " active";
    setTimeout(showSlides1, 3000); // Change image every 2 seconds
  }

  var slideIndex = 1;
  showSlides(slideIndex);

  function plusSlides(n) {
    showSlides(slideIndex += n);
  }

  function currentSlide(n) {
    showSlides(slideIndex = n);
  }

  function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
  }
</script>
</body>
</html>
