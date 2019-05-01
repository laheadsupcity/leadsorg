<?php
    require_once('config.php');
    require_once('Database.php');
    $apn=isset($_POST['apn']) ? $_POST['apn'] : '';
    $case_id=isset($_POST['case_id']) ? $_POST['case_id'] : '';
    $case_det_id=isset($_POST['case_det_id']) ? $_POST['case_det_id'] : '';
    $case_inspections_with_updates = isset($_POST['case_inspections_with_updates']) ? $_POST['case_inspections_with_updates'] : array();

    function getcasedetail($apn, $case_id)
    {
        $db = Database::instance();
        $db->select('property_cases_detail', array('APN' => $apn,'case_id'=>$case_id), false, false, 'AND', '*');
        $result=$db->result_array();
        return $result;
    }
    function getcaselist($apn, $case_id)
    {
        $db = Database::instance();
        $db->select('property_cases', array('APN' => $apn,'case_id'=>$case_id), false, false, 'AND', '*');
        $result=$db->result_array();
        return $result;
    }

    function getCaseInspection($apn, $case_id, $case_det_id)
    {
        $db = Database::instance();
        $db->select(
          'property_inspection',
          array(
            'APN' => $apn,
            'lblCaseNo'=> $case_id ,
            'property_case_detail_id'=>$case_det_id
          ),
          false,
          'STR_TO_DATE(`date`, "%m/%d/%Y %h:%i:%s %p") DESC, staus',
            'AND',
            '*'
        );
        $result=$db->result_array();
        return $result;
    }

    $det=getcasedetail($apn, $case_id);
    $case=getcaselist($apn, $case_id);
    $case_statuses=getCaseInspection($apn, $case_id, $case_det_id);

    $det =isset($det[0]) ?  $det[0]: '';
    $url=isset($det['imageurl']) ?  $det['imageurl']: '';
    $img = explode('http://', $url);
?>
<div class="col-sm-12" style="padding:0;">
	<h4>PROPERTY ACTIVITY REPORT</h4>
	<table cellspacing="5" style="width:100%; margin:0 auto;">
		<tr>
		<td class="field2">Assessor Parcel Number:</td>
		<td class="field2data"><?php echo $det['apn']; ?></td>
		<td class="field2">Official Address:</td>
		<td class="field2data"><?php echo $det['office_address']; ?></td>
		</tr>
		<tr>
		<td class="field2">Council District:</td>
		<td class="field2data"><?php echo $det['council_district']; ?></td>
		<td class="field2">Case Number:</td>
		<td class="field2data"><?php echo $det['case_id']; ?></td>
		</tr>
		<tr>
		<td class="field2">Census Tract:</td>
		<td class="field2data"><?php echo $det['census_tract']; ?></td>
		<td class="field2">Case Type:</td>
		<td class="field2data"><?php echo $case[0]['case_type']; ?></td>
		</tr>
		<tr>
		<td class="field2">Rent Registration:</td>
		<td class="field2data"><?php echo $det['rent_registration']; ?></td>
		<td class="field2">Inspector:</td>
		<td class="field2data"><?php echo  $det['inspector']; ?></td>
		</tr>
		<tr>
		<td class="field2">Historical Preservation Overlay Zone:</td>
		<td class="field2data"><?php echo $det['hp_overlay_zone']; ?></td>
		<td class="field2">Case Manager:</td>
		<td class="field2data"><?php echo $det['case_manager']; ?></td>
		</tr>
		<tr>
		<td class="field2">Total Units:</td>
		<td class="field2data"><?php echo $det['total_units']; ?></td>
		<td class="field2">Total Exemption Units:</td>
		<td class="field2data"><?php echo $det['total_exemptionunits']; ?></td>
		</tr>
		<tr>
		<td class="field2">Regional&nbsp;Office:</td>
		<td class="field2data"><?php echo $det['regional_office']; ?></td>
		<td class="field2">Regional Office Contact:</td>
		<td class="field2data"><?php echo $det['ro_contact']; ?></td>
		</tr>
		<tr>
		<td class="field2">Nature of Complaint:</td>
		<td class="field2data" colspan="4"><?php echo $det['complaint_nature']; ?></td>
		</tr>
	</table>
</div>

<div class="d-flex mt-2">
	<div class="col-sm-7 property-inspection-case-list p-0">
		<table cellspacing="5" style="width:100%; margin:0 auto; border:1px solid #337ab7; font-size:12px;">
			<tr class="text-white font-weight-bold bg-primary p-1">
				<td class="pl-1">Date</td>
				<td class="pl-1">Status</td>
			</tr>
			<?php
      foreach ($case_statuses as $case_status) {
        $has_unseen_updates = in_array($case_status['id'], $case_inspections_with_updates);
      ?>
        <tr class="<?php if ($has_unseen_updates) {?>has-unseen-updates<?php } ?>">
          <td align='left' style='width:40%; border-right:1px solid #337ab7; border-bottom:1px solid #337ab7; padding:3px 5px;'>
            <?php echo $case_status['date']; ?>
          </td>
          <td align='left' style='width:60%; border-bottom:1px solid #337ab7; padding:3px 5px;'>
            <?php echo $case_status['staus']; ?>
          </td>
        </tr>
      <?php } ?>
		</table>
	</div>

	<div class="col-sm-5" style="padding:0 0 0 15px;">
	<?php $casedefaultimage="images/No_Image.jpg"; ?>
	<p> <img id="MainContent_Image2" src="<?php echo isset($img[1]) ? '//'.$img[1] : $casedefaultimage ?>" style="color:#FFFF80; height:auto; width:100%;"> </p>
	</div>
</div>
