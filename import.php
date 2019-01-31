<?php  

require_once('config.php');

$db = Database::instance();

$path = 'uploads/'; 

error_log("=================>".print_r($_FILES,true));

if(!empty($_FILES["import_list"]["name"]))  

{  

	$output = '';  

	$allowed_ext = array("csv");  

	$extension = end(explode(".", $_FILES["import_list"]["name"]));

	$created_date = date('Y-m-d H:i:s'); 

    if(in_array($extension, $allowed_ext))  

    {  

		$file_data = fopen($_FILES["import_list"]["tmp_name"], 'r');  

		fgetcsv($file_data);  

		$incount=0;

		$ducount=0;

        while($row = fgetcsv($file_data))  

        {  

			$rcount=checkapncount($row[0]);

            if($rcount==0){

				$incount++;

				$db->insert(

					'property',

					array(		

						'parcel_number'=> $row[0],

						'street_number'=> $row[1],

						'street_name'=> $row[2],

						'site_address_zip'=> getzipid($row[3]),

						'site_address_city_state'=> $row[4],

						'owner_name2'=> $row[5],

						'owner1_first_name'=> $row[6],

						'owner1_middle_name'=> $row[7],

						'owner1_last_name'=> $row[8],

						'owner1_spouse_first_name'=> $row[9],

						'owner2_first_name'=> $row[10],

						'owner2_middle_name'=> $row[11],

						'owner2_last_name'=> $row[12],

						'owner2_spouse_first_name'=> $row[13],

						'site_address_street_prefix'=> $row[14],

						'full_mail_address'=> $row[15],

						'mail_address_city_state'=> $row[16],

						'mail_address_zip'=> getzipid($row[17]),

						'site_address_unit_number'=> $row[18],

						'use_code_descrition'=> $row[19],

						'building_area'=> $row[20],

						'bedrooms'=> $row[21],

						'rooms'=>$row[22],

						'bathrooms'=> $row[23],

						'pool'=> $row[24],

						'number_of_stories'=> $row[25],

						'number_of_units'=> $row[26],

						'sales_price'=> $row[27],

						'sales_price_code'=> $row[28],

						'sales_date'=> isset($row[29]) ? date('Y-m-d',strtotime($row[29])) : '0000-00-00',

						'sales_document_number'=> $row[30],

						'assessed_land_value'=> $row[31],

						'assessed_improve_percent'=> $row[32],

						'total_assessed_value'=> $row[33],

						'lot_area_acres'=> $row[34],

						'lot_area_sqft'=> $row[35],

						'year_built'=> $row[36],

						'mail_flag'=> $row[37],

						'zoning'=> $row[38],

						'cost_per_sq_ft'=> $row[39],

						'assessed_improvement_value'=> $row[40],

						'total_market_value'=> $row[41],

						'owner_occupied'=> $row[42],

						'fireplace'=> $row[43],

						'tax_exemption_code'=> $row[44],

						'garage_type'=> $row[45],

						'use_code'=> $row[46],

						'tract'=> $row[47],

						'impstatus'=>1,

						'created_date'=>$created_date

					)

				);

		   }  

		   else{

			$ducount++;

		   }

		}

		//error_log("===========incount=======================".print_r($incount, true));

		//error_log("===========ducount=======================".print_r($ducount, true));





		$response=array('status'=>'sucess', 'insert'=>$incount, 'duplicate'=>$ducount);

		  // echo "sucess";

          

      }  

      else  

      {  

		$response=array('status'=>'Error1');    

    }  

 }  

 else  

 {  

	$response=array('status'=>'Error2');   

 }  

 echo json_encode($response);

 exit();

 function checkapncount($id){

	$db = Database::instance();

	$count=$db->select('property', array('parcel_number' => $id))->count();

	return $count;

 }

 function getzipid($id){

	$zip=substr_count ($id, '-');

	if($zip >0){

		$arr=explode("-",$id);

		$newzip=$arr[0];

	}

	else{

		$newzip=$id;

	}

	return $newzip;

 }

 ?>  
