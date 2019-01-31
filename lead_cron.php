<?php
require_once('config.php');
$getsch=getapnlist();
$curr=date('Y-m-d');
$apnlist=array();
if(count($getsch)>0){
    foreach ($getsch as $key=>$val){

        if(isset($val['data'])){
            $db->schedulelivesearchcron($val['data']);
            $apnlist=$db->result_array();
            updateschdule($val['id'],$apnlist);
        }
        //print_r($val['data']);
    }
}
if(count($apnlist)>0){
    foreach($apnlist as $k=>$v){
        $apn=$v['parcel_number'] ;
        $streetnum=0;
        $streetnam=0;
        $check=ckeckapnproperty($apn);
        updateimportsatus($apn);
        //error_log("==========check============>".print_r($check,true));
        //error_log("==========apn============>".print_r($apn,true));
        if($check==0){
            propertydata($streetnum,$streetnam,$apn);
        }
    }

}else {
    echo "no record found";

}
die();

function propertydata($streetnum,$streetnam,$apn){
    $a=isset($streetnum) ? $streetnum : '';
    $b=isset($streetnam ) ? $streetnam : '';
    $c=isset($apn) ? $apn : '';
    $array= json_decode(gethousingdetail($a,$b,$c));
    updateimportsatus($apn);
    //error_log("=========responsetest===========>".print_r($array,true));
    $impstatus=isset($array->status ) ? $array->status : '';

    if(isset($array->table)){
        $table=json_decode($array->table);
    }
    else {
        $table=array();

    }

    //$table=json_decode($array->table);
    $objmerged =  array_merge((array) $array, (array) $table);
    $totalcount=count($objmerged);

    if($totalcount >0 && $impstatus==1){

        $apn=$objmerged['propertyinfo']->APN;
        $lblCTval=$objmerged['propertyinfo']->lblCTval;
        $Address=$objmerged['propertyinfo']->Address;
        $lblrsuval=$objmerged['propertyinfo']->lblrsuval;
        $exemption=$objmerged['propertyinfo']->exemption;
        $lblYear=$objmerged['propertyinfo']->lblYear;
        $RentOffice=$objmerged['propertyinfo']->RentOffice;
        $lblCodeRegionalAreaval=$objmerged['propertyinfo']->lblCodeRegionalAreaval;
        $lblCDval=$objmerged['propertyinfo']->lblCDval;
        $number_of_units=$objmerged['propertyinfo']->number_of_units;
        insertprodetail($objmerged['propertyinfo']);
        if(isset($objmerged['tbldata'])){
            $tblcount=count($objmerged['tbldata']);

            $casety=array('1'=>'Complaint','2'=>'Systematic Code Enforcement Program','3'=>'Case Management','4'=>'Rent Escrow Account Program','5'=>'Hearing','6'=>'SCEP','8'=>'Home','9'=>'Substandard','10'=>'Property Management Training Program','11'=>'Legal','12'=>'Utility Maintenance Program','13'=>'Emergency','14'=>'Out Reach Case','15'=>'Franchise Tax Board','16'=>'Specialized Enforcement Unit');
            if($tblcount >0){
                foreach($objmerged['tbldata'] as $key=>$val) {
                    $arr=explode("~",$val);

                    $casetype=isset($arr['0']) ? $arr['0'] : '';
                    $casenumber=isset($arr['1']) ? $arr['1'] : '';
                    $casedate=isset($arr['2']) ? $arr['2'] : '';
                    $case_type=array_search($casetype,$casety);
                    insertprocases($casetype,$casenumber,$casedate,$apn);
                    //echo $arr[1]."<br/>";
                    $responsedata=gethousingcasedetail($apn,$casenumber,$case_type);
                    $rescount=count($responsedata);
                    if($rescount > 0 ){

                        $APN=$responsedata['propertyinfo']->APN;
                        $lblSource=$responsedata['propertyinfo']->lblSource;
                        $lblCD=$responsedata['propertyinfo']->lblCD;
                        $lblCodeOfficeContactNo=$responsedata['propertyinfo']->lblCodeOfficeContactNo;
                        $lnkbtnPropAddr=$responsedata['propertyinfo']->lnkbtnPropAddr;
                        $ttlUnits=$responsedata['propertyinfo']->ttlUnits;
                        $lblCT=$responsedata['propertyinfo']->lblCT;
                        $ComplaintNature=$responsedata['propertyinfo']->ComplaintNature;
                        $lblTotalExemptionUnits=$responsedata['propertyinfo']->lblTotalExemptionUnits;
                        $lblCodeOffice=$responsedata['propertyinfo']->lblCodeOffice;
                        $lblHPOZ=$responsedata['propertyinfo']->lblHPOZ;
                        $lblRSU=$responsedata['propertyinfo']->lblRSU;
                        $lblCaseManager=$responsedata['propertyinfo']->lblCaseManager;
                        $imageurl=$responsedata['propertyinfo']->imageurl;
                        $lblCaseNo=$responsedata['propertyinfo']->lblCaseNo;
                        insertprocases_detail($responsedata['propertyinfo']);
                        if(isset($responsedata['casetbldata'])){
                            $ctblcount=count($responsedata['casetbldata']);

                            if($ctblcount >0){

                                foreach($responsedata['casetbldata'] as $ke=>$vl) {

                                    $carr=explode("~",$vl);


                                    $date=isset($carr['0']) ? $carr['0'] : '';
                                    $status=isset($carr['1']) ? $carr['1'] : '';
                                    insertinspection($date,$status,$APN,$lblCaseNo);



                                }


                            }

                        }


                    }
                }

            }
        }
    }

}

