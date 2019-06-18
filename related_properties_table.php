<?php
require_once('Property.php');
require_once('FavoriteProperties.php');

$parcel_number = $_GET['parcel_number'];
$user_id = $_GET['user_id'];

$properties = Property::getRelatedPropertiesForAPN($parcel_number);

$read_only_fields = true;
$show_favorites_flag = false;
$show_matching_cases = false;
$include_related_properties = false;
$select_all = true;

include('includes/properties_list.php');
