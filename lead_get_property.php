<?php
  require_once('config.php');
  require_once('CustomDatabaseSearch.php');
?>
<!doctype html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/moment.js"></script>
  <script src="js/jquery.datetimepicker.full.min.js"></script>
  <script type="text/javascript" src="js/multiselect.js"></script>
  <script type="text/javascript" src="js/myscr.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .btn-block {display: inline !important;width: auto;background:#0070c0; color:#fff;padding:5px;}
    .btn-block+.btn-block { margin-top: 0px!important;}
    .active1{background:#337ab7!important;}
    .menu{margin:0 auto 0px!important;}
    .pactive{background:#ddd!important; color:#000!important;}
    tr{background-color: #fff;}
    tr:nth-child(even) {background-color: #e9ebf5;}
    .disabled {
        pointer-events: none;
        cursor: default;
    }
  </style>
  <meta name="format-detection" content="telephone=no"/>
</head>
<body>
  <div style="width:100%; float:left; margin:0;">
    <?php
      include('nav.php');
      if (isset($_GET['searchid'])) {
          unset($_SESSION['SearchFormData']);
          $num_units_min=isset($_GET['num_units_min']) ? $_GET['num_units_min'] : '';
          $num_units_max=isset($_GET['num_units_max']) ? $_GET['num_units_max'] : '';

          $zip=isset($_GET['zip_codes']) ? $_GET['zip_codes'] : '';
          $cities=isset($_GET['cities']) ? $_GET['cities'] : '';
          $zoning_to=isset($_GET['zoning']) ? $_GET['zoning'] : '';
          $tax_exemption_codes=isset($_GET['tax_exemption_codes']) ? $_GET['tax_exemption_codes'] : '';

          $num_bedrooms_min=isset($_GET['num_bedrooms_min']) ? $_GET['num_bedrooms_min'] : '';
          $num_bedrooms_max=isset($_GET['num_bedrooms_max']) ? $_GET['num_bedrooms_max'] : '';

          $num_baths_min=isset($_GET['num_baths_min']) ? $_GET['num_baths_min'] : '';
          $num_baths_max=isset($_GET['num_baths_max']) ? $_GET['num_baths_max'] : '';

          $num_stories_min=isset($_GET['num_stories_min']) ? $_GET['num_stories_min'] : '';
          $num_stories_max=isset($_GET['num_stories_max']) ? $_GET['num_stories_max'] : '';

          $cost_per_sq_ft_min=isset($_GET['cost_per_sq_ft_min']) ? $_GET['cost_per_sq_ft_min'] : '';
          $cost_per_sq_ft_max=isset($_GET['cost_per_sq_ft_max']) ? $_GET['cost_per_sq_ft_max'] : '';

          $lot_area_sq_ft_min=isset($_GET['lot_area_sq_ft_min']) ? $_GET['lot_area_sq_ft_min'] : '';
          $lot_area_sq_ft_max=isset($_GET['lot_area_sq_ft_max']) ? $_GET['lot_area_sq_ft_max'] : '';

          $sales_price_min=isset($_GET['sales_price_min']) ? $_GET['sales_price_min'] : '';
          $sales_price_max=isset($_GET['sales_price_max']) ? $_GET['sales_price_max'] : '';

          $year_built_min=isset($_GET['year_built_min']) ? $_GET['year_built_min'] : '';
          $year_built_max=isset($_GET['year_built_max']) ? $_GET['year_built_max'] : '';

          $sales_date_from=isset($_GET['sales_date_from']) ? $_GET['sales_date_from'] : '';
          $sales_date_to=isset($_GET['sales_date_to']) ? $_GET['sales_date_to'] : '';

          $is_owner_occupied=isset($_GET['is_owner_occupied']) ? $_GET['is_owner_occupied'] : '';

          $case_types = isset($_GET['case_types']) ? $_GET['case_types'] : '';

          $search_params=array(
            'num_units_min'=>$num_units_min,
            'num_units_max'=>$num_units_max,
            'zip'=>$zip,
            'city'=>$cities,
            'zoning'=>$zoning_to,
            'exemption'=>$tax_exemption_codes,
            'casetype'=>$case_types,
            'num_bedrooms_min'=>$num_bedrooms_min,
            'num_bedrooms_max'=>$num_bedrooms_max,
            'num_baths_min'=>$num_baths_min,
            'num_baths_max'=>$num_baths_max,
            'num_stories_min'=>$num_stories_min,
            'num_stories_max'=>$num_stories_max,
            'cost_per_sq_ft_min'=>$cost_per_sq_ft_min,
            'cost_per_sq_ft_max'=>$cost_per_sq_ft_max,
            'lot_area_sq_ft_min'=>$lot_area_sq_ft_min,
            'lot_area_sq_ft_max'=>$lot_area_sq_ft_max,
            'sales_price_min'=>$sales_price_min,
            'is_owner_occupied'=>$is_owner_occupied,
            'sales_price_max'=>$sales_price_max,
            'year_built_min'=>$year_built_min,
            'year_built_max'=>$year_built_max,
            'sales_date_from'=>$sales_date_from,
            'sales_date_to'=>$sales_date_to
          );

          $_SESSION['SearchFormData']  = $search_params;
      } elseif (isset($_SESSION['SearchFormData'])) {
          $search_params = $_SESSION['SearchFormData'];
      } else {
          header("LOCATION:lead_customdatabase_search.php");
      }

      $start_from = $offset = null;
      $page = isset($_GET["page"])?$_GET["page"]:1;
      $num_rec_per_page = isset($_REQUEST['num_rec_per_page'])?$_REQUEST['num_rec_per_page']:100;

      $searcher = new CustomDatabaseSearch($search_params);
      $result = $searcher->getResults();
      $total_records = $searcher->getResultCount();
    ?>
  </div>
  <h1 style="text-align:center; float:left; width:100%; margin:-10px 0 0px;">Search Results</h1>
  <div class="scr1" style="height:auto;">
    <?php  if ($total_records > 0) {
        ?>
    <div style="float:left; width:100%; padding:10px;">
      <div style="float:left; width:45%;">
        <p style="margin:0; font-weight:600; font-size:12px;">Total Records: <?php echo $total_records; ?></p>
      </div>
      <div style="float:right; width:45%; text-align:right; font-size:12px; font-weight:600;">
        <form id ='perpgform1'  style="width: 80px;float: right;">
          <input type='text' id='num_rec_per_page1' name="num_rec_per_page" style="padding:2px;width: 65px;height: 21px;<?= isset($_GET['otherfrm'])?'display:block':'display:none' ?>;float:right;" value='<?= isset($num_rec_per_page)?$num_rec_per_page:'' ?>'/>
          <input type="hidden" class='pagecl' name="page" value="<?php echo isset($_GET["page"])?$_GET["page"]:1; ?>">
          <input type='hidden' name='otherfrm' value='other'/>
        </form>
        <form id ='perpgform'  style="width: 80px;float: right;">
          <select id='num_rec_per_page' name="num_rec_per_page" style="padding:2px;">
          <option value=''>Select</option>
          <option <?php echo ($num_rec_per_page==10)?"selected='selected'":''; ?> value='10'>10</option>
          <option <?php echo ($num_rec_per_page==25)?"selected='selected'":''; ?> value='25'>25</option>
          <option <?php echo ($num_rec_per_page==50)?"selected='selected'":''; ?> value='50'>50</option>
          <option <?php echo ($num_rec_per_page==100)?"selected='selected'":''; ?> value='100'>100</option>
          <option <?php echo ($num_rec_per_page==250)?"selected='selected'":''; ?> value='250'>250</option>
          <option <?php echo ($num_rec_per_page==500)?"selected='selected'":''; ?> value='500'>500</option>
          <option <?php echo ($num_rec_per_page==1000)?"selected='selected'":''; ?> value='1000'>1000</option>
          <option <?php echo ($num_rec_per_page==5000)?"selected='selected'":''; ?> value='5000'>5000</option>
          <option <?= isset($_GET['otherfrm'])?"selected='selected'":'' ?> value='Other'>Other</option>
          </select>
          <input type="hidden" class='pagecl' name="page" value="<?php echo isset($_GET["page"])?$_GET["page"]:1; ?>">
        </form>
        <span style='float:right;'>No. of Records Per Page :</span>
      </div>
    </div>

    <table class="table table-fixed" style="margin:0;">
      <thead>
        <tr style="background:#337ab7!important;">
          <th class="lf0"><input type="checkbox" id="checkAll" name="all" style="width:15px; height:15px; top:1px; position:relative;"></th>
          <!--<th>Last Search Date</th>-->
          <th class="lf2">Parcel Number</th>
          <th class="lf2">Address</th>
          <th class="lf2">Owner Name</th>
          <th class="lf0">#Units</th>
          <th class="lf0">#Stories</th>
          <th class="lf0">#Bed</th>
          <th class="lf0">#Bath</th>
          <th class="lf1">Lot SQFT</th>
          <th class="lf1">Cost Per SQFT</th>
          <th class="lf1">Year Built</th>
          <th class="lf2">Sale Date</th>
          <th class="lf1">Sale Price</th>
          <th class="lf1">Options</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $start_from = ($page-1) * $num_rec_per_page;
        $active="pactive";
        $deactive="pdeactive";
        $total_pages = ceil($total_records / $num_rec_per_page);

        if ($num_rec_per_page) {
            $goto_1st_pg  = "<a href='?num_rec_per_page=$num_rec_per_page&page=1' style='text-decoration:none'>".'First'."</a> "; // Goto 1st page
        } else {
            $goto_1st_pg  = "<a href='?page=1' style='text-decoration:none'>".'First'."</a> "; // Goto 1st page
        }

        $prepg = ($page == '1')?'1':($page - 1);
        $previous = "<a href='?num_rec_per_page=$num_rec_per_page&page=".$prepg."' style='text-decoration:none'>&laquo;</a> ";
        $nxtpg = ($page == $total_pages)?$total_pages:($page + 1);
        $next = "<a href='?num_rec_per_page=$num_rec_per_page&page=".$nxtpg."' style='text-decoration:none'>&raquo;</a> ";

        $goto_pg_no = null;
        for ($j = $page - 3 ; $j <= $page + 3 ; $j++) {
            if ($j > 0 && $j <= $total_pages) {
                $goto_pg_no .=  "<a href='?page=".$j."&num_rec_per_page=".$num_rec_per_page."' style='text-decoration:none' class=".($j == $page ? $active : $deactive)." >".$j."</a> ";
            }
        };
        if ($num_rec_per_page) {
            $goto_last_pg = "<a href='?num_rec_per_page=$num_rec_per_page&page=$total_pages' style='text-decoration:none'>".'Last'."</a> "; // Goto last page
        } else {
            $goto_last_pg = "<a href='?page=$total_pages' style='text-decoration:none'>".'Last'."</a> "; // Goto last page
        }
        $i = $start_from;
        foreach ($result as $key=>$val) {
            ?>
        <tr>
          <td class="lf0"><input type="checkbox" class="apncheck chk"  value="<?php echo $val['parcel_number']; ?>" style="width:15px; height:15px; top:1px; position:relative;"/></td>
          <!--<td><?//php echo date("m-d-Y");?></td>-->
          <td class="lf2"><?php echo $val['parcel_number']; ?></td>
          <td class="lf2"><?php echo $val['street_number'].','.$val['street_name'].',<br/> '.$val['site_address_city_state'].',
 '.$val['site_address_zip']; ?></td>
          <td class="lf2"><?php echo $val['owner_name2']; ?></td>
          <td class="lf0"><?php echo $val['number_of_units']; ?></td>
          <td class="lf0"><?php echo $val['number_of_stories']; ?></td>
          <td class="lf0"><?php echo $val['bedrooms']; ?></td>
          <td class="lf0"><?php echo $val['bathrooms']; ?></td>
          <td class="lf1" style="text-align:right;"><?php echo $val['lot_area_sqft']; ?></td>
          <td class="lf1" style="text-align:right;"><?php echo $val['cost_per_sq_ft']; ?></td>
          <td class="lf1" style="text-align:right;"><?php echo $val['year_built']; ?></td>
          <td class="lf2" style="text-align:center;">
            <?php
              if ($val['sales_date']!='0000-00-00') {
                  echo date('m/d/Y',
 strtotime($val['sales_date']));
              } else {
                  echo "";
              } ?>
          </td>
          <td class="lf1" style="text-align:right;"><?php echo $val['sales_price']; ?></td>
          <td class="lf1" style="text-align:right;"><a target="_blank" href="lead_property_detail.php?apn=<?php echo $val['parcel_number']; ?>" style="font-size:12px; font-weight:600;">View</a>&nbsp;|&nbsp;<a href="lead_update_customtask.php?editid=<?php echo $val['id']; ?>" style="font-size:12px; font-weight:600;" target="_blank">Edit</a></td>
        </tr>
          <?php $i++;
        } ?>
      </tbody>
    </table>
    <!--<p style='text-align:center;'>Showing <?php echo $start_from; ?> to <?php echo $page*$num_rec_per_page-1; ?> of <?php echo $total_records; ?> entries</p>-->
    <!--<p style='text-align:center;'><?php echo $goto_1st_pg.$goto_pg_no.$goto_last_pg."&emsp;<span style='color:#0066CC'>[ PAGE : ".$page." ]</span>"; ?></p>-->
    <p class="pageno" style='text-align:center; font-size:14px; padding:15px 0 5px;'><span style='color:#333; font-size:14px; font-weight:500;'>Page <?php echo $page; ?> of <?php echo $total_pages; ?> &nbsp;</span> <?php echo $goto_1st_pg.$previous.$goto_pg_no.$next.$goto_last_pg. "&emsp;"; ?></p>
    <?php
    } else {
        ?>
    <table class="table table-fixed" style="margin:0;">
      <thead>
        <tr style="background:#337ab7!important;">
          <th class="lf0"><input type="checkbox" id="checkAll" name="all" style="width:15px; height:15px; top:1px; position:relative;"></th>
          <!--<th>Last Search Date</th>-->
          <th class="lf2">Parcel Number</th>
          <th class="lf2">Address</th>
          <th class="lf2">Owner Name</th>
          <th class="lf0">#Units</th>
          <th class="lf0">#Stories</th>
          <th class="lf0">#Bed</th>
          <th class="lf0">#Bath</th>
          <th class="lf1">Lot SQFT</th>
          <th class="lf1">Cost Per SQFT</th>
          <th class="lf1">Year Built</th>
          <th class="lf2">Sale Date</th>
          <th class="lf1">Sale Price</th>
          <th class="lf1">Options</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="15" style="text-align:center;">No record found</td>
        </tr>
      </tbody>
    </table>
    <?php
    } ?>
  </div>
  <div class="scr6" style="border:none; margin:-10px auto 0">
        <input type="hidden" value="" id="ckeckvall" />
    <div style="float:none; margin:0px auto; width:100%; padding:5px;" >
      <button type="submit" id="batch" class="btn btn-block">Create Lead batch </button>&nbsp;
      <button type="submit" id="expcsvbtn" class="btn btn-block">Export selected</button>
    </div>
  </div>
  <div id="overlay">
      <div id="batchform">
       <div class="closeicon"><a href="#" class="closeimg"><img src='images/close.png'></a></div>
        <form method="post" action="#">
          <label style="color:#000;">Batch Name</label><br/><input type="text" name="batchname" id="batchname" placeholder="Enter Batch Name">
          <button type="submit" id="batchsubmit" class="btn btn-block leadbatch" style="padding:5px 10px;">Submit</button> &nbsp; <button type="submit" id="closebtn" class="btn btn-block" style="padding:5px 10px;">Close</button>
          <p style="text-align:left; padding:10px; color:green; display:none;" class="succmsg">Batch created successfully</p>
          <p style="text-align:left; padding:10px; color:red; display:none;" class="errormsg">Please enter batch name</p>
        </form>
      </div>
    </div>
  </div>
 </body>
</html>

<script>
  $(document).ready(function(){
    var totrec = <?= $total_records ?>;

    var currpg = <?= (isset($_GET['page']))?$_GET['page']:1 ?>

    var num_rec_per_page = <?= (isset($_GET['num_rec_per_page']))?$_GET['num_rec_per_page']:10 ?>;
        if($("#num_rec_per_page option[value='"+num_rec_per_page+"']").length != 0)
        {

        }else{
      $("#num_rec_per_page").val('Other');
            $("#num_rec_per_page1").val(num_rec_per_page);
      $("#num_rec_per_page1").css('display',
'block');
    }

    function chk(val){
      total_pages = Math.ceil(totrec / val);

      if(total_pages < currpg){
        $(".pagecl").val(total_pages);
        return false;
      }
      return true;
    }
    $('#num_rec_per_page').change(function(){
      var val = this.value;
      if(val == 'Other'){
        $("#num_rec_per_page1").val('');
        $("#num_rec_per_page1").css('display',
'block');
      }else{
        if(val != ''){
          var chkflag = chk(val);
          $('#perpgform').submit();
        }
      }
    });
    $('#num_rec_per_page1').blur(function(){
      var val = this.value;
      if(val != ''){
        var chkflag = chk(val);
        $('#perpgform1').submit();
      }
    });
  });
</script>