function ckeckapnproperty($apn){
    $db = Database::instance();
    $row=$db->select('property_detail', array('apn' => $apn))->count();
    return $row;


}

function updateschdule($id,$apnlist){
    $db = Database::instance();
    $db->update(
        'scheduled_search',
        array( // fields to be updated
            'cstatus' => 1,
            'record'=>serialize($apnlist)


        ),
        array( // 'WHERE' clause
            'id' => $id
            )
        );

    }

    function updateimportsatus($apn){
        $db = Database::instance();
        $db->update(
            'property',
            array( // fields to be updated
                'impstatus' => 0
            ),
            array( // 'WHERE' clause
                'parcel_number' =>$apn
                )
            );

        }

        function getapnlist(){
            $db = Database::instance();
            $db->getapnlistcron();
            $result=$db->result_array();
            return $result;

        }
        function insertinspection($date,$status,$APN,$lblCaseNo){
            $db = Database::instance();
            $db->insert(
                'property_inspection',
                array(
                    'lblCaseNo' => $lblCaseNo,
                    'APN' => $APN,
                    'date' => $date,
                    'staus' => $status
                    )
                );

            }
            function insertprodetail($arr){
                $db = Database::instance();
                $db->insert(
                    'property_detail',
                    array(
                        'apn' =>$arr->APN ,
                        'census_tract'=>$arr->lblCTval,
                        'address' =>$arr->Address ,
                        'rent_registration_number' =>$arr->lblrsuval ,
                        'exemption' =>$arr->exemption ,
                        'year_built' =>$arr->lblYear ,
                        'rentoffice' =>$arr->RentOffice ,
                        'coderegionalaea' =>$arr->lblCodeRegionalAreaval ,
                        'council_district' =>$arr->lblCDval ,
                        'number_of_units' =>$arr->number_of_units

                        )
                    );

                }

                function insertprocases($casetype,$casenumber,$casedate,$apn){
                    $db = Database::instance();
                    $db->insert(
                        'property_cases',
                        array(
                            'case_type' => $casetype,
                            'case_id'=>$casenumber,
                            'APN'=>$apn,
                            'case_date'=>$casedate
                            )
                        );
                    }
                    function insertprocases_detail($arr){
                        $db = Database::instance();
                        $db->insert(
                            'property_cases_detail',
                            array(
                                'apn' => $arr->APN,
                                'case_id' => $arr->lblCaseNo,
                                'case_type' => $arr->lblSource,
                                'council_district' => $arr->lblCD,
                                'ro_contact' => $arr->lblCodeOfficeContactNo,
                                'office_address' => $arr->lnkbtnPropAddr,
                                'total_units' => $arr->ttlUnits,
                                'census_tract' => $arr->lblCT,
                                'complaint_nature'=>$arr->ComplaintNature,
                                'total_exemptionunits' => $arr->lblTotalExemptionUnits,
                                'regional_office' => $arr->lblCodeOffice,
                                'hp_overlay_zone' => $arr->lblHPOZ,
                                'rent_registration' => $arr->lblRSU,
                                'case_manager' => $arr->lblCaseManager,
                                'imageurl' => $arr->imageurl
                                )
                            );
                        }
                        function gethousingcasedetail($a,$b,$c){


                            $command = escapeshellcmd("sudo python /var/www/html/leads/python/case.py $a $b $c" );
                            $command_output = shell_exec($command);
                            $array= json_decode($command_output);
                            // error_log("=========responsecase===========>".print_r($array,true));
                            if(isset($array->casetable)){
                                $table=json_decode($array->casetable);
                            }
                            else {

                                $table=array();

                            }


                            $objmerged =  array_merge((array) $array, (array) $table);
                            return $objmerged;

                        }


                        function gethousingdetail($a,$b,$c){


                            $command = escapeshellcmd("sudo python /var/www/html/leads/python/property.py $a $b $c" );
                            $command_output = shell_exec($command);
                            //error_log("=========responsecase===========>".print_r($array,true));
                            return $command_output;

                        }
                        ?>
